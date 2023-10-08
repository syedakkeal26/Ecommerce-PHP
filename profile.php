<?php

include('header.php');
if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mobile = $_POST['mobile'];
    
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

    if (empty($password)) {
        $errors['password'] = "Password is required";
    } 
  //   elseif (strlen($password) < 8 || !preg_match("/[!@#\$%^&*()\-_=+{};:,<.>]/", $password)) {
  //     $errors['password'] = "Password must be at least 8 characters long and contain at least one special character.";
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
        $insert_query = "INSERT INTO users (username, email, password, mobile,type) VALUES ('$username', '$email', '$hashed_password', '$mobile','$type')";
        $insert_result = mysqli_query($conn, $insert_query);

        if ($insert_result) {
        unset($_SESSION['username']);
        unset($_SESSION['email']);
        unset($_SESSION['mobile']);
        unset($_SESSION['type']);
        $_SESSION['success_message'] = 'New Role Created successfully.';
        header('Location: manageroles.php');
        exit();
        } 
    }
}
?>
      <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
                <h4 class="text-center"><span class="text-muted fw-light"></span> Profile Details</h4>

                <div class="row">
                  <div class="col-md-12"> 
                    <div class="card mb-4">
                      <h5 class="card-header"></h5>
                      <div class="card-body">
                        <div class="d-flex align-items-start align-items-sm-center gap-4">
                          <img
                            src="assets/img/avatars/1.png"
                            alt="user-avatar"
                            class="d-block rounded"
                            height="100"
                            width="100"
                            id="uploadedAvatar" />
                          <div class="button-wrapper">
                            <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                              <span class="d-none d-sm-block">Upload new photo</span>
                              <i class="bx bx-upload d-block d-sm-none"></i>
                              <input
                                type="file"
                                id="upload"
                                class="account-file-input"
                                hidden
                                accept="image/png, image/jpeg" />
                            </label>
                            <button type="button" class="btn btn-outline-secondary account-image-reset mb-4">
                              <i class="bx bx-reset d-block d-sm-none"></i>
                              <span class="d-none d-sm-block">Reset</span>
                            </button>

                            <p class="text-muted mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                          </div>
                        </div>
                      </div>
                      <hr class="my-0" />
                    <div class="card-body">
                      <form id="formAuthentication" class="mb-3" action="profile.php" method="POST">
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
                        <div class="row">
                        <div class="mb-3 col-md-6">
                              <label for="address" class="form-label">Address</label>
                              <input type="text" class="form-control" id="address" name="address" placeholder="Address" />
                            </div>
                            <div class="mb-3 col-md-6">
                              <label for="zipCode" class="form-label">Pin Code</label>
                              <input
                                type="number"
                                class="form-control"
                                id="zipCode"
                                name="zipCode"
                                placeholder="231465"
                                maxlength="6" />
                            </div>
                        <div class="mb-3 col-md-6">
                              <label class="form-label" for="country">Country</label>
                              <select id="country" class="select2 form-select">
                                <option value="">Select</option>
                                <option value="Australia">Australia</option>
                                <option value="Bangladesh">Bangladesh</option>
                                <option value="Belarus">Belarus</option>
                                <option value="Brazil">Brazil</option>
                                <option value="Canada">Canada</option>
                                <option value="China">China</option>
                                <option value="France">France</option>
                                <option value="Germany">Germany</option>
                                <option value="India">India</option>
                                <option value="Indonesia">Indonesia</option>
                                <option value="Israel">Israel</option>
                                <option value="Italy">Italy</option>
                                <option value="Japan">Japan</option>
                                <option value="Korea">Korea, Republic of</option>
                                <option value="Mexico">Mexico</option>
                                <option value="Philippines">Philippines</option>
                                <option value="Russia">Russian Federation</option>
                                <option value="South Africa">South Africa</option>
                                <option value="Thailand">Thailand</option>
                                <option value="Turkey">Turkey</option>
                                <option value="Ukraine">Ukraine</option>
                                <option value="United Arab Emirates">United Arab Emirates</option>
                                <option value="United Kingdom">United Kingdom</option>
                                <option value="United States">United States</option>
                              </select>
                            </div>
                            <div class="mb-3 col-md-6">
                              <label for="language" class="form-label">Language</label>
                              <select id="language" class="select2 form-select">
                                <option value="">Select Language</option>
                                <option value="en">English</option>
                                <option value="fr">French</option>
                                <option value="de">German</option>
                                <option value="pt">Portuguese</option>
                              </select>
                            </div>
                        </div>
                        <div class="row justify-content-end">
                          <div class="col-sm-10">
                          <div class="mt-2">
                            <button type="submit" name="submit" class="btn btn-primary me-2">Save changes</button>
                            <a href="index.php">
                              <button type="reset" class="btn btn-outline-secondary">Cancel</button>
                            </a>
                          </div>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
        <div class="content-backdrop fade"></div>
      </div>