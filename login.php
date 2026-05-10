<?php include('db.php'); ?>

<?php
// REDIRECT IF ALREADY LOGGED IN
if(isset($_SESSION['user_id'])){
    header("Location: dashboard.php");
    exit();
}

$error = "";

// LOGIN PROCESS
if(isset($_POST['login'])){

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if(empty($email) || empty($password)){
        $error = "Please fill in all fields.";
    } else {

        // HASH PASSWORD (same as database storage)
        $password = md5($password);

        // PREPARED STATEMENT (SECURE VERSION)
        $stmt = $conn->prepare("SELECT user_id, name FROM users WHERE email=? AND password=?");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();

        $result = $stmt->get_result();

        if($result->num_rows > 0){

            $row = $result->fetch_assoc();

            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['name'] = $row['name'];

            header("Location: dashboard.php");
            exit();

        } else {
            $error = "Invalid Email or Password!";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>

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
            background: blue;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        button:hover{
            background: darkblue;
        }

        .error{
            color: red;
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

<h2>Login</h2>

<!-- ERROR MESSAGE -->
<?php if($error != ""){ ?>
    <p class="error"><?php echo $error; ?></p>
<?php } ?>

<form method="POST">

    <input type="email" name="email" placeholder="Email" required>

    <input type="password" name="password" placeholder="Password" required>

    <button type="submit" name="login">Login</button>

</form>

<a href="register.php">Register Here</a>

</div>

</body>
</html>