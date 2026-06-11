<?php

class Item{
    private $conn;

    public $id;
    public $event_date;
    public $event_header;
    public $event_note;


    public function __construct($db){
        $this->conn = $db;
    }

    public function read(){
        $query = 'SELECT 
            id as id,
            date as event_date,
            header as event_header,
            note as event_note
            FROM events
            ORDER BY date ASC';
    
        $stmt = sqlsrv_query($this->conn,$query);
        return $stmt;
    }


    public function create() {
        $this->event_date = htmlspecialchars(strip_tags($this->event_date));
        $this->event_header = htmlspecialchars(strip_tags($this->event_header));
        $this->event_note = htmlspecialchars(strip_tags($this->event_note));
        $query = 'INSERT INTO events (date,header,note) VALUES ($this->event_date, $this->event_header, $this->event_note)';

        if($stmt = sqlsrv_query($this->conn,$query)){
            return true;
        } 
        else{
            printf("error %s \n", $stmt->error);
            return false;
        }
    }

    public function update(){


        $this->event_date = htmlspecialchars(strip_tags($this->event_date));
        $this->event_header = htmlspecialchars(strip_tags($this->event_header));
        $this->event_note = htmlspecialchars(strip_tags($this->event_note));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $query = 'UPDATE events
        SET date = $this->event_date, header = $this->event_header, note = $this->event_note
        WHERE id = $this->id';
        
        if($stmt = sqlsrv_query($this->conn,$query)){
            return true;
        } 
        else{
            printf("error %s \n", $stmt->error);
            return false;
        }
    }

    public function delete(){
         $query = 'DELETE FROM events
            WHERE id = $this->id';

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt ->bindParam(':id', $this->id);
        
        if($stmt = sqlsrv_query($this->conn,$query)){
            return true;
        } 
        else{
            printf("error %s \n", $stmt->error);
            return false;
        }
    }
}

?>