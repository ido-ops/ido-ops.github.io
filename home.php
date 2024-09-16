<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Car Tracking - Multiple Cars</title>
    <link rel="stylesheet" href="home.css">

    <!-- Mapbox CSS -->
    <link href='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css' rel='stylesheet' />

    <!-- Mapbox Directions CSS -->
    <link href='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-directions/v4.1.1/mapbox-gl-directions.css' rel='stylesheet' />

    <!-- Mapbox JS -->
    <script src='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js'></script>

    <!-- Mapbox Directions JS -->
    <script src='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-directions/v4.1.1/mapbox-gl-directions.js'></script>
</head>
<body>
<div class="container">
<div class="dashboard">
    <h2>Dashboard</h2>
    <a href="home.php">Home</a>
    <a href = "booking.php">Booking</a>
    <a href="booking_status.php">Booking Status</a>
    <a href="booking_history.php">Booking History</a>
    <a href="account.php">Account</a>
    <a href="logout.php">Logout</a>
</div>
    <div id="map"></div>
    
    <script>
        mapboxgl.accessToken = 'pk.eyJ1IjoiYWtvc2lhbmdlbDEyMyIsImEiOiJjbTBxaTJnenAwZDVhMnFwcmwwNmgzZWE1In0.Ux5I8Bxb9fRapk25oecpXQ';

        // Initialize the map
        var map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v11',
            center: [103.8198, 1.3521], // Initial center of the map
            zoom: 12
        });

        // Add navigation controls (zoom buttons)
        map.addControl(new mapboxgl.NavigationControl());

        // Function to track multiple cars
        function trackCars(cars) {
            cars.forEach(function(car) {
                var directions = new MapboxDirections({
                    accessToken: mapboxgl.accessToken,
                    unit: 'metric',
                    profile: 'mapbox/driving',
                    controls: {
                        inputs: false, // Disable user inputs
                        instructions: true // Show route instructions
                    }
                });

                // Add the directions control for each car (rendering multiple routes)
                map.addControl(directions);

                // Set each car's current location and destination
                directions.setOrigin(car.currentLocation);
                directions.setDestination(car.destination);

                // Simulate live tracking (replace this with real-time updates)
                setTimeout(function() {
                    // Update the car's location after some time (real-time GPS would go here)
                    directions.setOrigin(car.newLocation); // Example of updating car location
                }, car.updateTime); // Different time delay for each car
            });
        }

        // Example data for multiple cars
        var cars = [
            {
                currentLocation: [103.8198, 1.3521], // Car 1's current location
                destination: [103.851959, 1.290270], // Car 1's destination
                newLocation: [103.8205, 1.3530], // Updated location for car 1
                updateTime: 5000 // 5 seconds delay before updating location
            },
            {
                currentLocation: [103.8100, 1.3600], // Car 2's current location
                destination: [103.8350, 1.2750], // Car 2's destination
                newLocation: [103.8120, 1.3610], // Updated location for car 2
                updateTime: 8000 // 8 seconds delay before updating location
            },
            {
                currentLocation: [103.8000, 1.3400], // Car 3's current location
                destination: [103.8450, 1.2800], // Car 3's destination
                newLocation: [103.8020, 1.3410], // Updated location for car 3
                updateTime: 6000 // 6 seconds delay before updating location
            }
        ];

        // Start tracking the cars
        trackCars(cars);
    </script>
    </div>
</body>
</html>
