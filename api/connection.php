<?php

class DB{
    public function __construct(){
    try {
        $login = file_get_contents("../keys/login.txt");
        $pass = file_get_contents("../keys/pass.txt");        
        $connectionInfo = array("UID" => $login, "pwd" => $pass, "Database" => "kalendarz", "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
        $serverName = "tcp:kalendarz-sqldb.database.windows.net,1433";
        $conn = sqlsrv_connect($serverName, $connectionInfo);
    }
    catch (PDOException $e) {
        print("Error connecting to SQL Server.");
        die(print_r($e));
    }
    }
    public function getConn(){
        return $this->conn;
    }
}
?>