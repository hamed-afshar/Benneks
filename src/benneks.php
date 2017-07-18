<?php
class user {
    public $conn;
    public function __construct() {
        //$this->conn = new mysqli("185.55.226.141", "root", "Ha13651362", "benneks");
        //$this->conn = new mysqli("174.142.210.218", "benneks", "13651362", "benneks");
        $this->conn = new mysqli("localhost", "root", "", "benneks");
        if ($this->conn->connect_error) {
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
    
    public function executeQuery($query) {
        return $this->conn->query($query);
    }

}

?>