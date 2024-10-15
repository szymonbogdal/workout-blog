<?php
require_once __DIR__ . '/../config/databse.php';
class Workout{
  private $db;
  public function __construct(){
    $this->db = Database::getInstance()->getConnection();
  }
  public function getWorkouts($params){
    //base query
    $sql = 
    "SELECT 
      p.id,
      p.title,
      p.body,
      p.week_days,
      p.created_at,
      p.updated_at,
      u.username AS author,
      (SELECT COUNT(*) FROM workout_likes wl WHERE wl.workout_id = p.id) AS like_count,
      CASE 
        WHEN ? IS NOT NULL AND EXISTS (
          SELECT 1 FROM workout_likes wl2 
          WHERE wl2.workout_id = p.id AND wl2.user_id = ?
        ) THEN 1 
        ELSE 0 
        END AS is_liked_by_user,
      CASE 
        WHEN ? IS NOT NULL AND p.user_id = ? THEN 1 
        ELSE 0 
      END AS is_user_author
    FROM workouts p
    JOIN users u ON p.user_id = u.id
    ";

    //variables to hold additional data
    //at start assign user to null
    $conditions = [];
    $values = [null,null,null,null];
    $types = "iiii";

    //if there is user provided, use his id in query
    if(isset($params['user_id'])){
      $values[0] = $params['user_id'];
      $values[1] = $params['user_id'];
      $values[2] = $params['user_id'];
      $values[3] = $params['user_id'];
    }

    //Add filter parameters to query
    if(isset($params['title'])){
      $conditions[] = "p.title LIKE ?";
      $values[] = "%".$params['title']."%";
      $types .= "s";
    }
    if(isset($params['week_days'])){
      $conditions[] = "p.week_days = ?";
      $values[] = $params['week_days'];
      $types .= "i";
    }
    if(!empty($conditions)){
      $sql .= " WHERE ".implode(" AND ",$conditions);
    }

    //Allow ordering by some fields
    $sortFields = ["like_count", "created_at", "updated_at", "week_days"];
    $sortOrders = ["ASC", "DESC"];
    if(isset($params['sort']) && in_array(strtolower($params['sort']), $sortFields)){
      $sql .= " ORDER BY ".strtolower($params['sort']);
      if(isset($params['order']) && in_array(strtoupper($params['order']), $sortOrders)){
        $sql .= " ".strtoupper($params['order']);
      }
    }else{
      $sql .= " ORDER BY like_count DESC";
    }
    
    //Prepare and execute query, return data
    $stmt = $this->db->prepare($sql);
    if(!empty($values)){
      $stmt->bind_param($types,...$values);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $workouts = [];
    while($row = $result->fetch_assoc()){
      $workouts[] = $row;
    }
    $stmt->close();
    return $workouts;
  }

  public function newWorkout($user_id, $title, $difficulty, $days){
    $this->db->begin_transaction();
    try {
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
      return ["status"=>"success", "message"=>"Workout has been succesfully created." ];
    } catch (mysqli_sql_exception $e) {
      $this->db->rollback();
      return ["status"=>"failed", "message"=>$e->getMessage()];
    }
  }
}