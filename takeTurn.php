<?php require_once('resources/php/session.php'); ?>
<?php

// it requires game variable

if( !isset($_SESSION["loggedUser"]) || !isset($_GET['cardNum']) ){
	header("Location: index.php");
	die();
}

// ============ GET GAME ==============
require_once('resources/class/Game.php');
$game = new Game();
//print_r($game->setgame());
$game->setgame();
if($game->_status!=1){
	header("Location: dashboard.php");
	die();
}
// ============ GET GAME ==============

if($game->_turn!=$_SESSION["loggedUser"]["id"]){
	header("Location: dashboard.php");
	die();
}

if(intval($_GET['cardNum'])==-1){
	// Things Checked
	// -> Use logged in
	// -> Game is running
	// -> This is valid turn
	$game->drawCard();
}else{
	require_once('resources/class/Card.php');
	$card = new Card();

	if( !$card->isPlayable($game->_top_card,intval($_GET['cardNum']),$game->_bounty) ){
		header("Location: dashboard.php");
		die();
	}
	if(!$game->removeCard($_SESSION["loggedUser"]["id"],intval($_GET['cardNum']))){
		header("Location: dashboard.php");
		die();
	}else{
		// Things Checked
		// -> Use logged in
		// -> Game is running
		// -> This is valid turn
		// -> Card is playable
		// -> Card belongs to this user
		$game->takeTurn(intval($_GET['cardNum']));
	}
}



header("Location: dashboard.php");
die();
?>

