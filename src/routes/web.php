<?php
require_once __DIR__ . '/../controllers/PageController.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/WorkoutController.php';
require_once __DIR__ . '/../controllers/UserController.php';
class Router{
  private $routes = [
    'GET' => [
      '/' => 'PageController@home',
      '/login' => 'PageController@login',
      '/profile' => 'PageController@profile',

      '/api/workouts' => 'WorkoutController@workouts',
      '/api/users/{user_id}/statistics' => 'UserController@getUserStatistics',
    ],
    'POST' => [
      '/api/register' => 'AuthController@register',
      '/api/login' => 'AuthController@login',
      '/api/logout' => 'AuthController@logout',

      '/api/workouts' => 'WorkoutController@newWorkout',
      '/api/workouts/{workout_id}/like' => 'WorkoutController@toggleLike'
    ],
    'DELETE' => [
      '/api/workouts/{workout_id}' => 'WorkoutController@deleteWorkout'
    ]
];

  private function matchRoute($requestUri, $requestMethod){
    //Check if provided method is supported
    if(!isset($this->routes[$requestMethod])){
      return null;
    }

    //Check if there is identical route (no url parameters)
    if(isset($this->routes[$requestMethod][$requestUri])){
      return [
        'controller' => $this->routes[$requestMethod][$requestUri],
        'params' => array_merge($_GET, $_POST)
      ];
    }

    //Find corresponding route including url parameters
    foreach($this->routes[$requestMethod] as $route => $controller){      
      //Get names of the uri parameters
      preg_match_all('/\{([a-zA-Z0-9_]+)\}/', $route, $paramNames);

      //Convert parameter placeholder to pattern
      $routePattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([^\/]+)', $route);
      $routePattern = '#^' . $routePattern . '$#';

      //Match uri with route pattern
      if(preg_match($routePattern, $requestUri, $matches)){
        array_shift($matches);
        
        //Assign every url parameter value
        $urlParams = [];
        foreach($paramNames[1] as $index => $paramName){
          if(isset($matches[$index])){
            $urlParams[$paramName] = $matches[$index];
          }
        }
        return [
          'controller' => $controller,
          'params' =>  array_merge($urlParams, $_GET, $_POST)
        ];
      }
    }

    //No route found
    return null;
  }

  public function dispatch($requestUri, $requestMethod) {
    try{
      $isApi = stripos($requestUri, '/api/') === 0;
      $route = $this->matchRoute($requestUri, $requestMethod);
      if(!isset($route['controller']) && !isset($route['params'])){
        throw new Exception('Action not found', 404);
      }

      [$controllerName, $action] = explode('@', $route['controller']);
      if(!class_exists($controllerName)){
        throw new Exception('Action not found', 404);
      }

      $controller = new $controllerName(); 
      if(!method_exists($controller, $action)){
        throw new Exception('Action not found', 404);
      }

      if(!empty($route['params'])){
        $result = $controller->$action($route['params']);  
      }else{
        $result = $controller->$action();
      }
      
      if($isApi){
        header('Content-Type: application/json');
        echo json_encode($result);
      }
    }catch(Exception $e){
      if($isApi){
        header('Content-Type: application/json');
        http_response_code($e->getCode() ?: 500);
        echo json_encode(['status'=>"error", 'message' => $e->getMessage()]);
      }else{
        $pageController = new PageController();
        $pageController->error($e->getCode() ?: 500);
      }
    }
  }
}