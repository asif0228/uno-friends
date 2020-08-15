<?php

class DB {
	private $_servername = "localhost";
	// private $_username = "sundorif_uno";
	// private $_password = "3&7V6K4FBJc@hsd_";
	// private $_dbname = "sundorif_uno";
	private $_username = "root";
	private $_password = "";
	private $_dbname = "uno";
	public $conn;
	public $is_connected;

	function __construct() {
		$this->is_connected = false;
	}

	function connect(){
		// Create connection
		$this->conn = new mysqli($this->_servername, $this->_username, $this->_password, $this->_dbname);
		// Check connection
		if ($this->conn->connect_error) {
		  $this->is_connected = false;
		}
		$this->is_connected = true;
		return $this->is_connected;
	}

	function close(){
		$this->conn->close();
	}
}

?>