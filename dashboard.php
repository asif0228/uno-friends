<?php require_once('resources/php/session.php'); ?>
<?php
if( !isset($_SESSION["loggedUser"]) ){
	header("Location: index.php");
	die();
}


// ============ GET GAME ==============
require_once('resources/class/Game.php');
$game = new Game();
//print_r($game->setgame());
$game->setgame();
// ============ GET GAME ==============

// ============ GET Players ==============
require_once('resources/dao/playerDao.php');
$pd = new PlayerDao();
$players = $pd->getPlayersByLimit(100);
// ============ GET Players ==============

?>
<?php require_once('resources/html/header.html'); ?>
<style type="text/css">
	.top-button .col-sm-3{
			margin: 0;
			padding: 0;
	}
	.top-button .btn{
			border-radius: 0;
	}
	.status-window .col-sm-4{
		background-color: white;
		border-radius: 5%;
		border: 1px solid green;
	}
	.cards-window .col-sm-12{
		background-color: white;
		border-radius: 5%;
		border: 1px solid green;
	}
</style>
<div class="row top-button">
	<?php
		if($_SESSION["loggedUser"]["role"]==1){
			if($game->_status!=1) echo '<div class="col-sm-3"><button class="btn btn-block btn-primary" data-toggle="modal" data-target="#createGameModal">Game</button></div>';
			else echo '<div class="col-sm-3"></div>';
			echo '<div class="col-sm-3"><button class="btn btn-block btn-info">Players</button></div>';
		}else{
			echo '<div class="col-sm-3"></div>';
			echo '<div class="col-sm-3"></div>';
		}
	?>
	<div class="col-sm-3"><button class="btn btn-block btn-warning">Passwod Change</button></div>
	<div class="col-sm-3"><a href="logout.php" class="btn btn-block btn-danger">Logout</a></div>
</div>

<br/>

<div class="row text-center status-window">
	<div class="col-sm-4">
		<?php require_once('resources/php/scoreboard.php'); ?>
	</div>
	<div class="col-sm-4">
		<?php require_once('resources/php/gamestatus.php'); ?>
	</div>
	<div class="col-sm-4">
		<?php require_once('resources/php/gamedetails.php'); ?>
	</div>
</div>

<br/>

<div class="row text-center cards-window">
	<div class="col-sm-12">
		<?php if($game->_status==1) require_once('resources/php/cards.php'); else echo "<h1>NO GAME FOUND</h1>"; ?>
	</div>
</div>

<br/>

<div class="row top-button">
	<?php
		if($_SESSION["loggedUser"]["role"]==1){
			if($game->_status==1) echo '<div class="col-sm-12"><a href="endGame.php" class="btn btn-block btn-primary">End Game</a></div>';
			else echo '<div class="col-sm-12"></div>';
		}
	?>
</div>


<?php require_once('resources/html/footer.html'); ?>


<!-- The Modal -->
<div class="modal" id="createGameModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Create New Game</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form action="createGame.php" method="post">
			<table class="table table-dark table-striped text-center" style="width:100%;">
				<thead>
				  <tr>
				  	<th>NO</th>
				    <th>USERNAME</th>
				    <th>PLAYED</th>
				    <th>WON</th>
				  </tr>
				</thead>
				<tbody>
					<?php
						$i=1;
						foreach ($players as $player) {
							echo "<tr>";
							echo "<td><input name='players[]' type='checkbox' class='form-check-input' value='".$player->_id."' /> ".$i."</td>";
							echo "<td>".$player->_username."</td>";
							echo "<td>".$player->_played."</td>";
							echo "<td>".$player->_won."</td>";
							echo "</tr>";
							$i++;
						}
					?>
				</tbody>
			</table>
			<button type="submit" class="btn btn-success">Create Game</button>
		</form>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>


<?php require_once('resources/html/turnChange.html'); ?>