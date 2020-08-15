<?php require_once('resources/html/header.html'); ?>

<?php

if( !isset($_POST['regun']) || !isset($_POST['regmail']) || !isset($_POST['regpass']) || strcmp($_POST['regun'],"")==0 || strcmp($_POST['regmail'],"")==0 || strcmp($_POST['regpass'],"")==0 ){
	header("Location: index.php");
	die();
}

if (filter_var($_POST['regmail'], FILTER_VALIDATE_EMAIL)){
	require_once('resources/class/Player.php');
	$player = new Player();
	$player->_id = null;
	$player->_username = $_POST['regun'];
	$player->_email = $_POST['regmail'];
	$player->_role = 2;
	$player->_status = 2;
	$player->_played = 0;
	$player->_won = 0;
	$res = $player->registerPlayer($_POST['regpass']);
	$msg = $res[2];
}else $msg = "Given Email is not a valid email address";

?>

<div class="jumbotron">
  <h1>Read Message Below</h1>
  <p><?php echo $msg; ?></p>
  <a class="btn btn-success" href="index.php">return back to login</a>
</div>

<?php require_once('resources/html/footer.html'); ?>
