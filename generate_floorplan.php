<?php
// Sample floor data (replace this with your actual data)
$floorSize = 500; // Adjust as needed
$floor = [
    (object) [
        'room_type' => 'Bedroom',
        'size' => 100,
        'cost_type' => 'PAID',
    ],
    (object) [
        'room_type' => 'Kitchen',
        'size' => 150,
        'cost_type' => 'FREE',
    ],
];

// Create an image with white background
$floorPlan = imagecreatetruecolor($floorSize, $floorSize);
$white = imagecolorallocate($floorPlan, 255, 255, 255);
imagefill($floorPlan, 0, 0, $white);

// Define room colors
$roomColors = [
    'PAID' => imagecolorallocate($floorPlan, 255, 0, 0), // Red for paid rooms
    'FREE' => imagecolorallocate($floorPlan, 0, 255, 0), // Green for free rooms
];

// Loop through the rooms and draw rectangles
foreach ($floor as $room) {
    $roomColor = $roomColors[$room->cost_type];
    $roomSize = $room->size;
    $x = 10; // Adjust as needed
    $y = 10; // Adjust as needed

    // Draw the room rectangle
    imagefilledrectangle($floorPlan, $x, $y, $x + $roomSize, $y + $roomSize, $roomColor);

    // Add room type text
    imagettftext($floorPlan, 12, 0, $x, $y - 5, $white, 'arial.ttf', $room->room_type);
}

// Output the image (you can save it to a file or send it to the browser)
header('Content-Type: image/png');
imagepng($floorPlan);
imagedestroy($floorPlan);
?>
