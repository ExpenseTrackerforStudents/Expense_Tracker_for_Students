<?php
include('db.php');

// IF USER IS ALREADY LOGGED IN, GO TO DASHBOARD
if(isset($_SESSION['user_id'])){
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Expense Tracker</title>

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
            width: 300px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.2);
        }

        h1{
            font-size: 20px;
        }

        a{
            display: block;
            margin: 10px 0;
            padding: 10px;
            text-decoration: none;
            color: white;
            border-radius: 5px;
        }

        .login{
            background: blue;
        }

        .register{
            background: green;
        }
    </style>
</head>

<body>

<div class="box">

    <h1>Student Expense Tracker</h1>
    <p>Manage your expenses easily</p>

    <a class="login" href="login.php">Login</a>
    <a class="register" href="register.php">Register</a>

</div>

</body>
</html>