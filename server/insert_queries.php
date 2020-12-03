<?php
include_once("issue.php");
include_once("user.php");

function addUser(User $user, PDO $conn)
{
    $hashed_pass = password_hash($user->getPassword(), PASSWORD_DEFAULT);
    $userquery = "INSERT INTO `users` (`id`, `firstname`, `lastname`, `password`, `email`, `date_joined`) VALUES (NULL, '{$user->getFirstName()}', '{$user->getLastName()}', '$hashed_pass', '{$user->getEmail()}', '{$user->getDateJoined()}');";
    $conn->query($userquery);
}

function addIssue(Issue $issue, PDO $conn)
{
    $issuequery = "INSERT INTO `issues` (`id`, `title`, `description`, `type`, `priority`, `status`, `assigned_to`, `created_by`, `created`, `updated`) VALUES (NULL, '{$issue->getTitle()}', '{$issue->getDescription()}', '{$issue->getType()}', '{$issue->getPriority()}', '{$issue->getStatus()}', '{$issue->getAssignedTo()}', '{$issue->getCreatedBy()}', '{$issue->getCreated()}','{$issue->getUpdated()}');";
    $conn->query($issuequery);
}

?>