<?php
include_once("user.php");
include_once("issue.php");

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

    // GET methods
    public function getUsers($keys = array())
    {
        try {
            $sql = "SELECT * FROM `users`";
            if (!empty($keys)) {
                if (isset($keys["password"])) {
                    $pass = $keys["password"];
                    unset($keys["password"]);
                }
                $sql = $this->buildQueryTail($sql,$keys,"OR");
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
                    if ($pass != null) {
                        if ($this->verifyUser($user, $pass)) {
                            array_push($userList, $user->toJSON());
                        } else {
                            return FALSE;
                        }
                    } else {
                        //echo "no pass";
                        array_push($userList, $user->toJSON());
                    }
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
                $sql = $this->buildQueryTail($sql,$json,"AND");
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

    // POST methods
    public function addUser($data)
    {
        $user = new User(
            intval(filter_var($data["id"], FILTER_SANITIZE_NUMBER_INT)),
            filter_var($data["firstname"], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            filter_var($data["lastname"], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            filter_var($data["password"], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            filter_var($data["email"], FILTER_SANITIZE_EMAIL),
            filter_var($data["date_joined"], FILTER_SANITIZE_FULL_SPECIAL_CHARS)
        );
        $hashed_pass = password_hash($user->getPassword(), PASSWORD_DEFAULT);
        $userquery = "INSERT INTO `users` (`id`, `firstname`, `lastname`, `password`, `email`, `date_joined`) VALUES ('{$user->getID()}', '{$user->getFirstName()}', '{$user->getLastName()}', '$hashed_pass', '{$user->getEmail()}', '{$user->getDateJoined()}');";
        $result = $this->conn->query($userquery);
        if ($result === FALSE) {
            return null;
        } else {
            return TRUE;
        }
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
        $issuequery = "INSERT INTO `issues` (`id`, `title`, `description`, `type`, `priority`, `status`, `assigned_to`, `created_by`, `created`, `updated`) VALUES ('{$issue->getID()}', '{$issue->getTitle()}', '{$issue->getDescription()}', '{$issue->getType()}', '{$issue->getPriority()}', '{$issue->getStatus()}', '{$issue->getAssignedTo()}', '{$issue->getCreatedBy()}', '{$issue->getCreated()}','{$issue->getUpdated()}');";
        $result = $this->conn->query($issuequery);
        if ($result === FALSE) {
            return null;
        } else {
            return TRUE;
        }
    }

    // PATCH requests

    public function updateIssue($data, $query)
    {
        $issue = json_decode($this->getIssues($query),true);
        $issue = $issue[0];
        echo var_dump($issue);
        $sql = "UPDATE `issues` SET";
        foreach ($issue as $key => $value) {
            if(isset($data[$key])){
                $issue[$key] = filter_var($data[$key],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $sql = $sql." `$key` = '{$issue[$key]}',";
            }
        }
        $sql = substr($sql,0,-1);
        $sql = $this->buildQueryTail($sql,$query,"AND");
        $sql = $sql.";";
        $result= $this->conn->query($sql);
        if ($result === FALSE) {
            return null;
        } else {
            return TRUE;
        }

    }

    // helper methods
    public function verifyUser($user, $password)
    {
        //echo var_dump($user);
        return password_verify($password, $user->getPassword());
    }

    public function buildQueryTail($sql, $keys, $conjunction)
    {
        $sql = $sql . " WHERE";
        foreach ($keys as $key => $values) {
            if (is_array($values)) {
                foreach ($values as $value) {
                    //echo var_dump($id);
                    $sql = $sql . " $key='$value' $conjunction";
                }
            } else {
                $sql = $sql . " $key='$values' $conjunction";
            }
        }
        $sql = substr($sql, 0, -1-strlen($conjunction));
        return $sql;
    }
}
