<?php
session_start();

include_once("user.php");
include_once("issue.php");
include_once("get_queries.php");
include_once("insert_queries.php");
include_once("update_queries.php");
include_once("session.php");

class Database
{
    use InsertQueries;
    use UpdateQueries;
    use GetQueries;
    
    private $conn;
    private $host = 'localhost';
    private $dbname = 'issuetracker';
    private $username = 'issuetrackeradmin';
    private $password = 'issueTRACKER11';

    public function __construct($shouldInit)
    {
        $this->connect();
        if ($shouldInit) {
            $this->initDb();
        }
    }

    private function connect()
    {
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbname;charset=utf8mb4", $this->username, $this->password);
        } catch (PDOException $except) {
            echo "Connection error";
            die();
        }
    }

    private function initDb()
    {
        $schemasql = file_get_contents("../sql/schema.sql", FILE_USE_INCLUDE_PATH);
        //var_dump($schemasql);
        $this->conn->query($schemasql);
        $initialuser = array(
            "id" => null,
            "firstname" => 'John',
            "lastname" => 'Doe',
            "password" => 'password123',
            "email" => 'admin@project2.com',
            "date_joined" => null
        );
        $this->addUser($initialuser, $this->conn);
    }

    public function __destruct()
    {
        $this->conn = null;
    }

    public function __wakeup()
    {
        $this->connect();
    }

    // helper methods
    private function verifyUser($user, $password)
    {
        //echo var_dump($user);
        return password_verify($password, $user->getPassword());
    }

    private function buildQueryTail($sql, $keys, $conjunction, $table)
    {
        $sql = $sql . " WHERE";
        foreach ($keys as $key => $values) {
            if (is_array($values)) {
                foreach ($values as $value) {
                    //echo var_dump($id);
                    $sql = $sql . " $table.$key='$value' $conjunction";
                }
            } else {
                $sql = $sql . " $table.$key='$values' $conjunction";
            }
        }
        $sql = substr($sql, 0, -1-strlen($conjunction));
        return $sql;
    }
}
