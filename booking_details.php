<?php
session_start();

// Check if session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'connection.php'; // Assuming this file contains your database connection

// Fetch booking details from the database
if (isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];
    $user_id = $_SESSION['user_id'];

    // Check if the connection is established
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prepare the SQL statement
    $details_query = "SELECT bookings.*, cars.model, cars.latitude, cars.longitude, drivers.name AS driver_name
                      FROM bookings
                      JOIN cars ON bookings.car_id = cars.car_id
                      JOIN drivers ON bookings.driver_id = drivers.driver_id
                      WHERE bookings.id = ? AND bookings.user_id = ?";

    $stmt = $conn->prepare($details_query);

    // Check if the prepare() method was successful
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ii", $booking_id, $user_id);
    $stmt->execute();
    $booking_details = $stmt->get_result()->fetch_assoc();

    if (!$booking_details) {
        echo "Booking not found or you don't have permission to view this booking.";
        exit();
    }
} else {
    echo "No booking ID specified.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details</title>
    <link rel="stylesheet" href="booking_details.css">
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&callback=initMap" async defer></script>
    <script>
        function initMap() {
            var carLocation = { lat: <?php echo htmlspecialchars($booking_details['latitude'], ENT_QUOTES); ?>, lng: <?php echo htmlspecialchars($booking_details['longitude'], ENT_QUOTES); ?> };

            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: carLocation
            });

            var marker = new google.maps.Marker({
                position: carLocation,
                map: map
            });
        }
    </script>
</head>
<body onload="initMap()">

<div class="dashboard">
    <h2>Dashboard</h2>
    <a href="home.php">Home</a>
    <a href="booking.php">Booking</a>
    <a href="booking_status.php">Booking Status</a>
    <a href="booking_history.php">Booking History</a>
    <a href="account.php">Account</a>
    <a href="logout.php">Logout</a>
</div>

<div class="container">
    <div class="booking-details-section">
        <h2>Booking Details</h2>
        <button class="back-button" onclick="window.history.back();">Back</button>
        <table class="booking-details-table">
            <tr>
                <th>Booking ID</th>
                <td><?php echo htmlspecialchars($booking_details['id'], ENT_QUOTES); ?></td>
            </tr>
            <tr>
                <th>Car Model</th>
                <td><?php echo htmlspecialchars($booking_details['model'], ENT_QUOTES); ?></td>
            </tr>
            <tr>
                <th>Driver Name</th>
                <td><?php echo htmlspecialchars($booking_details['driver_name'], ENT_QUOTES); ?></td>
            </tr>
            <tr>
                <th>Start Date & Time</th>
                <td><?php echo htmlspecialchars($booking_details['start_datetime'], ENT_QUOTES); ?></td>
            </tr>
            <tr>
                <th>End Date & Time</th>
                <td><?php echo htmlspecialchars($booking_details['end_datetime'], ENT_QUOTES); ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><?php echo htmlspecialchars($booking_details['status'], ENT_QUOTES); ?></td>
            </tr>
            <tr>
                <th>Total Price</th>
                <td><?php echo htmlspecialchars($booking_details['total_price'], ENT_QUOTES); ?></td>
            </tr>
        </table>
        <div id="map" style="height: 400px; width: 100%;"></div>
    </div>  
</div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
