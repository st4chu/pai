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

//include_once '../connection.php';
include_once 'user.php';
include_once 'controller.php';

$conn = null;
try{
    $login = file_get_contents('../keys/login.txt');
    $pass = file_get_contents('../keys/pass.txt')
    $conn = new PDO("sqlsrv:server = tcp:kalendarz-sqldb.database.windows.net,1433; Database = kalendarz", $login, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}catch(PDOException $e){
	echo "connection error: " . $e->getMessage();
}

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
        if(isset($_GET['login']))
            $controller->readOne($_GET['login']);
        else{
            $controller->read();
        }
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