<?php
class user {
	private $conn;
	public function __construct() {
		$this->conn = new mysqli("174.142.210.218", "benneks", "13651362", "benneks");
		if($this->conn->connect_error) {
			die("Connection Failed : " . $this->conn->connect_error);
		}
		return $this->conn;
	}
	
	public function existUser($query) {
		return $this->conn->query($query);
	}
	
	public function addUser($query) {
		 return $this->conn->query($query);
	}
	
	public function loginUser($query) {
		return $this->conn->query($query);
	}
        public function addOrderUser($query) {
            return $this->conn->query($query);
        }
	
}



?>