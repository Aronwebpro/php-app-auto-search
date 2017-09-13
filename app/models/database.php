<?php 
require_once(LIB_PATH."config/config.php");


class MySQLDatabase {

	private $connection;

	function __construct() {
		$this->open_connection();
	}
	
	public function open_connection() {
		$this->connection = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
		
		//Test if connection occured.
		if(mysqli_connect_errno()) {
		  die("Database connection failed: " . mysqli_connect_error() . "( " . mysqli_connect_errno() . ")" );
		}
	}
	
	public function closed_connection() {
		if (isset($this->connection)) {
			mysqli_close($this->connection);
			unset($this->connection);
		}
	}
	
	public function query($sql) {
		$result = mysqli_query($this->connection, $sql);
		$this->confirm_query($result);
		return $result;
	}
	
	public function confirm_query($result) {
  		if (!$result) {
    		die("Database query failed.");
  		}
	}

	public function escape_value($string) {
		$escaped_string = mysqli_real_escape_string($this->connection, $string);
		return $escaped_string;
	}

	public function fetch_array($result_set) {
		return mysqli_fetch_array($result_set);
	}
	// "database neutral" functions 
	public function num_rows($result_set) {
		return mysqli_num_rows($result_set);
	}

	public function insert_id() {
		// get the last id inserted over the current db
		return mysqli_insert_id($this->connection); 
	}

	public function affected_rows() {
		return mysqli_affected_rows($this->connection);
	}

}

$database = new MySQLDatabase();
$db =& $database;

?>