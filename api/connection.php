<?php

class DB{
    private $conn;
    public function __construct(){
    try {
        $login = file_get_contents("../keys/login.txt");
        $pass = file_get_contents("../keys/pass.txt");        
        $this->conn = new PDO("sqlsrv:server = tcp:kalendarz-sqldb.database.windows.net,1433; Database = kalendarz", $login, $pass);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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