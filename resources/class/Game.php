<?php
require_once('resources/dao/gameDao.php');
require_once('resources/dao/playerDao.php');
require_once('resources/class/Card.php');

class Game {

	public $_players;
	public $_player_cards;
	public $_rest_cards;
	public $_turn;
	public $_direction;
	public $_top_card;
	public $_bounty;
	public $_status;

	function __construct(){}

	function setgame(){
		$gd = new GameDao();
		$game = $gd->getGame();
		if($game!=null){
			$this->_players = unserialize($game["players"]);
			$this->_player_cards = unserialize($game["player_cards"]);
			$this->_rest_cards = unserialize($game["rest_cards"]);
			$this->_turn = intval($game["turn"]);
			$this->_direction = intval($game["direction"]);
			$this->_top_card = intval($game["top_card"]);
			$this->_bounty = intval($game["bounty"]);
			$this->_status = intval($game["status"]);
			return [true,0,"Success",null]; // [status,code,message,data]
		}else{
			return [false,0,"No Game Found.",null]; // [status,code,message,data]
		}
	}

	function getTurn(){
		$gd = new GameDao();
		$game = $gd->getTurn();
		if($game!=null){
			return $game["turn"];
		}
		return -1;
	}

	function newgame($players){
		$gd = new GameDao();
		$allCards = [];
		$winnerNotInTheGame = true;
		for($i=0;$i<108;$i++){
			array_push($allCards, false);
		}
		$player_cards = [];
		$rest_cards = [];
		foreach ($players as $player) {
			if($player==$this->_turn) $winnerNotInTheGame = false;
			$tmpPlayerCard = [];
			for($i=0;$i<7;$i++){
				$tmpNum = rand(0,107);
				while($allCards[$tmpNum]) $tmpNum = rand(0,107);
				array_push($tmpPlayerCard,$tmpNum);
				$allCards[$tmpNum] = true;
			}
			array_push($player_cards,$tmpPlayerCard);
		}
		for($i=0;$i<108;$i++){
			if(!$allCards[$i])
				array_push($rest_cards,$i);
		}
		if($this->_turn==-1 || $winnerNotInTheGame){
			$this->_turn = $players[rand(0,count($players)-1)];
		}
		return $gd->updateGame(serialize($players),serialize($player_cards),serialize($rest_cards),$this->_turn,$this->_direction,0,1,1);
	}

	function endgame(){
		$gd = new GameDao();
		return $gd->updateGame(serialize([]),serialize([]),serialize([]),-1,1,0,1,2);
	}

	function takeTurn($cardNum){
		// Seach the Player
		$playerIndex = -1;
		$playersIdString = null;
	    for ($i=0; $i < count($this->_players); $i++) {
	    	if($this->_turn==$this->_players[$i])
	    		$playerIndex = $i;
	    	if($playersIdString==null)
	    		$playersIdString = "".$this->_players[$i];
	    	else
	    		$playersIdString .= ",".$this->_players[$i];
	    }
	    if($playerIndex==-1) return false;

	    $gd = new GameDao(); // Create Game Table (Database: uno.game) instance
	    // Check if player has no card
	    if(count($this->_player_cards[$playerIndex])==0){
	    	// player wins
	    	$playerDao = new PlayerDao(); // Create Player Table (Database: uno.player) instance
	    	$playerDao->finishGame($playersIdString,$this->_turn);
	    	return $gd->updateGame(serialize([]),serialize([]),serialize([]),$this->_turn,$this->_direction,0,1,2);
	    }

	    $tc = Card::$_deck[$this->_top_card]; // top card [type, color]
        $yc = Card::$_deck[$cardNum]; // players card [type, color]
        $this->_top_card = $cardNum; // change the top card to given card

		if( strcmp($yc[0],"+2")==0 ){ // If player gave +2 card
			if($this->_bounty==1) $this->_bounty += 1;
			else $this->_bounty += 2;
		}else if( strcmp($yc[0],"+4")==0 ){ // If player gave +4 card
			if($this->_bounty==1) $this->_bounty += 3;
			else $this->_bounty += 4;
		}else $this->_bounty = 1;
		// If player gave <- card
		if( strcmp($yc[0],"<-")==0 ){
			if(count($this->_players)==2)
				return $gd->updateGame(serialize($this->_players),serialize($this->_player_cards),serialize($this->_rest_cards),$this->_turn,$this->_direction,$this->_top_card,$this->_bounty,$this->_status);
			$this->_direction = ($this->_direction==1?2:1); // change direction
		}
		
		$playerIndex = $this->changeTurn($playerIndex); // change turn
		// If player gave X card
		if( strcmp($yc[0],"X")==0 ) $playerIndex = $this->changeTurn($playerIndex);
		
		// save & update
		return $gd->updateGame(serialize($this->_players),serialize($this->_player_cards),serialize($this->_rest_cards),$this->_turn,$this->_direction,$this->_top_card,$this->_bounty,$this->_status);
	}

