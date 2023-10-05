<?php
include('config.php');

// Check if the user is logged in with appropriate permissions
session_start();
// if (!isset($_SESSION['admin'])) {
//     header("Location: login.php");
//     exit();
// }

// Fetch products from the database
$query = "SELECT * FROM products";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Welcome, Admin!</h1>

    <h2>Product Management</h2>
    <a href="add_product.php">Add New Product</a>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Action</th>
        </tr>
        <?php
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>$" . $row['price'] . "</td>";
            echo "<td><a href='edit_product.php?id=" . $row['id'] . "'>Edit</a> | <a href='delete_product.php?id=" . $row['id'] . "'>Delete</a></td>";
            echo "</tr>";
        }
        ?>
    </table>

    <a href="logout.php">Logout</a>
</body>
</html>
