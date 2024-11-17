<?php
require_once __DIR__ . '/../models/User.php';
class AuthController{
  private $user;
  public function __construct(){
    $this->user = new User();
  }

  public function register($params = []){
    if(isset($_SESSION['user_id'])){
      return ['status'=>"error", 'message'=>"User is already logged.'"];
    }
    $username = $params['username'] ?? null;
    $password = $params['password'] ?? null;
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

  public function login($params = []){
    if(isset($_SESSION['user_id'])){
      return ['status'=>"error", 'message'=>"User is already logged.'"];
    }
    $username = $params['username'] ?? null;
    $password = $params['password'] ?? null;
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
    $_SESSION['user_id'] = $user['id'];
    return ['status'=>"success", 'message'=>"User $username logged in."];
  }
  public function logout(){
    if(!isset($_SESSION['user_id'])){
      return ['status'=>"error", 'message'=>"There is no logged user."];
    }
    session_unset();
    session_destroy();
    return ['status'=>"success", 'message'=>"User logged out."];
  }
}