<?php
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
//Include files
require_once('config.php');

//Declare variables
$server = 'localhost';//Server name
$username = 'root';//Server username
$password = '';//Server password
$db = 'surveyengine';//Database name
$sql;//SQL query to be executed

//Create connection to database
$conn = connect($server, $username, $password, $db);

//Check if form data sent through post
if($_SERVER["REQUEST_METHOD"] == "POST") {//Process input from user
    //If login else if sign up
    if(isSet($_POST["loginUsername"])) {
        //Search database for username provided
        $uname = $_POST["loginUsername"];
        $sql = "SELECT `account_id`, `account_username`, `account_password`, `account_type` FROM `storefront`.`entity_account` WHERE `account_username`='$uname'";
        $result = $conn->query($sql);

        //If login was found
        if($result->num_rows > 0) {
            $loginInfo = $result->fetch_assoc();
            if($_POST["loginPassword"] == $loginInfo["account_password"]){
                $_SESSION["username"] = $uname;
                $_SESSION["password"] = $pword;
                $_SESSION["id"] = $loginInfo["account_id"];
                $_SESSION["type"] = $loginInfo["account_type"];
                header("location:index.php");
            } else {//Invalid password
                echo "<script>" .
                    "window.onload = function () {" .
                        "setMsg(1, 'Invalid password');" . 
                        "document.getElementById('loginForm').style.display = 'block';" .
                        "document.getElementById('loginUsername').value = '$uname';" .
                    "}" .
                "</script>";
            }
        } else {//Login not found
            echo "<script>" . 
                    "window.onload = function () {" .
                        "setMsg(0, 'Username does not exist');" . 
                        "document.getElementById('loginForm').style.display = 'block';" .
                    "}" .
                "</script>";
        }
    } else if($_POST["signUpUsername"]) {
        //Get new username and password from post
        $uname = $_POST["signUpUsername"];
        $pword = $_POST["signUpPassword"];

        //Check if username already exists
        $sql = "SELECT `account_id`, `account_username`, `account_password` FROM `storefront`.`entity_account` WHERE `account_username`='$uname'";
        $result = $conn->query($sql);
        
        //If username is found
        if($result->num_rows > 0) {
            //Echo script that lets user know name is unavailable
            echo "<script>" . 
                    "window.onload = function () {" .
                        "setMsg(2, 'Username already exists');" . 
                        "document.getElementById('signUpForm').style.display = 'block';" .
                    "}" .
                "</script>";
        } else {
            $sql = "INSERT INTO `storefront`.`entity_account` (`account_username`, `account_password`) VALUES ('$uname', '$pword');";
            $conn->query($sql);
            $_SESSION["username"] = $uname;
            $_SESSION["password"] = $pword;
            $_SESSION["id"] = $conn->insert_id;
            $_SESSION["type"] = "user";
            header("location:index.php");
        }
    }
    //Close database connection
    $conn->close();
}
?>