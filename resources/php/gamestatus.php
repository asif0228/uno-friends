<?php

// it requires game variable

if( !isset($_SESSION["loggedUser"]) ){
	header("Location: index.php");
	die();
}

?>


<h3>Game Status</h3>
<hr/>

<?php if($game->_status==1){ ?>

	<h6>Last Card</h6>
	<hr/>

	<div class="row">
		<div class="col-sm-3"></div>
		<div class="col-sm-6">
			<?php
				if($game->_top_card!=-1){

					require_once('resources/class/Card.php');
					$crd = new Card();
					echo $crd->getCardDesign($game->_top_card,false,'');

				}else echo "<h1>PLAY ANY CARD</h1>";
			?>
			<br/>
		</div>
	</div>
	
<?php }else echo "<h1>NO GAME FOUND</h1>"; ?>