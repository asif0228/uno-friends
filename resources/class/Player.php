<?php
require_once('resources/php/session.php');
require_once('resources/dao/playerDao.php');

class Player {
	public $_id;
	public $_username;
	public $_email;
	public $_role;
	public $_status;
	public $_played;
	public $_won;
	// public $_cards;
	// public $_isTurn;

	function __construct() {}

	function loginPlayer($un,$pass){
		// filter values
		$un = $this->filterVaiable($un);
		$pass = $this->filterVaiable($pass);
		// get the player by username
		$pd = new PlayerDao();
		$p = $pd->getPlayerByUsername($un);
		if($p!=null){
			// match password
			if(password_verify($pass, $p["pass"])) {
				if($p["status"]!=1){ // is player active
					return [false,-2,"Please ask site admin to activate your account.",null]; // [status,code,message,data]
				}
				$this->_id = $p["id"];
				$this->_username = $p["un"];
				$this->_email = $p["email"];
				$this->_role = $p["role"];
				$this->_status = $p["status"];
				$this->_played = $p["played"];
				$this->_won = $p["won"];
				$p["pass"] = null;
				$_SESSION["loggedUser"] = $p;
			    return [true,1,"Success",null]; // [status,code,message,data]
			}
			return [false,-1,"Password Error.",null]; // [status,code,message,data]
		}else{
			return [false,0,"Usename Error.",null]; // [status,code,message,data]
		}
	}

	function registerPlayer($pass){
		$this->_username = $this->filterVaiable($this->_username);
		$this->_email = $this->filterVaiable($this->_email);
		$pass = $this->filterVaiable($pass);
		$pass = password_hash($pass, PASSWORD_DEFAULT);
		$pd = new PlayerDao();
		return $pd->addPlayer($this->_username,$this->_email,$pass,$this->_role,$this->_status,$this->_played,$this->_won);
	}

	function filterVaiable($v){
		return str_replace("<script","",trim($v));
	}
}

?>