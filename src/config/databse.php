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
    $this->createWorkoutsTable();
    $this->createWorkoutDaysTable();
    $this->createWorkoutLikesTable();
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

  private function createWorkoutsTable(){
    $sql = "CREATE TABLE IF NOT EXISTS workouts(
      id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      user_id INT UNSIGNED NOT NULL,
      title VARCHAR(255) NOT NULL,
      difficulty VARCHAR(255) NOT NULL,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $this->executeQuery($sql);
  }

  private function createWorkoutDaysTable(){
    $sql = "CREATE TABLE IF NOT EXISTS workout_days(
      id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      workout_id INT UNSIGNED NOT NULL,
      day_order INT UNSIGNED,
      body TEXT NOT NULL,
      FOREIGN KEY (workout_id) REFERENCES workouts(id) ON DELETE CASCADE
    )";
    $this->executeQuery($sql);
  }

  private function createWorkoutLikesTable(){
    $sql = "CREATE TABLE IF NOT EXISTS workout_likes(
      id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      workout_id INT UNSIGNED NOT NULL,
      user_id INT UNSIGNED NOT NULL,
      FOREIGN KEY (workout_id) REFERENCES workouts(id) ON DELETE CASCADE,
      FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
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