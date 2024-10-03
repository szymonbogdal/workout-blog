<?php
class PageController{
  private static $instance;
  public static function getInstance(){
    if(self::$instance === null){
      self::$instance = new PageController();
    }
    return self::$instance;
  }
  
  public function home(){
    require __DIR__ . "/../views/home.php";
  }
}