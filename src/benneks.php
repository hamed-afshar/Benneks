<?php

class user {

    public $conn;
    public function __construct() {
        $this->conn = new mysqli("174.142.210.218", "benneks", "13651362", "benneks");
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

class paginator {

    public $_conn;
    private $_limit;
    private $_page;
    private $_query;
    private $_total;

    public function __construct($query) {
        $this->_query = $query;
        $this->_conn = new mysqli("174.142.210.218", "benneks", "13651362", "benneks");
        $result = $this->_conn->query($this->_query);
        $this->_total = mysqli_num_rows($result);
    }

    public function getData($page, $limit) {
        $this->_limit = $limit;
        $this->_page = $page;
        if ($this->_limit == 'all') {
            $query = $this->_query;
        } else {
            $query = $this->_query . "LIMIT " . (($this->_page - 1) * $this->_limit) . ", $this->_limit";
        }
        $result = $this->_conn->query($query);
        while ($row = $result->fetch_assoc()) {
            $allResults[] = $row;
        }
        $resultObj = new stdClass();
        $resultObj->page    = $this->_page;
        $resultObj->limit   = $this->_limit;
        $resultObj->total   = $this->_total;
        $resultObj->data    = $allResults;    

        return $resultObj;
    }

    public function createLinks($list_class) {
        if ($this->_limit == 'all') {
            return '';
        }
        $last = ceil($this->_total / $this->_limit);
        $start = (($this->_page - 1) > 0) ? $this->_page * $this->_limit : 0;
        $end = $last;
        $html = '<ul class="' . $list_class . '">';
        $class = ( $this->_page == 1 ) ? "disabled" : "";
        $html .= '<li class="' . $class . '"><a href="?limit=' . $this->_limit . '&page=' . ($this->_page) . '">&laquo;</a></li>';
        /*if ($start > 1) {
            $html .= '<li><a href="?limit=' . $this->_limit . '&page=1">1</a></li>';
            $html .= '<li class="disabled"><span>...</span></li>';
        }*/
        for ($i = $start; $i <= $end; $i++) {
            $class = ( $this->_page == $i ) ? "active" : "";
            $html .= '<li class="' . $class . '"><a href="?limit=' . $this->_limit . '&page=' . $i . '">' . $i . '</a></li>';
        }
        /*if ($end < $last) {
            $html .= '<li class="disabled"><span>...</span></li>';
            $html .= '<li><a href="?limit=' . $this->_limit . '&page=' . $last . '">' . $last . '</a></li>';
        }*/
        $class = ( $this->_page == $last ) ? "disabled" : "";
        $html .= '<li class="' . $class . '"><a href="?limit=' . $this->_limit . '&page=' . ( $this->_page + 1 ) . '">&raquo;</a></li>';

        $html .= '</ul>';
        return $html;
    }

}

?>