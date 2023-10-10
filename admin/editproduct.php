<?php
ob_start();
include('header.php');
$currentProfileImageUrl = '';

if (isset($_POST['submit'])) {

    if (!empty($_FILES['images']['name'][0])) {
        $uploadDir = '../uploads/';
        $imageReferences = array();

        foreach ($_FILES['images']['name'] as $key => $fileName) {
            $uploadFile = $uploadDir . basename($fileName);

            // Generate a unique file name to prevent overwriting
            $uniqueFileName = uniqid() . '_' . $fileName;
            $uploadPath = $uploadDir . $uniqueFileName;

            // Check if the file upload was successful
            if (move_uploaded_file($_FILES['images']['tmp_name'][$key], $uploadPath)) {
                $imageReferences[] = $uploadPath; // Add the reference to the array
            } else {
                $errors['images'][] = "Error uploading file: " . $_FILES['images']['error'][$key];
            }
        }

        // Convert the array of references to a comma-separated string
        $imageReferencesString = implode(',', $imageReferences);

        // Update the product record in the database with the new images
        $updateQuery = "UPDATE products SET name=?, description=?, price=?, stock=?, category_id=?, image=? WHERE id=?";
        $stmt = mysqli_prepare($conn, $updateQuery);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssdissi", $name, $description, $price, $stock, $category_id, $imageReferencesString, $productId);
            if (mysqli_stmt_execute($stmt)) {
                // Product updated successfully
                $_SESSION['success_message'] = 'Product updated successfully.';
                header('Location: manageproducts.php');
                exit();
            } else {
                $errors['database'] = "Error updating product: " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);
        } else {
            $errors['database'] = "Error preparing the database statement.";
        }
    }
} else {
    // Retrieve the product information for editing
    $productId = $_GET['id']; // Replace with your method of getting the product ID

    $query = "SELECT * FROM products WHERE id = $productId";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $productData = mysqli_fetch_assoc($result);
    } else {
        // Product not found
        echo 'Product not found.';
        exit();
    }
}

// HTML form to edit the product with multiple image inputs
?>
<!-- jQuery library -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- jQuery Validation Plugin -->
<script src="https://cdn.jsdelivr.net/jquery.validation/1.19.3/jquery.validate.min.js"></script>

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
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Product Name" value="<?php echo $productData['name']; ?>" />
                                    errorPlacement: function (error, element) {
            error.insertAfter(element);
        }<span style="color: red;"><?php echo isset($errors['name']) ? $errors['name'] : ''; ?></span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="description">Description</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" id="description" name="description" placeholder="Enter Product Description"><?php echo $productData['description']; ?></textarea>
                                    <span style="color: red;"><?php echo isset($errors['description']) ? $errors['description'] : ''; ?></span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="images">Additional Images</label>
                                <div class="col-sm-10">
                                    <div id="image-upload-container">
                                        <input type="file" class="form-control" name="images[]" accept="image/png, image/jpeg" />
                                    </div>
                                    <button type="button" id="add-image-input" class="btn btn-primary">Add More Images</button>
                                    <button type="button" id="remove-image-input" class="btn btn-danger">Close</button>
                                    <span style="color: red;"><?php echo isset($errors['images']) ? $errors['images'] : ''; ?></span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="price">Price</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="price" name="price" placeholder="Enter Price" value="<?php echo $productData['price']; ?>" />
                                    <span style="color: red;"><?php echo isset($errors['price']) ? $errors['price'] : ''; ?></span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="stock">Stock</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="stock" name="stock" placeholder="Enter Stock Quantity" value="<?php echo $productData['stock']; ?>" />
                                    <span style="color: red;"><?php echo isset($errors['stock']) ? $errors['stock'] : ''; ?></span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="images">Existing Images</label>
                                <div class="col-sm-10">
                                    <?php
                                    // Display existing product images
                                    $existingImageReferences = explode(',', $productData['image']);
                                    foreach ($existingImageReferences as $imageReference) {
                                        echo "<img src='$imageReference' alt='Image' height='50px' width='50px'>";
                                    }
                                    ?>
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
$(document).ready(function () {
    // Initialize the form validation
    $("#formProduct").validate({
        rules: {
            name: {
                required: true,
                maxlength: 255 // You can set the maximum length as needed
            },
            description: {
                required: true,
                maxlength: 1000 // You can set the maximum length as needed
            },
            price: {
                required: true,
                number: true, // Validate as a number
                min: 0.01 // Minimum price value
            },
            stock: {
                required: true,
                digits: true, // Validate as digits (non-negative whole number)
                min: 0 // Minimum stock value
            },
            // Add rules for other fields as needed
        },
        messages: {
            name: {
                required: "Please enter a product name",
                maxlength: "Product name must not exceed 255 characters"
            },
            description: {
                required: "Please enter a product description",
                maxlength: "Product description must not exceed 1000 characters"
            },
            price: {
                required: "Please enter a price",
                number: "Please enter a valid number",
                min: "Price must be greater than or equal to 0.01"
            },
            stock: {
                required: "Please enter stock quantity",
                digits: "Please enter a non-negative whole number",
                min: "Stock quantity must be greater than or equal to 0"
            },
            // Add custom validation messages for other fields as needed
        },
        
    });
});
</script>   
<!-- JavaScript to add more image input fields -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const container = document.getElementById("image-upload-container");
        const addImageButton = document.getElementById("add-image-input");
        const removeImageButton = document.getElementById("remove-image-input");

        addImageButton.addEventListener("click", function () {
            const input = document.createElement("input");
            input.type = "file";
            input.className = "form-control";
            input.name = "images[]";
            input.accept = "image/png, image/jpeg";
            container.appendChild(input);
        });

        removeImageButton.addEventListener("click", function () {
            const inputs = container.querySelectorAll("input[type='file']");
            if (inputs.length > 1) {
                container.removeChild(inputs[inputs.length - 1]);
            }
        });
    });
</script>
