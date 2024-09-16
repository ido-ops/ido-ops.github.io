<?php
// Check if session is already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'connection.php'; // Assuming this file contains your database connection

// Handle delete user action
if (isset($_GET['delete_user'])) {
    $user_id = intval($_GET['delete_user']);
    $delete_query = "DELETE FROM user WHERE user_id = ?";
    $stmt = $conn->prepare($delete_query);

    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();
        header("Location: manage_users.php?status=user_deleted");
        exit();
    } else {
        die("Database query preparation failed: " . htmlspecialchars($conn->error));
    }
}

// Handle update user action
if (isset($_POST['update_user'])) {
    $user_id = intval($_POST['user_id']);
    $email = $_POST['email'];

    $update_query = "UPDATE user SET email = ? WHERE user_id = ?";
    $stmt = $conn->prepare($update_query);

    if ($stmt) {
        $stmt->bind_param("si", $email, $user_id);
        $stmt->execute();
        $stmt->close();
        header("Location: manage_users.php?status=user_updated");
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

$conn->close();

// Check if action was successful
$status_message = '';
if (isset($_GET['status'])) {
    if ($_GET['status'] === 'user_deleted') {
        $status_message = '<p style="color: green;">User deleted successfully!</p>';
    } elseif ($_GET['status'] === 'user_updated') {
        $status_message = '<p style="color: green;">User updated successfully!</p>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - DLCL Transport</title>
    <link rel="stylesheet" href="manage_users.css">
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
    <h1>Manage Users</h1>

    <?php echo $status_message; ?>

    <h2>User List</h2>
    <table>
        <thead>
            <tr>
                <th>User ID</th>
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
                        <a href="manage_users.php?delete_user=<?php echo $row['user_id']; ?>" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                        <a href="#" onclick="openEditModal(<?php echo $row['user_id']; ?>, '<?php echo htmlspecialchars($row['email']); ?>')">Edit</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Edit User Modal -->
    <div id="editUserModal" style="display: none;">
        <form action="manage_users.php" method="post">
            <input type="hidden" id="edit_user_id" name="user_id">
            <div class="form-group">
                <label for="edit_email">Email</label>
                <input type="email" id="edit_email" name="email" required>
            </div>
            <button type="submit" name="update_user">Update User</button>
            <button type="button" onclick="closeEditModal()">Cancel</button>
        </form>
    </div>

    <script>
        function openEditModal(userId, email) {
            document.getElementById('edit_user_id').value = userId;
            document.getElementById('edit_email').value = email;
            document.getElementById('editUserModal').style.display = 'block';
        }

        function closeEditModal() {
            document.getElementById('editUserModal').style.display = 'none';
        }
    </script>
</div>

</body>
</html>
