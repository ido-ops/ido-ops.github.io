<?php
// Check if session is already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'connection.php'; // Assuming this file contains your database connection

$user_id = $_SESSION['user_id'];

// Fetch user settings from the database
$settings_query = "SELECT theme, email_notifications FROM user_settings WHERE user_id = ?";
$stmt = $conn->prepare($settings_query);

if (!$stmt) {
    die("Prepare failed: " . $conn->error); // Output error if prepare fails
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($theme, $email_notifications);
$stmt->fetch();
$stmt->close();

// Handle settings update form submission
if (isset($_POST['update_settings'])) {
    $theme = $_POST['theme'];
    $email_notifications = isset($_POST['email_notifications']) ? 1 : 0;

    $update_query = "UPDATE user_settings SET theme = ?, email_notifications = ? WHERE user_id = ?";
    $stmt = $conn->prepare($update_query);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error); // Output error if prepare fails
    }

    $stmt->bind_param("sii", $theme, $email_notifications, $user_id);
    $stmt->execute();
    $stmt->close();
    echo "<p>Settings updated successfully!</p>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - DLCL Transport</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>

<div class="dashboard">
    <h2>Dashboard</h2>
    <a href="booking.php">Home</a>
    <a href="booking_status.php">Booking Status</a>
    <a href="booking_history.php">Booking History</a>
    <a href="account.php">Account</a>
    <a href="settings.php">Settings</a>
    <a href="logout.php">Logout</a>
</div>

<div class="container">
    <h1>Settings</h1>

    <form action="settings.php" method="post">
        <div class="form-group">
            <label for="theme">Theme</label>
            <select id="theme" name="theme" required>
                <option value="light" <?php echo ($theme == 'light') ? 'selected' : ''; ?>>Light</option>
                <option value="dark" <?php echo ($theme == 'dark') ? 'selected' : ''; ?>>Dark</option>
            </select>
        </div>
        <div class="form-group">
            <label for="email_notifications">Email Notifications</label>
            <input type="checkbox" id="email_notifications" name="email_notifications" <?php echo ($email_notifications ? 'checked' : ''); ?>>
            <label for="email_notifications">Receive email notifications</label>
        </div>
        <button type="submit" name="update_settings">Update Settings</button>
    </form>
</div>

</body>
</html>
