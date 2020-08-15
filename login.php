<?php

if( !isset($_POST['un']) || !isset($_POST['pass']) || strcmp($_POST['un'],"")==0 || strcmp($_POST['pass'],"")==0 ){
	header("Location: index.php");
	die();
}else{

	//help: https://www.w3schools.com/php/php_mysql_select.asp

	require_once('resources/class/Player.php');

	$player = new Player();
	$res = $player->loginPlayer($_POST['un'],$_POST['pass']);

	if($res[0]){
		header("Location: dashboard.php");
		die();
	}else{
		require_once('resources/html/header.html');
		echo '<div class="alert alert-danger"><strong>Error!</strong> '.$res[2].'</div><br/><a class="btn btn-success" href="index.php">return back to login</a>';
		require_once('resources/html/footer.html');
	}

}

?>