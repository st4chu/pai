<?php

class DB{
    public $conn;
    public $login =file_get_contents("../keys/login.txt");
    public $pass = file_get_contents("../keys/pass.txt");

    public function __construct(){
        try {
            $connectionInfo = array("UID" => $this->login, "pwd" => $this->pass, "Database" => "kalendarz", "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
            $serverName = "tcp:kalendarz-sqldb.database.windows.net,1433";
            $this->conn = sqlsrv_connect($serverName, $connectionInfo);
            echo "\n $connectionInfo \n $servername \n";
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