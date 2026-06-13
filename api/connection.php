<?php

class DB{
    public $conn;

    public function getConn(){
        $this->conn = null;
        $username = file_get_contents('../keys/login.txt');
        $password = file_get_contents('../keys/pass.txt');

        $connectionInfo = array("UID" => $username, "pwd" => $password, "Database" => "kalendarz", "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
        $serverName = "tcp:kalendarz-sqldb.database.windows.net,1433";
        $conn = sqlsrv_connect($serverName, $connectionInfo);
        return $this->conn;
    }
}