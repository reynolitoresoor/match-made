<?php 
require_once('../database.php');
require_once('../classes.php');

if($_POST) {
	$data = $_POST;
    $user = new User();
    
    $result = $user->getUserFriends($data['user_id']);
    echo json_encode($result);
}
?>