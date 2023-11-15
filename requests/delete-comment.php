<?php 
require_once('../database.php');
require_once('../classes.php');

if($_POST) {
	$data = $_POST;
    $comment = new Comment();
    
    $result = $comment->deleteComment($data);
    echo json_encode($result);
}
?>