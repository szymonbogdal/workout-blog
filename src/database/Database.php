<?php
class Database{
  private $conn;
  private static $instance = null;

  public function __construct(){
		$dbConfig = require_once __DIR__ . '/../config/config.php';
    $this->conn = new mysqli('p:'.$dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['database']);
    if($this->conn->connect_error){
      throw new Exception("Connection failed: ".$this->conn->connect_error);
    }
  }

  public static function getInstance(){
    if(self::$instance === null){
      self::$instance = new Database();
    }
    return self::$instance;
  }

  public function getConnection(){
    return $this->conn;
  }
}