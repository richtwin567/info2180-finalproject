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
    public function getUsers($ids = array())
    {
        try {
            $sql = "SELECT * FROM `users`";
            if (!empty($ids)) {
                $ids = $ids["id"];
                $sql = $sql . " WHERE";
                foreach ($ids as $id) {
                    //echo var_dump($id);
                    $sql = $sql . " id='$id' OR";
                }
                $sql = substr($sql, 0, -3);
            }
            $sql = $sql . ";";
            //echo $sql;
            $stmt = $this->conn->query($sql);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //echo var_dump($results);
            if ($results != FALSE && !empty($results)) {
                $userList = array();
                foreach ($results as $row) {
                    $user = new User($row["id"], $row["firstname"], $row["lastname"], $row["password"], $row["email"], $row["date_joined"]);
                    array_push($userList, $user->toJSON());
                }
                return json_encode($userList);
            } else {
                return null;
            }
        } catch (Exception $e) {
            return null;
        }
    }


    public function getIssues($json = array())
    {
        try {
            $sql = "SELECT * FROM `issues`";
            if (!empty($json)) {
                $sql = $sql . " WHERE";
                foreach ($json as $key => $value) {
                    $sql = $sql . " $key='$value' AND";
                }
                $sql = substr($sql, 0, -4);
            }
            $sql = $sql . ";";
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
                return json_encode($issueList);
            } else {
                return null;
            }
        } catch (Exception $e) {
            return null;
        }
    }


    public function addUser($data)
    {
        $user = new User(
            intval(filter_var(htmlspecialchars($data["id"]), FILTER_SANITIZE_NUMBER_INT)),
            filter_var($data["firstname"], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            filter_var($data["lastname"], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            filter_var($data["password"], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            filter_var($data["email"], FILTER_SANITIZE_EMAIL),
            filter_var($data["data_joined"], FILTER_SANITIZE_FULL_SPECIAL_CHARS)
        );
        $hashed_pass = password_hash($user->getPassword(), PASSWORD_DEFAULT);
        $userquery = "INSERT INTO `users` (`id`, `firstname`, `lastname`, `password`, `email`, `date_joined`) VALUES (NULL, '{$user->getFirstName()}', '{$user->getLastName()}', '$hashed_pass', '{$user->getEmail()}', '{$user->getDateJoined()}');";
        $this->conn->query($userquery);
    }


    public function addIssue($data)
    {
        $issue = new Issue(
            intval(filter_var(htmlspecialchars($data["id"]), FILTER_SANITIZE_NUMBER_INT)),
            filter_var($data["title"], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            filter_var($data["description"], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            filter_var($data["type"], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            filter_var($data["priority"], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            filter_var($data["status"], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            filter_var($data["assigned_to"], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            filter_var($data["created_by"], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            filter_var($data["created"], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            filter_var($data["updated"], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
        );
        $issuequery = "INSERT INTO `issues` (`id`, `title`, `description`, `type`, `priority`, `status`, `assigned_to`, `created_by`, `created`, `updated`) VALUES (NULL, '{$issue->getTitle()}', '{$issue->getDescription()}', '{$issue->getType()}', '{$issue->getPriority()}', '{$issue->getStatus()}', '{$issue->getAssignedTo()}', '{$issue->getCreatedBy()}', '{$issue->getCreated()}','{$issue->getUpdated()}');";
        $this->conn->query($issuequery);
    }


    // to be moved from this file
    function verifyUser()
    {
    }
}
