<?php include('db.php'); ?>

<?php
// SECURITY CHECK
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// TOTAL EXPENSES
$totalQuery = mysqli_query($conn,"
SELECT SUM(amount) as total
FROM expenses
WHERE user_id='$user_id'
");

$totalRow = mysqli_fetch_assoc($totalQuery);
$total = $totalRow['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>

    <style>
        body{
            font-family: Arial;
            background: #f4f4f4;
        }

        .container{
            width: 90%;
            margin: auto;
        }

        table{
            width: 100%;
            background: white;
            border-collapse: collapse;
        }

        th, td{
            padding: 10px;
            text-align: center;
        }

        th{
            background: #333;
            color: white;
        }

        .top-box{
            background: white;
            padding: 15px;
            margin: 20px 0;
        }

        a{
            text-decoration: none;
            margin: 0 5px;
        }

        .btn{
            padding: 5px 10px;
            border-radius: 5px;
            color: white;
        }

        .edit{
            background: green;
        }

        .delete{
            background: red;
        }
    </style>

</head>
<body>

<div class="container">

<h2>Welcome, <?php echo $_SESSION['name']; ?></h2>

<div class="top-box">
    <h3>Total Expenses: ₱<?php echo $total ? $total : 0; ?></h3>
    <a href="add_expense.php">+ Add Expense</a> |
    <a href="logout.php">Logout</a>
</div>

<h2>My Expenses</h2>

<table>

<tr>
    <th>Date</th>
    <th>Category</th>
    <th>Amount</th>
    <th>Description</th>
    <th>Action</th>
</tr>

<?php

$query = mysqli_query($conn,"
SELECT expenses.*, categories.category_name
FROM expenses
INNER JOIN categories
ON expenses.category_id = categories.category_id
WHERE expenses.user_id = '$user_id'
ORDER BY expenses.date DESC
");

while($row = mysqli_fetch_array($query)){
?>

<tr>
    <td><?php echo $row['date']; ?></td>
    <td><?php echo $row['category_name']; ?></td>
    <td><?php echo $row['amount']; ?></td>
    <td><?php echo $row['description']; ?></td>

    <td>
        <a class="btn edit" href="edit_expense.php?id=<?php echo $row['expense_id']; ?>">Edit</a>

        <a class="btn delete"
        href="delete_expense.php?id=<?php echo $row['expense_id']; ?>"
        onclick="return confirm('Delete this expense?')">
        Delete
        </a>
    </td>
</tr>

<?php } ?>

</table>

</div>

</body>
</html>