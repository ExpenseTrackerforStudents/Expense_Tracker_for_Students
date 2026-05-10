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
    $password = md5(trim($_POST['password']));

    if(empty($email) || empty($password)){
        $error = "Please fill in all fields.";
    } else {

        $query = mysqli_query($conn,
        "SELECT * FROM users
        WHERE email='$email'
        AND password='$password'");

        if(mysqli_num_rows($query) > 0){

            $row = mysqli_fetch_assoc($query);

            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['name'] = $row['name'];

            header("Location: dashboard.php");
            exit();

        } else {
            $error = "Invalid Email or Password!";
        }
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
        }

        .error{
            color: red;
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