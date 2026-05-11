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

    // CHECK EMPTY FIELDS
    if(empty($name) || empty($email) || empty($password)){

        $message = "Please fill in all fields.";

    } else {

        // CHECK IF EMAIL EXISTS
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE email=?");

        $stmt->bind_param("s", $email);

        $stmt->execute();

        $result = $stmt->get_result();

        // EMAIL ALREADY EXISTS
        if($result->num_rows > 0){

            $message = "Email already exists!";

        } else {

            // HASH PASSWORD
            $hashedPassword = md5($password);

            // INSERT USER
            $insert = $conn->prepare("
                INSERT INTO users(name, email, password)
                VALUES(?, ?, ?)
            ");

            $insert->bind_param(
                "sss",
                $name,
                $email,
                $hashedPassword
            );

            if($insert->execute()){

                $message = "Registered successfully! You can now login.";

            } else {

                $message = "Registration failed!";
            }

            $insert->close();
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

    <title>Register</title>

    <!-- EXTERNAL CSS -->
    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="auth-box">

    <h2>Create Account</h2>

    <!-- MESSAGE -->
    <?php if($message != ""){ ?>

        <p class="success">
            <?php echo $message; ?>
        </p>

    <?php } ?>

    <!-- REGISTER FORM -->
    <form method="POST">

        <input
            type="text"
            name="name"
            placeholder="Enter Full Name"
            required
        >

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

        <button
            type="submit"
            name="register"
            class="btn-success"
        >
            Register
        </button>

    </form>

    <p>
        Already have an account?
    </p>

    <a href="login.php">
        Login Here
    </a>

</div>

</body>
</html>