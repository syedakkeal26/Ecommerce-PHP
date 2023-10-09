<?php

include('header.php');
$id= $_REQUEST['id'];
$res=mysqli_query($conn,"SELECT * from users WHERE id=$id limit 1");
    if($row=mysqli_fetch_array($res))
    {
    $username=$row['username'];
    $email=$row['email'];
    $mobile=$row['mobile'];
    $type = $row['type'];
    $status = $row['status'];

    $_SESSION['username'] = $username ;
    $_SESSION['email'] = $email ;
    $_SESSION['mobile'] = $mobile ;
    $_SESSION['type'] = $type ;
    $_SESSION['status'] = $status ;
    }
    else
    {
    $result=mysqli_query($conn,"UPDATE users SET name='$Name', status='$Status' WHERE id=$id");
    }


if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mobile = $_POST['mobile'];
    $type = $_POST['type'];
    $status = $_POST['status'];

    $errors = array(); // Use an associative array to store field-specific errors

    // Validation checks for each field
    if (empty($username)) {
        $errors['username'] = "Username is required";
    }

    if (empty($email)) {
        $errors['email'] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }

    if (empty($mobile)) {
        $errors['mobile'] = "Mobile number is required";
    }
    // elseif (!preg_match("/^[0-9]{10}$/", $mobile)) {
    //     $errors['mobile'] = "Invalid mobile number format.";
    // }


    // Check if the email already exists in the database
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $errors['email'] = 'Email already exists!';
    }

    // If no errors, proceed with registration
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $insert_query = "INSERT INTO users (username, email, password, mobile,type,status) VALUES ('$username', '$email', '$hashed_password', '$mobile','$type','$status')";
        $insert_result = mysqli_query($conn, $insert_query);

        if ($insert_result) {
        $_SESSION['success_message'] = 'Edited successfully.';
        header('Location: manageroles.php');
        exit();
        }
    }
}
?>

<div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="py-3 mb-4"><span class="text-muted fw-light">Roles / <a href="manageroles.php">Manage Roles</a> /</span> Edit Role</h4>

              <!-- Basic Layout & Basic with Icons -->
              <div class="row">
                <!-- Basic Layout -->
                <div class="col-xxl">
                  <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                      <h5 class="mb-0">Add Role</h5>
                    </div>
                    <div class="card-body">
                      <form id="formAuthentication" class="mb-3" action="" method="POST">
                        <div class="row mb-3">
                          <label class="col-sm-2 col-form-label" for="basic-default-name">Username</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" id="username" name="username" placeholder="Enter Name" value="<?php echo (isset($_SESSION['username'])) ? $_SESSION['username'] : '' ?>"/>
                            <span style="color: red;"><?php echo isset($errors['username']) ? $errors['username'] : ''; ?></span>
                          </div>
                        </div>

                        <div class="row mb-3">
                          <label class="col-sm-2 col-form-label" for="basic-default-email">Email</label>
                          <div class="col-sm-10">
                            <div class="input-group input-group-merge">
                              <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control"
                                placeholder="Enter Email"
                                aria-label="john.doe"
                                aria-describedby="basic-default-email2" value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>" />
                              </div>
                              <span style="color: red;"><?php echo isset($errors['email']) ? $errors['email'] : ''; ?></span>
                            <div class="form-text"></div>
                          </div>
                        </div>
                        <div class="row mb-3">
                          <label class="col-sm-2 col-form-label" for="basic-default-phone">Mobile</label>
                          <div class="col-sm-10">
                            <input
                              type="number"
                              id="mobile"
                              name="mobile"
                              class="form-control phone-mask"
                              placeholder="12345 67890 "
                              aria-describedby="basic-default-phone" value="<?php echo (isset($_SESSION['mobile'])) ? $_SESSION['mobile'] : '' ?>"/>
                              <span style="color: red;"><?php echo isset($errors['mobile']) ? $errors['mobile'] : ''; ?></span>
                          </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="user-type">User Type</label>
                            <div class="col-sm-10">
                                <select id="type" name="type" class="form-select" aria-describedby="user-type-help">
                                  <option value="0" <?php echo (isset($_SESSION['type'])) ? 'selected' : ''; ?>>User</option>
                                  <option value="1" <?php echo (isset($_SESSION['type'])) ? 'selected' : ''; ?>>Admin</option>
                                </select>
                                <span id="user-type-help" class="form-text" style="color: red;"><?php echo isset($errors['type']) ? $errors['type'] : ''; ?></span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="user-type">Status</label>
                            <div class="col-sm-10">
                                <select id="status" name="status" class="form-select" aria-describedby="user-type-help">
                                  <option value="1" <?php echo (isset($_SESSION['status']) && $_SESSION['status'] === 'Active') ? 'selected' : ''; ?>>Active</option>
                                  <option value="0" <?php echo (isset($_SESSION['status']) && $_SESSION['status'] === 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                                <span id="user-type-help" class="form-text" style="color: red;"><?php echo isset($errors['status']) ? $errors['status'] : ''; ?></span>
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
