<?php

function loadEnv(){
  $env = file_get_contents(__DIR__."/../../.env");
  $lines = explode("\n",$env);

  foreach($lines as $line){
    preg_match("/([^#]+)\=(.*)/",$line,$matches);
    if(isset($matches[2])){ 
      putenv(trim($line)); 
    }
  } 
}

loadEnv();

return[
  'host' => getenv('DB_HOST') ?: 'localhost',
  'username' => getenv('DB_USERNAME') ?: 'root',
  'password' => getenv('DB_PASSWORD') ?: '',
  'database' => getenv('DB_DATABASE') ?: 'workout_blog',
];