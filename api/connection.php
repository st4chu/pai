<?php

class DB{
    public $conn;
    private $username;
    private $password;
    public function __construct($l, $p){
        $this->username = $l;
        $this->password = $p;
    }

    public function getConn(){
        $this->conn = null;
        
        #$username = "handler";
        #$password ="QWEasd123";
        $connectionInfo = array("UID" => $this->username, "pwd" => $this->password, "Database" => "kalendarz", "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
        $serverName = "tcp:kalendarz-sqldb.database.windows.net,1433";
        $this->conn = sqlsrv_connect($serverName, $connectionInfo);
        return $this->conn;
    }
}