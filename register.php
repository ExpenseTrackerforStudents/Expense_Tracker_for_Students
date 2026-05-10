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
    $password = trim($_POST['password']);

    // CHECK IF EMPTY
    if(empty($name) || empty($email) || empty($password)){
        $message = "Please fill in all fields.";
    }
    else {

        // CHECK IF EMAIL ALREADY EXISTS (PREPARED STATEMENT)
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0){
            $message = "Email already exists!";
        }
        else {

            // HASH PASSWORD
            $password = md5($password);

            // INSERT USER (PREPARED STATEMENT)
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $password);

            if($stmt->execute()){
                $message = "Registered successfully! You can now login.";
            } else {
                $message = "Error in registration.";
            }

            $stmt->close();
        }

        $stmt->close();
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
            width: 320px;
            margin: 100px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
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
            border-radius: 5px;
        }

        button:hover{
            background: darkgreen;
        }

        .msg{
            color: blue;
            margin-bottom: 10px;
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