	function removeCard($playerId,$cardNum){
		// Seach the Player
		$playerIndex = -1;
	    for ($i=0; $i < count($this->_players); $i++) {
	    	if($playerId==$this->_players[$i]){
	    		$playerIndex = $i;
	    		break;
	    	}
	    }
	    if($playerIndex==-1) return false;
	    $isMyCard = false;
	    $newCards = [];
	    for ($i=0; $i < count($this->_player_cards[$playerIndex]); $i++) {
	    	if($this->_player_cards[$playerIndex][$i]==$cardNum){
	    		$isMyCard = true;
	    	}else
	    		array_push($newCards, $this->_player_cards[$playerIndex][$i]);
	    }
	    if($isMyCard) $this->_player_cards[$playerIndex] = $newCards;
		return $isMyCard;
	}

	function drawCard(){
		// Seach the Player
		$playerIndex = -1;
	    for ($i=0; $i < count($this->_players); $i++) {
	    	if($this->_turn==$this->_players[$i]){
	    		$playerIndex = $i;
	    		break;
	    	}
	    }
	    if($playerIndex==-1) return false;
	    // Count How many cards left in deck
		$cardCount = count($this->_rest_cards);
		// if deck has no card or less then bounty
		// do something - keeping for future
		// pick random cards from deck
		$pickedCards = [];
		for($i=0;$i<$this->_bounty;$i++){
			$tmpNum = rand(0,$cardCount-1);
			array_push($this->_player_cards[$playerIndex], $this->_rest_cards[$tmpNum]);
			array_push($pickedCards, $this->_rest_cards[$tmpNum]);
		}
		// remove taken cards from deck
		$newRestCard = [];
		for($i=0;$i<$cardCount;$i++){
			$cardTaken = false;
			for($j=0;$j<count($pickedCards);$j++){
				if($this->_rest_cards[$i]==$pickedCards[$j]){
					$cardTaken = true;
					break;
				}
			}
			if(!$cardTaken) array_push($newRestCard, $this->_rest_cards[$i]);
		}
		$this->_rest_cards = $newRestCard; // update deck
		// change turn
		$this->changeTurn($playerIndex);
		$gd = new GameDao(); // Create Game Table (Database: uno.game) instance
		$this->_bounty = 1; // resetting bounty to be 1
		// save and return
		return $gd->updateGame(serialize($this->_players),serialize($this->_player_cards),serialize($this->_rest_cards),$this->_turn,$this->_direction,$this->_top_card,$this->_bounty,$this->_status);
	}

	function changeTurn($playerIndex){
		if($this->_direction==1){ // forwad direction
			if(isset($this->_players[$playerIndex+1])){
				$this->_turn = $this->_players[$playerIndex+1]; // next player
				return ($playerIndex+1);
			}else{
				$this->_turn = $this->_players[0]; // first player
				return 0;
			}
		}else{ // backword direction
			if(isset($this->_players[$playerIndex-1])){
				$this->_turn = $this->_players[$playerIndex-1]; // pevious player
				return ($playerIndex-1);
			}else{
				$this->_turn = $this->_players[count($this->_players)-1]; // last player
				return (count($this->_players)-1);
			}
		}
	}
}
?>