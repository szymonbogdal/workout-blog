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
    if(isset($_SESSION['user_id'])){
      header("Location: /workout-blog");
    }
    $title = "Login";
    $js = "login.js";
    $css = "login.css";
    $content = __DIR__ . "/../views/login.php";
    require __DIR__ . "/../views/layout.php";
  }
  public function profile(){
    if(!isset($_SESSION['user_id'])){
      header("Location: /workout-blog");
    }
    $title = "Profile";
    $js = "profile.js";
    $css = "profile.css";
    $content = __DIR__ . "/../views/profile.php";
    require __DIR__ . "/../views/layout.php";
  }

  public function error($code = 404){
    $title = "Error $code";
    $css = "error.css";
    $content = __DIR__ . "/../views/error.php";
    require __DIR__ . "/../views/layout.php";
  }
}