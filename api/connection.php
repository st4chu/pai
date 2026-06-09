<?php

class DB{
    public $host = "tc-ms-db.database.windows.net";
    public $database = "azure-db";
    public $username = "CloudSA349e26e7";
    public $password = "Chmurowa123!";
    public $conn;

    public function getConn(){
        $this->conn = null;

       try{
			$this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database, $this->username, $this->password);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $e){
			echo "connection error: " . $e->getMessage();
		}
        return $this->conn;
    }
}