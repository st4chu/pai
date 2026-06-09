<?php

class DB{
    $serverName - "tc-ms-us.database.windows.net"   ;
    $connectionOptions = array(
        "Database" => "kalendarz",
        "Uid" => "kalendarz",
        "PWD" => "QWEasd123"
    );

    public function getConn(){
        $this->conn = null;

    try {
        $conn = sqlsrv_connect($serverName, $connectionOptions);
        #$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (exception $e) {
        print("Error connecting to SQL Server.");
        die(print_r($e));
    }
        return $this->conn;
    }
}