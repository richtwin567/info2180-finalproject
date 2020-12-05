<?php
include_once("session.php");


trait InsertQueries {

    // POST methods
    public function addUser($data)
    {
        $user = User::buildAndSanitize($data);
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
        $issue = Issue::buildAndSanitize($data);
        $issuequery = "INSERT INTO `issues` (`id`, `title`, `description`, `type`, `priority`, `status`, `assigned_to`, `created_by`, `created`, `updated`) VALUES ('{$issue->getID()}', '{$issue->getTitle()}', '{$issue->getDescription()}', '{$issue->getType()}', '{$issue->getPriority()}', '{$issue->getStatus()}', '{$issue->getAssignedTo()}', '{$issue->getCreatedBy()}', '{$issue->getCreated()}','{$issue->getUpdated()}');";
        $result = $this->conn->query($issuequery);
        if ($result === FALSE) {
            return null;
        } else {
            return TRUE;
        }
    }

}