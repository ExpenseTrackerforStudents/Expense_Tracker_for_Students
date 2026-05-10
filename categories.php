<?php include('db.php'); ?>

<?php
// CHECK LOGIN
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

// ADD CATEGORY
if(isset($_POST['add'])){

    $category = trim($_POST['category']);

    if(!empty($category)){

        mysqli_query($conn,
        "INSERT INTO categories(category_name)
        VALUES('$category')");

        header("Location: categories.php");
        exit();
    }
}

// DELETE CATEGORY
if(isset($_GET['delete'])){

    $id = $_GET['delete'];

    mysqli_query($conn,
    "DELETE FROM categories WHERE category_id='$id'");

    header("Location: categories.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Categories</title>

    <style>
        body{
            font-family: Arial;
            background: #f4f4f4;
        }

        .container{
            width: 500px;
            margin: auto;
            background: white;
            padding: 20px;
            margin-top: 50px;
            border-radius: 10px;
        }

        input{
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }

        button{
            width: 100%;
            padding: 10px;
            background: blue;
            color: white;
            border: none;
            cursor: pointer;
        }

        table{
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        th, td{
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th{
            background: #333;
            color: white;
        }

        a{
            color: red;
            text-decoration: none;
        }
    </style>
</head>

<body>

<div class="container">

<h2>Manage Categories</h2>

<!-- ADD CATEGORY -->
<form method="POST">

<input type="text" name="category" placeholder="Enter Category Name" required>

<button type="submit" name="add">Add Category</button>

</form>

<!-- CATEGORY LIST -->
<table>

<tr>
    <th>ID</th>
    <th>Category Name</th>
    <th>Action</th>
</tr>

<?php

$result = mysqli_query($conn, "SELECT * FROM categories");

while($row = mysqli_fetch_array($result)){
?>

<tr>
    <td><?php echo $row['category_id']; ?></td>
    <td><?php echo $row['category_name']; ?></td>
    <td>
        <a href="categories.php?delete=<?php echo $row['category_id']; ?>"
        onclick="return confirm('Delete this category?')">
        Delete
        </a>
    </td>
</tr>

<?php } ?>

</table>

<br>

<a href="dashboard.php">Back to Dashboard</a>

</div>

</body>
</html>