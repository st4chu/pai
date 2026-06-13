<?php

class DB{
    public $conn;

    public function getConn(){
        $this->conn = null;
        #$username = file_get_contents("login.txt");
        #$password = file_get_contents("pass.txt");

        $username = "handler";
        $password ="QWEasd123";
        $connectionInfo = array("UID" => $username, "pwd" => $password, "Database" => "kalendarz", "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
        $serverName = "tcp:kalendarz-sqldb.database.windows.net,1433";
        $this->conn = sqlsrv_connect($serverName, $connectionInfo);
        return $this->conn;
    }
}