<?php include('db.php'); ?>

<?php
// SECURITY CHECK
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$error = "";

// CHECK ID
if(!isset($_GET['id'])){
    header("Location: dashboard.php");
    exit();
}

$id = $_GET['id'];

/* =========================
   GET EXISTING EXPENSE
========================= */
$stmt = $conn->prepare("
    SELECT * FROM expenses
    WHERE expense_id=? AND user_id=?
");

$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

$stmt->close();

// IF NO DATA FOUND
if(!$row){
    die("Expense not found.");
}

/* =========================
   UPDATE EXPENSE
========================= */
if(isset($_POST['update'])){

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
    else{

        $update = $conn->prepare("
            UPDATE expenses
            SET amount=?, category_id=?, description=?, date=?
            WHERE expense_id=? AND user_id=?
        ");

        $update->bind_param(
            "dissii",
            $amount,
            $category_id,
            $description,
            $date,
            $id,
            $user_id
        );

        if($update->execute()){
            header("Location: dashboard.php");
            exit();
        }
        else{
            $error = "Failed to update expense.";
        }

        $update->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Edit Expense</title>

    <!-- EXTERNAL CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="page-center">

    <div class="form-card">

        <h2>Edit Expense</h2>

        <?php if($error != ""){ ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>

        <form method="POST">

            <label>Amount</label>
            <input 
                type="number"
                step="0.01"
                name="amount"
                value="<?php echo $row['amount']; ?>"
                required
            >

            <label>Category</label>
            <select name="category_id" required>

                <option value="">Select Category</option>

                <?php
                $cat = mysqli_query($conn, "SELECT * FROM categories ORDER BY category_name ASC");

                while($c = mysqli_fetch_array($cat)){
                ?>

                <option 
                    value="<?php echo $c['category_id']; ?>"
                    <?php if($c['category_id'] == $row['category_id']) echo "selected"; ?>
                >
                    <?php echo $c['category_name']; ?>
                </option>

                <?php } ?>

            </select>

            <label>Description</label>
            <input
                type="text"
                name="description"
                value="<?php echo $row['description']; ?>"
                placeholder="Enter description"
            >

            <label>Date</label>
            <input
                type="date"
                name="date"
                value="<?php echo $row['date']; ?>"
                required
            >

            <button type="submit" name="update" class="btn-success">
                Update Expense
            </button>

        </form>

        <a href="dashboard.php" class="back-link">
            ← Back to Dashboard
        </a>

    </div>

</div>

</body>
</html>