<?php
require_once __DIR__ . '/../models/User.php';
class AuthController{
  private $user;
  public function __construct(){
    $this->user = new User();
  }

  public function register(){
    $username = $_POST['username'] ?? null;
    $password = $_POST['password'] ?? null;
    if(!isset($username) || !isset($password)){
      return ['status'=>"error", 'message'=>'Missing username or password parameter.'];
    }
    if(strlen($username) < 3 || strlen($username) > 50){
      return ['status'=>"error", 'message'=>'Username need to be between 3 and 50 characters.'];
    }
    if(strlen($password) < 8 || strlen($password) > 72){
      return ['status'=>"error", 'message'=>'Password need to be between 8 and 72 characters.'];
    }
    $password = password_hash($password, PASSWORD_BCRYPT);
    return $this->user->newUser($username, $password);
  }

  public function login(){
    $username = $_POST['username'] ?? null;
    $password = $_POST['password'] ?? null;
    if(!isset($username) || !isset($password)){
      return ['status'=>"error", 'message'=>"Missing username or password parameter.'"];
    }
    $user =  $this->user->getUser($username);
    if(!isset($user['username']) || !isset($user['password'])){
      return ['status'=>"error", 'message'=>"Invaild username or password."];
    }
    if(!password_verify($password, $user['password'])){
      return ['status'=>"error", 'message'=>"Invaild username or password."];
    }
    return ['status'=>"success", 'message'=>"User $username logged in."];
  }
}