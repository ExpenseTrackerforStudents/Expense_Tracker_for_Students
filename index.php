<?php
include('db.php');

// IF USER IS ALREADY LOGGED IN, GO TO DASHBOARD
if(isset($_SESSION['user_id'])){
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Expense Tracker</title>

    <!-- READY FOR YOUR EXTERNAL CSS -->
    <link rel="stylesheet" href="css/style.css">

    <!-- TEMP STYLE (YOU SAID STYLE.CSS WILL BE ADDED LATER) -->
    <style>
        body{
            font-family: Arial;
            background: linear-gradient(to right, #4facfe, #00f2fe);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .box{
            background: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px;
            width: 320px;
            box-shadow: 0px 0px 15px rgba(0,0,0,0.2);
        }

        h1{
            font-size: 22px;
            margin-bottom: 10px;
            color: #333;
        }

        p{
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
        }

        a{
            display: block;
            margin: 10px 0;
            padding: 12px;
            text-decoration: none;
            color: white;
            border-radius: 5px;
            font-weight: bold;
        }

        .login{
            background: #007bff;
        }

        .register{
            background: #28a745;
        }

        a:hover{
            opacity: 0.9;
        }
    </style>
</head>

<body>

<div class="box">

    <h1>Student Expense Tracker</h1>
    <p>Track and manage your daily expenses easily</p>

    <!-- NAVIGATION -->
    <a class="login" href="login.php">Login</a>
    <a class="register" href="register.php">Register</a>

</div>

</body>
</html>