<?php
require_once('session.php');

if(!defined('base_url')) define('base_url','http://localhost/match-made/');
if(!defined('BASE_APP')) define('BASE_APP', $_SERVER['DOCUMENT_ROOT'].'/match-made/' );
if(!defined('DB_SERVER')) define('HOST',"localhost");
if(!defined('DB_USERNAME')) define('DB_USERNAME',"root");
if(!defined('DB_PASSWORD')) define('DB_PASSWORD',"");
if(!defined('DB_NAME')) define('DB_NAME',"match_made");
?>