<?php
include('header.php');
if (!isset($_SESSION['user'])) {
    header('Location: login.php'); 
    exit;
 }
include('config.php'); 


if (isset($_SESSION['user'])) {
    $user_id = $_SESSION['user'];

    // Retrieve cart items for the authenticated user from the database
    $query = "SELECT carts.product_id, products.name, products.price, carts.quantity 
              FROM carts 
              INNER JOIN products ON carts.product_id = products.id 
              WHERE carts.user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
} 
?>

    <section class="h-100 h-custom" style="background-color: #eee;">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col">
                <div class="card">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-lg-7">
                                <h5 class="mb-3">
                                    <a href="#!" class="text-body"><i class="fas fa-long-arrow-alt-left me-2"></i>Continue shopping</a>
                                </h5>
                                <hr>
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div>
                                        <p class="mb-1">Shopping cart</p>
                                        <p class="mb-0">
                                            You have <?php echo isset($_SESSION['cart_count']) ? $_SESSION['cart_count'] : 0; ?> items in your cart
                                        </p>
                                    </div>
                                </div>
                                <?php
                                if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                                    $totalPrice = 0;
                                    foreach ($_SESSION['cart'] as $cartItem) {
                                        $subtotal = $cartItem['price'] * $cartItem['quantity'];
                                        $totalPrice += $subtotal;
                                ?>
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div class="d-flex flex-row align-items-center">
                                                    <div>
                                                        <img
                                                            src=""
                                                            class="img-fluid rounded-3"
                                                            alt="Shopping item"
                                                            style="width: 65px;"
                                                        >
                                                    </div>
                                                    <div class="ms-3">
                                                        <h5><?php echo $cartItem['name']; ?></h5>
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-row align-items-center">
                                                    <div style="width: 50px;">
                                                        <h5 class="fw-normal mb-0"><?php echo $cartItem['quantity']; ?></h5>
                                                    </div>
                                                    <div style="width: 80px;">
                                                        <h5 class="mb-0">₹<?php echo $cartItem['price']; ?></h5>
                                                    </div>
                                                    <a href="#!" style="color: #cecece;"><i class="fas fa-trash-alt"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                    }
                                }
                                ?>
                            </div>
                            <div class="col-lg-5">
                                <div class="card bg-primary text-white rounded-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <h5 class="mb-0">Card details</h5>
                                            <img src="https://mdbcdn.b-cdn.net/img/Photos/Avatars/avatar-6.webp" class="img-fluid rounded-3" style="width: 45px;" alt="Avatar">
                                        </div>
                                        <p class="small mb-2">Card type</p>
                                        <a href="#!" type="submit" class="text-white"><i class="fab fa-cc-mastercard fa-2x me-2"></i></a>
                                        <a href="#!" type="submit" class="text-white"><i class="fab fa-cc-visa fa-2x me-2"></i></a>
                                        <a href="#!" type="submit" class="text-white"><i class="fab fa-cc-amex fa-2x me-2"></i></a>
                                        <a href="#!" type="submit" class="text-white"><i class="fab fa-cc-paypal fa-2x"></i></a>
                                        <form action="checkout.php" method="POST">
    <!-- Add fields for user information -->
    <input type="text" name="name" placeholder="Full Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <textarea name="address" placeholder="Shipping Address" required></textarea>

    <!-- Add fields for payment details -->
    <input type="text" name="cardholder" placeholder="Cardholder's Name" required>
    <input type="text" name="cardnumber" placeholder="Card Number" required>
    <!-- Other payment fields like expiration and CVV -->
    
    <button type="submit">Place Order</button>
</form>
                                        <form class="mt-4">
                                            <div class="form-outline form-white mb-4">
                                                <input type="text" id="typeName" class="form-control form-control-lg" size="17" placeholder="Cardholder's Name" />
                                                <label class="form-label" for="typeName">Cardholder's Name</label>
                                            </div>
                                            <div class="form-outline form-white mb-4">
                                                <input type="text" id="typeText" class="form-control form-control-lg" size="17" placeholder="1234 5678 9012 3457" minlength="19" maxlength="19" />
                                                <label class="form-label" for="typeText">Card Number</label>
                                            </div>
                                            <div class="row mb-4">
                                                <div class="col-md-6">
                                                    <div class="form-outline form-white">
                                                        <input type="text" id="typeExp" class="form-control form-control-lg" placeholder="MM/YYYY" size="7" id="exp" minlength="7" maxlength="7" />
                                                        <label class="form-label" for="typeExp">Expiration</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-outline form-white">
                                                        <input type="password" id="typeText" class="form-control form-control-lg" placeholder="&#9679;&#9679;&#9679;" size="1" minlength="3" maxlength="3" />
                                                        <label class="form-label" for="typeText">Cvv</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <hr class="my-4">
                                        <div class="d-flex justify-content-between">
                                            <p class="mb-2">Subtotal</p>
                                            <p class="mb-2">₹<?php echo $totalPrice; ?></p>
                                        </div>
                                        <div class="d-flex justify-content-between mb-4">
                                            <p class="mb-2">Total(Incl. taxes)</p>
                                            <p class="mb-2">₹<?php echo $totalPrice; ?></p>
                                        </div>
                                        <button type="button" class="btn btn-info btn-block btn-lg">
                                            <div class="d-flex justify-content-between">
                                                <span>₹<?php echo $totalPrice; ?></span>
                                                <span>Checkout <i class="fas fa-long-arrow-alt-right ms-2"></i></span>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
