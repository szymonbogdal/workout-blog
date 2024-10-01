<?php
class Database{
  private $conn;
  private static $instance = null;
  private $db = "php_workout_blog";

  public function __construct(){
    $host = "localhost";
    $username = "root";
    $password = "";
    $this->conn = new mysqli('p:'.$host, $username, $password);
    if($this->conn->connect_error){
      throw new Exception("Connection failed: ".$this->conn->connect_error);
    }
    $this->createDatabase();
    $this->conn->select_db($this->db);

    $this->createUsersTable();
  }

  private function createDatabase(){
    $sql = "CREATE DATABASE IF NOT EXISTS $this->db";
    $this->executeQuery($sql);
  }

  private function createUsersTable(){
    $sql = "CREATE TABLE IF NOT EXISTS users(
      id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      username VARCHAR(50) NOT NULL UNIQUE,
      password VARCHAR(255) NOT NULL
    )";
    $this->executeQuery($sql);
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
  private function executeQuery($sql) {
    $this->conn->query($sql);
    if($this->conn->error){
      throw new Exception("Error creating database: ".$this->conn->error);
    }
  }
}