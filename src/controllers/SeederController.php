<?php
require_once __DIR__ . "/../models/User.php";
require_once __DIR__ . "/../models/Workout.php";
require_once __DIR__ . "/../models/WorkoutLike.php";
require_once __DIR__ . "/../controllers/AuthController.php";
class SeederController{
  private $user, $workout, $workoutLike;
  private $authController;
  public function __construct(){
    $this->user = new User();
    $this->workout = new Workout();
    $this->workoutLike = new WorkoutLike();
    $this->authController = new AuthController();
  }

  public function seed(){
    $users = [
      ["username" => "Liam", "password" => password_hash("password123", PASSWORD_BCRYPT)],
      ["username" => "Noah", "password" => password_hash("password234", PASSWORD_BCRYPT)],
      ["username" => "James", "password" => password_hash("123pass123", PASSWORD_BCRYPT)],
    ];

    $workouts = [
      [
        "user_id" => 1,
        "title" => "Push Pull Legs",
        "difficulty" => "intermediate",
        "days" => [
          "Bench Press\r\nIncline Press\r\nShoulder Press\r\nLateral Raise\r\nTriceps Pushdown",
          "Lat Pulldown\r\nLat Row\r\nT-Bar Row\r\nFace Pulls\r\nBicep Curls",
          "Squats\r\nLeg Extensions\r\nHamstring Curls\r\nCalf Raises",
        ]
      ],
      [
        "user_id" => 2,
        "title" => "Full Body Strength",
        "difficulty" => "advanced",
        "days" => [
          "Deadlift\r\nBarbell Row\r\nOverhead Press\r\nPull-ups\r\nPlank",
          "Bench Press\r\nIncline Dumbbell Press\r\nDips\r\nSkull Crushers\r\nLeg Press",
          "Squats\r\nLunges\r\nLeg Curls\r\nSeated Calf Raises\r\nLeg Extensions",
        ]
      ],
      [
        "user_id" => 3,
        "title" => "Bodyweight Circuit",
        "difficulty" => "beginner",
        "days" => [
          "Push-ups\r\nTriceps Dips\r\nPlank\r\nMountain Climbers\r\nLeg Raises",
          "Pull-ups\r\nInverted Rows\r\nBicep Curls (bodyweight)\r\nBurpees\r\nCrunches",
          "Squats\r\nLunges\r\nGlute Bridges\r\nCalf Raises",
        ]
      ],
      [
        "user_id" => 1,
        "title" => "Upper Body Focus",
        "difficulty" => "intermediate",
        "days" => [
          "Barbell Bench Press\r\nIncline Dumbbell Press\r\nOverhead Press\r\nTricep Dips\r\nTriceps Pushdown",
          "Barbell Row\r\nLat Pulldown\r\nFace Pulls\r\nBicep Curls\r\nHammer Curls",
        ]
      ],
      [
        "user_id" => 2,
        "title" => "Legs and Core",
        "difficulty" => "advanced",
        "days" => [
          "Back Squats\r\nWalking Lunges\r\nLeg Curls\r\nSeated Calf Raises",
          "Leg Press\r\nStep-ups\r\nLeg Extensions\r\nAb Rollouts\r\nRussian Twists",
          "Deadlifts\r\nHip Thrusts\r\nHamstring Curls\r\nPlank\r\nSide Plank",
          "Cable Crunches\r\nLeg Raises\r\nMountain Climbers",
        ]
      ],
      [
        "user_id" => 3,
        "title" => "Push Pull Legs",
        "difficulty" => "beginner",
        "days" => [
          "Push-ups\r\nDumbbell Shoulder Press\r\nDumbbell Chest Flys\r\nTricep Extensions",
          "Assisted Pull-ups\r\nSeated Row\r\nLat Pulldown\r\nBicep Curls",
        ]
      ],
      [
        "user_id" => 1,
        "title" => "Upper Body Strength",
        "difficulty" => "advanced",
        "days" => [
          "Flat Bench Press\r\nIncline Bench Press\r\nOverhead Press\r\nTricep Pushdowns\r\nSkull Crushers",
          "Barbell Rows\r\nLat Pulldown\r\nPull-ups\r\nBiceps Curls\r\nBarbell Shrugs",
          "Deadlift\r\nHip Thrusts\r\nRomanian Deadlifts\r\nLeg Extensions",
          "Barbell Shrugs\r\nDumbbell Rows\r\nDumbbell Curls",
        ]
      ],
      [
        "user_id" => 2,
        "title" => "Core and Cardio",
        "difficulty" => "intermediate",
        "days" => [
          "Plank\r\nSide Plank\r\nRussian Twists\r\nLeg Raises\r\nMountain Climbers",
          "Deadlifts\r\nBarbell Rollouts\r\nV-ups\r\nJump Rope\r\nBurpees",
          "Cable Crunches\r\nBicycle Crunches\r\nLunges\r\nSquats",
        ]
      ],
      [
        "user_id" => 3,
        "title" => "Circuit Training",
        "difficulty" => "beginner",
        "days" => [
          "Bodyweight Squats\r\nPush-ups\r\nPlank\r\nMountain Climbers",
          "Lunges\r\nJumping Jacks\r\nCrunches\r\nBicycle Crunches",
        ]
      ],
  ];
  

    $workout_likes = [
      ["user_id" => 1, "workout_id" => 1],
      ["user_id" => 1, "workout_id" => 2],
      ["user_id" => 1, "workout_id" => 5],
      ["user_id" => 2, "workout_id" => 1],
      ["user_id" => 2, "workout_id" => 3],
      ["user_id" => 2, "workout_id" => 6],
      ["user_id" => 2, "workout_id" => 7],
      ["user_id" => 3, "workout_id" => 2],
      ["user_id" => 3, "workout_id" => 4],
      ["user_id" => 3, "workout_id" => 5],
      ["user_id" => 3, "workout_id" => 8],
      ["user_id" => 1, "workout_id" => 4],
      ["user_id" => 1, "workout_id" => 6],
      ["user_id" => 2, "workout_id" => 8],
      ["user_id" => 2, "workout_id" => 9],
      ["user_id" => 3, "workout_id" => 1],
      ["user_id" => 3, "workout_id" => 9],
      ["user_id" => 1, "workout_id" => 9],
      ["user_id" => 2, "workout_id" => 4],
      ["user_id" => 3, "workout_id" => 7],
    ];

    try{ 
      if(!empty($this->workout->getWorkouts(['per_page' => 1, 'offset' => 0])['data'])){
        return ["code" => 400, "message" => "Database needs to be empty."];
      }
      foreach($users as $user){
        $this->user->newUser($user["username"], $user["password"]);
      }
      foreach($workouts as $workout){
        $this->workout->newWorkout($workout["user_id"], $workout["title"], $workout["difficulty"], $workout["days"]);
      }
      foreach ($workout_likes as $like) {
        $this->workoutLike->toggleLike($like["workout_id"], $like["user_id"]);
      }
      $this->authController->login(["username" => "Liam", "password" => "password123"]);

      return ["code" => 200, 'message' => 'Database seeded successfully.'];
    }catch(Exception $e){
      return ["code" => 500, 'message' => "Internal server error."];
    }
  }

}