<?php
require_once __DIR__ . '/../models/User.php';
class AuthController{
  private $user;
  public function __construct(){
    $this->user = new User();
  }

  public function register($params = []){
    if(isset($_SESSION['user_id'])){
      return ["code" => 400, "message" => "User is already logged."];
    }
    $username = $params['username'] ?? null;
    $password = $params['password'] ?? null;
    if(!isset($username) || !isset($password)){
      return ["code" => 400, "message" => "Missing username or password parameter."];
    }
    if(strlen($username) < 3 || strlen($username) > 50){
      return ["code" => 400, "message" => "Username need to be between 3 and 50 characters."];
    }
    if(strlen($password) < 8 || strlen($password) > 72){
      return ["code" => 400, "message" => "Password need to be between 8 and 72 characters."];
    }
    $password = password_hash($password, PASSWORD_BCRYPT);
    return $this->user->newUser($username, $password);
  }

  public function login($params = []){
    if(isset($_SESSION['user_id'])){
      return ["code" => 400, "message" => "User is already logged."];
    }
    $username = $params['username'] ?? null;
    $password = $params['password'] ?? null;
    if(!isset($username) || !isset($password)){
      return ["code" => 400, "message" => "Missing username or password parameter.'"];
    }
    $user =  $this->user->getUser($username);
    if(!isset($user['data']['username']) || !isset($user['data']['password']) || !isset($user['data']['id'])){
      return ["code" => 401, "message" => "Invalid username or password."];
    }
    if(!password_verify($password, $user['data']['password'])){
      return ["code" => 401, "message" => "Invalid username or password."];
    }
    $_SESSION['user_id'] = $user['data']['id'];
    return ["code" => 200, "message" => "User $username logged in."];
  }
  public function logout(){
    if(!isset($_SESSION['user_id'])){
      return ["code" => 400, "message" => "There is no logged user."];
    }
    session_unset();
    session_destroy();
    return ["code" => 200, "message" => "User logged out."];
  }
}