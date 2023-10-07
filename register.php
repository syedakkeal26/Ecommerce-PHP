<?php

include('config.php');

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $mobile = $_POST['mobile'];

    $_SESSION['username'] = $username ;
    $_SESSION['email'] = $email ;
    $_SESSION['mobile'] = $mobile ;

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
    } elseif (!preg_match("/^[0-9]{10}$/", $mobile)) {
        $errors['mobile'] = "Invalid mobile number format.";
    }

    if (empty($password)) {
        $errors['password'] = "Password is required";
    }

    if ($password != $cpassword) {
        $errors['cpassword'] = 'Password not matched!';
    }

    // Check if the email already exists in the database
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $errors['email'] = 'Email already exists!';
    }

    // If no errors, proceed with registration
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $insert_query = "INSERT INTO users (username, email, password, mobile) VALUES ('$username', '$email', '$hashed_password', '$mobile')";
        $insert_result = mysqli_query($conn, $insert_query);

        if ($insert_result) {
            // Registration successful, redirect to login page
            header('location: login.php');
            exit();
        } else {
            $errors['database'] = 'Registration failed. Please try again later.';
        }
    }
}
?>


<!DOCTYPE html>
<html
  lang="en"
  class="light-style layout-wide customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="assets/"
  data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Register Page</title>


    <meta name="description" content="" />

    <!-- Favicon -->
    <!-- <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" /> -->

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet" />

    <link rel="stylesheet" href="assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="assets/vendor/css/pages/page-auth.css" />

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
    <script src="assets/js/config.js"></script>
  </head>

  <body>
    <!-- Content -->

    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Register Card -->
          <div class="card">
            <div class="card-body">
              <div class="app-brand justify-content-center" >
                <h4 class="mb-2">Register</h4>
              </div>
              <form id="formAuthentication" class="mb-3"  method="POST">
                <div class="mb-3">
                  <label for="username" class="form-label">Username</label>
                  <input type="text" class="form-control" id="username" name="username"  placeholder="Enter your username" value="<?php echo (isset($_SESSION['username'])) ? $_SESSION['username'] : '' ?>" autofocus />
                  <span style="color: red;"><?php echo isset($errors['username']) ? $errors['username'] : ''; ?></span>
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="text" class="form-control" id="email" name="email"  placeholder="Enter your email" value="<?php echo (isset($_SESSION['email'])) ? $_SESSION['email'] : '' ?>"/>        
                  <span style="color: red;"><?php echo isset($errors['email']) ? $errors['email'] : ''; ?></span>
                </div>
                <div class="mb-3">
                  <label for="mobile" class="form-label">Mobile Number</label>
                  <input type="number" class="form-control" id="mobile" name="mobile"  placeholder="Enter your mobile number" value="<?php echo (isset($_SESSION['mobile'])) ? $_SESSION['mobile'] : '' ?>" />
                  <span style="color: red;"><?php echo isset($errors['mobile']) ? $errors['mobile'] : ''; ?></span>
                </div>
                <div class="mb-3 form-password-toggle">
                  <label class="form-label" for="password">Password</label>
                  <div class="input-group input-group-merge">
                    <input type="password" id="password" class="form-control" name="password"  placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  </div>
                  <span style="color: red;"><?php echo isset($errors['password']) ? $errors['password'] : ''; ?></span>
                </div>
                <div class="mb-3 form-password-toggle">
                  <label class="form-label" for="password">Confirm Password</label>
                  <div class="input-group input-group-merge">
                  <input type="password" id="cpassword" class="form-control" name="cpassword"  placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  </div>
                  <span style="color: red;"><?php echo isset($errors['cpassword']) ? $errors['cpassword'] : ''; ?></span>
                </div>
                <input type="submit" name="submit" value="Register" class="btn btn-primary d-grid w-100">
              </form>
              <p class="text-center">
                <span>Already have an account?</span>
                <a href="login.php">
                  <span>Sign in</span>
                </a>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
