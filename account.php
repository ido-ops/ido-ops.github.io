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

// Initialize variables
$id_front = '';
$id_back = '';

// Fetch user details from the database
$user_query = "SELECT id_number, last_name, first_name, middle_name, email, contact_number, birthdate, gender, address, profile_photo, id_front, id_back FROM user WHERE user_id = ?";
$stmt = $conn->prepare($user_query);

if (!$stmt) {
    die("Prepare failed: " . $conn->error); // Output error if prepare fails
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($id_number, $last_name, $first_name, $middle_name, $email, $contact_number, $birthdate, $gender, $address, $profile_photo, $id_front, $id_back);
$stmt->fetch();
$stmt->close();

// Handle update details form submission
if (isset($_POST['update'])) {
    $id_number = $_POST['id_number'];
    $last_name = $_POST['last_name'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact_number'];
    $birthdate = $_POST['birthdate'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];

    $update_query = "UPDATE user SET id_number = ?, last_name = ?, first_name = ?, middle_name = ?, email = ?, contact_number = ?, birthdate = ?, gender = ?, address = ? WHERE user_id = ?";
    $stmt = $conn->prepare($update_query);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error); // Output error if prepare fails
    }

    $stmt->bind_param("sssssssssi", $id_number, $last_name, $first_name, $middle_name, $email, $contact_number, $birthdate, $gender, $address, $user_id);
    $stmt->execute();
    $stmt->close();
    echo "<p>Details updated successfully!</p>";
}

// Handle profile photo upload
if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == UPLOAD_ERR_OK) {
    $photo = $_FILES['profile_photo'];
    $photo_name = basename($photo['name']);
    $upload_dir = 'uploads/profile_photos/';
    $upload_file = $upload_dir . $photo_name;

    // File validation
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_size = 2 * 1024 * 1024; // 2MB

    if (in_array($photo['type'], $allowed_types) && $photo['size'] <= $max_size) {
        // Create uploads directory if it doesn't exist
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        if (move_uploaded_file($photo['tmp_name'], $upload_file)) {
            // Update the database with the new photo path
            $stmt = $conn->prepare("UPDATE user SET profile_photo = ? WHERE user_id = ?");
            if (!$stmt) {
                die("Prepare failed: " . $conn->error); // Output error if prepare fails
            }

            $stmt->bind_param("si", $photo_name, $user_id);
            $stmt->execute();
            $stmt->close();
            // Refresh the profile photo URL
            $profile_photo = $photo_name;
            echo "<p>Profile photo updated successfully!</p>";
        } else {
            echo "<p>Failed to upload photo.</p>";
        }
    } else {
        echo "<p>Invalid file type or size. Please upload a valid image (JPEG, PNG, GIF) under 2MB.</p>";
    }
}

// Handle change password form submission
if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        echo "<p>New passwords do not match.</p>";
    } else {
        // Fetch the current hashed password from the database
        $stmt = $conn->prepare("SELECT password FROM user WHERE user_id = ?");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error); // Output error if prepare fails
        }

        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($hashed_password);
        $stmt->fetch();
        $stmt->close();

        if (password_verify($current_password, $hashed_password)) {
            $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE user SET password = ? WHERE user_id = ?");
            if (!$stmt) {
                die("Prepare failed: " . $conn->error); // Output error if prepare fails
            }

            $stmt->bind_param("si", $new_hashed_password, $user_id);
            $stmt->execute();
            $stmt->close();
            echo "<p>Password updated successfully!</p>";
        } else {
            echo "<p>Current password is incorrect.</p>";
        }
    }
}

// Handle ID photo uploads
if (isset($_FILES['id_front']) && $_FILES['id_front']['error'] == UPLOAD_ERR_OK) {
    $id_front = $_FILES['id_front'];
    $id_front_name = basename($id_front['name']);
    $upload_dir_front = 'uploads/id_front/';
    $upload_file_front = $upload_dir_front . $id_front_name;

    if (!is_dir($upload_dir_front)) {
        mkdir($upload_dir_front, 0777, true);
    }

    if (move_uploaded_file($id_front['tmp_name'], $upload_file_front)) {
        // Update the database with the new ID front photo path
        $stmt = $conn->prepare("UPDATE user SET id_front = ? WHERE user_id = ?");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("si", $id_front_name, $user_id);
        $stmt->execute();
        $stmt->close();
        $id_front = $id_front_name;
        echo "<p>ID Front photo updated successfully!</p>";
    } else {
        echo "<p>Failed to upload ID Front photo.</p>";
    }
}

