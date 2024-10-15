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
}