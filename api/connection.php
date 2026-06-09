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
    $conn = new PDO("sqlsrv:server = tcp:tc-ms-us.database.windows.net,1433; Database = kalendarz", "kalendarz", "QWEasd123");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (PDOException $e) {
        print("Error connecting to SQL Server.");
        die(print_r($e));
    }

        return $this->conn;
    }
}