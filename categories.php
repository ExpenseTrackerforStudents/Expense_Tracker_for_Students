<?php include('db.php'); ?>

<?php
// ========================================
// CHECK LOGIN
// ========================================
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$message = "";

// ========================================
// ADD CATEGORY
// ========================================
if(isset($_POST['add'])){

    $category = trim($_POST['category']);

    // VALIDATION
    if(empty($category)){

        $message = "Category name is required.";

    } else {

        // CHECK IF CATEGORY EXISTS
        $check = $conn->prepare("
            SELECT category_id
            FROM categories
            WHERE category_name=?
        ");

        $check->bind_param("s", $category);

        $check->execute();

        $result = $check->get_result();

        // CATEGORY ALREADY EXISTS
        if($result->num_rows > 0){

            $message = "Category already exists.";

        } else {

            // INSERT CATEGORY
            $stmt = $conn->prepare("
                INSERT INTO categories(category_name)
                VALUES(?)
            ");

            $stmt->bind_param("s", $category);

            if($stmt->execute()){

                $message = "Category added successfully.";

            } else {

                $message = "Failed to add category.";
            }

            $stmt->close();
        }

        $check->close();
    }
}

// ========================================
// DELETE CATEGORY
// ========================================
if(isset($_GET['delete'])){

    $id = $_GET['delete'];

    // DELETE QUERY
    $stmt = $conn->prepare("
        DELETE FROM categories
        WHERE category_id=?
    ");

    $stmt->bind_param("i", $id);

    $stmt->execute();

    $stmt->close();

    header("Location: categories.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
    content="width=device-width, initial-scale=1.0">

    <title>Manage Categories</title>

    <!-- STYLE -->
    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container">

    <div class="form-box">

        <h2>Manage Categories</h2>

        <!-- MESSAGE -->
        <?php if($message != ""){ ?>

            <p class="success">
                <?php echo $message; ?>
            </p>

        <?php } ?>

        <!-- ADD CATEGORY FORM -->
        <form method="POST">

            <input
                type="text"
                name="category"
                placeholder="Enter Category Name"
                required
            >

            <button
                type="submit"
                name="add"
                class="btn btn-primary"
            >
                Add Category
            </button>

        </form>

    </div>

    <!-- CATEGORY TABLE -->
    <table>

        <tr>

            <th>ID</th>
            <th>Category Name</th>
            <th>Action</th>

        </tr>

        <?php

        $result = mysqli_query(
            $conn,
            "SELECT * FROM categories ORDER BY category_name ASC"
        );

        while($row = mysqli_fetch_array($result)){

        ?>

        <tr>

            <td>
                <?php echo $row['category_id']; ?>
            </td>

            <td>
                <?php echo $row['category_name']; ?>
            </td>

            <td>

                <a
                href="categories.php?delete=<?php echo $row['category_id']; ?>"
                class="btn btn-danger"
                onclick="return confirm('Delete this category?')">

                    Delete

                </a>

            </td>

        </tr>

        <?php } ?>

    </table>

    <br>

    <a href="dashboard.php"
    class="btn btn-success">

        ← Back to Dashboard

    </a>

</div>

</body>
</html>