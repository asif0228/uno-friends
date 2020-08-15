<?php
require_once('resources/dao/db.php');
class GameDao {

	function __construct() {}

	function getGame(){
		$db = new DB();
		$db->connect();
		if($db->is_connected){
			$sql = "SELECT * FROM games LIMIT 1;";
			$stmt = $db->conn->prepare($sql);
			//$stmt->bind_param('s', $un); // 's' specifies the variable type => 'string'

			$stmt->execute();

			$result = $stmt->get_result();
			if ($result->num_rows > 0) {
				$result = $result->fetch_assoc();
				$db->close();
				return $result;
			}
			// while ($row = $result->fetch_assoc()) {
			//     // Do something with $row
			// }return [false,-1,"Username Aleady Exist.",null];
			$db->close();
			return null;
		}else{
			return null;
		}
	}

	function getTurn(){
		$db = new DB();
		$db->connect();
		if($db->is_connected){
			$sql = "SELECT turn FROM games LIMIT 1;";
			$stmt = $db->conn->prepare($sql);
			//$stmt->bind_param('s', $un); // 's' specifies the variable type => 'string'

			$stmt->execute();

			$result = $stmt->get_result();
			if ($result->num_rows > 0) {
				$result = $result->fetch_assoc();
				$db->close();
				return $result;
			}
			// while ($row = $result->fetch_assoc()) {
			//     // Do something with $row
			// }return [false,-1,"Username Aleady Exist.",null];
			$db->close();
			return null;
		}else{
			return null;
		}
	}

	function updateGame($players,$player_cards,$rest_cards,$turn,$direction,$top_card,$bounty,$status){
		$db = new DB();
		$db->connect();
		if($db->is_connected){
			$sql = "UPDATE games SET players=?, player_cards=?, rest_cards=?, turn=?, direction=?, top_card=?, bounty=?, status=? WHERE id=1;";
			$stmt = $db->conn->prepare($sql);
			$stmt->bind_param('sssiiiii', $players,$player_cards,$rest_cards,$turn,$direction,$top_card,$bounty,$status);

			if ($stmt->execute()) {
				$db->close();
				return true;
			}

			$db->close();
			return false;
		}else{
			return false;
		}
	}

}

?>