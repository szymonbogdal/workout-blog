<?php
require_once __DIR__ . '/../models/User.php';
class UserController{
  private $user;
  public function __construct(){
    $this->user = new User();
  }

  public function getUserStatistics(){
    $params = $_GET;
    if(!isset($params['user_id'])){
      return ['status'=>"error", 'message'=>'Missing required parameters.'];
    }
    return $this->user->getUserStatistics($params['user_id']);
  }
}