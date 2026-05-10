<?php

// DATABASE CONNECTION SETTINGS
$host = "localhost";
$user = "root";
$pass = "";
$db   = "expense_tracker";

// CREATE CONNECTION
$conn = mysqli_connect($host, $user, $pass, $db);

// CHECK CONNECTION
if(!$conn){
    die("Database Connection Failed: " . mysqli_connect_error());
}

// START SESSION (for login system)
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

?>