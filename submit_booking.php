<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'connection.php'; // Ensure this file contains your database connection

// Get form data
$user_id = $_SESSION['user_id'];
$car_id = intval($_POST['car-id']);
$driver_id = intval($_POST['driver-id']);
$start_date = $_POST['start-date'] . ' ' . $_POST['start-time'];
$end_date = $_POST['end-date'] . ' ' . $_POST['end-time'];
$total_price = floatval($_POST['total-price']); // Get the total price from the form

// Insert booking into the database
$insert_booking_query = "INSERT INTO bookings (user_id, car_id, driver_id, start_datetime, end_datetime, status, total_price) VALUES (?, ?, ?, ?, ?, 'pending', ?)";
$stmt = $conn->prepare($insert_booking_query);

if ($stmt) {
    $stmt->bind_param("iiissd", $user_id, $car_id, $driver_id, $start_date, $end_date, $total_price);
    $stmt->execute();
    $stmt->close();

    // Reduce the car stock by 1 after the booking
    $update_car_stock_query = "UPDATE cars SET stock = stock - 1 WHERE car_id = ?";
    $update_stmt = $conn->prepare($update_car_stock_query);

    if ($update_stmt) {
        $update_stmt->bind_param("i", $car_id);
        $update_stmt->execute();
        $update_stmt->close();
    }

    // Redirect to the booking status page with success message
    header("Location: booking_status.php?status=success");
    exit();
} else {
    die("Database error: " . $conn->error);
}

$conn->close();
?>
