<?php
require_once __DIR__ . '/../config/databse.php';
class Workout{
  private $db;
  public function __construct(){
    $this->db = Database::getInstance()->getConnection();
  }
  public function getWorkouts($params){
    try{
      //base query
      $workoutSql = 
      "SELECT SQL_CALC_FOUND_ROWS 
        w.id,
        w.title,
        w.difficulty,
        w.created_at,
        w.updated_at,
        u.username AS author,
        (SELECT COUNT(*) FROM workout_days wd WHERE wd.workout_id = w.id) AS week_days,
        (SELECT COUNT(*) FROM workout_likes wl WHERE wl.workout_id = w.id) AS like_count,
        CASE 
          WHEN ? IS NOT NULL AND EXISTS (
            SELECT 1 FROM workout_likes wl2 
            WHERE wl2.workout_id = w.id AND wl2.user_id = ?
          ) THEN 1 
          ELSE 0 
          END AS is_liked_by_user
      FROM workouts w
      JOIN users u ON w.user_id = u.id
      ";

      //variables to hold additional data
      //if there is usser assign him to values
      $workoutConditions = [];
      $workoutValues = array_fill(0,2,$params['user_id'] ?? null);
      $workoutTypes = "ii";

      //Add filter parameters to query
      if(isset($params['title'])){
        $workoutConditions[] = "w.title LIKE ?";
        $workoutValues[] = "%".$params['title']."%";
        $workoutTypes .= "s";
      }

      if(isset($params['difficulty'])){
        $workoutConditions[] = "w.difficulty LIKE ?";
        $workoutValues[] = "%".$params['difficulty']."%";
        $workoutTypes .= "s";
      }

      if(isset($params['author'])){
        $workoutConditions[] = "w.user_id LIKE ?";
        $workoutValues[] = $params['author'];
        $workoutTypes .= "i";
      }

      if(isset($params['liked'])){
        $workoutConditions[] = "EXISTS (
          SELECT 1 FROM workout_likes wl 
          WHERE wl.workout_id = w.id AND wl.user_id = ?
        )";
        $workoutValues[] = $params['liked'];
        $workoutTypes .= "i";
      }

      if(!empty($workoutConditions)){
        $workoutSql .= " WHERE ".implode(" AND ",$workoutConditions);
      }

      if(isset($params['week_days'])){
        $workoutSql .= " HAVING week_days = ?";
        $workoutValues[] = $params["week_days"];
        $workoutTypes .= "i";
      }

      //Allow ordering by some fields
      $sortFields = ["like_count", "created_at", "updated_at", "week_days"];
      $sortOrders = ["ASC", "DESC"];
      if(isset($params['sort']) && in_array(strtolower($params['sort']), $sortFields)){
        $workoutSql .= " ORDER BY ".strtolower($params['sort']);
        if(isset($params['order']) && in_array(strtoupper($params['order']), $sortOrders)){
          $workoutSql .= " ".strtoupper($params['order']);
        }
      }else{
        $workoutSql .= " ORDER BY like_count DESC";
      }

      //Add pagination
      $workoutSql .= " LIMIT ? OFFSET ?";
      $workoutValues[] = $params['per_page'];
      $workoutValues[] = $params['offset'];
      $workoutTypes .= "ii";

      //Prepare and execute query
      $workoutStmt = $this->db->prepare($workoutSql);
      if(!empty($workoutValues)){
        $workoutStmt->bind_param($workoutTypes,...$workoutValues);
      }
      $workoutStmt->execute();
      $workoutResult = $workoutStmt->get_result();
      
      //total row count
      $totalCount = $this->db->query('SELECT FOUND_ROWS()')->fetch_row()[0];

      //Assign result to associative array
      $workouts = [];
      $workoutIds = [];
      while($row =  $workoutResult->fetch_assoc()){
        $workouts[] = $row;
        $workouts[count($workouts)-1]['workout_days'] = [];
        $workoutIds[] = $row['id'];
      }

      //Get workout_days assigned to returned workouts
      if(!empty($workoutIds)){
        $dayValues = implode(',', array_fill(0, count($workoutIds), "?"));
        $daySql = 
          "SELECT
            workout_id,
            day_order,
            body
            FROM workout_days
            WHERE workout_id IN ($dayValues)
            ORDER BY workout_id ASC, day_order ASC
          ";
        $dayStmt = $this->db->prepare($daySql);
        $dayTypes = str_repeat('i', count($workoutIds));
        $dayStmt->bind_param($dayTypes, ...$workoutIds);
        $dayStmt->execute();
        
        //Assign workout_days to corresponding workouts
        $workoutIndex = array_flip($workoutIds);  
        $dayResult = $dayStmt->get_result();
        while($row = $dayResult->fetch_assoc()){
          $index = $workoutIndex[$row['workout_id']];
          $workouts[$index]['workout_days'][] = [
            "day_order" => $row['day_order'],
            "body" => $row['body']
          ];
        }
      }
      return ["code" => 200, "data" => $workouts, "total_pages" => ceil($totalCount / $params['per_page'])];
    }catch(mysqli_sql_exception $e){
      return ["code" => 500, "message" => "Internal server error."];
    }finally{
      if(isset($workoutStmt)){
        $workoutStmt->close();
      }
      if(isset($dayStmt)){
        $dayStmt->close();
      }
    }
  }

  public function newWorkout($user_id, $title, $difficulty, $days){
    $this->db->begin_transaction();
    try{
      $workoutSql = "INSERT INTO workouts (user_id, title, difficulty) VALUES (?, ?, ?)";
      $workoutStmt = $this->db->prepare($workoutSql);
      $workoutStmt->bind_param("iss", $user_id, $title, $difficulty);
      $workoutStmt->execute();

      $workout_id = $this->db->insert_id;

      $daySql = "INSERT INTO workout_days (workout_id, day_order, body) VALUES ";
      $dayValues = [];
      $dayParams = [];
      $types = "";

      foreach($days as $index => $day){
        if(!empty($day)){
          $dayValues[] = "(?, ?, ?)";
          $dayParams[] = $workout_id;
          $dayParams[] = $index + 1;
          $dayParams[] = $day;
          $types .= "iis";
        }
      }

      $daySql .= implode(", ", $dayValues);
      $dayStmt = $this->db->prepare($daySql);
      $dayStmt->bind_param($types, ...$dayParams);
      $dayStmt->execute();

      $this->db->commit();
      return ["code" => 201, "message" => "Workout has been succesfully created." ];
    }catch(mysqli_sql_exception $e){
      $this->db->rollback();
      return ["code" => 500, "message" => "Internal server error."];
    }finally{
      if(isset($workoutStmt)){
        $workoutStmt->close();
      }
      if(isset($dayStmt)){
        $dayStmt->close();
      }
    }
  }

  public function deleteWorkout($user_id, $workout_id){
    try{
      $sql = "DELETE FROM workouts WHERE id = ? AND user_id = ?";
      $stmt = $this->db->prepare($sql);
      $stmt->bind_param("ii", $workout_id, $user_id);
      $stmt->execute();

      if($stmt->affected_rows > 0){
        return ["code" => 204];  
      }else{
        return ["code" => 404, "message" => "Workout not found."];  
      }
    }catch(mysqli_sql_exception $e){
      return ["code" => 500, "message" => "Internal server error."];
    }finally{
      if(isset($stmt)){
        $stmt->close();
      }
    }
  }
}