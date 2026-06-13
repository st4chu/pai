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
        $stmt = $this->conn->prepare($query); 
        $stmt->execute();
        return $stmt;
    }


    public function readOne(){
        $query = 'SELECT 
            id as id,
            login as login,
            password as password
            FROM users
            WHERE login LIKE :login';
        $stmt = $this->conn->prepare($query);
        $this->login = htmlspecialchars(strip_tags($this->login));
        $stmt-> bindParam(':login', $this->login);
        $stmt -> execute();
        return $stmt;
    }


    public function create() {
        $query = 'INSERT INTO users (login, password) VALUES (:login, :password)';
        $stmt = $this->conn->prepare($query);
        $this->login = htmlspecialchars(strip_tags($this->login));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $stmt->bindParam(':login', $this->login);
        $stmt->bindParam(':password', hash('sha256', $this->password));
        if($stmt->execute()){
            return true;
        } 
        else{
            printf("error %s \n", $stmt->error);
            return false;
        }
    }
    
    public function update(){
        $query = 'UPDATE users
        SET login = :login, password = :password
        WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        $this->login = htmlspecialchars(strip_tags($this->login));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $pass = hash('sha256', $this->password);
        $stmt ->bindParam(':id', $this->id);
        $stmt->bindParam(':login', $this->login);
        $stmt->bindParam(':password', $pass);
        
        if($stmt->execute()){
            return true;
        } 
        else{
            printf("error %s \n", $stmt->error);
            return false;
        }
    }

    public function delete(){
        $query = 'DELETE FROM users
            WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt ->bindParam(':id', $this->id);
        
        if($stmt->execute()){
            return true;
        } 
        else{
            printf("error %s \n", $stmt->error);
            return false;
        }
    }
}


?>