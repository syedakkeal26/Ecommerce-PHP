<?php
ob_start();

include('header.php');


if (isset($_POST['submit'])) {
  $category_name = mysqli_real_escape_string($conn, $_POST['name']);

  $_SESSION['category'] = $category_name ;

  $errors = array();

    if (empty($category_name)) {
        $errors['name'] = "Category name is required";
    }

    if (empty($errors)) {
      $stmt = mysqli_prepare($conn, "INSERT INTO categories (name) VALUES (?)");
      mysqli_stmt_bind_param($stmt, "s", $category_name);

      if (mysqli_stmt_execute($stmt)) {
          $_SESSION['success_message'] = 'Category added successfully.';
          unset($_SESSION['category']);
          header('Location: managecategories.php');
          exit();
      } else {
          echo "Error: " . mysqli_error($conn);
          // Handle the case where the insert query fails
      }

      mysqli_stmt_close($stmt);
  }
}
?>
<div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="py-3 mb-4"><span class="text-muted fw-light">Category / <a href="managecategories.php">Manage Category</a> /</span> Add Category</h4>

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
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Category Name" value="<?php echo (isset($_SESSION['category'])) ? $_SESSION['category'] : '' ?>"/>
                            <span style="color: red;"><?php echo isset($errors['name']) ? $errors['name'] : ''; ?></span>
                          </div>
                        </div>

                        <div class="row justify-content-end">
                          <div class="col-sm-10">
                          <input type="submit" name="submit" value="Submit" class="btn btn-primary ">
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
