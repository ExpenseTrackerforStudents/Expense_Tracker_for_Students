<?php include('db.php'); ?>

<?php
// SECURITY CHECK
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$id = $_GET['id'];

// GET EXISTING DATA (SAFE VERSION)
$stmt = $conn->prepare("SELECT * FROM expenses WHERE expense_id=? AND user_id=?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if(!$row){
    echo "Expense not found.";
    exit();
}

// UPDATE PROCESS
if(isset($_POST['update'])){

    $amount = trim($_POST['amount']);
    $description = trim($_POST['description']);
    $category_id = trim($_POST['category_id']);
    $date = trim($_POST['date']);

    if(empty($amount) || empty($category_id) || empty($date)){
        echo "Please fill required fields.";
    }
    else {

        $update = $conn->prepare("
            UPDATE expenses
            SET amount=?, description=?, category_id=?, date=?
            WHERE expense_id=? AND user_id=?
        ");

        $update->bind_param(
            "ssissi",
            $amount,
            $description,
            $category_id,
            $date,
            $id,
            $user_id
        );

        $update->execute();

        header("Location: dashboard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Expense</title>

    <style>
        body{
            font-family: Arial;
            background: #f4f4f4;
        }

        .container{
            width: 400px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
        }

        input, select{
            width: 100%;
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
    </style>

</head>
<body>

<div class="container">

<h2>Edit Expense</h2>

<form method="POST">

    <input type="number" name="amount" value="<?php echo $row['amount']; ?>" required>

    <select name="category_id" required>
        <option value="">Select Category</option>

        <?php
        $cat = mysqli_query($conn, "SELECT * FROM categories");
        while($c = mysqli_fetch_array($cat)){
        ?>
            <option value="<?php echo $c['category_id']; ?>"
                <?php if($c['category_id'] == $row['category_id']) echo "selected"; ?>>
                <?php echo $c['category_name']; ?>
            </option>
        <?php } ?>

    </select>

    <input type="text" name="description" value="<?php echo $row['description']; ?>">

    <input type="date" name="date" value="<?php echo $row['date']; ?>" required>

    <button type="submit" name="update">Update</button>

</form>

</div>

</body>
</html>