<?php
$conn = new mysqli('localhost', 'root', '', 'recipe_management');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, title, description, image FROM recipes";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="view_recipe.css">
    <title>Recipes</title>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <a href="#" class="logo">Recipes</a>
        <i class="fa-solid fa-bars" id="menu-icon"></i>
        <nav class="navbar" id="navbar">
            <a href="#Home" class="active">Home</a>
            <a href="#about">About</a>
            <a href="#contact">Contact</a>
            <a href="index.html" class="btn logout-btn">Logout</a>
        </nav>

    </header>

    <!-- Slideshow -->
    <div class="banner">
        <div class="slideshow-container">
            <div class="mySlides fade">
                <div class="numbertext">1/3</div>
                <img src="assets/banner1.jpg" alt="Delicious meal image">
                <div class="text">
                    <p>Unleash your inner chef with our expert recipe guidance, turning everyday meals into extraordinary creations.</p>
                    <a href="#cards-container"><button class="slide-btn">Read More</button></a>
                </div>
            </div>
            <div class="mySlides fade">
                <div class="numbertext">2/3</div>
                <img src="assets/banner2.jpg" alt="Exquisite dish">
                <div class="text">
                    <p>From beginners to pros, our curated recipes will inspire you to master the art of cooking delicious dishes.</p>
                    <a href="#cards-container"><button class="slide-btn">Read More</button></a>
                </div>
            </div>
            <div class="mySlides fade">
                <div class="numbertext">3/3</div>
                <img src="assets/banner3.jpg" alt="Perfectly cooked meal">
                <div class="text">
                    <p>Bring your kitchen to life with our step-by-step recipes, designed to make every meal a flavorful experience.</p>
                    <a href="#cards-container"><button class="slide-btn">Read More</button></a>
                </div>
            </div>
            <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
            <a class="next" onclick="plusSlides(1)">&#10095;</a>
        </div>
        <br>
        <div style="text-align:center">
            <span class="dot" onclick="currentSlide(1)"></span>
            <span class="dot" onclick="currentSlide(2)"></span>
            <span class="dot" onclick="currentSlide(3)"></span>
        </div>

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
    </div>

    <!-- Recipe Cards -->
    <div class="cards-container" id="cards-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '
                <div class="card">
                    <a href="recipe_detail.php?id=' . $row['id'] . '" class="recipe-link">
                        <img src="uploads/' . $row['image'] . '" class="card-img" alt="' . $row['title'] . '">
                        <div class="card-body">
                            <h5 class="card-title">' . $row['title'] . '</h5>
                            <p class="card-text">' . substr($row['description'], 0, 50) . '...</p>
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
    
    <script>
    
        const menuIcon = document.getElementById('menu-icon');
        const navbar = document.getElementById('navbar');

        menuIcon.addEventListener('click', function() {
            navbar.classList.toggle('active');
        });
    </script>

</body>
</html>
