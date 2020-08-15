<?php require_once('resources/php/session.php'); ?>
<?php

if( !isset($_SESSION["loggedUser"]) ){
	header("Location: index.php");
	die();
}

session_destroy();
header("Location: index.php");
die();

?>