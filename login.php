<?php
session_start();
include 'connection.php'; // Ensure this file properly creates $conn

$email_error = $password_error = "";
$email_value = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $email_value = htmlspecialchars($email);

    // Check if connection is established
    if (!$conn || $conn->connect_error) {
        die("Connection error: " . $conn->connect_error);
    }

    // Check if user exists
    if ($stmt = $conn->prepare("SELECT * FROM user WHERE email = ?")) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            // Verify password
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                header("Location: dashboard.php"); // Redirect to the dashboard or another page
                exit();
            } else {
                $password_error = "Incorrect password.";
            }
        } else {
            $email_error = "No account found.";
        }

        $stmt->close();
    } else {
        die("Error preparing statement: " . $conn->error);
    }

    $conn->close(); // Close the connection after all queries are complete
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .error-input {
            border-color: red;
        }
        .error {
            color: red;
            margin-bottom: 10px;e
        }
        /* Add more styles here */
    </style>
</head>
<body>
    <div id="login-section">
        <h2>Login</h2>
        <form method="post" action="login.php">
            <input type="email" name="email" placeholder="Email" value="<?php echo $email_value; ?>" class="<?php echo $email_error ? 'error-input' : ''; ?>" required><br>
            <input type="password" name="password" placeholder="Password" class="<?php echo $password_error ? 'error-input' : ''; ?>" required><br>
             <?php if ($email_error || $password_error): ?>
                <p class="error"><?php echo "$email_error $password_error"; ?></p>
            <?php endif; ?>
            <button type="submit">Login</button>
        </form>
        <p>No account? <a href="signup.php">Sign up here</a>.</p>
    </div>
</body>
</html>
