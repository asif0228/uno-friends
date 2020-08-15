<?php
require_once('resources/php/session.php');
if( !isset($_SESSION["loggedUser"]) || $_SESSION["loggedUser"]["role"]!=1 ){
	header("Location: index.php");
	die();
}else{

	// ============ GET GAME ==============
	require_once('resources/class/Game.php');
	$game = new Game();
	$game->setgame();
	// ============ GET GAME ==============

	if( $game->_status==1 ){
		header("Location: dashboard.php");
		die();
	}else{
		//unserialize and serialize
		$game->newgame($_POST['players']);

		header("Location: dashboard.php");
		die();
	}

}

?>
