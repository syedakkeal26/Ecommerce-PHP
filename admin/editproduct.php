<?php
ob_start();
include('header.php');

function handleNewImageUploads($newImages, $existingImages) {
    $uploadDir = '../uploads/';
    $newImageReferences = [];

    foreach ($newImages['name'] as $key => $filename) {
        if ($newImages['error'][$key] === UPLOAD_ERR_OK) {
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            $newFilename = uniqid() . '.' . $ext;
            $newImageReferences[] = $newFilename;
            move_uploaded_file($newImages['tmp_name'][$key], $uploadDir . $newFilename);
        }
    }

    // Merge the existing and new images
    $updatedImages = array_merge($existingImages, $newImageReferences);

    return implode(',', $updatedImages);
}

if (isset($_POST['submit'])) {
    $productId = mysqli_real_escape_string($conn, $_POST['product_id']);

    // Retrieve existing product data from the database
    $query = "SELECT * FROM products WHERE id = $productId";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        // Handle the query error (you can add more detailed error handling)
        echo 'Error: ' . mysqli_error($conn);
        exit();
    }

    if (mysqli_num_rows($result) > 0) {
        $productData = mysqli_fetch_assoc($result);

        // Existing images as an array
        $existingImages = explode(',', $productData['image']);

        // Handle new image uploads
        if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            $newImages = handleNewImageUploads($_FILES['images'], $existingImages);
        } else {
            $newImages = $existingImages; // Keep the existing images as they are
        }

        // Convert the updated image references back to a comma-separated string
        $imageReferencesString = implode(',', (array) $newImages);

        // Retrieve and sanitize other product data
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $category_id = $_POST['category_id'];

        // Other validation and update code goes here
        $errors = array();
        if (empty($name)) {
            $errors['name'] = "Product name is required";
        }
        if (empty($description)) {
            $errors['description'] = "Description is required";
        }
        if (empty($price) || !is_numeric($price) || $price <= 0) {
            $errors['price'] = "Invalid price format";
        }
        if (empty($stock) || !is_numeric($stock) || $stock < 0) {
            $errors['stock'] = "Invalid stock quantity format";
        }
        if (empty($category_id)) {
            $errors['category_id'] = "Category is required";
        }

        if (empty($errors)) {
            $updateQuery = "UPDATE products SET name=?, description=?, price=?, stock=?, category_id=?, image=? WHERE id=?";
            $stmt = mysqli_prepare($conn, $updateQuery);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "ssdissi", $name, $description, $price, $stock, $category_id, $imageReferencesString, $productId);

                if (mysqli_stmt_execute($stmt)) {
                    $_SESSION['success_message'] = 'Product updated successfully';
                    header('Location: manageproducts.php');
                    exit();
                } else {
                    $errors['database'] = "Error updating product: " . mysqli_error($conn);
                }

                mysqli_stmt_close($stmt);
            } else {
                $errors['database'] = "Error preparing the database statement";
            }
        }
    } else {
        echo 'Product not found.';
        exit();
    }
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $productId = intval($_GET['id']);
    $query = "SELECT * FROM products WHERE id = $productId";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $productData = mysqli_fetch_assoc($result);
        $existingImages = explode(',', $productData['image']);

        $name = $productData['name'];
        $description = $productData['description'];
        $image = $productData['image'];
        $price = $productData['price'];
        $stock = $productData['stock'];
        $category_id = $productData['category_id'];

        $_SESSION['newname'] = $name;
        $_SESSION['newimages'] = $image;
        $_SESSION['newdescription'] = $description;
        $_SESSION['newprice'] = $price;
        $_SESSION['newstock'] = $stock;
        $_SESSION['newcategory_id'] = $category_id;
    } else {
       $existingImages = [];  // Define an empty array if no product is found
        echo 'Product not found.';
        exit();
    }
}


