<?php
include_once("session.php");
trait GetQueries
{
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
                $sql = $this->buildQueryTail($sql, $keys, "OR", "users");
            }
            $sql = $sql . ";";
            $stmt = $this->conn->query($sql);
            if ($stmt != false) {
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if ($results != FALSE && !empty($results)) {
                    $userList = array();

                    foreach ($results as $row) {
                        $user = User::buildAndSanitize($row);
                        if ($pass != null) {
                            if ($this->verifyUser($user, $pass)) {
                                array_push($userList, $user->toJSON());
                                // store the user in the session
                                loginUser($user);
                            } else {
                                return FALSE;
                            }
                        } else {
                            array_push($userList, $user->toJSON());
                        }
                    }

                    return json_encode($userList);
                } else {
                    return null;
                }
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
            $sql = <<<EOT
SELECT 
    res.`id`,
    res.`title`,
    res.`description`,
    res.`type`,
    res.`priority`,
    res.`status`,
    res.assigned_to_id,
    res.assigned_to, 
    CONCAT(`users`.`firstname`," ",`users`.`lastname`) as created_by, 
    res.`created`,    
    res.`updated` 
FROM (
        SELECT 
        `issues`.`id`,
        `issues`.`title`,
        `issues`.`description`,
        `issues`.`type`,
        `issues`.`priority`,
        `issues`.`status`,
        `issues`.`assigned_to` as assigned_to_id,
        CONCAT(`users`.`firstname`," ",`users`.`lastname`) as assigned_to, 
        `issues`.`created_by`, 
        `issues`.`created`, 
        `issues`.`updated` 
    FROM 
        `issues` LEFT JOIN `users` ON `users`.`id`=`issues`.`assigned_to`) as res 
LEFT JOIN `users` ON `users`.`id`=res.created_by
EOT;
            if (!empty($json)) {
                $sql = $this->buildQueryTail($sql, $json, "AND", "res");
            }
            $sql = $sql . ";";
            //echo $sql;
            $stmt = $this->conn->query($sql);
            //echo var_dump($stmt);
            if ($stmt != false) {
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if ($results != FALSE && !empty($results)) {
                    $issueList = array();
                    foreach ($results as $row) {
                        $issue = Issue::buildAndSanitize($row);
                        //echo var_dump($issue);
                        array_push($issueList, $issue->toJSON());
                    }
                    return json_encode($issueList);
                } else {
                    return null;
                }
            } else {
                return null;
            }
        } catch (Exception $e) {
            return null;
        }
    }
}
