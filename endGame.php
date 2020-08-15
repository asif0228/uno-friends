<?php
require_once('resources/php/session.php');
if( !isset($_SESSION["loggedUser"]) || $_SESSION["loggedUser"]["role"]!=1 ){
	header("Location: index.php");
	die();
}else{

	// ============ GET GAME ==============
	require_once('resources/class/Game.php');
	$game = new Game();
	$a = $game->endgame();
	// ============ GET GAME ==============

	header("Location: dashboard.php");
	die();

}

?>
