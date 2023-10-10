<?php
ob_start();
include('header.php');

include('../config.php');
$id = $_REQUEST['id'];

$checkProductQuery = "SELECT * FROM products WHERE id = $id LIMIT 1";
$checkProductResult = mysqli_query($conn, $checkProductQuery);

if (mysqli_num_rows($checkProductResult) > 0) {
    // Product exists, proceed with deletion
    $query = "DELETE FROM products WHERE id='$id'";

    if (mysqli_query($conn, $query)) {
        $_SESSION['success_message'] = 'Product deleted successfully.';
        header('Location: manageproducts.php');
        exit();
    } else {
        $_SESSION['error_message'] = 'Error deleting product: ' . mysqli_error($conn);
        header('Location: manageproducts.php');
        exit();
    }
} else {
    $_SESSION['error_message'] = 'Product not found.';
    header('Location: manageproducts.php');
    exit();
}
?>
