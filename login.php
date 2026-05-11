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

        // HASH PASSWORD
        $hashedPassword = md5($password);

        // PREPARED STATEMENT
        $stmt = $conn->prepare("SELECT user_id, name FROM users WHERE email=? AND password=?");

        $stmt->bind_param("ss", $email, $hashedPassword);

        $stmt->execute();

        $result = $stmt->get_result();

        // CHECK LOGIN
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
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login</title>

    <!-- EXTERNAL CSS -->
    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="auth-box">

    <h2>Login</h2>

    <!-- ERROR MESSAGE -->
    <?php if($error != ""){ ?>
        <p class="error"><?php echo $error; ?></p>
    <?php } ?>

    <!-- LOGIN FORM -->
    <form method="POST">

        <input 
            type="email" 
            name="email" 
            placeholder="Enter Email"
            required
        >

        <input 
            type="password" 
            name="password" 
            placeholder="Enter Password"
            required
        >

        <button type="submit" name="login" class="btn-primary">
            Login
        </button>

    </form>

    <p>
        Don't have an account?
    </p>

    <a href="register.php">
        Register Here
    </a>

</div>

</body>
</html>