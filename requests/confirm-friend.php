<?php 
require_once('../database.php');
require_once('../classes.php');

if($_POST) {
	$data = $_POST;
    $user = new User();
    
    $result = $user->confirmFriend($data);
    if($result) {
        $result = $user->getUserConfirmedFriends($data['user_id']);
        echo json_encode($result);
    }
}
?>