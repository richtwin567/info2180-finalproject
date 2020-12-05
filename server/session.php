<?php
function loginUser($conn, $email) {
    $query = "SELECT * FROM users WHERE email = '$email'";
    $_SESSION['userInfo'] = mysqli_query($conn,$query);

    $_SESSION['id'] = $_SESSION['userInfo'][0];
    $_SESSION['firstname'] = $_SESSION['userInfo'][1];
    $_SESSION['lastname'] = $_SESSION['userInfo'][2];
    $_SESSION['password'] = $_SESSION['userInfo'][3];
    $_SESSION['email'] = $_SESSION['userInfo'][4];
    $_SESSION['date_joined'] = $_SESSION['userInfo'][5];

    $_SESSION['user'] = $_SESSION['firstname'] . ' ' . $_SESSION['lastname'];
}

function logoutUser() {
    session_destroy();
    header("location:index.html?=login"); //redirect to login screen
}

function isLoggedIn() {
    if(isset($_SESSION['user'])) {
        return true;
    }
}
?>