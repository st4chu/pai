<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$username = file_get_contents("../login.txt");
$password = file_get_contents("../pass.txt");
include_once '../connection.php';
include_once 'item.php';
include_once 'controller.php';

$db = new DB();
$conn = $db->getConn();
$controller = new Controller($conn);

$method = $_SERVER['REQUEST_METHOD'];
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);
$segments = explode('/', trim($path, '/'));

$inputData = [];
if($method == 'POST' || $method == 'PUT'){
    $inputData = json_decode(file_get_contents("php://input"), true);
}


switch($method){
    case 'GET':
        $controller->read($_GET['owner']);
        break;
    
    case 'POST':
        $controller->create($inputData);
        break;

    case 'PUT':
        $controller->update($inputData);
        break;

    case 'DELETE':
        if(isset($_GET['id'])){
            $controller->delete($_GET['id']);
        }
        break;
    
    default:
        echo json_encode(["message" => "unknown method"]);
}

?>