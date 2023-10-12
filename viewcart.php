<?php
ob_start();
include('header.php');
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
include('config.php');

if (isset($_SESSION['user'])) {
    $user_id = $_SESSION['user'];

    $query = "SELECT carts.product_id, products.name, products.price, carts.quantity, products.image
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
                <div class ="card">
                    <div class="card-body p-4">
                    <div class="col-md-12">
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
                      $totalPrice = 0;
                      ?>
                      <table class="table table-hover progress-table text-center">
                          <thead>
                              <tr>
                                  <th>Item</th>
                                  <th></th>
                                  <th>Quantity</th>
                                  <th>Price</th>
                                  <th>Total per product</th>
                              </tr>
                          </thead>
                          <tbody>
                              <?php while ($cartItem = $result->fetch_assoc()) {
                                  $images = explode(',', $cartItem['image']);
                                  $firstImage = !empty($images) ? $images[0] : '';
                                  $subtotal = $cartItem['price'] * $cartItem['quantity'];
                                  $totalPrice += $subtotal;
                              ?>
                              <tr>
                                  <td>
                                      <img
                                          src="uploads/<?php echo $firstImage; ?>"
                                          class="img-fluid rounded-3"
                                          alt="Shopping item"
                                          style="width: 40px;">
                                  <div class="mb-2">
                                      <h5><?php echo strlen($cartItem['name']) > 20 ? substr($cartItem['name'], 0, 20) . '...' : $cartItem['name']; ?></h5>                </div>
                                  </td>
                                  <td>
                                      <a href="remove_from_cart.php?product_id=<?php echo $cartItem['product_id']; ?>">
                                          <i class="fa fa-trash"></i>
                                      </a>
                                  </td>
                                  <td>
                                      <div class="quantity-input">
                                          <button class="decrement-button checkbtn" data-product="<?php echo $cartItem['product_id']; ?>">-</button>
                                          <input type="number" name="quantity" class="checkbtn" value="<?php echo $cartItem['quantity']; ?>" min="1">
                                          <button class="increment-button checkbtn" data-product="<?php echo $cartItem['product_id']; ?>">+</button>
                                      </div>
                                  </td>
                                </td>
                                  <td>₹<?php echo $cartItem['price']; ?></td>
                                  <td class="product-total">₹<?php echo $cartItem['quantity'] * $cartItem['price'];?> </td>

                              </tr>
                              <?php } ?>
                          </tbody>
                      </table>
                          <table class="table table progress-table text-center">
                          <tr>
                              <td>Subtotal</td>
                              <td id="subtotal">₹<?php echo $totalPrice; ?></td>
                          </tr>
                          <tr>
                              <td>Total (Incl. taxes)</td>
                              <td id="total">₹<?php echo $totalPrice * (1 + 0.1); ?></td>
                          </tr>
                      </table>
                      <a href="checkout.php">
                        <button type="button" class="btn btn-info float-end">
                          <span>Continue<i class="fas fa-long-arrow-alt-right ms-2"></i></span>
                      </button>
                      </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
        $(".quantity-input").each(function() {
            const quantityInput = $(this).find("input[name='quantity']");
            const productId = $(this).find(".decrement-button").data("product");

            $(this).find(".decrement-button").on("click", function() {
                const currentValue = parseInt(quantityInput.val());
                if (currentValue > 1) {
                    quantityInput.val(currentValue - 1);
                    updateQuantityInDatabase(productId, currentValue - 1);
                    updateProductTotal(productId, currentValue - 1);
                }
            });

            $(this).find(".increment-button").on("click", function() {
                const currentValue = parseInt(quantityInput.val());
                quantityInput.val(currentValue + 1);
                updateQuantityInDatabase(productId, currentValue + 1);
                updateProductTotal(productId, currentValue + 1);
            });
        });

        function updateQuantityInDatabase(productId, newQuantity) {
            $.post('update_cart.php', { product_id: productId, quantity: newQuantity }, function(data) {
                // Handle the response from the server if needed
            });
        }

        function updateProductTotal(productId, newQuantity) {
            const productPrice = parseFloat($(`input[name='product_id'][value='${productId}']`).closest("tr").find(".price").text().replace('₹', ''));
            const newTotal = productPrice * newQuantity;
            $(`input[name='product_id'][value='${productId}']`).closest("tr").find(".product-total").text(`₹${newTotal.toFixed(2)}`);
            updateSubtotalAndTotal();
        }

        function updateSubtotalAndTotal() {
            let subtotal = 0;
            $(".product-total").each(function() {
                const productTotal = parseFloat($(this).text().replace('₹', ''));
                subtotal += productTotal;
            });
            $("#subtotal").text(`₹${subtotal.toFixed(2)}`);

            // Calculate total including taxes (change the tax rate as needed)
            const taxRate = 0.1; // 10% tax
            const total = subtotal * (1 + taxRate);
            $("#total").text(`₹${total.toFixed(2)}`);
        }
    });
</script>
