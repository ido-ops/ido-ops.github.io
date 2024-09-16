<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $birthdate = $_POST['birthdate'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $contact_number = $_POST['contact_number'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Additional server-side validation
    $errors = [];
    $age = date_diff(date_create($birthdate), date_create('today'))->y;
    if ($age < 18) {
        $errors[] = "You must be 18 years old or older to sign up.";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Password and Confirm Password do not match.";
    }
    if (strlen($password) < 8 || !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password) || !preg_match('/[A-Z]/', $password)) {
        $errors[] = "Password must be at least 8 characters long, contain at least 1 special character, and include at least 1 capital letter.";
    }

    if (empty($errors)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert the data into the database
        $servername = "localhost";
        $db_username = "root"; // your database username
        $db_password = ""; // your database password
        $dbname = "dlcl_transport"; // your database name

        $conn = new mysqli($servername, $db_username, $db_password, $dbname);

        // Check connection
        if ($conn->connect_error) { 
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "INSERT INTO user (first_name, middle_name, last_name, birthdate, gender, email, address, contact_number, password)
                VALUES ('$first_name', '$middle_name', '$last_name', '$birthdate', '$gender', '$email', '$address', '$contact_number', '$hashed_password')";

        if ($conn->query($sql) === TRUE) {
            echo "<div style='color: green;'>Sign up successful! Redirecting to login...</div>";
            echo "<script>setTimeout(function() { window.location.href = 'login.php'; }, 2000);</script>";
        } else {
            echo "<div style='color: red;'>Error: " . $sql . "<br>" . $conn->error . "</div>";
        }

        $conn->close();
    } else {
        echo "<div id='errorMessages' style='color: red;'>" . implode("<br>", $errors) . "</div>";
    }
}
?>
