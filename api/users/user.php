<?php

class User{
    private $conn;

    public $id;
    public $login;
    public $password;


    public function __construct($db){
        $this->conn = $db;
    }

    public function read(){
        $query = 'SELECT 
            id as id,
            login as login,
            password as password
            FROM users';
        $stmt = sqlsrv_query($this->conn, $query); 
        return $stmt;
    }


    public function readOne(){
        $query = 'SELECT 
            id as id,
            login as login,
            password as password
            FROM users
            WHERE login LIKE ?';
        $this->login = htmlspecialchars(strip_tags($this->login));
        $params = array($this->login);
        $stmt = sqlsrv_query($this->conn, $query, $params); 
        return $stmt;
    }


    public function create() {
        $query = 'INSERT 
        INTO users (login, password) 
        VALUES (?, ?)';
        $this->login = htmlspecialchars(strip_tags($this->login));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $params = array($this->login, $this->password);
        $stmt = sqlsrv_query($this->conn, $query, $params); 
        if($stmt){
            return true;
        } 
        else{
            printf("error %s \n", $stmt->error);
            return false;
        }
    }
    
    public function update(){
        $query = 'UPDATE users
        SET login = ?, password = ?
        WHERE id = ?';
        $stmt = $this->conn->prepare($query);

        $this->login = htmlspecialchars(strip_tags($this->login));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $params = array($this->login, $this->password, $this->id);
        $stmt = sqlsrv_query($this->conn, $query, $params); 
        
        if($stmt){
            return true;
        } 
        else{
            printf("error %s \n", $stmt->error);
            return false;
        }
    }

    public function delete(){
        $query = 'DELETE FROM users
            WHERE id = ?';

        $this->id = htmlspecialchars(strip_tags($this->id));
        $params = array($this->id);
        $stmt = sqlsrv_query($this->conn, $query, $params); 
        
        if($stmt){
            return true;
        } 
        else{
            printf("error %s \n", $stmt->error);
            return false;
        }
    }
}


?>