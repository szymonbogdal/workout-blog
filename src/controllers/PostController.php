<?php
require_once __DIR__."/../models/Post.php";
class PostController{
  private $post;
  public function __construct(){
    $this->post = new Post();
  }
  public function posts(){
    $params = $_GET;
    if(isset($_SESSION['user_id']) && isset($_SESSION['username'])){
      $params['user_id'] = $_SESSION['user_id'];
    }
    return $this->post->getPosts($params);
  }
}