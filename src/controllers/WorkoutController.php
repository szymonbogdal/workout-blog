<?php
require_once __DIR__."/../models/Workout.php";
require_once __DIR__ . "/../models/WorkoutLike.php";
class WorkoutController{
  private $workout, $workoutLike;
  public function __construct(){
    $this->workout = new Workout();
    $this->workoutLike = new WorkoutLike();
  }
  public function workouts($params = []){
    if(isset($_SESSION['user_id'])){
      $params['user_id'] = $_SESSION['user_id'];
    }
    if(!isset($params['page']) || !is_numeric($params['page'])){
      $params['page'] = 1;
    }
    $params['per_page'] = 3;
    $params['offset'] = ($params['page'] - 1 ) * $params['per_page'];
    return $this->workout->getWorkouts($params);
  }

  public function newWorkout($params = []){
    if(!isset($_SESSION['user_id'])){
      return ["code" => 401, "message" => "You need to be logged to add new workout."];
    }
    if(!isset($params['title']) || !isset($params['difficulty']) || !isset($params["workoutDays"][0])){
      return ["code" => 400, "message" => "Missing required parameters."];
    }
    if(strlen($params['title']) > 255){
      return ["code" => 400, "message" => "Title can not be longer than 255 characters."];
    }
    if(!in_array($params['difficulty'], ["beginner", "intermediate", "advanced"])){
      return ["code" => 400, "message" => "Difficulty is wrongly formatted."];
    }
    
    return $this->workout->newWorkout($_SESSION['user_id'], $params['title'], $params['difficulty'], $params['workoutDays']);
  }

  public function deleteWorkout($params = []){
    if(!isset($_SESSION['user_id'])){
      return ["code" => 401, "message" => "You need to be logged to delete workout."];
    }
    if(!isset($params['workout_id'])){
      return ["code" => 400, "message" => "Missing required parameters."];
    }

    return $this->workout->deleteWorkout($_SESSION['user_id'], $params['workout_id']);
  }

  public function toggleLike($params = []){
    if(!isset($_SESSION['user_id'])){
      return ["code" => 401, "message" => "You need to be logged to like workout."];
    }
    if(!isset($params['workout_id'])){
      return ["code" => 400, "message" => "Missing required parameters."];
    }
    
    return $this->workoutLike->toggleLike($params['workout_id'], $_SESSION['user_id']);
  }
}