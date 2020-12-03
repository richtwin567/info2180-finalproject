<?php

include_once("user.php");
include_once("issue.php");

function getUser($id, $conn)
{
    $sql = "SELECT * FROM `users` WHERE id=$id;";
    $stmt = $conn->query($sql);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($results != FALSE && !empty($results)) {
        $user = new User($results["id"], $results["firstname"], $results["lastname"], $results["password"], $results["email"], $results["date_joined"]);
        return $user;
    } else {
        return null;
    }
}


function getAllIssues($conn)
{
    $sql = "SELECT * FROM `issues`;";
    $stmt = $conn->query($sql);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($results != FALSE && !empty($results)) {
        $issueList = array();
        foreach ($results as $row) {
            $issue = new Issue($row["id"], $row["title"], $row["description"], $row["type"], $row["priority"], $row["status"], $row["assigned_to"], $row["created_by"], $row["created"], $row["updated"]);
            array_push($issueList, $issue);
        }
        return $issueList;
    } else {
        return null;
    }
}
