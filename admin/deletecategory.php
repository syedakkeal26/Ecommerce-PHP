<?php
ob_start();
include('header.php');

include('../config.php');
$id = $_REQUEST['id'];

$checkCategoryQuery = "SELECT * FROM categories WHERE id = $id LIMIT 1";
$checkCategoryResult = mysqli_query($conn, $checkCategoryQuery);

if (mysqli_num_rows($checkCategoryResult) > 0) {

    $updateProductsQuery = "UPDATE products SET category_id = 6 WHERE category_id = $id";
    mysqli_query($conn, $updateProductsQuery);

    // Now, you can safely delete the category
    $deleteCategoryQuery = "DELETE FROM categories WHERE id='$id'";

    if (mysqli_query($conn, $deleteCategoryQuery)) {
        $_SESSION['success_message'] = 'Category deleted successfully.';
        header('Location: managecategories.php');
        exit();
    } else {
        $_SESSION['error_message'] = 'Error deleting category: ' . mysqli_error($conn);
        header('Location: managecategories.php');
        exit();
    }
} else {
    $_SESSION['error_message'] = 'Category not found.';
    header('Location: managecategories.php');
    exit();
}
?>
