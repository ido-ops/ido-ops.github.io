<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include 'connection.php';

// Handle booking submission
$booking_success = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date_of_booking = $_POST['date_of_booking'];
    $car_selection = $_POST['car_selection'];
    $driver_selection = $_POST['driver_selection'];

    $stmt = $conn->prepare("INSERT INTO bookings (user_id, date_of_booking, car_selection, driver_selection) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $_SESSION['user_id'], $date_of_booking, $car_selection, $driver_selection);

    if ($stmt->execute()) {
        $booking_success = "Booking successfully submitted!";
    } else {
        $booking_success = "Error submitting booking: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
include 'booking.php'; // Include the HTML view
?>
