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
    <title>About Us</title>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <h1 class="logo">Recipe Details</h1>
        <nav class="navbar">
            <a href="view_recipe.php?">Home</a>
            <a href="about.html">About</a>
            <a href="#contact">Contact</a>
            <?php if (isset($_SESSION['username'])): ?>
                <a href="logout.php" class="btn logout-btn">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn login-btn">Login</a>
            <?php endif; ?>
        </nav>
        <div class="user-data-div">  
            <a href="#">
                <img src="https://img.freepik.com/free-psd/3d-illustration-person-with-long-hair_23-2149436197.jpg" alt="useravatar" class="user-avatar">
            </a>
            <span class="user-info"><?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?></span>
            
            <?php if (isset($_SESSION['username'])): ?>
                <a href="logout.php"><img class="logout-png" src="assets/logout.png" alt=""></a>
            <?php else: ?>
                <a href="login.php" class="btn login-btn">Login</a>
            <?php endif; ?>
            
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
        const menuIcon = document.getElementById('menu-icon');
        const navbar = document.getElementById('navbar');

        menuIcon.addEventListener('click', function() {
            navbar.classList.toggle('active');
        });
    </script>
</body>
</html>
