<?php
require_once __DIR__ . '/../config/databse.php';
class User{
  private $db;
  public function __construct(){
    $this->db = Database::getInstance()->getConnection();
  }

  public function newUser($username, $password){
    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param('ss', $username, $password);
    try{
      $stmt->execute();
      return ["status"=>"success", "message"=>"User has been succesfully created." ];
    }catch(mysqli_sql_exception $e){
      return ["status"=>"failed", "message"=>$e->getMessage()];
    }
  }

  public function getUser($username){
    $sql =  "SELECT * FROM users WHERE username = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param('s', $username);
    try{
      $stmt->execute();
      $result = $stmt->get_result();
      return $result->fetch_assoc();
    }catch(mysqli_sql_exception $e){
      return ["status"=>"failed", "message"=>$e->getMessage()];
    }
  }
}