<?php

// it requires game variable and players aay

if( !isset($_SESSION["loggedUser"]) ){
	header("Location: index.php");
	die();
}

?>


<h3>Game Details</h3>
<hr/>

<?php if($game->_status==1){ ?>

<h6>Players</h6>
<hr/>

<table class="table table-dark text-center" style="width:100%;">
	<thead>
	  <tr>
	  	<th>NO</th>
	    <th>USERNAME</th>
	    <th>TURN</th>
	    <th>CARDS</th>
	  </tr>
	</thead>
	<tbody>
		<?php
			$i=0;
			foreach ($game->_players as $player_id) {
				$tmpPlayer = null;
				foreach ($players as $player) {
					if($player->_id==$player_id){
						$tmpPlayer = $player;
						break;
					}
				}
				if($game->_turn==$tmpPlayer->_id)
					echo "<tr class='table-active'>";
				else
					echo "<tr>";
				echo "<td>".($i+1)."</td>";
				echo "<td>".$tmpPlayer->_username."</td>";
				if($game->_turn==$tmpPlayer->_id)
					echo "<td>YES</td>";
				else
					echo "<td>NO</td>";
				if(count($game->_player_cards[$i])>1)
					echo "<td>".count($game->_player_cards[$i])."</td>";
				else
					echo "<td>uno</td>";
				echo "</tr>";
				$i++;
			}
		?>
	</tbody>
</table>

<?php }else echo "<h1>NO GAME FOUND</h1>"; ?>

