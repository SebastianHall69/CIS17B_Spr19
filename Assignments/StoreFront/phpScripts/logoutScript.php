<?php
//Start session
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}

//Unset session variables to log user out
if(isSet($_SESSION["username"])) {
    unset($_SESSION["username"]);
}
if(isSet($_SESSION["id"])) {
    unset($_SESSION["id"]);
}
if(isSet($_SESSION["password"])) {
    unset($_SESSION["password"]);
}
if(isSet($_SESSION["type"])) {
    unset($_SESSION["type"]);
}

//Return to home page
header("location:../index.php");
?>