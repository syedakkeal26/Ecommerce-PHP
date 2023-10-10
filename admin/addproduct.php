<?php
ob_start();
include('header.php');
$currentProfileImageUrl = '';

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category_id = $_POST['category_id'];

    $_SESSION['name'] = $name;
    $_SESSION['description'] = $description;
    $_SESSION['price'] = $price;
    $_SESSION['stock'] = $stock;
    $_SESSION['category_id'] = $category_id;

    $errors = array();

    // Validation checks for each field
    if (empty($name)) {
        $errors['name'] = "Product name is required";
    }

    if (empty($description)) {
        $errors['description'] = "Description is required";
    }

    if (empty($price)) {
        $errors['price'] = "Price is required";
    } elseif (!is_numeric($price) || $price <= 0) {
        $errors['price'] = "Invalid price format";
    }

    if (empty($stock)) {
        $errors['stock'] = "Stock quantity is required";
    } elseif (!is_numeric($stock) || $stock < 0) {
        $errors['stock'] = "Invalid stock quantity format";
    }

    if (empty($category_id)) {
        $errors['category_id'] = "Category is required";
    }

    // Check if any files were uploaded
    if (!empty($_FILES['images']['name'][0])) {
        $uploadDir = '../uploads/';
        $imageReferences = array();

        // Iterate through each uploaded file
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

        // Insert the image references into the database
        $insertQuery = "INSERT INTO products (name, description, price, stock, category_id, image)
                         VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($conn, $insertQuery);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssdiss", $name, $description, $price, $stock, $category_id, $imageReferencesString);
            if (mysqli_stmt_execute($stmt)) {
                unset($_SESSION['name']);
                unset($_SESSION['description']);
                unset($_SESSION['price']);
                unset($_SESSION['stock']);
                unset($_SESSION['category_id']);
    
                $_SESSION['success_message'] = 'Product added successfully.';
                header('Location: manageproducts.php');
                exit();
            } else {
                $errors['database'] = "Error adding product to the database: " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);
        } else {
            $errors['database'] = "Error preparing the database statement.";
        }
    }
}
?>

<!-- Add Product Form -->
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Products / <a href="manageproducts.php">Manage Products</a> /</span> Add Product</h4>
        <div class="row">
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Add Product</h5>
                    </div>
                    <div class="card-body">
                    <form id="formProduct" class="mb-3" action="" method="POST" enctype="multipart/form-data">
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="name">Product Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Product Name" value="<?php echo (isset($_SESSION['name'])) ? $_SESSION['name'] : ''; ?>" />
                                    <span style="color: red;"><?php echo isset($errors['name']) ? $errors['name'] : ''; ?></span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="images">Product Images</label>
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
                                <label class="col-sm-2 col-form-label" for="description">Description</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" id="description" name="description" placeholder="Enter Product Description"><?php echo (isset($_SESSION['description'])) ? $_SESSION['description'] : ''; ?></textarea>
                                    <span style="color: red;"><?php echo isset($errors['description']) ? $errors['description'] : ''; ?></span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="price">Price</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="price" name="price" placeholder="Enter Price" value="<?php echo (isset($_SESSION['price'])) ? $_SESSION['price'] : ''; ?>" />
                                    <span style="color: red;"><?php echo isset($errors['price']) ? $errors['price'] : ''; ?></span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="stock">Stock</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="stock" name="stock" placeholder="Enter Stock Quantity" value="<?php echo (isset($_SESSION['stock'])) ? $_SESSION['stock'] : ''; ?>" />
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
                                        echo "<option value='$categoryId'>$categoryName</option>";
                                    }
                                } else {
                                    echo "<option value=''>Error fetching categories</option>";
                                }
                                ?>

                            </select>
                            <span style="color: red;"><?php echo isset($errors['category_id']) ? $errors['category_id'] : ''; ?></span>
                                </div>
                            </div>
                            <div class="row justify-content-end">
                                <div class="col-sm-10">
                                    <input type="submit" name="submit" value="ADD" class="btn btn-primary" />
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