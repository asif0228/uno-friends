<?php

// It needs players array to print score board

if( !isset($_SESSION["loggedUser"]) ){
	header("Location: index.php");
	die();
}

?>

<h3>Score Board</h3>
<hr/>

<table class="table table-dark text-center" style="width:100%;">
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
				echo "<td>".$i."</td>";
				if($game->_status!=1 && $game->_turn==$player->_id)
					echo "<td>".$player->_username." (W)</td>";
				else
					echo "<td>".$player->_username."</td>";
				echo "<td>".$player->_played."</td>";
				echo "<td>".$player->_won."</td>";
				echo "</tr>";
				$i++;
			}
		?>
	</tbody>
</table>