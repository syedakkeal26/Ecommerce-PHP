<?php
ob_start();
include('header.php');
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Include your database connection
include('config.php');

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>E-Commerce shop</title>
    <!-- bootstrap core css -->
    <link rel="stylesheet" type="text/css" href="famms-1.0.0/css/bootstrap.css" />
    <!-- font awesome style -->
    <link href="famms-1.0.0/css/font-awesome.min.css" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="famms-1.0.0/css/style.css" rel="stylesheet" />
    <!-- responsive style -->
    <link href="famms-1.0.0/css/responsive.css" rel="stylesheet" />
</head>
<body>
    <section class="product_section layout_padding">
        <div class="container">
            <div class="heading_container heading_center">
            </div>
            <div class="row">
                <?php
                // Retrieve the product details from the database
                $query = "SELECT * FROM products WHERE id = $product_id";
                $result = mysqli_query($conn, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    $product = mysqli_fetch_assoc($result);

                    // Display the product details
                    echo '<h1 class="text-center">' . $product['name'] . '</h1>';
                    // Display images in a carousel
                    echo '<div id="productImageCarousel" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">';

                    $imageReferences = explode(',', $product['image']);
                    $numImages = count($imageReferences);

                    for ($i = 0; $i < $numImages; $i++) {
                        echo '<li data-target="#productImageCarousel" data-slide-to="' . $i . '"';
                        if ($i === 0) {
                            echo ' class="active"';
                        }
                        echo '></li>';
                    }

                    echo '</ol>
                            <div class="carousel-inner">';

                    foreach ($imageReferences as $index => $image) {
                        echo '<div class="carousel-item';
                        if ($index === 0) {
                            echo ' active';
                        }
                        echo '">
                                <img src="./uploads/' . $image . '" alt="' . $product['name'] . '" class="d-block w-100">

                            </div>';
                    }
                    echo '</div>
                            <a class="carousel-control-prev" href="#productImageCarousel" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#productImageCarousel" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>';

                    echo '<p>' . $product['description'] . '</p>';
                    echo '<p>Price: ₹' . $product['price'] . '</p>';
                    echo '<p>Stock: ' . $product['stock'] . ' items available</p>';

                    // Add to Cart button
                    echo '<form method="post" action="cart.php">';
                    echo '<input type="hidden" name="product_id" value="' . $product['id'] . '">';
                    echo '<input type="number" name="quantity" value="1" min="1" style="width: 10%;">';
                    echo '<br>';
                    echo '<input type="submit" value="Add to Cart" style="float: left;">';
                    echo '</form>';
                } else {
                    echo 'Product not found.';
                }
                ?>
            </div>
        </div>
        <div class="btn-box">
               <a href="products.php">
               View All products
               </a>
            </div>
    </section>
    <script src="famms-1.0.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
