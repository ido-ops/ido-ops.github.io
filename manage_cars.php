<?php
// Check if session is already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'connection.php'; // Assuming this file contains your database connection

// Handle delete selected cars action
if (isset($_POST['remove_selected'])) {
    if (isset($_POST['car_ids']) && is_array($_POST['car_ids'])) {
        $car_ids = array_map('intval', $_POST['car_ids']); // Sanitize and convert to integers
        $placeholders = implode(',', array_fill(0, count($car_ids), '?'));
        $delete_query = "DELETE FROM cars WHERE car_id IN ($placeholders)";
        $stmt = $conn->prepare($delete_query);

        if ($stmt) {
            $stmt->bind_param(str_repeat('i', count($car_ids)), ...$car_ids);
            $stmt->execute();
            $stmt->close();
            header("Location: manage_cars.php?status=cars_deleted");
            exit();
        } else {
            die("Database query preparation failed: " . htmlspecialchars($conn->error));
        }
    }
}

// Handle add/edit car action
if (isset($_POST['save_car'])) {
    $car_id = isset($_POST['car_id']) ? intval($_POST['car_id']) : 0;
    $model = $_POST['model'];
    $status = $_POST['status'];
    $image = $_POST['image']; // Assuming you handle image upload separately

    if ($car_id) {
        // Update existing car
        $update_query = "UPDATE cars SET model = ?, status = ?, image = ? WHERE car_id = ?";
        $stmt = $conn->prepare($update_query);
        if ($stmt) {
            $stmt->bind_param("sssi", $model, $status, $image, $car_id);
            $stmt->execute();
            $stmt->close();
            header("Location: manage_cars.php?status=car_updated");
            exit();
        } else {
            die("Database query preparation failed: " . htmlspecialchars($conn->error));
        }
    } else {
        // Add new car
        $insert_query = "INSERT INTO cars (model, status, image) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        if ($stmt) {
            $stmt->bind_param("sss", $model, $status, $image);
            $stmt->execute();
            $stmt->close();
            header("Location: manage_cars.php?status=car_added");
            exit();
        } else {
            die("Database query preparation failed: " . htmlspecialchars($conn->error));
        }
    }
}

// Fetch cars
$cars_query = "SELECT car_id, model, status, image FROM cars";
$cars_result = $conn->query($cars_query);

if (!$cars_result) {
    die("Error fetching cars: " . htmlspecialchars($conn->error));
}

$conn->close();

// Check if action was successful
$status_message = '';
if (isset($_GET['status'])) {
    if ($_GET['status'] === 'cars_deleted') {
        $status_message = '<p style="color: green;">Selected cars deleted successfully!</p>';
    } elseif ($_GET['status'] === 'car_updated') {
        $status_message = '<p style="color: green;">Car updated successfully!</p>';
    } elseif ($_GET['status'] === 'car_added') {
        $status_message = '<p style="color: green;">Car added successfully!</p>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Cars - DLCL Transport</title>
    <link rel="stylesheet" href="manage_cars.css">
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
    <h1>Manage Cars</h1>

    <?php echo $status_message; ?>

    <form action="manage_cars.php" method="post">
        <h2>Car List</h2>
        <table>
            <thead>
                <tr>
                    <th>Select</th>
                    <th>Car ID</th>
                    <th>Model</th>
                    <th>Status</th>
                    <th>Image</th>
                </tr>
            </thead>
            <tbody>
    <?php while ($row = $cars_result->fetch_assoc()): ?>
        <tr>
            <td><input type="checkbox" name="car_ids[]" value="<?php echo htmlspecialchars($row['car_id']); ?>"></td>
            <td><?php echo htmlspecialchars($row['car_id']); ?></td>
            <td><?php echo htmlspecialchars($row['model']); ?></td>
            <td><?php echo htmlspecialchars($row['status']); ?></td>
            <td>
                <?php
                $imageUrl = htmlspecialchars($row['image']);
                $fullImagePath = 'images/' . $imageUrl;
                if (!empty($imageUrl) && file_exists($fullImagePath)): ?>
                    <img src="<?php echo $fullImagePath; ?>" alt="Car Image" style="width: 100px; height: auto;">
                <?php else: ?>
                    <p>No image available</p>
                <?php endif; ?>
            </td>
            <td>
                <button type="button" onclick="openEditModal('<?php echo $row['car_id']; ?>', '<?php echo htmlspecialchars(addslashes($row['model'])); ?>', '<?php echo $row['status']; ?>', '<?php echo addslashes($row['image']); ?>')">Edit</button>
            </td>
        </tr>
    <?php endwhile; ?>
</tbody>

        </table>
        <button type="submit" name="remove_selected">Remove Selected</button>
    </form>

    <!-- Add/Edit Car Button -->
    <button onclick="openEditModal()">Add New Car</button>

    <!-- Edit/Add Car Modal -->
    <div id="editCarModal" style="display: none;">
        <form action="manage_cars.php" method="post">
            <input type="hidden" id="edit_car_id" name="car_id">
            <div class="form-group">
                <label for="edit_model">Model</label>
                <input type="text" id="edit_model" name="model" required>
            </div>
            <div class="form-group">
                <label for="edit_status">Status</label>
                <select id="edit_status" name="status" required>
                    <option value="Available">Available</option>
                    <option value="Unavailable">Unavailable</option>
                </select>
            </div>
            <div class="form-group">
                <label for="edit_image">Image URL</label>
                <input type="text" id="edit_image" name="image">
            </div>
            <button type="submit" name="save_car">Save Car</button>
            <button type="button" onclick="closeEditModal()">Cancel</button>
        </form>
    </div>

        <script>
            function openEditModal(carId = '', model = '', status = '', image = '') {
        document.getElementById('edit_car_id').value = carId;
        document.getElementById('edit_model').value = model;
        document.getElementById('edit_status').value = status;
        document.getElementById('edit_image').value = image;
        document.getElementById('editCarModal').style.display = 'block';
    }

    function closeEditModal() {
        document.getElementById('editCarModal').style.display = 'none';
    }

    </script>
</div>

</body>
</html>
