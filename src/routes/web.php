<?php
require_once __DIR__ . '/../controllers/PageController.php';
require_once __DIR__ . '/../controllers/AuthController.php';
class Router{
  private $routes = [ 
    "/api/register" => ["controller" => "AuthController", "method" => "register", 'type'=>'api'], 
    "/api/login" => ["controller" => "AuthController", "method" => "login", 'type'=>'api'], 
    
    "/" => ["controller" => "PageController", "method" => "home", 'type'=>'page'],
    "/login" => ["controller" => "PageController", "method" => "login", 'type'=>'page'],
  ]; 

  public function dispatch($action) {
    try{
      if(!array_key_exists($action, $this->routes)){
        throw new Exception("Action $action is not defined");
      }
      $route = $this->routes[$action];
      $controllerName = $route["controller"];
      $method = $route["method"];
      $type = $route["type"];
      if(!class_exists($controllerName)){
        throw new Exception("Controller $controllerName not found");
      }
      $controller = new $controllerName();
      if(!method_exists($controller, $method)){
        throw new Exception("Method $method not found in $controllerName");
      }
      $result = $controller->$method();
      if($type === 'api'){
        header('Content-Type: application/json');
        echo json_encode($result);
      }  
    }catch(Exception $e){
      if(str_contains($action, "/api/")){
        header('Content-Type: application/json');
        echo json_encode(['error' => $e->getMessage()]);
      }else{
        header('Content-Type: text/html');
        echo "<h1>Error</h1><p>".$e->getMessage()."</p>";
      }  
    }
  }
}