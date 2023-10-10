<?php
ob_start();
include('header.php');

$category_name = '';

if (isset($_REQUEST['id'])) {
    $id = $_REQUEST['id'];

    // Retrieve user data based on the ID
    $res = mysqli_query($conn, "SELECT * FROM categories WHERE id = $id LIMIT 1");

    if ($row = mysqli_fetch_array($res)) {
        $category_name = $row['name'];

        $_SESSION['categoryname'] = $category_name;

    } else {
        echo "User not found.";
    }
} else {
    echo "No user ID provided.";
}

$errors = array();

if (isset($_POST['submit'])) {
    $newcategory_name = mysqli_real_escape_string($conn, $_POST['name']);

    if (empty($newcategory_name)) {
        $errors['name'] = "Name is required";
    }

    if (empty($errors)) {
        $result = mysqli_query($conn, "UPDATE categories SET name='$newcategory_name' WHERE id=$id");

        if ($result) {
            $_SESSION['categoryname'] = $newcategory_name;

            $_SESSION['success_message'] = 'Category Edited successfully.';
            header('Location: managecategories.php');
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
            // Handle the case where the update query fails
        }
    }
}
?>
<div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="py-3 mb-4"><span class="text-muted fw-light">Category / <a href="managecategories.php">Manage Roles</a> /</span> Edit Category</h4>

              <!-- Basic Layout & Basic with Icons -->
              <div class="row">
                <!-- Basic Layout -->
                <div class="col-xxl">
                  <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                      <h5 class="mb-0">Add Category</h5>
                    </div>
                    <div class="card-body">
                      <form id="formAuthentication" class="mb-3" action="" method="POST">
                        <div class="row mb-3">
                          <label class="col-sm-2 col-form-label" for="basic-default-name">Category Name</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Category Name" value="<?php echo (isset($_SESSION['categoryname'])) ? $_SESSION['categoryname'] : '' ?>"/>
                            <span style="color: red;"><?php echo isset($errors['name']) ? $errors['name'] : ''; ?></span>
                          </div>
                        </div>

                        <div class="row justify-content-end">
                          <div class="col-sm-10">
                          <input type="submit" name="submit" value="Update" class="btn btn-primary ">
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- / Content -->



            <div class="content-backdrop fade"></div>
          </div>
