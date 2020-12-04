<?php
function loginUser($con) {
    session_start();
    if(isset($_POST['Login'])) {
       if(empty($_POST['email']) || empty($_POST['password'])) {
            echo 'Please enter your email/password';
       } else {
            $query = "select * from user where email='" . $_POST['email'] . "' and password='". $_POST['password'] . "'";
            $result = mysqli_query($con, $query);

            if(mysqli_fetch_assoc($result)) {
                $_SESSION['email']=$_POST['email'];
                //Link to dashboard
            } else {
                echo "Invalid email/password";
            }
       }
    } else {
        echo 'Error in connecting';
    }
}

function logoutUser()
{
}

function isLoggedIn()
{
}
?>