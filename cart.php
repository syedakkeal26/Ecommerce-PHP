
<?php
ob_start();
include('header.php');
if (!isset($_SESSION['user'])) {
   header('Location: login.php');
   exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
       $product_id = $_POST['product_id'];
       $quantity = $_POST['quantity'];

       if (isset($_SESSION['user'])) {
        $user_id = $_SESSION['user'];

        $query = "SELECT id FROM carts WHERE product_id = ? AND user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $product_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $query = "UPDATE carts SET quantity = quantity + ? WHERE product_id = ? AND user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("iii", $quantity, $product_id, $user_id);
            $stmt->execute();
        } else {
            $query = "INSERT INTO carts (product_id, quantity, user_id) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("iii", $product_id, $quantity, $user_id);
            $stmt->execute();
        }
   }
  }
  if (isset($_SESSION['user'])) {
    $user_id = $_SESSION['user'];

    // Fetch the total number of items in the user's cart
    $total_items_query = "SELECT COUNT(*) AS total_items FROM carts WHERE user_id = $user_id";
    $total_items_result = $conn->query($total_items_query);

    if ($total_items_result->num_rows > 0) {
        $row = $total_items_result->fetch_assoc();
        $total_items = $row['total_items'];

        $insert_query = "INSERT INTO user_cart (user_id, total_items) VALUES (?, ?) ON DUPLICATE KEY UPDATE total_items = VALUES(total_items)";
        $stmt = $conn->prepare($insert_query);

        // Check if the query was prepared successfully
        if ($stmt) {
            $stmt->bind_param("ii", $user_id, $total_items);
            $stmt->execute();

            // Update the $_SESSION['cart_count']
            $_SESSION['cart_count'] = $total_items;
        } else {
            // Handle the case where the query couldn't be prepared
            echo "Error preparing the query: " . $conn->error;
        }
    }
}
}
header('Location: products.php');
?>
