<?php

// ===============================
// DATABASE CONFIGURATION
// ===============================
$host = "localhost";
$user = "root";
$pass = "";
$db   = "expense_tracker";

// ===============================
// CREATE DATABASE CONNECTION
// ===============================
$conn = mysqli_connect($host, $user, $pass, $db);

// CHECK CONNECTION
if(!$conn){
    die("Database Connection Failed: " . mysqli_connect_error());
}

// ===============================
// START SESSION SAFELY
// ===============================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>