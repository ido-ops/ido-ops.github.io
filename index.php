<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DLCL Transport</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="icon" type="image/png" href="ph.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,1,0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="script.js"></script>
</head>
<body>
    <header class="header">
        <div class="logo">
            <img src="images/dlcl.jpg" alt="DLCL Transport Logo">
        </div>
        <div class="header-content">
            <div class="header-navigation">
                <a href="#home" class="header-button">Home</a>
                <a href="#about-us" class="header-button">About Us</a> <!-- New About Us Button -->
                <a href="#values" class="header-button">Values</a>
                <a href="#our-cars" class="header-button">Cars</a>
                <a href="#our-drivers" class="header-button">Drivers</a>
                <a href="#customer-feedback" class="header-button">Feedback</a>
                <a href="#contact-us" class="header-button">Contact Us</a>
            </div>
        </div>
    </header>

    <!-- Home Section without Photo Slider -->
    <section id="home" class="landing-section">
        <div class="home-content">
            <h1>Drive Your Dreams</h1>
            <p><h3>Discover the freedom of the open road with our top-tier car rental services. Whether it's for a weekend getaway or a business trip, our vehicles are ready to take you where you need to go. Experience unmatched comfort and reliability with every ride.</h3></p>
            <br>
            <a href="login.php" target="_blank" class="get-started-btn">Get Started</a>
        </div>
    </section>

    <section id="about-us" class="fade-in">
        <div class="about-us-container">
            <h2>About Us</h2>
            <p>DLCL Transport is a premier car rental service provider dedicated to offering top-notch vehicles and exceptional customer service. Established in 2010, we have been committed to ensuring our customers have the best possible experience, whether they are renting a car for business, leisure, or special occasions.</p>
            <p>Our fleet includes a wide range of vehicles from luxury sedans to rugged SUVs, ensuring that we have something to suit every need. We believe in providing quality, reliability, and safety to our customers, making us a trusted name in the transportation industry.</p>
            <p>At DLCL Transport, we strive to exceed expectations and create lasting relationships with our clients by delivering superior service every time.</p>
        </div>
    </section>

    <!-- DLCL Transport Values Section -->
    <section id="values" class="fade-in">
        <div class="values-container">
            <h2>What We Care About</h2>
            <div class="values-content">
                <div class="value-item">
                    <img src="q.png" alt="Quality Service Logo" class="value-logo">
                    <h3>Quality Service</h3>
                    <p>We are committed to providing the highest quality of service to ensure a smooth and pleasant experience. Our vehicles are well-maintained, and our staff is trained to offer exceptional support.</p>
                </div>
                <div class="value-item">
                    <img src="cs.png" alt="Customer Service Logo" class="value-logo">
                    <h3>Customer Care</h3>
                    <p>Your satisfaction is our top priority. We are here to assist you at every step of the way, from booking to returning your vehicle. Our friendly team is always available to address your concerns and ensure your needs are met.</p>
                </div>
                <div class="value-item">
                    <img src="s.png" alt="Safety Logo" class="value-logo">
                    <h3>Safety</h3>
                    <p>Your safety is crucial to us. We rigorously inspect our vehicles to meet safety standards and provide you with peace of mind during your journey. Our vehicles are equipped with the latest safety features to keep you secure on the road.</p>
                </div>
                <div class="value-item">
                    <img src="a.png" alt="Affordability Logo" class="value-logo">
                    <h3>Affordability</h3>
                    <p>We offer competitive pricing without compromising on quality. Our goal is to make car rental accessible and affordable for everyone, so you can enjoy your travel without breaking the bank.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Cars Section -->
    <section id="our-cars" class="fade-in">
        <h2>Our Cars</h2>
        <div class="cars-grid">
            <?php include 'cars.php'; ?>
        </div>
    </section>

    <!-- Our Drivers Section -->
    <section id="our-drivers" class="fade-in">
        <h2>Our Drivers</h2>
        <div class="drivers-grid">
            <div class="driver-item">
                <img src="images/d1.jpg" alt="Driver 1">
                <div class="driver-info">
                    <h3>De Coy Soon</h3>
                    <p>With over 10 years of experience, John is known for his punctuality and excellent customer service.</p>
                    <div class="rating">
                        <span class="material-symbols-outlined">star</span>
                        <span class="material-symbols-outlined">star</span>
                        <span class="material-symbols-outlined">star</span>
                        <span class="material-symbols-outlined">star</span>
                        <span class="material-symbols-outlined">star_outline</span>
                    </div>
                </div>
            </div>
            <div class="driver-item">
                <img src="images/d2.jpg" alt="Driver 2">
                <div class="driver-info">
                    <h3>De Coy Soon</h3>
                    <p>With over 10 years of experience, John is known for his punctuality and excellent customer service.</p>
                    <div class="rating">
                        <span class="material-symbols-outlined">star</span>
                        <span class="material-symbols-outlined">star</span>
                        <span class="material-symbols-outlined">star</span>
                        <span class="material-symbols-outlined">star</span>
                        <span class="material-symbols-outlined">star_outline</span>
                    </div>
                </div>
            </div>
            <div class="driver-item">
                <img src="images/d3.jpg" alt="Driver 3">
                <div class="driver-info">
                    <h3>De Coy Soon</h3>
                    <p>With over 10 years of experience, John is known for his punctuality and excellent customer service.</p>
                    <div class="rating">
                        <span class="material-symbols-outlined">star</span>
                        <span class="material-symbols-outlined">star</span>
                        <span class="material-symbols-outlined">star</span>
                        <span class="material-symbols-outlined">star</span>
                        <span class="material-symbols-outlined">star_outline</span>
                    </div>
                </div>
            </div>
            <div class="driver-item">
                <img src="images/d4.jpg" alt="Driver 4">
                <div class="driver-info">
                    <h3>De Coy Soon</h3>
                    <p>With over 10 years of experience, John is known for his punctuality and excellent customer service.</p>
                    <div class="rating">
                        <span class="material-symbols-outlined">star</span>
                        <span class="material-symbols-outlined">star</span>
                        <span class="material-symbols-outlined">star</span>
                        <span class="material-symbols-outlined">star</span>
                        <span class="material-symbols-outlined">star_outline</span>
                    </div>
                </div>
            </div>
            <div class="driver-item">
                <img src="images/d5.jpg" alt="Driver 5">
                <div class="driver-info">
                    <h3>De Coy Soon</h3>
                    <p>With over 10 years of experience, John is known for his punctuality and excellent customer service.</p>
                    <div class="rating">
                        <span class="material-symbols-outlined">star</span>
                        <span class="material-symbols-outlined">star</span>
                        <span class="material-symbols-outlined">star</span>
                        <span class="material-symbols-outlined">star</span>
                        <span class="material-symbols-outlined">star_outline</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Customer Feedback Section -->
    <section id="customer-feedback" class="fade-in">
        <div class="feedback-container">
            <h2>What Our Customers Say</h2>
            <div class="feedback-grid">
                <div class="feedback-item">
                    <img src="images/cs1.jpg" alt="Customer 1">
                    <h3>John Doe</h3>
                    <p>"DLCL Transport provided an amazing service. The driver was punctual and the car was in excellent condition!"</p>
                </div>
                <div class="feedback-item">
                    <img src="images/cs2.jpg" alt="Customer 2">
                    <h3>Jane Smith</h3>
                    <p>"I had a wonderful experience. The booking process was seamless and the car exceeded my expectations."</p>
                </div>
                <div class="feedback-item">
                    <img src="images/cs3.jpg" alt="Customer 3">
                    <h3>Michael Johnson</h3>
                    <p>"Great service, highly recommend DLCL Transport for any transportation needs. I will definitely book again!"</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Us Section -->
    <section id="contact-us" class="fade-in">
        <div class="contact-container">
        <div class="contact-content">
            <h2>Contact Us</h2>
            <p>If you have any questions or need assistance, feel free to reach out to us.</p>
            <div class="contact-details">
                <div class="contact-item">
                    <i class="material-symbols-outlined">phone</i>
                    <h3>Phone</h3>
                    <p>+1 123 456 7890</p>
                </div>
                <div class="contact-item">
                    <i class="material-symbols-outlined">email</i>
                    <h3>Email</h3>
                    <a href="mailto:cxserv.dlcltransport@gmail.com" target="_blank">cxserv.dlcltransport@gmail.com</a>
                </div>
                <div class="contact-item">
                        <a href="https://www.google.com/maps/search/?api=1&query=1234+Elm+Street,+City,+Country" target="_blank">
                        <i class="material-symbols-outlined">location_on</i>
                        <h3>Location</h3>
                        <p>1234 Elm Street, City, Country</p>
                    </a>
                </div>
            </div>
            </div>
        </div>
    </section>

    <footer id="footer">
        <div class="footer-container">
            <div class="footer-logo">
                <h2>DLCL Transport</h2>
            </div>
            <div class="footer-social">
                <a href="https://web.facebook.com/dlcltransport" target="_blank" class="social-icon">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://twitter.com" target="_blank" class="social-icon">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="https://instagram.com" target="_blank" class="social-icon">
                    <i class="fab fa-instagram"></i>
                </a>
            </div>
            <p>&copy; DLCL Transport 2024. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>
