<?php

class Item{
    private $conn;

    public $id;
    public $owner;
    public $date;
    public $header;
    public $note;


    public function __construct($db){
        $this->conn = $db;
    }

    public function read(){
        $query = 'SELECT 
            id,
            owner,
            date,
            header,
            note
            FROM events
            WHERE owner LIKE ?
            ORDER BY date ASC';

        $this->owner = htmlspecialchars(strip_tags($this->owner));
        $params = array($this->owner);
        $stmt = sqlsrv_query($this->conn, $query, $params); 
        return $stmt;
    }


    public function create() {
        $query = 'INSERT INTO events (owner,date,header,note) 
        VALUES (?, ?, ?, ?)';

        $this->date = htmlspecialchars(strip_tags($this->date));
        $this->header = htmlspecialchars(strip_tags($this->header));
        $this->note = htmlspecialchars(strip_tags($this->note));
        $this->owner = htmlspecialchars(strip_tags($this->owner));

        $params = array($this->owner, $this->date, $this->header, $this->note);
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
        $query = 'UPDATE events
        SET date = ?, header = ?, note = ?
        WHERE id = ?';
        $stmt = $this->conn->prepare($query);

        $this->date = htmlspecialchars(strip_tags($this->date));
        $this->header = htmlspecialchars(strip_tags($this->header));
        $this->note = htmlspecialchars(strip_tags($this->note));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $params = array($this->date, $this->header, $this->note, $this->id);
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
         $query = 'DELETE FROM events
            WHERE id = ?';
            
        $this->owner = htmlspecialchars(strip_tags($this->id));
        $params = array($this->id);
        $stmt = sqlsrv_query($this->conn, $query, $params); 
        
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