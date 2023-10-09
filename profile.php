<?php
ob_start();
include('header.php');
if(!isset($_SESSION['user'])){
  header('location: login.php');
}

$username = '';
$email = '';
$mobile = '';
$currentProfileImageUrl = '';

if (isset($_SESSION['user'])) {
    $id = $_SESSION['user'];

    // Retrieve user data based on the ID
    $res = mysqli_query($conn, "SELECT * FROM users WHERE id = $id LIMIT 1");

    if ($row = mysqli_fetch_array($res)) {
      $username = $row['username'];
      $email = $row['email'];
      $mobile = $row['mobile'];
      $currentProfileImageUrl = $row['profile_image_url'];

      $_SESSION['username'] = $username;
      $_SESSION['email'] = $email;
      $_SESSION['mobile'] = $mobile;
      $_SESSION['profile_image_url'] = $currentProfileImageUrl;
    } else {
        echo "User not found.";
    }
}

$errors = array();

      if (isset($_POST['image_submit'])) {
      
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
          $uploadDir = 'images/';
          $uploadFile = $uploadDir . basename($_FILES['image']['name']);

          // Generate a unique file name to prevent overwriting
          $uniqueFileName = uniqid() . '_' . $_FILES['image']['name'];
          $uploadPath = $uploadDir . $uniqueFileName;

          if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
              $updateQuery = "UPDATE users SET profile_image_url = '$uploadPath' WHERE id = $id";
              if (mysqli_query($conn, $updateQuery)) {
                  $currentProfileImageUrl = $uploadPath;
              } else {
                  echo "Error updating profile image: " . mysqli_error($conn);
              }
          } else {
              $errors['file'] = "Error uploading file.";
          }
      } else {
        $errors['file'] = "No file uploaded or an error occurred during upload.";
      }
      }
      if (isset($_POST['submit'])) {
        $newUsername = mysqli_real_escape_string($conn, $_POST['username']);
        $newEmail = mysqli_real_escape_string($conn, $_POST['email']);
        $newMobile = $_POST['mobile'];


        if (empty($newUsername)) {
            $errors['username'] = "Username is required";
        }

        if (empty($newEmail)) {
            $errors['email'] = "Email is required";
        } elseif (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Invalid email format.";
        }

        if (empty($newMobile)) {
            $errors['mobile'] = "Mobile number is required";
        }

        $emailCheckResult = mysqli_query($conn, "SELECT id FROM users WHERE email = '$newEmail' AND id != $id");

        if (mysqli_num_rows($emailCheckResult) > 0) {
            $errors['email'] = 'Email already exists for another user.';
        }

        // If there are no errors, update the user's information
        if (empty($errors)) {
            $result = mysqli_query($conn, "UPDATE users SET username='$newUsername', email='$newEmail', mobile='$newMobile' WHERE id=$id");

            if ($result) {
                $_SESSION['username'] = $newUsername;
                $_SESSION['email'] = $newEmail;
                $_SESSION['mobile'] = $newMobile;
                
                $_SESSION['success_message'] = 'Profile Edited successfully.';
                header('Location: profile.php');
                exit();
            } else {
                echo "Error: " . mysqli_error($conn);
                // Handle the case where the update query fails
            }
        }
      }
?>

      <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
                <h4 class="py-3 mb-4"><span class="text-muted fw-light"><a href="index.php">Home</a> / </span> Profile Details</h4>
                <?php
                if (isset($_SESSION['success_message']) && !empty($_SESSION['success_message'])) {
                    $success_message = $_SESSION['success_message'];
                    unset($_SESSION['success_message']);
                    echo '<div class="alert alert-success">' . $success_message . '</div>';
                }
                ?>
                <div class="row">
                  <div class="col-md-12"> 
                    <div class="card mb-4">
                      <h5 class="card-header">Profile Details</h5>
                      <div class="card-body">
                      <div class="d-flex align-items-start align-items-sm-center gap-4">
                            <img
                              src="<?php echo $currentProfileImageUrl; ?>"
                                alt="user-avatar"
                                class="d-block rounded"
                                height="100"
                                width="100"
                                id="uploadedAvatar"
                            />
                            <div class="button-wrapper">
                                <form method="POST" enctype="multipart/form-data">
                                    <label for="image-upload"  tabindex="0">
                                          
                                        <input
                                            type="file"
                                            id="image-upload"
                                            name="image"  
                                            class="account-file-input"
                                            accept="image/png, image/jpeg"
                                        />
                                      </label>
                                      <button type="submit" name="image_submit" class="btn btn-outline-secondary ">
                                        <span class="d-none d-sm-block">Upload</span>
                                      </button>
                                    </form>
                                    <span style="color: red;"><?php echo isset($errors['file']) ? $errors['file'] : ''; ?></span>
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
                            <button type="reset" class="btn btn-outline-secondary">Cancel</button>
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