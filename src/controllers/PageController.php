<?php
class PageController{  
  public function home(){
    require __DIR__ . "/../views/home.php";
  }
  public function login(){
    require __DIR__ . "/../views/login.php";
  }
}