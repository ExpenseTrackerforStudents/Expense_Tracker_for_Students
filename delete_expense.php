<?php include('db.php'); ?>

<?php
// SECURITY CHECK
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// CHECK ID
if(isset($_GET['id'])){

    $id = $_GET['id'];

    // SECURE DELETE (ONLY OWNER CAN DELETE)
    $stmt = $conn->prepare("DELETE FROM expenses WHERE expense_id=? AND user_id=?");
    $stmt->bind_param("ii", $id, $user_id);

    $stmt->execute();
    $stmt->close();
}

// REDIRECT BACK
header("Location: dashboard.php");
exit();
?>