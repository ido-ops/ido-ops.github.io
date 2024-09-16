<?php
// Database connection
include 'connection.php'; // Assuming connection.php contains your MySQL connection

// Get the raw POST data
$data = json_decode(file_get_contents('php://input'), true);

// Extract latitude, longitude, and car_id (or user_id)
$latitude = $data['latitude'];
$longitude = $data['longitude'];
$car_id = $data['car_id'];

// Insert the data into the database
$query = "INSERT INTO car_locations (car_id, latitude, longitude, timestamp) VALUES (?, ?, ?, NOW())";
$stmt = $conn->prepare($query);
$stmt->bind_param('idd', $car_id, $latitude, $longitude);

if ($stmt->execute()) {
    echo 'Location saved successfully.';
} else {
    echo 'Failed to save location.';
}

$stmt->close();
$conn->close();
?>
