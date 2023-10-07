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

// for ($i = 1; $i <= 50; $i++) {
//     $name = "Product " . $i;
//     $description = "Description for Product " . $i;
//     $price = rand(10, 100); // Random price between 10 and 100
//     $stock = rand(0, 100); // Random stock quantity between 0 and 100
//     $category = "Category " . rand(1, 5); // Random category
//     $image_url = "image" . $i . ".jpg"; // Assuming image filenames follow a pattern

//     // SQL query to insert a product record
//     $sql = "INSERT INTO products (name, description, price, stock, category, image)
//             VALUES ('$name', '$description', $price, $stock, '$category', '$image_url')";

//     // Execute the SQL query
//     if ($conn->query($sql) === TRUE) {
//         echo "Record $i inserted successfully<br>";
//     } else {
//         echo "Error inserting record: " . $conn->error . "<br>";
//     }
// }

// // Close the database connection
// $conn->close();
?>
