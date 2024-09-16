    <?php
    include 'connection.php'; // Ensure this file is included and $conn is initialized

    // Check if session is already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    // Fetch cars from the database
    $cars_query = "SELECT * FROM cars";
    $cars_result = $conn->query($cars_query);

    if (!$cars_result) {
        die("Error executing cars query: " . $conn->error);
    }

    // Fetch drivers from the database
    $drivers_query = "SELECT * FROM drivers";
    $drivers_result = $conn->query($drivers_query);

    if (!$drivers_result) {
        die("Error executing drivers query: " . $conn->error);
    }
    ?>



    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard - Book a Car</title>
        <link rel="stylesheet" href="booking.css">
        <script src="booking.js" defer></script>
    </head>
    <body>

    <div class="dashboard">
        <h2>Dashboard</h2>
        <a href="home.php">Home</a>
        <a href="booking.php">Booking</a>
        <a href="booking_status.php">Booking Status</a>
        <a href="booking_history.php">Booking History</a>
        <a href="account.php">Account</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="container">
        <div class="booking-section">
            <h2>Book a Car</h2>
            <form action="submit_booking.php" method="POST">
                <div class="form-group">
                    <label for="start-date">Pick a Start Date</label>
                    <input type="date" id="start-date" name="start-date" required>
                </div>
                <div class="form-group">
                    <label for="start-time">Pick a Start Time</label>
                    <input type="time" id="start-time" name="start-time" required>
                </div>
                <div class="form-group">
                    <label for="end-date">Pick an End Date</label>
                    <input type="date" id="end-date" name="end-date" required>
                </div>
                <div class="form-group">
                    <label for="end-time">Pick an End Time</label>
                    <input type="time" id="end-time" name="end-time" required>
                </div>
                
                <!-- Car selection section -->
                <h3 class="divider"><span>Select a Car:</span></h3>
                <div class="car-selection">
                    <div class="cars-grid">
                    <?php while ($car = $cars_result->fetch_assoc()): ?>
                            <div class="car-item" data-car-id="<?php echo $car['car_id']; ?>" data-rate-per-hour="<?php echo $car['rate_per_hour']; ?>" onclick="toggleCarSelection(this)">
                                <img src="images/<?php echo htmlspecialchars($car['image'], ENT_QUOTES); ?>" alt="<?php echo htmlspecialchars($car['model'], ENT_QUOTES); ?>">
                                <p><?php echo htmlspecialchars($car['model'], ENT_QUOTES); ?></p>
                                <p>Stock Available: <?php echo htmlspecialchars($car['stock'], ENT_QUOTES); ?></p>
                                <p>Price: ₱<?php echo number_format($car['rate_per_hour'], 2); ?> per hour</p>
                                <?php if ($car['stock'] > 0): ?>
                                    <button type="button" onclick="openModal(
                                        '<?php echo htmlspecialchars($car['model'], ENT_QUOTES); ?>', 
                                        'images/<?php echo htmlspecialchars($car['image'], ENT_QUOTES); ?>', 
                                        '<?php echo htmlspecialchars(addslashes($car['dimensions']), ENT_QUOTES); ?>', 
                                        '<?php echo htmlspecialchars(addslashes($car['engine_type']), ENT_QUOTES); ?>',
                                        '<?php echo htmlspecialchars(addslashes($car['fuel_efficiency']), ENT_QUOTES); ?>',
                                        '<?php echo htmlspecialchars($car['seating_capacity'], ENT_QUOTES); ?>',
                                        '<?php echo htmlspecialchars(addslashes($car['transmission']), ENT_QUOTES); ?>',
                                        '<?php echo htmlspecialchars($car['horsepower'], ENT_QUOTES); ?>',
                                        '<?php echo htmlspecialchars(addslashes($car['acceleration']), ENT_QUOTES); ?>',
                                        '<?php echo htmlspecialchars(addslashes($car['top_speed']), ENT_QUOTES); ?>'
                                    )">View</button>
                                <?php else: ?>
                                    <p style="color: red;">Out of Stock</p>
                                <?php endif; ?>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>

                <!-- Color selection (for selected car) -->
                <div id="color-selection" class="form-group" style="display: none;">
                    <label for="car-color">Select Car Color:</label>
                    <select id="car-color" name="car-color">
                        <!-- Options will be filled dynamically -->
                    </select>
                </div>

                <!-- Modal structure -->
                <div id="carModal" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <h2 id="modalTitle">Car Model</h2>
                        <img id="modalImage" src="" alt="Car Image">
                        <div id="modalDetails">
                            <p><strong>Dimensions:</strong> <span id="modalDimensions"></span></p>
                            <p><strong>Engine Type:</strong> <span id="modalEngine"></span></p>
                            <p><strong>Fuel Efficiency:</strong> <span id="modalFuel"></span></p>
                            <p><strong>Seating Capacity:</strong> <span id="modalSeating"></span></p>
                            <p><strong>Transmission:</strong> <span id="modalTransmission"></span></p>
                            <p><strong>Horsepower:</strong> <span id="modalHorsepower"></span></p>
                            <p><strong>Acceleration:</strong> <span id="modalAcceleration"></span></p>
                            <p><strong>Top Speed:</strong> <span id="modalSpeed"></span></p>
                        </div>
                    </div>
                </div>

                <!-- Driver selection section -->
                <div class="form-group">
                <label for="include-driver">Include a Driver</label>
                <input type="checkbox" id="include-driver" name="include-driver">
            </div>
            
            <!-- Driver selection section (initially hidden) -->
            <div id="driver-selection" style="display: none;">
                <h3 class="divider"><span>Select a Driver:</span></h3>
                <div class="drivers-grid">
                    <?php while ($driver = $drivers_result->fetch_assoc()): ?>
                        <div class="driver-item" data-driver-id="<?php echo $driver['driver_id']; ?>" onclick="toggleDriverSelection(this)">
                            <img src="images/<?php echo $driver['image']; ?>" alt="<?php echo htmlspecialchars($driver['name'], ENT_QUOTES); ?>">
                            <p><?php echo htmlspecialchars($driver['name'], ENT_QUOTES); ?></p>
                            <div class="rating">
                                <?php 
                                $rating = $driver['rating'];
                                $fullStars = floor($rating);
                                $halfStar = ($rating - $fullStars) >= 0.5 ? true : false;
                                $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                                ?>
                                <?php for ($i = 0; $i < $fullStars; $i++): ?>
                                    <span class="star full-star">&#9733;</span>
                                <?php endfor; ?>
                                <?php if ($halfStar): ?>
                                    <span class="star half-star">&#9733;</span>
                                <?php endif; ?>
                                <?php for ($i = 0; $i < $emptyStars; $i++): ?>
                                    <span class="star empty-star">&#9734;</span>
                                <?php endfor; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>



            
            <!-- Hidden input to store selected driver ID -->
        <input type="hidden" name="driver-id" id="driver-id">

        <!-- Add this section below the driver selection or any other appropriate location -->
        <!-- Hidden input to store total price -->
        <div class="car-item" data-car-id="<?php echo $car['car_id']; ?>" data-rate-per-hour="<?php echo $car['rate_per_hour']; ?>" onclick="toggleCarSelection(this)">

        <input type="hidden" name="total-price" id="total-price">



        <button type="submit" class="submit-button">Submit Booking</button>

        </form>
    </div>
</div>
</body>
</html>
