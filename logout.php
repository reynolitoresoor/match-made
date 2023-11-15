<?php  
require_once('config.php');
if(session_destroy()) {
	header('Location: '.base_url);
}
?>