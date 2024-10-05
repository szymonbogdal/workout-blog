<?php
class PageController{  
  public function home(){
    if(!isset($_SESSION['user_id']) || !isset($_SESSION['username'])){
      header("Location: /workout_blog/login");
    }
    require __DIR__ . "/../views/home.php";
  }
  public function login(){
    if(isset($_SESSION['user_id']) && isset($_SESSION['username'])){
      header("Location: /workout_blog");
    }
    require __DIR__ . "/../views/login.php";
  }
}