<?php
session_start();
include_once("database.php");

/* 
* create a new database object and initialize with the first admin user
* only if it is a new session.
*/
if (isset($_SESSION["is_init"])) {
    $db = new Database(FALSE);
} else {
    $db = new Database(TRUE);
    $_SESSION["is_init"] = TRUE;
}

// retrieve the path eg. "/users"
$path = $_SERVER['PATH_INFO'];
// retrieve the type of request ("GET","POST" etc. )
$request = $_SERVER['REQUEST_METHOD'];

// In the event that an OPTIONS request is sent, respond with the available options and end execution.
if ($request == 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, PATCH, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, X-Requested-With, Accept, Accept-Encoding');
    die();
}

// handle request based on path and type of request
switch ($path) {
    case '/issues':
        switch ($request) {

            case 'POST':
                setHeaders();
                $data = json_decode(file_get_contents("php://input"), true);
                $result = $db->addIssue($data);
                if ($result !== null) {
                    http_response_code(200);
                    echo "1";
                } else {
                    http_response_code(400);
                    echo "0";
                }
                die();
                break;


            case 'PATCH':
                $data = json_decode(file_get_contents("php://input"), true);
                $result = $db->updateIssue($data, $_GET);
                if ($result !== null) {
                    http_response_code(200);
                    echo "1";
                } else {
                    http_response_code(422);
                    echo "0";
                }
                die();
                break;


            case 'GET':
                setHeaders();
                $result = $db->getIssues($_GET);
                if ($result !== null) {
                    http_response_code(200);
                    echo $result;
                } else {
                    http_response_code(404);
                }
                die();
                break;

            default:
                http_response_code(400);
                die();
                break;
        }
        break;

    case '/users':
        switch ($request) {


            case 'POST':
                setHeaders();
                $data = json_decode(file_get_contents("php://input"), true);
                $result = $db->addUser($data);
                if ($result !== null) {
                    http_response_code(200);
                    echo "1";
                } else {
                    http_response_code(400);
                    echo "0";
                }
                die();
                break;


            case 'GET':
                setHeaders();
                $result = $db->getUsers($_GET);
                if ($result !== null) {
                    if ($result === FALSE) {
                        http_response_code(401);
                        echo $result;
                    } else {
                        http_response_code(200);
                        echo $result;
                    }
                } else {
                    http_response_code(404);
                }
                die();
                break;

            default:
                http_response_code(400);
                die();
                break;
        }
        break;


    case "/session":
        switch ($request) {
            case 'GET':
                setHeaders();
                $result = fetchSessionData();
                if ($result === null) {
                    http_response_code(404);
                } else if ($result === false) {
                    http_response_code(500);
                } else {
                    http_response_code(200);
                    echo $result;
                }
                die();
                break;

            case 'DELETE':
                setHeaders();
                $result = logoutUser();
                if ($result === null) {
                    http_response_code(404);
                } else if ($result === false) {
                    http_response_code(500);
                } else {
                    http_response_code(200);
                    echo "1";
                }
                die();
                break;

            default:
                http_response_code(400);
                die();
                break;
        }
        break;

    default:
        http_response_code(400);
        die();
        break;
}

/**
 * Sets the necessary headers to prevent the CORS error and for the returned data
 * to be correctly interpreted as JSON.
 */
function setHeaders()
{
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
}
