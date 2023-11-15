<?php 
require_once('../database.php');
require_once('../classes.php');

if($_POST) {
	$data = $_POST;
    $post = new Post();
    
    $result = $post->updatePost($data);
    echo json_encode($result);
}
?>