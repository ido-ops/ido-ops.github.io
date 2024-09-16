<?php
$servername = "localhost";
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "dlcl_transport";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM cars";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo '<div class="car-item">';
        echo '<img src="images/' . $row["image"] . '" alt="' . $row["model"] . '">';
        echo '<div class="car-info">';
        echo '<h3>' . $row["model"] . '</h3>';
        echo '<p><strong>Dimensions (mm):</strong> ' . $row["dimensions"] . '</p>';
        echo '<p><strong>Engine Type:</strong> ' . $row["engine_type"] . '</p>';
        echo '<p><strong>Fuel Efficiency:</strong> ' . $row["fuel_efficiency"] . '</p>';
        echo '<p><strong>Seating Capacity:</strong> ' . $row["seating_capacity"] . '</p>';
        echo '<p><strong>Transmission:</strong> ' . $row["transmission"] . '</p>';
        echo '<p><strong>Horsepower:</strong> ' . $row["horsepower"] . '</p>';
        echo '<p><strong>0-100 km/h:</strong> ' . $row["acceleration"] . '</p>';
        echo '<p><strong>Top Speed:</strong> ' . $row["top_speed"] . '</p>';
        echo '<button class="select-btn">Select</button>';
        echo '</div>';
        echo '</div>';
}} else {
    echo "0 results";
}
$conn->close();
?>
