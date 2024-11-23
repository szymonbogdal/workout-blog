<?php
session_start();

require_once __DIR__ . "/src/routes/web.php";

$requestUri = parse_url($_SERVER['REQUEST_URI'])['path'];
$requestMethod = $_SERVER['REQUEST_METHOD'];
$basePath = '/workout-blog';
if(strpos($requestUri, $basePath) === 0){
  $requestUri = substr($requestUri, strlen($basePath));
}
if($requestUri){
  $router = new Router();
  $router->dispatch($requestUri, $requestMethod);
}
