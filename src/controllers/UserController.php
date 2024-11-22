<?php
require_once __DIR__ . '/../models/User.php';
class UserController{
  private $user;
  public function __construct(){
    $this->user = new User();
  }

  public function getUserStatistics($params = []){
    if(!isset($params['user_id'])){
      return ["code" => 400, "message" => "Missing required parameters."];
    }
    return $this->user->getUserStatistics($params['user_id']);
  }
}