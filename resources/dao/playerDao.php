<?php
require_once('resources/dao/db.php');
class PlayerDao {

	function __construct() {

	}

	function getPlayerByUsername($un){
		$db = new DB();
		$db->connect();
		if($db->is_connected){
			$sql = "SELECT * FROM players WHERE un=? LIMIT 1;";
			$stmt = $db->conn->prepare($sql);
			$stmt->bind_param('s', $un); // 's' specifies the variable type => 'string'

			$stmt->execute();

			$result = $stmt->get_result();
			if ($result->num_rows > 0) {
				$result = $result->fetch_assoc();
				$db->close();
				return $result;
			}
			// while ($row = $result->fetch_assoc()) {
			//     // Do something with $row
			// }
			$db->close();
			return null;
		}else{
			return null;
		}
	}

	function addPlayer($un,$email,$pass,$role,$status,$played,$won){
		$db = new DB();
		$db->connect();
		if($db->is_connected){
			$sql = "SELECT * FROM players WHERE un=? LIMIT 1;";
			$stmt = $db->conn->prepare($sql);
			$stmt->bind_param('s', $un); // 's' specifies the variable type => 'string'

			$stmt->execute();

			$result = $stmt->get_result();
			if ($result->num_rows > 0) {
				$db->close();
				return [false,-1,"Username Aleady Exist.",null];
			}

			$sql = "SELECT * FROM players WHERE email=? LIMIT 1;";
			$stmt = $db->conn->prepare($sql);
			$stmt->bind_param('s', $email); // 's' specifies the variable type => 'string'

			$stmt->execute();

			$result = $stmt->get_result();
			if ($result->num_rows > 0) {
				$db->close();
				return [false,-2,"Email Aleady Exist.",null];
			}

			$sql = "INSERT INTO players VALUES (null,?,?,?,?,?,?,?);";
			$stmt = $db->conn->prepare($sql);
			$stmt->bind_param('sssiiii', $un,$email,$pass,$role,$status,$played,$won);

			if ($stmt->execute()) {
				$db->close();
				return [true,1,"Please ask site admin to activate the account.",null];
			}
			$db->close();
			return [false,-3,"Something went wrong.",null];
		}else{
			return [false,-4,"Database not connected.",null];
		}
	}

	function getPlayersByLimit($limit){
		require_once('resources/class/Player.php');
		$db = new DB();
		$db->connect();
		if($db->is_connected){
			$sql = "SELECT * FROM players ORDER BY won DESC, played ASC LIMIT ".$limit.";";
			$stmt = $db->conn->prepare($sql);
			//$stmt->bind_param('s', $un); // 's' specifies the variable type => 'string'

			$stmt->execute();

			$result = $stmt->get_result();
			if ($result->num_rows > 0) {
				$players = [];
				while ($row = $result->fetch_assoc()) {
					$tmpPlayer = new Player();
					$tmpPlayer->_id = $row["id"];
					$tmpPlayer->_username = $row["un"];
					$tmpPlayer->_email = $row["email"];
					$tmpPlayer->_role = $row["role"];
					$tmpPlayer->_status = $row["status"];
					$tmpPlayer->_played = $row["played"];
					$tmpPlayer->_won = $row["won"];
					array_push($players, $tmpPlayer);
				}
				$db->close();
				return $players;
			}
			$db->close();
			return null;
		}else{
			return null;
		}
	}

	function finishGame($players, $playerId){
		$db = new DB();
		$db->connect();
		if($db->is_connected){
			$sql = "UPDATE players SET played=played+1 WHERE id IN (".$players.");";
			$stmt = $db->conn->prepare($sql);
			//$stmt->bind_param('s', $players);
			$stmt->execute();

			$sql = "UPDATE players SET won=won+1 WHERE id=?;";
			$stmt = $db->conn->prepare($sql);
			$stmt->bind_param('i', $playerId);
			$stmt->execute();
			
			$db->close();
			return true;
		}
		return false;
	}
}

?>