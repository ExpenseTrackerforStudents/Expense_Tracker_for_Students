<?php include('db.php'); ?>

<?php
// ========================================
// SECURITY CHECK
// ========================================
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ========================================
// SEARCH & SORT
// ========================================
$search = "";

if(isset($_GET['search'])){
    $search = trim($_GET['search']);
}

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

// ========================================
// TOTAL EXPENSES
// ========================================
$totalQuery = mysqli_query($conn,"
SELECT SUM(amount) as total
FROM expenses
WHERE user_id='$user_id'
");

$totalRow = mysqli_fetch_assoc($totalQuery);

$total = $totalRow['total'];

// ========================================
// CHART DATA
// INNER JOIN:
// Combines expenses table and categories table
// to display category names with total expenses
// ========================================
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

// ========================================
// READ EXPENSES WITH INNER JOIN
// INNER JOIN PURPOSE:
// Displays category name together with expenses
// ========================================
$query = mysqli_query($conn,"
SELECT
expenses.*,
categories.category_name

FROM expenses

INNER JOIN categories
ON expenses.category_id = categories.category_id

WHERE expenses.user_id='$user_id'
AND categories.category_name LIKE '%$search%'

ORDER BY $order
");
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
    content="width=device-width, initial-scale=1.0">

    <title>Dashboard</title>

    <!-- STYLE -->
    <link rel="stylesheet" href="style.css">

    <!-- CHART JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>

<div class="container">

    <!-- HEADER -->
    <div class="top-box">

        <h2>
            Welcome,
            <?php echo $_SESSION['name']; ?>
        </h2>

        <h3>
            Total Expenses:
            ₱<?php echo $total ? $total : 0; ?>
        </h3>

        <br>

        <a href="add_expense.php"
        class="btn btn-primary">
            + Add Expense
        </a>

        <a href="categories.php"
        class="btn btn-success">
            Categories
        </a>

        <a href="logout.php"
        class="btn btn-danger">
            Logout
        </a>

    </div>

    <!-- SEARCH + SORT -->
    <form method="GET">

        <input
            type="text"
            name="search"
            placeholder="Search Category"
            value="<?php echo $search; ?>"
        >

        <select name="sort">

            <option value="date_desc">
                Sort By Latest
            </option>

            <option value="date_asc">
                Sort By Oldest
            </option>

            <option value="amount_asc">
                Lowest Amount
            </option>

            <option value="amount_desc">
                Highest Amount
            </option>

        </select>

        <button type="submit"
        class="btn btn-primary">
            Search
        </button>

    </form>

    <!-- CHART -->
    <div class="chart-box">

        <h3>Expense Chart</h3>

        <canvas id="expenseChart"></canvas>

    </div>

    <!-- TABLE -->
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

            <td>
                <?php echo $row['date']; ?>
            </td>

            <td>
                <?php echo $row['category_name']; ?>
            </td>

            <td>
                ₱<?php echo $row['amount']; ?>
            </td>

            <td>
                <?php echo $row['description']; ?>
            </td>

            <td>

                <a
                href="edit_expense.php?id=<?php echo $row['expense_id']; ?>"
                class="btn btn-success">
                    Edit
                </a>

                <a
                href="delete_expense.php?id=<?php echo $row['expense_id']; ?>"
                class="btn btn-danger"
                onclick="return confirm('Delete this expense?')">
                    Delete
                </a>

            </td>

        </tr>

        <?php } ?>

    </table>

</div>

<!-- CHART SCRIPT -->
<script>

const ctx = document.getElementById('expenseChart');

new Chart(ctx, {

    type: 'pie',

    data: {

        labels:
        <?php echo json_encode($labels); ?>,

        datasets: [{

            label: 'Expenses',

            data:
            <?php echo json_encode($values); ?>

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