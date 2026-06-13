<?php

class Controller{
    private $user;
    
    public function __construct($db){
        $this->user = new User($db);
    }

    public function read(){
        $return = $this->user->read();
        $result = [];
        while($row = $return->fetch(PDO::FETCH_ASSOC)){
            array_push($result, $row);
        }
        echo json_encode($result);
    }

    public function readOne($data){
        $this->user->login = $data;
        $return = $this->user->readOne($data);
        $result = [];
        while($row = $return->fetch(PDO::FETCH_ASSOC)){
            array_push($result, $row);
        }
        echo json_encode($result);
    }

    public function create($data){
        if(!isset($data['login']) || empty($data['login'])){
            echo json_encode(["message" => "brak loginu"]);
        }
        else if(!isset($data['password']) || empty($data['password'])){
            echo json_encode(["message" => "brak hasla"]);
        }
        else{
            $this->user->login = $data['login'];
            $this->user->password = $data['password'];
            if($this->user->create()){
                echo json_encode(["message" => "create ok"]);
            }
            else{
                echo json_encode(["message" => "create not ok"]);
            }
        }
        return false;         
    }

    
    public function update($data){
         if(!isset($data['login']) || empty($data['login'])){
            echo json_encode(["message" => "brak loginu"]);
        }
        if(!isset($data['password']) || empty($data['password'])){
            echo json_encode(["message" => "brak hasla"]);
        }

        $this->user->id = $data['id'];
        $this->user->login = $data['login'];
        $this->user->password = $data['password'];
        
        if($this->user->update()){
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

        $this->user->id = $id;
        if($this->user->delete()){
            echo json_encode(["message" => "delete ok"]);
        }
        else{
            echo json_encode(["message" => "delete error"]);
        }
    }
}