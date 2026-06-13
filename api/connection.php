<?php

class DB{
    public $username = file_get_contents('../keys/login.txt');
    public $password = file_get_contents('../keys/pass.txt');
    public $conn;

    public function getConn(){
        $this->conn = null;

        try{
            $this->conn = new PDO("sqlsrv:server = tcp:kalendarz-sqldb.database.windows.net,1433; Database = kalendarz", $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e) {
            print("Error connecting to SQL Server.");
            die(print_r($e));
        }
        return $this->conn;
    }
}