<?php
session_name("MyAppSession");
session_start();
// Check if the user is already logged in
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && isset($_SESSION["not_allowed"])) {
    // Check if the session has expired (current time > expiration time)
    if ((isset($_SESSION["expiration_time"]) && time() > $_SESSION["expiration_time"]) || authModified(CONN, $_SESSION['id'])) {
        // Session has expired, destroy it and log the user out
        session_unset();
        session_destroy();
        header("location: ../../1402/login.php"); // Redirect to the login page
        exit;
    }
} else {
    // User is not logged in, redirect them to the login page
    header("location: ../../1402/login.php");
    exit;
}

$current_page = explode(".", basename($_SERVER['PHP_SELF']))[0];

if (in_array($current_page, $_SESSION['not_allowed'])) {
    header("location: ../../1402/notAllowed.php"); 
    // Redirect to the login page  header("location: login.php"); // Redirect to the login page
}

function authModified($con, $id)
{
    $sql = "SELECT modified FROM yadakshop1402.authorities WHERE user_id = $id";

    $result = $con->query($sql);

    $isModified = $result->fetch_assoc()['modified'];

    return $isModified;
}
