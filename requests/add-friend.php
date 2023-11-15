<?php 
require_once('../database.php');
require_once('../classes.php');

if($_POST) {
	$data = $_POST;
    $user = new User();
    
    $result = $user->addFriend($data);
    echo json_encode($result);
}
?>