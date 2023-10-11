<?php
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $data = json_decode(file_get_contents('php://input'), true);
    $imageFilename = $data['imageFilename'];

    // Get the product ID (modify this based on your setup)
    $productId = intval($_GET['id']);

    // Retrieve the current product images from the database
    $query = "SELECT image FROM products WHERE id = $productId";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $productData = mysqli_fetch_assoc($result);
        $currentImages = explode(',', $productData['image']);

        // Remove the specified image filename
        $updatedImages = array_diff($currentImages, [$imageFilename]);

        // Convert the updated images back to a comma-separated string
        $updatedImagesString = implode(',', $updatedImages);

        // Update the product's images in the database
        $updateQuery = "UPDATE products SET image = '$updatedImagesString' WHERE id = $productId";
        $updateResult = mysqli_query($conn, $updateQuery);

        if ($updateResult) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    } else {
        echo json_encode(['success' => false]);
    }

    // Close the database connection
    mysqli_close($conn);
} else {
    // Handle invalid requests
    http_response_code(400);
    echo 'Bad Request';
}
?>