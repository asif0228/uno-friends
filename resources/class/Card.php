<?php
class Card {
	public static $_deck = [[" ","all"],[" ","all"],[" ","all"],[" ","all"],["+4","all"],["+4","all"],["+4","all"],["+4","all"],["0","red"],["1","red"],["2","red"],["3","red"],["4","red"],["5","red"],["6","red"],["7","red"],["8","red"],["9","red"],["<-","red"],["X","red"],["+2","red"],["1","red"],["2","red"],["3","red"],["4","red"],["5","red"],["6","red"],["7","red"],["8","red"],["9","red"],["<-","red"],["X","red"],["+2","red"],["0","green"],["1","green"],["2","green"],["3","green"],["4","green"],["5","green"],["6","green"],["7","green"],["8","green"],["9","green"],["<-","green"],["X","green"],["+2","green"],["1","green"],["2","green"],["3","green"],["4","green"],["5","green"],["6","green"],["7","green"],["8","green"],["9","green"],["<-","green"],["X","green"],["+2","green"],["0","blue"],["1","blue"],["2","blue"],["3","blue"],["4","blue"],["5","blue"],["6","blue"],["7","blue"],["8","blue"],["9","blue"],["<-","blue"],["X","blue"],["+2","blue"],["1","blue"],["2","blue"],["3","blue"],["4","blue"],["5","blue"],["6","blue"],["7","blue"],["8","blue"],["9","blue"],["<-","blue"],["X","blue"],["+2","blue"],["0","yellow"],["1","yellow"],["2","yellow"],["3","yellow"],["4","yellow"],["5","yellow"],["6","yellow"],["7","yellow"],["8","yellow"],["9","yellow"],["<-","yellow"],["X","yellow"],["+2","yellow"],["1","yellow"],["2","yellow"],["3","yellow"],["4","yellow"],["5","yellow"],["6","yellow"],["7","yellow"],["8","yellow"],["9","yellow"],["<-","yellow"],["X","yellow"],["+2","yellow"]];
	public static $_colorMeaning = ["all","red","green","blue","yellow"]; // 0 1 2 3 4
	public static $_numberMeaning = ["0","1","2","3","4","5","6","7","8","9","<-","X","+2"," ","+4"]; // <- = reverse, X = skip, (space) = neutral

	function __construct() {}

	function getCardDesign($num,$isClickable,$methodName){
		if($isClickable)
			return '<div onclick="'.$methodName.'('.$num.');" class="card b-white"><div class="card-inner b-'.Card::$_deck[$num][1].'"><div class="card-number b-white c-'.Card::$_deck[$num][1].'">'.Card::$_deck[$num][0].'</div></div></div>';
		else
			return '<div class="card b-white"><div class="card-inner b-'.Card::$_deck[$num][1].'"><div class="card-number b-white c-'.Card::$_deck[$num][1].'">'.Card::$_deck[$num][0].'</div></div></div>';
	}

    // Is the card given by player is playable
	function isPlayable($topCard,$yourCard,$bounty){
        // User plays special card or top card is all card
		if($yourCard<=7 || $topCard<=3)
            return true;
        $tc = Card::$_deck[$topCard]; // top card [type, color]
        $yc = Card::$_deck[$yourCard]; // players card [type, color]
        // if top card is +4
        if( strcmp($tc[0],"+4")==0 ){
            if($bounty==1) return true; // another player has taken bounty
            else if( strcmp($yc[0],"+2")==0 || strcmp($yc[0],"<-")==0 || strcmp($yc[0],"X")==0 )
                return true;
            return false;
        }
        // Top card is +2
        if( strcmp($tc[0],"+2")==0 ){
            if( $bounty==1 ){ // another player has taken bounty
                if( strcmp($yc[0],"+2")==0 || strcmp($tc[1],$yc[1])==0 )
                    return true;
                return false;
            } 
            else if( strcmp($yc[0],"+2")==0 ) return true;
            return false;

        }
        // Top card is X
        if( strcmp($tc[0],"X")==0 ){
            if( strcmp($yc[0],"X")==0 || strcmp($tc[1],$yc[1])==0 ) return true;
            return false;

        }
        // Top card is <-
        if( strcmp($tc[0],"<-")==0 ){
            if( strcmp($yc[0],"<-")==0 ||  strcmp($tc[1],$yc[1])==0 ) return true;
            return false;
        }
        // Any other Top Card
        if( strcmp($yc[0],$tc[0])==0 ||  strcmp($yc[1],$tc[1])==0 )
            return true;
        return false;
	}

}

?>