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
      $selectStmt->close();

      if($selectResult->num_rows > 0){
        $workoutLike = $selectResult->fetch_assoc();
        $deleteSql = "DELETE FROM workout_likes WHERE id = ?"; 
        $deleteStmt = $this->db->prepare($deleteSql);
        $deleteStmt->bind_param("i", $workoutLike['id']);
        $deleteStmt->execute();
        $deleteStmt->close();
        return ["status"=>"success", "message"=>"unliked"];
      }else{
        $insertSql = "INSERT INTO workout_likes (workout_id, user_id) VALUES (?, ?)";
        $insertStmt = $this->db->prepare($insertSql);
        $insertStmt->bind_param("ii", $workout_id, $user_id);
        $insertStmt->execute();
        $insertStmt->close();
        return ["status"=>"success", "message"=>"liked"];
      }
    }catch(mysqli_sql_exception $e){
      return ["status"=>"error", "message"=>$e->getMessage()];
    }
  }
}