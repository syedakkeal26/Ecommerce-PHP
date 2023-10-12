<?php
session_start();

if (isset($_SESSION['user'])) {
    $user_id = $_SESSION['user'];

    if (isset($_GET['product_id'])) {
        $product_id = $_GET['product_id'];

        include('config.php');

        $delete_query = "DELETE FROM carts WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("ii", $user_id, $product_id);

        if ($stmt->execute()) {

            if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $key => $cartItem) {
                    if ($cartItem['product_id'] == $product_id) {
                        unset($_SESSION['cart'][$key]);
                    }
                }
            }

            if (isset($_SESSION['cart_count'])) {
                $_SESSION['cart_count'] -= 1;
            }
            else{
              $_SESSION['cart_count'] = 0;
            }

            header('Location: viewcart.php');
            exit();
        } else {
            echo "Failed to remove the product from the database: " . $conn->error;
        }
    }
}
?>