if (isset($_FILES['id_back']) && $_FILES['id_back']['error'] == UPLOAD_ERR_OK) {
    $id_back = $_FILES['id_back'];
    $id_back_name = basename($id_back['name']);
    $upload_dir_back = 'uploads/id_back/';
    $upload_file_back = $upload_dir_back . $id_back_name;

    if (!is_dir($upload_dir_back)) {
        mkdir($upload_dir_back, 0777, true);
    }

    if (move_uploaded_file($id_back['tmp_name'], $upload_file_back)) {
        // Update the database with the new ID back photo path
        $stmt = $conn->prepare("UPDATE user SET id_back = ? WHERE user_id = ?");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("si", $id_back_name, $user_id);
        $stmt->execute();
        $stmt->close();
        $id_back = $id_back_name;
        echo "<p>ID Back photo updated successfully!</p>";
    } else {
        echo "<p>Failed to upload ID Back photo.</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account - DLCL Transport</title>
    <link rel="stylesheet" href="account.css">
</head>
<body>

<div class="dashboard">
    <h2>Dashboard</h2>
    <a href="home.php">Home</a>
    <a href = "booking.php">Booking</a>
    <a href="booking_status.php">Booking Status</a>
    <a href="booking_history.php">Booking History</a>
    <a href="account.php">Account</a>
    <a href="logout.php">Logout</a>
</div>

<div class="container">
    <h1>Account Details</h1>
    <?php if ($profile_photo): ?>
        <div class="profile-photo">
            <img src="uploads/profile_photos/<?php echo htmlspecialchars($profile_photo); ?>" alt="Profile Photo">
        </div>
    <?php endif; ?>
   
    
    <form action="account.php" method="post" enctype="multipart/form-data">
        

        <div class="form-group">
        <label for="last_name">Last Name</label>
        <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" required>
    </div>
    <div class="form-group">
        <label for="first_name">First Name</label>
        <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" required>
    </div>
    <div class="form-group">
        <label for="middle_name">Middle Name</label>
        <input type="text" id="middle_name" name="middle_name" value="<?php echo htmlspecialchars($middle_name); ?>" required>
    </div>
    <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
    </div>
    <div class="form-group">
        <label for="contact_number">Contact Number</label>
        <input type="text" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars($contact_number); ?>" required>
    </div>
    <div class="form-group">
        <label for="birthdate">Birthdate</label>
        <input type="date" id="birthdate" name="birthdate" value="<?php echo htmlspecialchars($birthdate); ?>" required>
    </div>
    <div class="form-group">
        <label for="gender">Gender</label>
        <select id="gender" name="gender" required>
            <option value="Male" <?php echo ($gender == 'Male') ? 'selected' : ''; ?>>Male</option>
            <option value="Female" <?php echo ($gender == 'Female') ? 'selected' : ''; ?>>Female</option>
            <option value="Other" <?php echo ($gender == 'Other') ? 'selected' : ''; ?>>Other</option>
        </select>
    </div>
    <div class="form-group">
        <label for="address">Address</label>
        <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($address); ?>" required>
    </div>
    <div class="form-group">
        <label for="profile_photo">Profile Photo</label>
        <input type="file" id="profile_photo" name="profile_photo">
        </div>

        <div class="form-group">
            <label for="id_front">Upload ID Front</label>
            <input type="file" id="id_front" name="id_front">
            <?php if ($id_front): ?>
                <img src="uploads/id_front/<?php echo htmlspecialchars($id_front); ?>" alt="ID Front" width="200">
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="id_back">Upload ID Back</label>
            <input type="file" id="id_back" name="id_back">
            <?php if ($id_back): ?>
                <img src="uploads/id_back/<?php echo htmlspecialchars($id_back); ?>" alt="ID Back" width="200">
            <?php endif; ?>
        </div>

        <div class="form-group">
        <label for="id_number">ID Number</label>
        <input type="text" id="id_number" name="id_number" value="<?php echo htmlspecialchars($id_number); ?>" required>
    </div>
    
    <button type="submit" name="update">Update Details</button>

            </form>


    </div>
</body>
</html>
