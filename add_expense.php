<?php include('db.php'); ?>

<?php
// CHECK LOGIN SESSION
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$error = "";
$success = "";

// HANDLE FORM SUBMISSION
if(isset($_POST['save'])){

    $amount = trim($_POST['amount']);
    $category_id = trim($_POST['category_id']);
    $description = trim($_POST['description']);
    $date = trim($_POST['date']);

    // VALIDATION
    if(empty($amount) || empty($category_id) || empty($date)){
        $error = "Please fill in all required fields.";
    }
    else if(!is_numeric($amount)){
        $error = "Amount must be a number.";
    }
    else {

        // PREPARED STATEMENT (SECURE VERSION)
        $stmt = $conn->prepare("
            INSERT INTO expenses (user_id, category_id, amount, description, date)
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->bind_param("iisss", $user_id, $category_id, $amount, $description, $date);

        if($stmt->execute()){
            $success = "Expense added successfully!";
        } else {
            $error = "Error adding expense.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Expense</title>

    <style>
        body{
            font-family: Arial;
            background: #f4f4f4;
        }

        .container{
            width: 420px;
            margin: 50px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }

        input, select{
            width: 100%;
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
        }

        .success{
            color: green;
        }

        a{
            display: inline-block;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="container">

<h2>Add Expense</h2>

<!-- MESSAGE -->
<?php if($error != ""){ ?>
    <p class="error"><?php echo $error; ?></p>
<?php } ?>

<?php if($success != ""){ ?>
    <p class="success"><?php echo $success; ?></p>
<?php } ?>

<!-- FORM -->
<form method="POST">

    <input type="number" name="amount" placeholder="Amount" required>

    <select name="category_id" required>
        <option value="">Select Category</option>

        <?php
        $cat = mysqli_query($conn, "SELECT * FROM categories");
        while($c = mysqli_fetch_array($cat)){
        ?>
            <option value="<?php echo $c['category_id']; ?>">
                <?php echo $c['category_name']; ?>
            </option>
        <?php } ?>

    </select>

    <input type="text" name="description" placeholder="Description">

    <input type="date" name="date" required>

    <button type="submit" name="save">Save Expense</button>

</form>

<br>
<a href="dashboard.php">← Back to Dashboard</a>

</div>

</body>
</html>