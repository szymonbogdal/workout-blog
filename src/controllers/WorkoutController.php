<?php
require_once __DIR__."/../models/Workout.php";
require_once __DIR__ . "/../models/WorkoutLike.php";
class WorkoutController{
  private $workout, $workoutLike;
  public function __construct(){
    $this->workout = new Workout();
    $this->workoutLike = new WorkoutLike();
  }
  public function workouts(){
    $params = $_GET;
    if(isset($_SESSION['user_id']) && isset($_SESSION['username'])){
      $params['user_id'] = $_SESSION['user_id'];
    }
    if(!isset($params['page']) || !is_numeric($params['page'])){
      $params['page'] = 1;
    }
    $params['per_page'] = 3;
    $params['offset'] = ($params['page'] - 1 ) * $params['per_page'];
    return $this->workout->getWorkouts($params);
  }

  public function newWorkout(){
    $params = $_POST;
    if(!isset($_SESSION['user_id']) || !isset($_SESSION['username'])){
      return ['status'=>"error", 'message'=>"You need to be logged to add new workout."];
    }
    if(!isset($params['title']) || !isset($params['difficulty']) || !isset($params["workoutDays"][0])){
      return ['status'=>"error", 'message'=>'Missing required parameters.'];
    }
    if(strlen($params['title']) > 255){
      return ['status'=>"error", 'message'=>'Title can not be longer than 255 characters.'];
    }
    if(!in_array($params['difficulty'], ["beginner", "intermediate", "advanced"])){
      return ['status'=>"error", 'message'=>'Difficulty is wrongly formatted.'];
    }
    
    return $this->workout->newWorkout($_SESSION['user_id'], $params['title'], $params['difficulty'], $params['workoutDays']);
  }

  public function toggleLike(){
    $params = json_decode(file_get_contents("php://input"), true);
    if(!isset($_SESSION['user_id']) || !isset($_SESSION['username'])){
      return ['status'=>"error", 'message'=>"You need to be logged to like workout."];
    }
    if(!isset($params['workout_id'])){
      return ['status'=>"error", 'message'=>'Missing required parameters.'];
    }
    
    return $this->workoutLike->toggleLike($params['workout_id'], $_SESSION['user_id']);
  }
}