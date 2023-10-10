<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "ecommerce";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// if(isset($_SESSION['user'])){
//     $image = (!empty($user['photo'])) ? 'images/'.$user['photo'] : 'images/profile.jpg';
//     echo '
//       <li class="dropdown user user-menu">
//         <a href="#" class="dropdown-toggle" data-toggle="dropdown">
//           <img src="'.$image.'" class="user-image" alt="User Image">
//           <span class="hidden-xs">'.$user['firstname'].' '.$user['lastname'].'</span>
//         </a>
//         <ul class="dropdown-menu">
//           <!-- User image -->
//           <li class="user-header">
//             <img src="'.$image.'" class="img-circle" alt="User Image">

//             <p>
//               '.$user['firstname'].' '.$user['lastname'].'
//               <small>Member since '.date('M. Y', strtotime($user['created_on'])).'</small>
//             </p>
//           </li>
//           <li class="user-footer">
//             <div class="pull-left">
//               <a href="profile.php" class="btn btn-default btn-flat">Profile</a>
//             </div>
//             <div class="pull-right">
//               <a href="logout.php" class="btn btn-default btn-flat">Sign out</a>
//             </div>
//           </li>
//         </ul>
//       </li>
//     ';
//   }

// for ($i = 1; $i <= 25; $i++) {
//   $name = "Product " . $i;
//   $description = "Description for Product " . $i;
//   $price = rand(10, 100); // Random price between 10 and 100
//   $stock = rand(0, 100); // Random stock quantity between 0 and 100
//   $category_id = 1; // Replace with the actual category ID you want to use

//   // Check if the category_id exists in the categories table
//   $categoryCheckQuery = "SELECT COUNT(*) FROM categories WHERE id = $category_id";
//   $categoryCheckResult = mysqli_query($conn, $categoryCheckQuery);
//   $categoryExists = mysqli_fetch_array($categoryCheckResult)[0];

//   if ($categoryExists) {
//       // The category_id exists, so you can proceed with the product insertion
//       $sql = "INSERT INTO products (name, description, price, stock, category_id)
//               VALUES ('$name', '$description', $price, $stock, $category_id)";

//       if (mysqli_query($conn, $sql)) {
//           echo "Record $i inserted successfully<br>";
//       } else {
//           echo "Error inserting record: " . mysqli_error($conn) . "<br>";
//       }
//   } else {
//       echo "Category with ID $category_id does not exist.<br>";
//   }
// }
// $updateQuery = "UPDATE products SET image = 'default.jpg'";
// if (mysqli_query($conn, $updateQuery)) {
//     echo "Image updated to default.jpg for all products successfully.";
// } else {
//     echo "Error updating image: " . mysqli_error($conn);
// }
// Close the database connection
?>
