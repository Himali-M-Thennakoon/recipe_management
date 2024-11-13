<?php 
include 'config.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="about_contact.css">
    <link rel ="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="footer.css">
    <title>About Us</title>
</head>
<body>
    <!-- Header -->
    <!-- Header -->
    <header class="header">
        <h1 class="logo">About Us </h1>
        <nav class="navbar">
            <a href="view_recipe.php">Home</a>
            <a href="about.php">About</a>
            
        </nav>
        <div class="user-data-div">
            <a href="#" id="user-avatar">
                <!-- Display user avatar or a default avatar if none is available -->
                <img src="<?php echo isset($_SESSION['avatar']) ? htmlspecialchars($_SESSION['avatar']) : 'assets/default-avatar.png'; ?>" alt="useravatar" class="user-avatar">
            </a>
            <span class="user-info"><?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?></span>
        </div>

        <!-- Slide-out Menu -->
        <div id="slide-menu" class="slide-menu">
            <div class="slide-menu-content">
                <img src="<?php echo isset($_SESSION['avatar']) ? htmlspecialchars($_SESSION['avatar']) : 'assets/default-avatar.png'; ?>" alt="useravatar" class="slide-avatar">
                <p class="slide-username"><?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?></p>
                <p class="slide-email"><?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?></p>
                <a href="update_user_by_user.php" class="slide-link">Update Details</a>
                <?php if ($_SESSION['type'] == 'admin'): ?>
                    <a href="admin_dashboard.php" class="slide-link">Dash Board</a>
                <?php endif; ?>
                <?php if (isset($_SESSION['username'])): ?>
                    <a href="logout.php" class="slide-link"><img class="logout-png" src="assets/logout.png" alt="Logout"></a>
                <?php else: ?>
                    <a href="login.php" class="btn login-btn">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- About Us Section -->
    <div class="about-us">
        <h2>About Us</h2>
        <p>Welcome to our recipe management platform! We are dedicated to helping you find and create delicious meals that suit every taste and skill level. Our curated collection of recipes is designed to guide both beginners and expert chefs in their culinary adventures.</p>
    </div>

    <!-- Mission Section -->
    <div class="mission">
        <h3>Our Mission</h3>
        <p>Our mission is to provide easy-to-follow, innovative recipes that inspire home cooks to experiment and enjoy the art of cooking. We believe that great meals start with great ingredients, and we're here to help you find them!</p>
    </div>

    <!-- Vision Section -->
    <div class="vision">
        <h3>Our Vision</h3>
        <p>We envision a world where cooking is accessible to everyone, and every meal brings people together. We aim to be the go-to resource for anyone looking to expand their culinary skills and make every meal special.</p>
    </div>

    <!-- Team Section -->
    <div class="team">
        <h3>Our Team</h3>
        <p>Our team consists of experienced chefs, food enthusiasts, and developers who are passionate about food and technology. Together, we're creating a platform that offers the best recipes from around the world.</p>
    </div>

    
    <script>
    const menuIcon = document.getElementById('menu-icon'); // Assuming there's an icon for menu
    const navbar = document.getElementById('navbar'); // Assuming your navbar has the id 'navbar'
    const userAvatar = document.getElementById('user-avatar'); // Avatar for the user
    const slideMenu = document.getElementById('slide-menu'); // The actual slide menu

    // Toggle navbar on menu icon click (optional, if you have a menu icon for mobile version)
    if (menuIcon) {
        menuIcon.addEventListener('click', function() {
            navbar.classList.toggle('active');
            console.log('Menu icon clicked');
        });
    }

    // Toggle slide menu on user avatar click
    if (userAvatar) {
        userAvatar.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent any default behavior
            event.stopPropagation(); // Prevent the event from bubbling up
            slideMenu.classList.toggle('active'); // Toggle visibility of the slide menu
            console.log('User avatar clicked');
        });
    }

    // Close the slide menu if clicked outside of it
    document.addEventListener('click', function(event) {
        if (slideMenu.classList.contains('active') && !slideMenu.contains(event.target) && event.target !== userAvatar) {
            slideMenu.classList.remove('active'); // Hide the slide menu
            console.log('Clicked outside of the slide menu');
        }
    });

    // Prevent closing when clicking inside the slide menu
    slideMenu.addEventListener('click', function(event) {
        event.stopPropagation(); // Prevent closing when clicking inside the slide menu
    });
</script>
<footer class="footer">
    <div class="footer-content">
        <p>&copy; <?php echo date("Y"); ?> Recipe Hub. All rights reserved.</p>
        <div class="social-links">
            <a href="https://www.facebook.com" target="_blank" class="social-icon"><i class="fa-brands fa-facebook"></i></a>
            <a href="https://twitter.com" target="_blank" class="social-icon"><i class="fa-brands fa-twitter"></i></a>
            <a href="https://www.instagram.com" target="_blank" class="social-icon"><i class="fa-brands fa-instagram"></i></a>
            <a href="https://www.linkedin.com" target="_blank" class="social-icon"><i class="fa-brands fa-linkedin"></i></a>
        </div>
    </div>
</footer>
</body>
</html>
