<?php
session_start(); // Start session
include("connect.php"); // Include database connection
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goffee</title>

    <!-- Linking CSS -->
    <link rel="stylesheet" href="landingpage.css">
    <link rel="stylesheet" href="index.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Angkor&family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="index.css">
</head>
<body>

    <!-- Navigation Section -->
    <nav>
        <!-- Navigation Content -->
        <div class="left-nav">
            <ul>
                <li><a href="#home" class="nav-link active" id="homeLink">Home</a></li>
                <li><a href="#menu" class="nav-link" id="menuLink">Menu</a></li>
            </ul>
        </div>

        <div class="logo">
            <a href="#">
                <img src="images/cafe logo.png" alt="Cafe Logo">
                <span><span class="go">GO</span><span class="fee">ffee</span></span>
            </a>
        </div>

        <div class="right-nav">
            <ul>
                <li><a href="#about" class="nav-link" id="aboutLink">About Us</a></li>
                <li><a href="#contact" class="nav-link" id="contactLink">Contact</a></li>
                <li>
                    <a href="product-details.php">
                        <img src="images/shopping-cart.png" alt="Shopping Cart">
                    </a>
                </li>
                <?php if (isset($_SESSION['email']) && isset($_SESSION['fName'])): ?>
                    <li><a href="UserProfile.php">Hello, <?php echo htmlspecialchars($_SESSION['fName']); ?>!</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Sign In</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <!-- Home Section -->
    <section class="container" id="home">
        <div class="text-section">
            <h1><span class="highlight">GOffe</span></h1>
            <p>Elevating your cravings with every order. Discover the perfect mix of flavor, convenience, and delight, delivered straight to you for an unforgettable experience!</p>
            <button class="order-btn" id="orderButton" data-logged-in="<?php echo isset($_SESSION['email']) ? 'true' : 'false'; ?>">Order Now</button>
        </div>

        <div class="carousel-section" >
            <div class="carousel">
                <div class="carousel-images">
                    <img src="images/matcha.jpg" alt="Match Coffee" class="active">
                    <img src="images/sample.jpg" alt="Sample Coffee">
                    <img src="images/shopPic.jpg" alt="Shop Picture">
                    <img src="images/frontpic.jpg" alt="Shop Picture">
                </div>
            </div>
        </div>
    </section>

    <!-- Section 2: Menu Section -->
    <section class="section-two" id="menu">
        <div class="flavors-section">
            <h2>Explore Our Flavors</h2>
            <div class="flavors-container">
                <!-- Iced Coffee -->
                <div class="flavor-item">
                    <a href="iced-coffee.php" target="_blank">
                        <img src="images/iced coffe.png" alt="Iced Coffee" class="center-iced">
                    </a>
                    <p>Iced Coffee</p>
                </div>

                <!-- Meals -->
                <div class="flavor-item middle-box">
                    <a href="meals.php" target="_blank">
                        <img src="images/pasta.png" alt="Meals" class="center-pasta">
                    </a>
                    <p>Meals</p>
                </div>

                <!-- Non-Coffee -->
                <div class="flavor-item third-box">
                    <a href="non-coffee.php" target="_blank">
                        <img src="images/matcha.webp" alt="Non-Coffee">
                    </a>
                    <p>Non-Coffee</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Us Section -->
    <section id="about" class="about-us" style="background-color: #f9f1e8; padding: 40px 20px; text-align: center;">
        <h2 style="color: #d58c45; font-size: 2rem; margin-bottom: 20px;">About Us</h2>
        <p style="color: #333; font-size: 1rem; max-width: 800px; margin: 0 auto;">
            At GOffe, we value the art of crafting the perfect cup of coffee and offering delightful meals. 
            Every bean and ingredient is hand-selected to ensure quality and freshness. 
            Our team is committed to sustainability and community growth by sourcing locally 
            and maintaining a positive experience for every customer.
        </p>
    </section>

    <!-- Contact Us Section -->
    <section id="contact" class="contact-us" style="background-color: #fff; padding: 40px 20px; text-align: center; border-top: 2px solid #d58c45;">
        <h2 style="color: #d58c45; font-size: 2rem; margin-bottom: 20px;">Contact Us</h2>
        <p style="color: #333; font-size: 1rem; margin-bottom: 10px;">
            We'd love to hear from you! Whether you have inquiries, feedback, or collaboration ideas, you can reach out to us using the information below:
        </p>
        <ul style="list-style: none; padding: 0; color: #333; font-size: 1rem; margin-bottom: 20px;">
            <li>Email: <a href="mailto:support@goffe.com" style="color: #d58c45; text-decoration: none;">support@goffe.com</a></li>
            <li>Phone: <a href="tel:+15551234567" style="color: #d58c45; text-decoration: none;">(555) 123-4567</a></li>
            <li>Address: 123 GOffe Lane, Coffeeville, USA</li>
        </ul>
        <p style="color: #333; margin-bottom: 10px; text-align: center;">Follow us on social media channels for news and updates:</p>
        <ul style="list-style: none; padding: 0; display: flex; justify-content: center; align-items: center;">
            <li style="margin-right: 15px;">
                <a href="#" style="color: #d58c45; text-decoration: none; font-size: 24px;">
                    <i class="fab fa-instagram"></i>
                </a>
            </li>
            <li style="margin-right: 15px;">
                <a href="#" style="color: #d58c45; text-decoration: none; font-size: 24px;">
                    <i class="fab fa-facebook"></i>
                </a>
            </li>
            <li>
                <a href="#" style="color: #d58c45; text-decoration: none; font-size: 24px;">
                    <i class="fab fa-twitter"></i>
                </a>
            </li>
        </ul>
    </section>

    <!-- Footer Section -->
    <footer style="background-color: #f9f1e8; padding: 20px 0; text-align: center; font-size: 0.9rem; color: #333;">
        <p>© GOffe</p>
        <p style="margin-top: 10px;">Disclaimer: This website and its content are part of a school project and for educational purposes only.</p>
    </footer>

    <!-- JavaScript Logic -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const orderButton = document.getElementById("orderButton");
            const carousel = document.querySelector('.carousel-images');
            let currentIndex = 0;
            const images = carousel.querySelectorAll('img');

            function showImage(index) {
                // Hide all images first
                images.forEach(img => img.style.display = 'none');
                images[index].style.display = 'block';
            }

            function nextImage() {
                currentIndex = (currentIndex + 1) % images.length;
                showImage(currentIndex);
            }

            // Automatic carousel functionality
            setInterval(nextImage, 3000); // Change image every 3 seconds

            // Initially show the first image
            showImage(currentIndex);

            orderButton.addEventListener("click", function () {
                const loggedIn = orderButton.dataset.loggedIn === 'true';
                if (loggedIn) {
                    document.getElementById("menu").scrollIntoView({ behavior: "smooth" });
                } else {
                    window.location.href = "login.php";
                }
            });
        });
    </script>

</body>
</html>
