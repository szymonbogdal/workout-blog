<?php
class PageController{  
  public function home(){
    $title = "Home";
    $js = "home.js";
    $css = "home.css";
    $content = __DIR__ . "/../views/home.php";
    require __DIR__ . "/../views/layout.php";
  }
  public function login(){
    if(isset($_SESSION['user_id']) && isset($_SESSION['username'])){
      header("Location: /workout_blog");
    }
    $title = "Login";
    $js = "login.js";
    $css = "login.css";
    $content = __DIR__ . "/../views/login.php";
    require __DIR__ . "/../views/layout.php";
  }
}