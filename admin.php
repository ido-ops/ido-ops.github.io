<?php
// Check if session is already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}



include 'connection.php'; // Assuming this file contains your database connection

// Handle delete user action
if (isset($_GET['delete_user'])) {
    $user_id = intval($_GET['delete_user']);
    $delete_query = "DELETE FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($delete_query);

    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();
        header("Location: admin.php?status=user_deleted");
        exit();
    } else {
        die("Database query preparation failed: " . htmlspecialchars($conn->error));
    }
}

// Fetch users
$users_query = "SELECT user_id, email FROM user";
$users_result = $conn->query($users_query);

if (!$users_result) {
    die("Error fetching users: " . htmlspecialchars($conn->error));
}

// Fetch bookings
$bookings_query = "SELECT id, user_id, car_id, start_datetime, end_datetime FROM bookings";
$bookings_result = $conn->query($bookings_query);

if (!$bookings_result) {
    die("Error fetching bookings: " . htmlspecialchars($conn->error));
}

// Fetch cars
$cars_query = "SELECT car_id, model FROM cars";
$cars_result = $conn->query($cars_query);

if (!$cars_result) {
    die("Error fetching cars: " . htmlspecialchars($conn->error));
}

$bookings_query = "SELECT id, user_id, car_id, start_datetime, end_datetime FROM bookings";
$bookings_result = $conn->query($bookings_query);

if (!$bookings_result) {
    die("Error fetching bookings: " . htmlspecialchars($conn->error));
}

// Prepare bookings for the calendar
$bookings_data = [];
while ($row = $bookings_result->fetch_assoc()) {
    $bookings_data[] = [
        'id' => $row['id'],
        'title' => 'Booking ID: ' . $row['id'] . ' (Car ID: ' . $row['car_id'] . ')',
        'start' => $row['start_datetime'],
        'end' => $row['end_datetime'],
    ];
}

$conn->close();

// Check if action was successful
$status_message = '';
if (isset($_GET['status'])) {
    if ($_GET['status'] === 'user_deleted') {
        $status_message = '<p style="color: green;">User deleted successfully!</p>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - DLCL Transport</title>
    <link rel="stylesheet" href="admin.css">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js'></script>
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
    <h1>Admin Dashboard</h1>
    <h1>Booking Calendar</h1>
    <div id="calendar"></div> <!-- This is where the calendar will be rendered -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: <?php echo json_encode($bookings_data); ?>, // PHP array passed as JSON
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                }
            });

            calendar.render();
        });
    </script>

    <?php echo $status_message; ?>

    <h2>Manage Users</h2>
    <table>
        <thead>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $users_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td>
                        <a href="admin.php?delete_user=<?php echo $row['user_id']; ?>" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h2>Manage Bookings</h2>
    <table>
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>User ID</th>
                <th>Car ID</th>
                <th>Start Date</th>
                <th>End Date</th>
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
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h2>Manage Cars</h2>
    <table>
        <thead>
            <tr>
                <th>Car ID</th>
                <th>Model</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $cars_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['car_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['model']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