?>

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Edit Product Form -->
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Products / <a href="manageproducts.php">Manage Products</a> /</span> Edit Product</h4>
        <div class="row">
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Edit Product</h5>
                    </div>
                    <div class="card-body">
                        <form id="formProduct" class="mb-3" action="" method="POST" enctype="multipart/form-data">
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="name">Product Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Product Name" value="<?php echo (isset($_SESSION['newname'])) ? $_SESSION['newname'] : '' ?>" />
                              <span style="color: red;"><?php echo isset($errors['name']) ? $errors['name'] : ''; ?></span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="description">Description</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" id="description" name="description" placeholder="Enter Product Description"><?php echo (isset($_SESSION['newdescription'])) ? $_SESSION['newdescription'] : '' ?></textarea>
                                    <span style="color: red;"><?php echo isset($errors['description']) ? $errors['description'] : ''; ?></span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="images">Product Images</label>
                                <div class="col-sm-10">
                                <div id="image-upload-container">
                                        <?php
                                            foreach ($existingImages as $imageReference) {
                                            echo "<div class='image-preview'>";
                                            echo '<img src="../uploads/' . $imageReference . '" alt="Image" height="80px" width="80px">';
                                                // echo "<button class='btn btn-danger remove-image-button' type='button' data-image='$imageReference'>Remove</button>";
                                            echo "</div>";
                                        }
                                        ?>
                                    </div>
                                    <button type="button" id="add-image-input" class="btn btn-primary">Add More Images</button>
                                    <span style="color: red;"><?php echo isset($errors['images']) ? $errors['images'] : ''; ?></span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="price">Price</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="price" name="price" placeholder="Enter Price" value="<?php echo (isset($_SESSION['newprice'])) ? $_SESSION['newprice'] : '' ?>" />
                                    <span style="color: red;"><?php echo isset($errors['price']) ? $errors['price'] : ''; ?></span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="stock">Stock</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="stock" name="stock" placeholder="Enter Stock Quantity" value="<?php echo (isset($_SESSION['newstock'])) ? $_SESSION['newstock'] : '' ?>" />
                                    <span style="color: red;"><?php echo isset($errors['stock']) ? $errors['stock'] : ''; ?></span>
                                </div>
                            </div>
                            <div class="row mb-3">
                              <label class="col-sm-2 col-form-label" for="category_id">Category</label>
                              <div class="col-sm-10">
                                  <select class="form-select" id="category_id" name="category_id">
                                      <option value="" selected>Select a Category</option>

                                      <?php
                                      $query = "SELECT id, name FROM categories";
                                      $result = mysqli_query($conn, $query);

                                      if ($result) {
                                          while ($row = mysqli_fetch_assoc($result)) {
                                              $categoryId = $row['id'];
                                              $categoryName = $row['name'];
                                              $selected = (isset($_SESSION['newcategory_id']) && $_SESSION['newcategory_id'] == $categoryId) ? 'selected' : '';

                                              echo "<option value='$categoryId' $selected>$categoryName</option>";
                                          }
                                      } else {
                                          echo "<option value=''>Error fetching categories</option>";
                                      }
                                      ?>
                                  </select>
                                  <span style="color: red;"><?php echo isset($errors['category_id']) ? $errors['category_id'] : ''; ?></span>
                              </div>
                          </div>

                            <input type="hidden" name="product_id" value="<?php echo $productId; ?>" />
                            <div class="row justify-content-end">
                                <div class="col-sm-10">
                                    <input type="submit" name="submit" value="Update" class="btn btn-primary" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content-backdrop fade"></div>
</div>

<script>
 document.addEventListener("DOMContentLoaded", function () {
    const container = document.getElementById("image-upload-container");
    const addImageButton = document.getElementById("add-image-input");

    addImageButton.addEventListener("click", function () {
        const input = document.createElement("input");
        input.type = "file";
        input.className = "form-control";
        input.name = "images[]";
        input.accept = "image/png, image/jpeg";
        container.appendChild(input);
    });

    // container.addEventListener('click', function (e) {
    //     if (e.target.classList.contains('remove-image-button')) {
    //         const imagePreview = e.target.closest('.image-preview');
    //         if (imagePreview) {
    //             const imageReference = e.target.getAttribute('data-image');
    //             removeImage(imageReference);
    //             container.removeChild(imagePreview);
    //         }
    //     }
    // });
    
    // function removeImage(imageReference) {
  
    //     fetch('remove_image.php', {
    //         method: 'POST',
    //         headers: {
    //             'Content-Type': 'application/json'
    //         },
    //         body: JSON.stringify({ imageFilename: imageReference })
    //     }).then(response => response.json()).then(data => {
    //         if (data.success) {
    //             container.removeChild(imagePreview);
    //         } else {
    //             console.error('Error removing the image.');             // Handle the error
    //         }
    //     });
    // }
});   
</script>
