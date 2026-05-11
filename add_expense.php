<?php include('db.php'); ?>

<?php
// ========================================
// CHECK LOGIN SESSION
// ========================================
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$error = "";
$success = "";

// ========================================
// HANDLE FORM SUBMISSION
// ========================================
if(isset($_POST['save'])){

    $amount = trim($_POST['amount']);
    $category_id = trim($_POST['category_id']);
    $description = trim($_POST['description']);
    $date = trim($_POST['date']);

    // VALIDATION
    if(
        empty($amount) ||
        empty($category_id) ||
        empty($date)
    ){

        $error = "Please fill in all required fields.";

    }
    elseif(!is_numeric($amount)){

        $error = "Amount must be numeric.";

    }
    else {

        // ========================================
        // INSERT EXPENSE
        // ========================================
        $stmt = $conn->prepare("
            INSERT INTO expenses
            (
                user_id,
                category_id,
                amount,
                description,
                date
            )
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "iisss",
            $user_id,
            $category_id,
            $amount,
            $description,
            $date
        );

        if($stmt->execute()){

            $success = "Expense added successfully!";

        } else {

            $error = "Failed to add expense.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
    content="width=device-width, initial-scale=1.0">

    <title>Add Expense</title>

    <!-- STYLE -->
    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container">

    <div class="form-box">

        <h2>Add Expense</h2>

        <!-- ERROR -->
        <?php if($error != ""){ ?>

            <p class="error">
                <?php echo $error; ?>
            </p>

        <?php } ?>

        <!-- SUCCESS -->
        <?php if($success != ""){ ?>

            <p class="success">
                <?php echo $success; ?>
            </p>

        <?php } ?>

        <!-- FORM -->
        <form method="POST">

            <!-- AMOUNT -->
            <input
                type="number"
                step="0.01"
                name="amount"
                placeholder="Enter Amount"
                required
            >

            <!-- CATEGORY -->
            <select name="category_id" required>

                <option value="">
                    Select Category
                </option>

                <?php

                $cat = mysqli_query(
                    $conn,
                    "SELECT * FROM categories"
                );

                while($c = mysqli_fetch_array($cat)){

                ?>

                <option
                value="<?php echo $c['category_id']; ?>">

                    <?php echo $c['category_name']; ?>

                </option>

                <?php } ?>

            </select>

            <!-- DESCRIPTION -->
            <input
                type="text"
                name="description"
                placeholder="Enter Description"
            >

            <!-- DATE -->
            <input
                type="date"
                name="date"
                required
            >

            <!-- BUTTON -->
            <button
                type="submit"
                name="save"
                class="btn btn-primary"
            >
                Save Expense
            </button>

        </form>

        <br>

        <a href="dashboard.php">
            ← Back to Dashboard
        </a>

    </div>

</div>

</body>
</html>