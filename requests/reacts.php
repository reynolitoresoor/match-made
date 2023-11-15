<?php 
require_once('../database.php');
require_once('../classes.php');

if($_POST) {
	$data = $_POST;
    $react = new React();

    if($data['type'] == 'like') {
        $result = $react->like($data);
        echo json_encode($result);
    } else {
        $result = $react->dislike($data);
        echo json_encode($result);
    }
}
?>