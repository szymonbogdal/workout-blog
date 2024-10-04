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
}