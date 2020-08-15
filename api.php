<?php require_once('resources/php/session.php'); ?>
<?php
// check if user logged in
if( !isset($_SESSION["loggedUser"]) ){
	header("Location: index.php");
	die();
}

// ============ GET GAME ==============
require_once('resources/class/Game.php');
$game = new Game();
//print_r($game->setgame());
print_r($game->getTurn());
// ============ GET GAME ==============

?>