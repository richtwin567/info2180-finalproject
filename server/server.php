<?php
session_start();
include_once("database.php");

if (isset($_SESSION["is_init"])) {
    $db = new Database(FALSE);
} else {
    $db = new Database(TRUE);
    $_SESSION["is_init"] = TRUE;
}

$path = $_SERVER['PATH_INFO'];
$request = $_SERVER['REQUEST_METHOD'];

if ($request == 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, PATCH, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, X-Requested-With, Accept, Accept-Encoding');
    die();
}

switch ($path) {
    case '/issues':
        switch ($request) {
            case 'POST':
                $data = json_decode(file_get_contents("php://input"));

                break;
            case 'PATCH':
                $data = json_decode(file_get_contents("php://input"));

                break;
            case 'GET':
                setHeaders();
                $result = $db->getIssues($_GET);
                if ($result != null) {
                    http_response_code(200);
                    echo $result;
                    //echo "yes";
                } else {
                    //echo "no";
                    http_response_code(404);
                }
                die();
                break;

            default:
                # code...
                break;
        }
        break;

    case '/users':
        switch ($request) {
            case 'POST':
                $data = json_decode(file_get_contents("php://input"));

                break;
            case 'GET':

                break;
            default:
                # code...
                break;
        }
        break;

    default:
        # code...
        break;
}


function setHeaders()
{
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
}
