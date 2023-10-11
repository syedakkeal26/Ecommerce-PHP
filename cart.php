
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
       
       // Check if the user is authenticated and obtain the user ID
       if (isset($_SESSION['user'])) {
           $user_id = $_SESSION['user'];

           // Check if the product is already in the cart
           $query = "SELECT id FROM carts WHERE product_id = ? AND user_id = ?";
           $stmt = $conn->prepare($query);
           $stmt->bind_param("ii", $product_id, $user_id);
           $stmt->execute();
           $result = $stmt->get_result();

           if ($result->num_rows > 0) {
               // Product is already in the cart; update the quantity
               $query = "UPDATE carts SET quantity = quantity + ? WHERE product_id = ? AND user_id = ?";
               $stmt = $conn->prepare($query);
               $stmt->bind_param("iii", $quantity, $product_id, $user_id);
               $stmt->execute();
           } else {
               // Product not in cart; add it
               $query = "INSERT INTO carts (product_id, quantity, user_id) VALUES (?, ?, ?)";
               $stmt = $conn->prepare($query);
               $stmt->bind_param("iii", $product_id, $quantity, $user_id);
               $stmt->execute();
           }
       }
   }
   $_SESSION['cart_count']++;
}
header('Location: products.php');
?>