<?php include('db.php'); ?>

<?php

$message = "";

// REDIRECT IF ALREADY LOGGED IN
if(isset($_SESSION['user_id'])){
    header("Location: dashboard.php");
    exit();
}

// REGISTER PROCESS
if(isset($_POST['register'])){

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = md5(trim($_POST['password']));

    // CHECK IF EMPTY
    if(empty($name) || empty($email) || empty($password)){
        $message = "Please fill in all fields.";
    }
    else {

        // CHECK IF EMAIL ALREADY EXISTS
        $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

        if(mysqli_num_rows($check) > 0){
            $message = "Email already exists!";
        }
        else {

            // INSERT USER
            $insert = mysqli_query($conn,
            "INSERT INTO users(name,email,password)
            VALUES('$name','$email','$password')");

            if($insert){
                $message = "Registered successfully! You can now login.";
            } else {
                $message = "Error in registration.";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>

    <style>
        body{
            font-family: Arial;
            background: #f4f4f4;
        }

        .container{
            width: 300px;
            margin: 100px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        input{
            width: 90%;
            padding: 10px;
            margin: 8px 0;
        }

        button{
            width: 100%;
            padding: 10px;
            background: green;
            color: white;
            border: none;
            cursor: pointer;
        }

        .msg{
            color: blue;
        }

        a{
            display: block;
            margin-top: 10px;
        }
    </style>

</head>
<body>

<div class="container">

<h2>Register</h2>

<!-- MESSAGE -->
<?php if($message != ""){ ?>
    <p class="msg"><?php echo $message; ?></p>
<?php } ?>

<form method="POST">

<input type="text" name="name" placeholder="Name" required>

<input type="email" name="email" placeholder="Email" required>

<input type="password" name="password" placeholder="Password" required>

<button type="submit" name="register">Register</button>

</form>

<a href="login.php">Login Here</a>

</div>

</body>
</html>