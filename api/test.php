<?php
header('Content-Type: text/plain; charset=utf-8');

echo "THIS IS A TEST\n";

include_once 'connection.php';
include_once 'item.php';
include_once 'controller.php';
$db = new DB();
$conn = $db->getConn();
$controller = new Controller($conn);

echo "\n\t TEST CREATE \n";

$dummy = [
    'date' => '2026-10-01 17:30:00',
    'header' => 'test event',
    'note' => 'something may happen that day'
];


// TESTING CREATE
echo "\tINSERTING: ".json_encode($dummy)."\n";

$event = new Item($conn);
$event->event_date = $dummy['date'];
$event->event_header = $dummy['header'];
$event->event_note = $dummy['note'];

if($event->create()){
    $id = $conn->lastInsertId();
    echo "Added with id of ".$id."\n";
}
else{
    echo "Added nothing :(\n";
}

// TESTING READ
echo "\n\tTEST READ \n";
$stmt = $event->read();
$rows = $stmt->rowCount();
echo "Rows found: ".$rows."\n";

// TESTING UPDATE
echo "\n\t TEST UPDATE \n";
if(isset($id)){
    $event->id = $id;
    $event->note = 'i just got updated';
    if($event->update()){
        echo "Updated row with id: ".$id."\n";
    }
    else{
        echo "Updated nothing\n";
    }
}
else{
    echo "Nothing to update\n";
}

// TESTING DELETE
echo "\n\t TEST DELETE \n";
if(isset($id)){
    if($event->delete()){
        echo "Deleted successfully\n";
    }
    else{
        echo "Deleted nothing\n";
    }
}
else{
    echo "Nothing to delete\n";
}

echo "\n\t TEST FINISHED\n";
?>
