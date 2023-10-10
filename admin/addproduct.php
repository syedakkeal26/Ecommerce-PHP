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

    // Check if an image file was uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        $uploadFile = $uploadDir . basename($_FILES['image']['name']);
    
        // Generate a unique file name to prevent overwriting
        $uniqueFileName = uniqid() . '_' . $_FILES['image']['name'];
        $uploadPath = $uploadDir . $uniqueFileName;
    
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
            // Update the image_path variable with the uploaded file path
            $image_path = $uploadPath;
        } else {
            $errors['image'] = "Error uploading file.";
        }
    } else {
        $errors['image'] = "No file uploaded or an error occurred during upload.";
    }
    
    if (empty($errors)) {
        // Use prepared statements to prevent SQL injection
        $insert_query = "INSERT INTO products (name, description, price, stock, category_id, image)
                         VALUES (?, ?, ?, ?, ?, ?)";
    
        $stmt = mysqli_prepare($conn, $insert_query);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssdiss", $name, $description, $price, $stock, $category_id, $image_path);
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
                                    <input type="file" class="form-control" id="image" name="image" value="<?php echo (isset($_SESSION['image'])) ? $_SESSION['image'] : ''; ?>" accept="image/png, image/jpeg" />
                                    <span style="color: red;"><?php echo isset($errors['image']) ? $errors['image'] : ''; ?></span>
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
