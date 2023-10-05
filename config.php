<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "ecommerce";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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

// Close the database connection
// $conn->close();
?>
