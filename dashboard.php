<?php include('db.php'); ?>

<?php
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* SEARCH */
$search = "";
if(isset($_GET['search'])){
    $search = trim($_GET['search']);
}

/* SORT */
$order = "expenses.date DESC";

if(isset($_GET['sort'])){

    if($_GET['sort'] == "amount_asc"){
        $order = "expenses.amount ASC";
    }
    elseif($_GET['sort'] == "amount_desc"){
        $order = "expenses.amount DESC";
    }
    elseif($_GET['sort'] == "date_asc"){
        $order = "expenses.date ASC";
    }
    elseif($_GET['sort'] == "date_desc"){
        $order = "expenses.date DESC";
    }
}

/* TOTAL */
$totalQuery = mysqli_query($conn,"
SELECT SUM(amount) as total
FROM expenses
WHERE user_id='$user_id'
");

$totalRow = mysqli_fetch_assoc($totalQuery);
$total = $totalRow['total'] ?? 0;

/* CHART */
$chartQuery = mysqli_query($conn,"
SELECT categories.category_name,
SUM(expenses.amount) as total
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

/* TABLE */
$query = mysqli_query($conn,"
SELECT expenses.*, categories.category_name
FROM expenses
INNER JOIN categories
ON expenses.category_id = categories.category_id
WHERE expenses.user_id='$user_id'
AND categories.category_name LIKE '%$search%'
ORDER BY $order
");
?>

<!DOCTYPE html>
<html>
<head>

<title>Dashboard</title>

<link rel="stylesheet" href="style.css">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>

<div class="dashboard">

    <!-- SIDEBAR -->
    <div class="sidebar">

        <h2>Expense Tracker</h2>

        <a href="dashboard.php">Dashboard</a>
        <a href="add_expense.php">Add Expense</a>
        <a href="categories.php">Categories</a>
        <a href="logout.php">Logout</a>

    </div>

    <!-- MAIN -->
    <div class="main">

        <h2>Welcome, <?php echo $_SESSION['name']; ?></h2>

        <!-- CARDS -->
        <div class="cards">

            <div class="card">
                <h3>Total Expenses</h3>
                <p>₱<?php echo $total; ?></p>
            </div>

            <div class="card">
                <h3>Total Records</h3>
                <p><?php echo mysqli_num_rows($query); ?></p>
            </div>

        </div>

        <!-- SEARCH -->
        <form method="GET" class="search-box">

            <input type="text"
            name="search"
            placeholder="Search category"
            value="<?php echo $search; ?>">

            <select name="sort">
                <option value="date_desc">Latest</option>
                <option value="date_asc">Oldest</option>
                <option value="amount_asc">Lowest</option>
                <option value="amount_desc">Highest</option>
            </select>

            <button type="submit" class="btn btn-primary">
    Search
</button>

        </form>

        <!-- CHART -->
        <div class="chart-box">

            <canvas id="expenseChart"></canvas>

        </div>

        <!-- TABLE -->
        <div class="table-box">

            <table>

                <tr>
                    <th>Date</th>
                    <th>Category</th>
                    <th>Amount</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>

                <?php while($row = mysqli_fetch_array($query)){ ?>

                <tr>
                    <td><?php echo $row['date']; ?></td>
                    <td><?php echo $row['category_name']; ?></td>
                    <td>₱<?php echo $row['amount']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td>
                        <a href="edit_expense.php?id=<?php echo $row['expense_id']; ?>">Edit</a>
                        <a href="delete_expense.php?id=<?php echo $row['expense_id']; ?>">Delete</a>
                    </td>
                </tr>

                <?php } ?>

            </table>

        </div>

    </div>

</div>

<script>
new Chart(document.getElementById('expenseChart'), {
    type: 'pie',
    data: {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [{
            data: <?php echo json_encode($values); ?>
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});
</script>

</body>
</html>