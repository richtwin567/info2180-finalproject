<?php
include_once("user.php");
include_once("issue.php");
include_once("insert_queries.php");
include_once("delete_queries.php");
include_once("update_queries.php");


// TODO - Potentially rename this file
class DbHandler
{
    private $conn;

    public function __construct()
    {
        $host = 'localhost';
        $dbname = 'issuetracker';
        $username = 'issuetrackeradmin';
        $password = 'issueTRACKER11';
        $this->conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $this->initDb();
    }

    function initDb()
    {        
        $schemasql = file_get_contents("../sql/schema.sql", FILE_USE_INCLUDE_PATH);
        var_dump($schemasql);
        $this->conn->query($schemasql);
        $initialuser = new User(null, 'John', 'Doe', 'password123', 'admin@project2.com', null);
        addUser($initialuser, $this->conn);
    }
}

// to be moved from this file
function verifyUser($conn)
{
}

function loginUser()
{
}

function logoutUser()
{
}

function isLoggedIn()
{
}
