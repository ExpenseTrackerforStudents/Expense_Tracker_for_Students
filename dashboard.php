<?php include('db.php'); ?>

<?php
// SECURITY CHECK
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* =========================
   TOTAL EXPENSES
========================= */
$totalQuery = mysqli_query($conn,"
SELECT SUM(amount) as total
FROM expenses
WHERE user_id='$user_id'
");

$totalRow = mysqli_fetch_assoc($totalQuery);
$total = $totalRow['total'];

/* =========================
   CHART DATA (SQL JOIN + GROUP BY)
========================= */
$chartQuery = mysqli_query($conn,"
SELECT categories.category_name, SUM(expenses.amount) as total
FROM expenses
INNER JOIN categories
ON expenses.category_id = categories.category_id
WHERE expenses.user_id = '$user_id'
GROUP BY categories.category_name
");

$labels = [];
$values = [];

while($c = mysqli_fetch_array($chartQuery)){
    $labels[] = $c['category_name'];
    $values[] = $c['total'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>

    <!-- CHART.JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body{
            font-family: Arial;
            background: #f4f4f4;
        }

        .container{
            width: 90%;
            margin: auto;
        }

        .top-box{
            background: white;
            padding: 15px;
            margin: 20px 0;
            border-radius: 10px;
        }

        table{
            width: 100%;
            background: white;
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
        }

        th, td{
            padding: 10px;
            text-align: center;
        }

        th{
            background: #333;
            color: white;
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

        .edit{ background: green; }
        .delete{ background: red; }

        canvas{
            background: white;
            padding: 10px;
            margin-top: 20px;
            border-radius: 10px;
        }
    </style>

</head>
<body>

<div class="container">

<h2>Welcome, <?php echo $_SESSION['name']; ?></h2>

<!-- TOTAL + ACTIONS -->
<div class="top-box">
    <h3>Total Expenses: ₱<?php echo $total ? $total : 0; ?></h3>

    <a href="add_expense.php">+ Add Expense</a> |
    <a href="categories.php">Manage Categories</a> |
    <a href="logout.php">Logout</a>
</div>

<!-- CHART -->
<h3>Expense Chart</h3>
<canvas id="expenseChart"></canvas>

<script>
const ctx = document.getElementById('expenseChart');

new Chart(ctx, {
    type: 'pie',
    data: {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [{
            label: 'Expenses',
            data: <?php echo json_encode($values); ?>
        }]
    }
});
</script>

<!-- TABLE -->
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