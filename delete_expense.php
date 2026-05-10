<?php include('db.php'); ?>

<?php

$id = $_GET['id'];

mysqli_query($conn,"DELETE FROM expenses WHERE expense_id='$id'");

header("Location: dashboard.php");
?>