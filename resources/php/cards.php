<?php

// it requires game variable

if( !isset($_SESSION["loggedUser"]) ){
	header("Location: index.php");
	die();
}

require_once('resources/class/Card.php');

$turnMsg = "Not Your Turn";
if(intval($game->_turn)==intval($_SESSION["loggedUser"]["id"])) $turnMsg = "Your Turn";

?>


<h3>Your Cards (<?php echo $turnMsg; ?>)</h3>
<hr/>


<div class="row">
  <?php
    $card = new Card(); // create Card Table (Database uno:card) instances
    // search for the player
    $playerIndex = -1;
    for ($i=0; $i < count($game->_players); $i++) {
    	if(intval($_SESSION["loggedUser"]["id"])==intval($game->_players[$i])){
    		$playerIndex = $i;
    		break;
    	}
    }
    if($playerIndex==-1){
    	echo "<h1>You are an Spectator.</h1>";
    }else{
    	$myCards = $game->_player_cards[$playerIndex];
        $isTurn = false;
        if(intval($_SESSION["loggedUser"]['id'])==intval($game->_turn)){
            $isTurn = true;
        }
    	for ($i=0; $i < count($myCards); $i++) {
	      echo '<div class="col-sm-2">';
	      echo $card->getCardDesign($myCards[$i],$isTurn,'cardClick');
	      echo '<br/></div>';
	    }
        if($isTurn){
            echo '<div class="col-sm-2">';
            echo '<button onclick="drawCard();" class="btn btn-block btn-success"><br/><br/><br/><h5>Draw +'.$game->_bounty.' Card<h5/><br/><br/><br/></button>';
            echo '</div>';
        }
    }

  ?>
</div>

<script type="text/javascript">
    var deck = [[" ","all"],[" ","all"],[" ","all"],[" ","all"],["+4","all"],["+4","all"],["+4","all"],["+4","all"],["0","red"],["1","red"],["2","red"],["3","red"],["4","red"],["5","red"],["6","red"],["7","red"],["8","red"],["9","red"],["<-","red"],["X","red"],["+2","red"],["1","red"],["2","red"],["3","red"],["4","red"],["5","red"],["6","red"],["7","red"],["8","red"],["9","red"],["<-","red"],["X","red"],["+2","red"],["0","green"],["1","green"],["2","green"],["3","green"],["4","green"],["5","green"],["6","green"],["7","green"],["8","green"],["9","green"],["<-","green"],["X","green"],["+2","green"],["1","green"],["2","green"],["3","green"],["4","green"],["5","green"],["6","green"],["7","green"],["8","green"],["9","green"],["<-","green"],["X","green"],["+2","green"],["0","blue"],["1","blue"],["2","blue"],["3","blue"],["4","blue"],["5","blue"],["6","blue"],["7","blue"],["8","blue"],["9","blue"],["<-","blue"],["X","blue"],["+2","blue"],["1","blue"],["2","blue"],["3","blue"],["4","blue"],["5","blue"],["6","blue"],["7","blue"],["8","blue"],["9","blue"],["<-","blue"],["X","blue"],["+2","blue"],["0","yellow"],["1","yellow"],["2","yellow"],["3","yellow"],["4","yellow"],["5","yellow"],["6","yellow"],["7","yellow"],["8","yellow"],["9","yellow"],["<-","yellow"],["X","yellow"],["+2","yellow"],["1","yellow"],["2","yellow"],["3","yellow"],["4","yellow"],["5","yellow"],["6","yellow"],["7","yellow"],["8","yellow"],["9","yellow"],["<-","yellow"],["X","yellow"],["+2","yellow"]];
    var turn = parseInt(<?php echo $game->_turn; ?>);
    var playerNum = parseInt(<?php echo $_SESSION["loggedUser"]['id']; ?>);
    var topCard = parseInt(<?php echo $game->_top_card; ?>);
    var bounty = parseInt(<?php echo $game->_bounty; ?>);
    function cardClick(cardNum){
        if(playerNum!=turn){
            alert("Not Your Turn.");
            return;
        }
        if(playable(cardNum)){
            if (confirm('Do you want to use this card?'))
                window.location.href = 'takeTurn.php?cardNum='+cardNum;
        }else{
            alert("This Card Cannot be played.");
            return;
        }
    }
    // Is the card given by player is playable
    function playable(cardNum){
        // User plays special card or top card is all card
        if(cardNum<=7 || topCard<=3)
            return true;
        // if top card is +4
        if(deck[topCard][0]=="+4"){
            if(bounty==1) return true; // another player has taken bounty
            else if(deck[cardNum][0]=="+2" || deck[cardNum][0]=="<-" || deck[cardNum][0]=="X")
                return true;
            return false;
        }
        // Top card is +2
        if(deck[topCard][0]=="+2"){
            if( bounty==1 ){ // another player has taken bounty
                if(deck[cardNum][0]=="+2" || deck[topCard][1]==deck[cardNum][1])
                    return true;
                return false;
            }
            else if(deck[cardNum][0]=="+2") return true;
            return false;

        }
        // Top card is X
        if(deck[topCard][0]=="X"){
            if(deck[cardNum][0]=="X" || deck[topCard][1]==deck[cardNum][1] ) return true;
            return false;

        }
        // Top card is <-
        if(deck[topCard][0]=="<-"){
            if(deck[cardNum][0]=="<-" || deck[topCard][1]==deck[cardNum][1] ) return true;
            return false;
        }
        // Any other Top Card
        if(deck[cardNum][0]==deck[topCard][0] || deck[cardNum][1]==deck[topCard][1] )
            return true;
        return false;
    }
    function drawCard(){
        if(playerNum!=turn){
            alert("Not Your Turn.");
            return;
        }
        if (confirm('Do you want to use draw card?'))
            window.location.href = 'takeTurn.php?cardNum=-1';
    }
</script>