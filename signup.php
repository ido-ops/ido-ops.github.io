<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <script src="script.js" defer></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div id="signup-section">
        <h2>Sign Up</h2>
        <form id="signupForm" action="signup_process.php" method="POST">
            

            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required>

            <label for="middle_name">Middle Name:</label>
            <input type="text" id="middle_name" name="middle_name">

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required>

            <label for="birthdate">Birthdate:</label>
            <input type="date" id="birthdate" name="birthdate" required>

            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>

            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" required>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>

            <label for="contact_number">Contact Number:</label>
            <input type="text" id="contact_number" name="contact_number" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <div id="errorMessages" style="color: red;"></div> <!-- Error messages will be displayed here -->

            <button type="submit">Sign Up</button>
        </form>
    </div>
</body>
</html>
