<?php
session_start();

include_once("user.php");

/**
 * Stores a user object in the session.
 * @param User $user 
 * @return bool Returns `true` if the user was stored in the session. 
 * Returns `false` if a user is already logged in.
 */
function loginUser($user)
{
    if (!isLoggedIn()) {
        $_SESSION['user'] = serialize($user);
        return true;
    } else {
        return false;
    }
}

/**
 * Logs the user out if a user was logged in.
 * @return bool|null Returns true if the logout was sucessful. 
 * Returns false if the logout failed. Returns null if no user was logged in to be logged out.
 */
function logoutUser()
{
    if (isLoggedIn()) {
        return session_destroy();
    } else {
        return null;
    }
}

/**
 * Checks if there is a user currently logged in.
 * @return bool Returns true id a user is logged in and false if no user is logged in.
 */
function isLoggedIn()
{
    if (isset($_SESSION['user'])) {
        return true;
    } else {
        return false;
    }
}

/**
 * Fetches the user session data as a JSON string.
 * @return string|false|null Returns a JSON `string` once the retrieval of data was sucessful. 
 * If something goes wrong when trying to convert the data to string, `false` will be returned. 
 * If there is no session data because no user is logged in, `null` is returned.
 */
function fetchSessionData()
{
    if (isLoggedIn()) {
        $session_user = unserialize($_SESSION["user"]);
        return json_encode($session_user->toJSON());
    } else {
        return null;
    }
}
