<?php
ob_start();
include('header.php');

include('../config.php');
$id = $_REQUEST['id'];

$checkUserQuery = "SELECT * FROM users WHERE id = $id LIMIT 1";
$checkUserResult = mysqli_query($conn, $checkUserQuery);

if (mysqli_num_rows($checkUserResult) > 0) {
    // User exists, proceed with deletion
    $query = "DELETE FROM users WHERE id='$id'";

    if (mysqli_query($conn, $query)) {
        $_SESSION['success_message'] = 'Role deleted successfully.';
        header('Location: manageroles.php');
        exit();
    } else {
        $_SESSION['error_message'] = 'Error deleting user: ' . mysqli_error($conn);
        header('Location: manageroles.php'); 
        exit();
    }
} else {
    $_SESSION['error_message'] = 'User not found.';
    header('Location: manageroles.php'); 
    exit();
}
?>
