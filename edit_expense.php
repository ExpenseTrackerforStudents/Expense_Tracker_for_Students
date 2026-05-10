<?php include('db.php'); ?>

<?php
$id = $_GET['id'];

$data = mysqli_query($conn,"SELECT * FROM expenses WHERE expense_id='$id'");
$row = mysqli_fetch_array($data);

if(isset($_POST['update'])){

    $amount = $_POST['amount'];
    $description = $_POST['description'];

    mysqli_query($conn,
    "UPDATE expenses SET amount='$amount', description='$description'
    WHERE expense_id='$id'");

    header("Location: dashboard.php");
}
?>

<h2>Edit Expense</h2>

<form method="POST">

<input type="number" name="amount" value="<?php echo $row['amount']; ?>"><br><br>

<input type="text" name="description" value="<?php echo $row['description']; ?>"><br><br>

<button type="submit" name="update">Update</button>

</form>