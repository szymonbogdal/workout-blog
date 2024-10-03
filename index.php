<?php
require_once __DIR__ . "/src/routes/web.php";

$requestUri = $_SERVER['REQUEST_URI'];
$basePath = '/workout_blog';
if (strpos($requestUri, $basePath) === 0) {
    $requestUri = substr($requestUri, strlen($basePath));
}
if($requestUri){
  $router = new Router();
  $router->dispatch($requestUri);
}
