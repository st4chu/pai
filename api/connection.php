<?php

class DB{
    public function getConn(){
        $conn = null;

       try{
            $login = file_get_contents('../keys/login.txt');
            $pass = file_get_contents('../keys/pass.txt')
			$conn = new PDO("sqlsrv:server = tcp:kalendarz-sqldb.database.windows.net,1433; Database = kalendarz", $login, $pass);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $e){
			echo "connection error: " . $e->getMessage();
		}
        return $conn;
    }
}