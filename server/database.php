<?php
include_once("user.php");
include_once("issue.php");/* 
include_once("insert_queries.php");
include_once("delete_queries.php");
include_once("update_queries.php");
include_once("get_queries.php"); */

// TODO - Potentially rename this file
class Database
{
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
        }
    }

    private function initDb()
    {
        $schemasql = file_get_contents("../sql/schema.sql", FILE_USE_INCLUDE_PATH);
        //var_dump($schemasql);
        $this->conn->query($schemasql);
        $initialuser = new User(null, 'John', 'Doe', 'password123', 'admin@project2.com', null);
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

    // get methods
    public function getUser($id)
    {
        $sql = "SELECT * FROM `users` WHERE id=$id;";
        $stmt = $this->conn->query($sql);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($results != FALSE && !empty($results)) {
            $user = new User($results["id"], $results["firstname"], $results["lastname"], $results["password"], $results["email"], $results["date_joined"]);
            return $user;
        } else {
            return null;
        }
    }


    public function getIssues($json = array())
    {
        $sql = "SELECT * FROM `issues`";
        if (!empty($json)) {
            $sql = $sql . " WHERE";
            foreach ($json as $key => $value) {
                $sql = $sql . " $key=$value AND";
            }
            $sql = substr($sql, 0, -4);
        }
        //$sql = $sql . ";";
        //echo $sql;
        $stmt = $this->conn->query($sql);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //echo var_dump($results);
        if ($results != FALSE && !empty($results)) {
            $issueList = array();
            foreach ($results as $row) {
                $issue = new Issue($row["id"], $row["title"], $row["description"], $row["type"], $row["priority"], $row["status"], $row["assigned_to"], $row["created_by"], $row["created"], $row["updated"]);
                array_push($issueList, $issue->toJSON());
            }
            return $issueList;
        } else {
            return null;
        }
    }

    public function addUser($user)
    {
        $hashed_pass = password_hash($user->getPassword(), PASSWORD_DEFAULT);
        $userquery = "INSERT INTO `users` (`id`, `firstname`, `lastname`, `password`, `email`, `date_joined`) VALUES (NULL, '{$user->getFirstName()}', '{$user->getLastName()}', '$hashed_pass', '{$user->getEmail()}', '{$user->getDateJoined()}');";
        $this->conn->query($userquery);
    }

    public function addIssue($issue)
    {
        $issuequery = "INSERT INTO `issues` (`id`, `title`, `description`, `type`, `priority`, `status`, `assigned_to`, `created_by`, `created`, `updated`) VALUES (NULL, '{$issue->getTitle()}', '{$issue->getDescription()}', '{$issue->getType()}', '{$issue->getPriority()}', '{$issue->getStatus()}', '{$issue->getAssignedTo()}', '{$issue->getCreatedBy()}', '{$issue->getCreated()}','{$issue->getUpdated()}');";
        $this->conn->query($issuequery);
    }


    // to be moved from this file
    function verifyUser()
    {
    }
}
