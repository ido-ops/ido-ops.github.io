<?php
// Check if session is already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'connection.php'; // Assuming this file contains your database connection

// Handle delete booking action
if (isset($_GET['delete_booking'])) {
    $booking_id = intval($_GET['delete_booking']);
    $delete_query = "DELETE FROM bookings WHERE id = ?";
    $stmt = $conn->prepare($delete_query);

    if ($stmt) {
        $stmt->bind_param("i", $booking_id);
        $stmt->execute();
        $stmt->close();
        header("Location: manage_bookings.php?status=booking_deleted");
        exit();
    } else {
        die("Database query preparation failed: " . htmlspecialchars($conn->error));
    }
}

// Handle update booking action
if (isset($_POST['update_booking'])) {
    $booking_id = intval($_POST['booking_id']);
    $start_datetime = $_POST['start_datetime'];
    $end_datetime = $_POST['end_datetime'];
    $status = $_POST['status'];

    $update_query = "UPDATE bookings SET start_datetime = ?, end_datetime = ?, status = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);

    if ($stmt) {
        $stmt->bind_param("sssi", $start_datetime, $end_datetime, $status, $booking_id);
        $stmt->execute();
        $stmt->close();
        header("Location: manage_bookings.php?status=booking_updated");
        exit();
    } else {
        die("Database query preparation failed: " . htmlspecialchars($conn->error));
    }
}

// Fetch bookings
$bookings_query = "SELECT id, user_id, car_id, start_datetime, end_datetime, status FROM bookings";
$bookings_result = $conn->query($bookings_query);

if (!$bookings_result) {
    die("Error fetching bookings: " . htmlspecialchars($conn->error));
}

$conn->close();

// Check if action was successful
$status_message = '';
if (isset($_GET['status'])) {
    if ($_GET['status'] === 'booking_deleted') {
        $status_message = '<p style="color: green;">Booking deleted successfully!</p>';
    } elseif ($_GET['status'] === 'booking_updated') {
        $status_message = '<p style="color: green;">Booking updated successfully!</p>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings - DLCL Transport</title>
    <link rel="stylesheet" href="manage_bookings.css">
</head>
<body>

<div class="dashboard">
    <h2>Admin Dashboard</h2>
    <a href="admin.php">Home</a>
    <a href="manage_users.php">Manage Users</a>
    <a href="manage_bookings.php">Manage Bookings</a>
    <a href="manage_cars.php">Manage Cars</a>
    <a href="logout.php">Logout</a>
</div>

<div class="container">
    <h1>Manage Bookings</h1>

    <?php echo $status_message; ?>

    <h2>Booking List</h2>
    <table>
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>User ID</th>
                <th>Car ID</th>
                <th>Start DateTime</th>
                <th>End DateTime</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $bookings_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['car_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['start_datetime']); ?></td>
                    <td><?php echo htmlspecialchars($row['end_datetime']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td>
                        <a href="manage_bookings.php?delete_booking=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this booking?')">Delete</a>
                        <a href="#" onclick="openEditModal(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars($row['start_datetime']); ?>', '<?php echo htmlspecialchars($row['end_datetime']); ?>', '<?php echo htmlspecialchars($row['status']); ?>')">Edit</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Edit Booking Modal -->
    <div id="editBookingModal" style="display: none;">
        <form action="manage_bookings.php" method="post">
            <input type="hidden" id="edit_booking_id" name="booking_id">
            <div class="form-group">
                <label for="edit_start_datetime">Start DateTime</label>
                <input type="datetime-local" id="edit_start_datetime" name="start_datetime" required>
            </div>
            <div class="form-group">
                <label for="edit_end_datetime">End DateTime</label>
                <input type="datetime-local" id="edit_end_datetime" name="end_datetime" required>
            </div>
            <div class="form-group">
                <label for="edit_status">Status</label>
                <select id="edit_status" name="status" required>
                    <option value="Pending">Pending</option>
                    <option value="Confirmed">Confirmed</option>
                    <option value="Completed">Completed</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
            </div>
            <button type="submit" name="update_booking">Update Booking</button>
            <button type="button" onclick="closeEditModal()">Cancel</button>
        </form>
    </div>

    <script>
        function openEditModal(bookingId, startDatetime, endDatetime, status) {
            document.getElementById('edit_booking_id').value = bookingId;
            document.getElementById('edit_start_datetime').value = startDatetime;
            document.getElementById('edit_end_datetime').value = endDatetime;
            document.getElementById('edit_status').value = status;
            document.getElementById('editBookingModal').style.display = 'block';
        }

        function closeEditModal() {
            document.getElementById('editBookingModal').style.display = 'none';
        }
    </script>
</div>

</body>
</html>
