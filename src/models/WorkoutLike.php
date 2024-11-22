<?php
require_once __DIR__ . '/../config/databse.php';
class WorkoutLike{
  private $db;
  public function __construct(){
    $this->db = Database::getInstance()->getConnection();
  }

  public function toggleLike($workout_id, $user_id){
    try{
      $selectSql = "SELECT id FROM workout_likes WHERE workout_id = ? AND user_id = ?";
      $selectStmt= $this->db->prepare($selectSql);
      $selectStmt->bind_param("ii", $workout_id, $user_id);
      $selectStmt->execute();
      $selectResult = $selectStmt->get_result();

      if($selectResult->num_rows > 0){
        $workoutLike = $selectResult->fetch_assoc();
        $deleteSql = "DELETE FROM workout_likes WHERE id = ?"; 
        $deleteStmt = $this->db->prepare($deleteSql);
        $deleteStmt->bind_param("i", $workoutLike['id']);
        $deleteStmt->execute();
        return ["code" => 204];
      }else{
        $insertSql = "INSERT INTO workout_likes (workout_id, user_id) VALUES (?, ?)";
        $insertStmt = $this->db->prepare($insertSql);
        $insertStmt->bind_param("ii", $workout_id, $user_id);
        $insertStmt->execute();
        return ["code" => 201, "message" => "Succesfully liked workout."];
      }
    }catch(mysqli_sql_exception $e){
      return ["code" => 500, "message" => "Internal server error."];
    }finally{
      if(isset($selectStmt)){
        $selectStmt->close();
      }
      if(isset($deleteStmt)){
        $deleteStmt->close();
      }
      if(isset($insertStmt)){
        $insertStmt->close();
      }
    }
  }
}