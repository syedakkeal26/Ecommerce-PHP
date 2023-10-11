<body>
    <h1>Checkout</h1>

    <h2>Order Summary</h2>

    <?php
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        $totalPrice = 0;
        foreach ($_SESSION['cart'] as $cartItem) {
            $subtotal = $cartItem['price'] * $cartItem['quantity'];
            $totalPrice += $subtotal;
            ?>
            <div class="card">
                <div class="card-body">
                    <h5><?php echo $cartItem['name']; ?></h5>
                    <p>Quantity: <?php echo $cartItem['quantity']; ?></p>
                    <p>Price: ₹<?php echo $cartItem['price']; ?></p>
                </div>
            </div>
        <?php
        }
    }
    ?>

    <h3>Total: ₹<?php echo $totalPrice; ?></h3>

    <h2>Shipping Information</h2>

    <form action="process_order.php" method="post">
        <label for="name">Full Name:</label>
        <input type="text" name="name" required><br><br>

        <label for="address">Shipping Address:</label>
        <textarea name="address" required></textarea><br><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br><br>

        <h2>Payment Information</h2>

        <label for="cardholder">Cardholder's Name:</label>
        <input type="text" name="cardholder" required><br><br>

        <label for="cardnumber">Card Number:</label>
        <input type="text" name="cardnumber" required><br><br>

        <label for="expiration">Expiration Date (MM/YYYY):</label>
        <input type="text" name="expiration" required><br><br>

        <label for="cvv">CVV:</label>
        <input type="password" name="cvv" required><br><br>

        <button type="submit">Place Order</button>
    </form>
</body>
</html>