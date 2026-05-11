<?php
include('db.php');

// IF USER IS ALREADY LOGGED IN
if(isset($_SESSION['user_id'])){
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Student Expense Tracker</title>

    <!-- EXTERNAL CSS -->
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="auth-box">

    <h1>Student Expense Tracker</h1>

    <p style="margin-bottom:20px;">
        Track and manage your daily expenses easily
    </p>

    <!-- LOGIN BUTTON -->
    <a href="login.php" class="btn btn-primary" style="display:block; margin-bottom:10px;">
        Login
    </a>

    <!-- REGISTER BUTTON -->
    <a href="register.php" class="btn btn-success" style="display:block;">
        Register
    </a>

</div>

</body>
</html>