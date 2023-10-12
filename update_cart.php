<?php
include('config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
        $productId = $_POST['product_id'];
        $newQuantity = $_POST['quantity'];

        $updateQuery = "UPDATE carts SET quantity = ? WHERE product_id = ? AND user_id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("iii", $newQuantity, $productId, $_SESSION['user']);
        $updateSuccessful = $stmt->execute();

        if ($updateSuccessful) {
            http_response_code(200); // Success
            echo json_encode(['message' => 'Quantity updated successfully']);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['message' => 'Error updating quantity']);
        }
    } else {
        http_response_code(400); // Bad Request
        echo json_encode(['message' => 'Invalid data provided']);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['message' => 'Method not allowed']);
}
?>
