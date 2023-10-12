
<?php
ob_start();
include('header.php');
if (!isset($_SESSION['user'])) {
   header('Location: login.php');
   exit;
}
?>

<!DOCTYPE html>
<html>
   <head>
      <!-- Basic -->
      <meta charset="utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <!-- Mobile Metas -->
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
      <!-- Site Metas -->
      <meta name="keywords" content="" />
      <meta name="description" content="" />
      <meta name="author" content="" />
      <!-- <link rel="shortcut icon" href="famms-1.0.0/images/favicon.png" type=""> -->
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
               <h2>
                  Our <span>products</span>
               </h2>
            </div>
            <div class="row">
                <?php
                $query = "SELECT * FROM products";
                $result = mysqli_query($conn, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                  while ($product = mysqli_fetch_assoc($result)) {
                      echo '<div class="col-sm-6 col-md-4 col-lg-4">
                          <div class="box">
                              <div class="option_container">
                                  <div class="options">
                                      <form method="post" action="cart.php">
                                          <input type="hidden" name="product_id" value="' . $product['id'] . '">
                                          <input type="number" name="quantity" value="1" min="1">
                                          <input type="submit" class="option1" value="Add To Cart">
                                      </form>
                                      <br>
                                      <a class="option2" href="viewproduct.php?product_id=' . $product['id'] . '">VIEW</a>
                                  </div>
                              </div>
                              <div class="img-box">';

                      $imageReferences = explode(',', $product['image']);
                      if (!empty($imageReferences[0])) {
                          echo '<img src="./uploads/' . $imageReferences[0] . '" alt="' . $product['name'] . '">';
                      }
                      echo '</div>
                          <div class="detail-box">
                              <h5>' . $product['name'] . '</h5><br>
                              <h6>₹' . $product['price'] . '</h6>
                          </div>
                      </div>
                  </div>';
                  }
              } else {
                  echo 'No products found in this category.';
              }
              ?>
              </div>
            </div>
         </div>
      </section>


      <script src="famms-1.0.0/js/jquery-3.4.1.min.js"></script>
      <!-- popper js -->
      <script src="famms-1.0.0/js/popper.min.js"></script>
      <!-- bootstrap js -->
      <script src="famms-1.0.0/js/bootstrap.js"></script>
      <!-- custom js -->
      <script src="famms-1.0.0/js/custom.js"></script>
   </body>
</html>
