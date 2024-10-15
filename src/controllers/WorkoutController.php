<?php
require_once __DIR__."/../models/Workout.php";
class WorkoutController{
  private $workout;
  public function __construct(){
    $this->workout = new Workout();
  }
  public function workouts(){
    $params = $_GET;
    if(isset($_SESSION['user_id']) && isset($_SESSION['username'])){
      $params['user_id'] = $_SESSION['user_id'];
    }
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
}