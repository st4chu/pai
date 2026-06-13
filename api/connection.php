<?php

class DB{
    public $conn;

    public function getConn(){
        $this->conn = null;

        try{
            $username = file_get_contents('../keys/login.txt');
            $password = file_get_contents('../keys/pass.txt');
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