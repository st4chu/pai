<?php

class DB{
    public $host = "tc-ms-db.database.windows.net";
    public $database = "azure-db";
    public $username = "CloudSA349e26e7";
    public $password = "Chmurowa123!";
    public $conn;
    

    public function getConn(){
        $this->conn = null;

    try {
        $conn = new PDO("sqlsrv:server = tcp:tc-ms-db.database.windows.net,1433; Database = azure-db", "CloudSA349e26e7", "Chmurowa123!");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (PDOException $e) {
        print("Error connecting to SQL Server.");
        die(print_r($e));
    }

    $connectionInfo = array("UID" => "CloudSA349e26e7", "pwd" => "{your_password_here}", "Database" => "azure-db", "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
    $serverName = "tcp:tc-ms-db.database.windows.net,1433";
    $conn = sqlsrv_connect($serverName, $connectionInfo);

        return $this->conn;
    }
}