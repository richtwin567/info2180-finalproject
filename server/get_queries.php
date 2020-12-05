<?php

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
                $sql = $this->buildQueryTail($sql, $keys, "OR");
            }
            $sql = $sql . ";";
            $stmt = $this->conn->query($sql);
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
        } catch (Exception $e) {
            return null;
        }
    }


    public function getIssues($json = array())
    {
        try {
            $sql = "SELECT * FROM `issues`";
            if (!empty($json)) {
                $sql = $this->buildQueryTail($sql, $json, "AND");
            }
            $sql = $sql . ";";
            $stmt = $this->conn->query($sql);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($results != FALSE && !empty($results)) {
                $issueList = array();
                foreach ($results as $row) {
                    $issue = Issue::buildAndSanitize($row);
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
}
