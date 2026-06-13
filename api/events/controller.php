<?php

class Controller{
    private $event;
    
    public function __construct($db){
        $this->event = new Item($db);
    }

    public function read($data){
        $this->event->owner = $data;
        $return = $this->event->read($data);
        $result = [];
        while($row = sqlsrv_fetch_array($return)){
            array_push($result, $row);
        }
        echo json_encode($result);
    }

    public function create($data){
        if(!isset($data['header']) || empty($data['header'])){
            echo json_encode(["message" => "brak naglowka"]);
        }
        if(!isset($data['date']) || empty($data['date'])){
            echo json_encode(["message" => "brak daty"]);
        }
        if(!isset($data['owner']) || empty($data['owner'])){
            echo json_encode(["message" => "brak wlasciciela"]);
        }

        $this->event->date = $data['date'];
        $this->event->header = $data['header'];
        $this->event->note = $data['note'];
        $this->event->owner = $data['owner'];

        if($this->event->create()){
            echo json_encode(["message" => "create ok"]);
        }
        else{
            echo json_encode(["message" => "create not ok"]);
        }

            
    }

    public function update($data){
         if(!isset($data['header']) || empty($data['header'])){
            echo json_encode(["message" => "brak naglowka"]);
        }
        if(!isset($data['date']) || empty($data['date'])){
            echo json_encode(["message" => "brak daty"]);
        }

        $this->event->id = $data['id'];
        $this->event->date = $data['date'];
        $this->event->header = $data['header'];
        $this->event->note = $data['note'];

        if($this->event->update()){
            echo json_encode(["message" => "update ok"]);
        }
        else{
            echo json_encode(["message" => "update error"]);
        }
    }

    public function delete($id){
        if(empty($id)) {
            echo json_encode(["message" => "nothing to delete"]);
        }

        $this->event->id = $id;
        if($this->event->delete()){
            echo json_encode(["message" => "delete ok"]);
        }
        else{
            echo json_encode(["message" => "delete error"]);
        }
    }
}