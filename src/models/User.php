<?php
require_once __DIR__ . '/../config/databse.php';
class User{
  private $db;
  public function __construct(){
    $this->db = Database::getInstance()->getConnection();
  }

  public function newUser($username, $password){
    try{
      $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
      $stmt = $this->db->prepare($sql);
      $stmt->bind_param('ss', $username, $password);
      $stmt->execute();
      return ["status"=>"success", "message"=>"User has been succesfully created." ];
    }catch(mysqli_sql_exception $e){
      return ["status"=>"error", "message"=>$e->getMessage()];
    }finally{
      if(isset($stmt)){
        $stmt->close();
      }
    }
  }

  public function getUser($username){
    try{
      $sql = "SELECT id, username, password FROM users WHERE username = ?";
      $stmt = $this->db->prepare($sql);
      $stmt->bind_param('s', $username);
      $stmt->execute();
      $result = $stmt->get_result();
      return $result->fetch_assoc();
    }catch(mysqli_sql_exception $e){
      return ["status"=>"error", "message"=>$e->getMessage()];
    }finally{
      if(isset($stmt)){
        $stmt->close();
      }
    }
  }

  public function getUserStatistics($user_id){
    try{
      $sql = 
      "SELECT
        u.username, 
        COUNT(DISTINCT w.id) as workout_count,
        COUNT(DISTINCT wl.id) as workout_likes
      FROM users u
      LEFT JOIN workouts w ON u.id = w.user_id
      LEFT JOIN workout_likes wl ON w.id = wl.workout_id
      WHERE u.id = ?";

      $stmt = $this->db->prepare($sql);
      $stmt->bind_param("i", $user_id);
      $stmt->execute();
      $result = $stmt->get_result();
      return $result->fetch_assoc();
    }catch(mysqli_sql_exception $e){
      return ["status"=>"error", "message"=>$e->getMessage()];
    }finally{
      if(isset($stmt)){
        $stmt->close();
      }
    }
  }
}