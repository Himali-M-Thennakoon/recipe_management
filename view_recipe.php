<?php
include 'config.php';
session_start();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Search feature
$searchQuery = "";
$searchQuery = "";
$sql = "SELECT id, title, description, image FROM recipes";

if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    
    
    $sql .= " WHERE title LIKE ? OR tags LIKE ? OR ingredients LIKE ? OR category LIKE ?";
    
    $stmt = $conn->prepare($sql);
    
   
    $searchParam = "%" . $searchQuery . "%";
    $stmt->bind_param("ssss", $searchParam, $searchParam, $searchParam, $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($sql);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="view_recipe.css">
    <link rel ="stylesheet" href="navbar.css">
    <title>Recipes</title>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <a href="#" class="logo">Recipes</a>
        <i class="fa-solid fa-bars" id="menu-icon"></i>
        <nav class="navbar" id="navbar">
            <a href="#Home" class="active">Home</a>
            <a href="about.php">About</a>
            <a href="#contact">Contact</a>
            
            <!-- Search Form -->
            <form action="" method="GET" class="search-form">
                <input type="text" name="search" placeholder="Search recipes..." value="<?php echo htmlspecialchars($searchQuery); ?>" />
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>
        </nav>
        
        <!-- User Info Section -->
        <div class="user-data-div">
            <a href="#" id="user-avatar">
                <img src="https://img.freepik.com/free-psd/3d-illustration-person-with-long-hair_23-2149436197.jpg" alt="useravatar" class="user-avatar">
            </a>
            <span class="user-info"><?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?></span>
            
            
        </div>

        <!-- Slide-out Menu (Initially Hidden) -->
        <div id="slide-menu" class="slide-menu">
            <div class="slide-menu-content">
                <img src="https://img.freepik.com/free-psd/3d-illustration-person-with-long-hair_23-2149436197.jpg" alt="useravatar" class="slide-avatar">
                <p class="slide-username"><?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?></p>
                <p class="slide-username"><?php echo htmlspecialchars($_SESSION['email']?? '');?></p>

                <a href="update_details.php" class="slide-link">Update Details</a>
                
                <?php if (isset($_SESSION['username'])): ?>
                <a href="logout.php" class="slide-link"><img class="logout-png" src="assets/logout.png" alt="Logout"></a>
            <?php else: ?>
                <a href="login.php" class="btn login-btn">Login</a>
            <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Slideshow Banner Section -->
    <div class="banner">
        <div class="slideshow-container">
            <div class="mySlides fade">
                <img src="assets/banner1.png" alt="Delicious meal image">
                <div class="text">
                    <p>Unleash your inner chef with our expert recipe guidance, turning everyday meals into extraordinary creations.</p>
                    <a href="#cards-container"><button class="slide-btn">Read More</button></a>
                </div>
            </div>
            <div class="mySlides fade">
                <img src="assets/banner2.png" alt="Exquisite dish">
                <div class="text">
                    <p>From beginners to pros, our curated recipes will inspire you to master the art of cooking delicious dishes.</p>
                    <a href="#cards-container"><button class="slide-btn">Read More</button></a>
                </div>
            </div>
            <div class="mySlides fade">
                <img src="assets/banner3.png" alt="Perfectly cooked meal">
                <div class="text">
                    <p>Bring your kitchen to life with our step-by-step recipes, designed to make every meal a flavorful experience.</p>
                    <a href="#cards-container"><button class="slide-btn">Read More</button></a>
                </div>
            </div>
            <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
            <a class="next" onclick="plusSlides(1)">&#10095;</a>
        </div>
        <div style="text-align:center">
            <span class="dot" onclick="currentSlide(1)"></span>
            <span class="dot" onclick="currentSlide(2)"></span>
            <span class="dot" onclick="currentSlide(3)"></span>
        </div>
    </div>

    <!-- Recipe Cards -->
    <div class="cards-container" id="cards-container">
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '
                <div class="card">
                    <a href="recipe_detail.php?id=' . $row['id'] . '" class="recipe-link">
                        <img src="uploads/' . $row['image'] . '" class="card-img" alt="' . htmlspecialchars($row['title']) . '">
                        <div class="card-body">
                            <h5 class="card-title">' . htmlspecialchars($row['title']) . '</h5>
                            <p class="card-text">' . htmlspecialchars(substr($row['description'], 0, 50)) . '...</p>
                        </div>
                    </a>
                </div>';
            }
        } else {
            echo '<p>No recipes found.</p>';
        }
        $conn->close();
        ?>
    </div>

    <!-- Slideshow Script -->
    <script>
        let slideIndex = 0;
        showSlides();
        function showSlides() {
            let i;
            let slides = document.getElementsByClassName("mySlides");
            let dots = document.getElementsByClassName("dot");
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";  
            }
            slideIndex++;
            if (slideIndex > slides.length) {slideIndex = 1}    
            for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
            }
            slides[slideIndex-1].style.display = "block";  
            dots[slideIndex-1].className += " active";
            setTimeout(showSlides, 2000);
        }
    </script>

    <script>
        const menuIcon = document.getElementById('menu-icon');
const navbar = document.getElementById('navbar');
const userAvatar = document.getElementById('user-avatar');
const slideMenu = document.getElementById('slide-menu');

// Toggle navbar on menu icon click
menuIcon.addEventListener('click', function() {
    navbar.classList.toggle('active');
    console.log('Menu icon clicked');
});

// Toggle slide menu on user avatar click
userAvatar.addEventListener('click', function(event) {
    event.preventDefault();
    event.stopPropagation(); // Prevent the click from bubbling up to the document
    slideMenu.classList.toggle('active');
    console.log('User avatar clicked');
});

// Prevent closing when clicking inside the slide menu
slideMenu.addEventListener('click', function(event) {
    event.stopPropagation();
});

// Close the slide menu when clicking outside of it
document.addEventListener('click', function(e) {
    if (slideMenu.classList.contains('active')) {
        slideMenu.classList.remove('active');
        console.log('Clicked outside of the slide menu');
    }
});


    </script>
</body>
</html>
