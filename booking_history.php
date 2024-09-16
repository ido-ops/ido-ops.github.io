<?php
session_start();

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'connection.php'; // Include your database connection file

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Check if the connection is established
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Prepare the SQL statement to exclude pending bookings
$history_query = "SELECT bookings.*, cars.model, drivers.name AS driver_name
                   FROM bookings
                   JOIN cars ON bookings.car_id = cars.car_id
                   LEFT JOIN drivers ON bookings.driver_id = drivers.driver_id
                   WHERE bookings.user_id = ? AND bookings.status <> 'pending'";

$stmt = $conn->prepare($history_query);

if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $user_id);

if ($stmt->execute() === false) {
    die("Execute failed: " . $stmt->error);
}

$history_result = $stmt->get_result();

if ($history_result === false) {
    die("Get result failed: " . $stmt->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking History</title>
    <link rel="stylesheet" href="booking_history.css">
</head>
<body>

<div class="dashboard">
    <h2>Dashboard</h2>
    <a href="home.php">Home</a>
    <a href="booking.php">Booking</a>
    <a href="booking_status.php">Booking Status</a>
    <a href="booking_history.php">Booking History</a>
    <a href="account.php">Account</a>
    <a href="logout.php">Logout</a>
</div>

<div class="booking-history-container">
    <h2>Booking History</h2>
    <?php if ($history_result->num_rows > 0): ?>
        <table class="booking-history-table">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Car Model</th>
                    <th>Driver Name</th>
                    <th>Start Date & Time</th>
                    <th>End Date & Time</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($booking = $history_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($booking['id'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlspecialchars($booking['model'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlspecialchars($booking['driver_name'] ?? 'No Driver', ENT_QUOTES); ?></td>
                        <td><?php echo htmlspecialchars($booking['start_datetime'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlspecialchars($booking['end_datetime'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlspecialchars($booking['status'], ENT_QUOTES); ?></td>
                        <td><a href="booking_details.php?booking_id=<?php echo htmlspecialchars($booking['id'], ENT_QUOTES); ?>" class="view-button">View</a></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You have no completed bookings at the moment.</p>
    <?php endif; ?>
</div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
