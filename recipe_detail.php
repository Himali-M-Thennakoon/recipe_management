<?php
include 'config.php';
session_start();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the recipe ID from the URL parameter
if (isset($_GET['id'])) {
    $recipe_id = $_GET['id'];

    
    $sql = "SELECT title, description, image, ingredients, video, difficulty, category , tags FROM recipes WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $recipe_id);
    $stmt->execute();
    $result = $stmt->get_result();

   
    if ($result->num_rows > 0) {
        $recipe = $result->fetch_assoc();
      
        $difficulty = (int)$recipe['difficulty'];
        $difficulty = max(1, min($difficulty, 5)); 

        
        switch ($difficulty) {
            case 1:
                $difficulty_color = "linear-gradient(to right, #4CAF50, #8BC34A)"; 
                break;
            case 2:
                $difficulty_color = "linear-gradient(to right, #8BC34A, #FFEB3B)"; 
                break;
            case 3:
                $difficulty_color = "linear-gradient(to right, #FFEB3B, #FF9800)"; 
                break;
            case 4:
                $difficulty_color = "linear-gradient(to right, #FF9800, #FF5722)"; 
                break;
            case 5:
                $difficulty_color = "linear-gradient(to right, #FF5722, #D32F2F)"; 
                break;
            default:
                $difficulty_color = "linear-gradient(to right, #4CAF50, #8BC34A)"; 
                break;
        }
    } else {
        echo "Recipe not found!";
        exit;
    }
} else {
    echo "Invalid recipe ID!";
    exit;
}

// Insert comment and rating into the database if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $comment = trim($_POST['comment']);
    $rating = (int) $_POST['rating'];

    if (!empty($comment) && $rating >= 1 && $rating <= 5) { // Ensure rating is between 1 and 5
        $comment = htmlspecialchars($comment);

        $sql = "INSERT INTO comments (username, recipeid, comment, rating, status) VALUES (?, ?, ?, ?, 'pending')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sisi", $username, $recipe_id, $comment, $rating);
        $stmt->execute();

        // Redirect to prevent form resubmission
        header("Location: recipe_detail.php?id=" . $recipe_id);
        exit;
    } else {
        $error_message = "Comment and valid rating are required.";
    }
}

// Fetch approved comments along with their ratings
$sql = "SELECT username, comment, rating FROM comments WHERE recipeid = ? AND status = 'approved'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $recipe_id);
$stmt->execute();
$comments_result = $stmt->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="recipe_detail.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="navbar.css">
    <title><?php echo htmlspecialchars($recipe['title']); ?> - Recipe Details</title>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <h1 class="logo">Recipe Details</h1>
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

    <!-- Recipe Details -->
    <div class="recipe-detail-container">
        <h1 class="recipe-title"><?php echo htmlspecialchars($recipe['title']); ?></h1>
        <div class="recipe-image-container">
            <img src="uploads/<?php echo htmlspecialchars($recipe['image']); ?>" class="recipe-detail-img" alt="<?php echo htmlspecialchars($recipe['title']); ?>" />
        </div>
        <div class="recipe-description-section">
            <h2>Description</h2>
            <p><?php echo nl2br(htmlspecialchars($recipe['description'])); ?></p>
        </div>
        <div class="recipe-ingredients-section">
            <h2>Ingredients</h2>
            <ul>
                <?php foreach (explode("\n", $recipe['ingredients']) as $ingredient): ?>
                    <li><?php echo htmlspecialchars($ingredient); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="recipe-description-section">
            <h2>Category</h2>
            <p><?php echo nl2br(htmlspecialchars($recipe['category'])); ?></p>
        </div>
        <div class="recipe-ingredients-section">
            <h2>Tags</h2>
            <ul>
                <?php foreach (explode("\n", $recipe['tags']) as $tags): ?>
                    <li><?php echo htmlspecialchars($tags); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <!-- Recipe Video -->
         <div class="video">
            <?php if (!empty($recipe['video'])): ?>
            <div class="video-section recipe-ingredients-section">
                <h2>Recipe Video</h2>
                <?php 
                if (preg_match('/(youtube\.com|youtu\.be)/', $recipe['video'])):
                    if (strpos($recipe['video'], 'watch?v=') !== false) {
                        $video_url = str_replace("watch?v=", "embed/", $recipe['video']);
                    } elseif (strpos($recipe['video'], 'youtu.be/') !== false) {
                        $video_id = explode("youtu.be/", $recipe['video'])[1];
                        $video_url = "https://www.youtube.com/embed/" . $video_id;
                    } else {
                        $video_url = $recipe['video'];
                    }
                ?>
                    <iframe  src="<?php echo htmlspecialchars($video_url); ?>" 
                        frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                <?php else: ?>
                    <video controls>
                        <source src="uploads/<?php echo htmlspecialchars($recipe['video']); ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Difficulty Bar with Gradient -->
        <div class="difficulty">
            <h3>Difficulty Level</h3>
            <p><?php echo $difficulty; ?>/5</p>
            <div class="difficulty-bar-container">
                <!-- Apply the color gradient dynamically based on difficulty value -->
                <div class="difficulty-bar" style="width: <?php echo $difficulty * 20; ?>%; background: <?php echo $difficulty_color; ?>;"></div>
            </div>
        </div>

    </div>

    <!-- Comments Section -->
    <div class="comments-section">
        <h2>Comments</h2>
        <?php if (isset($_SESSION['username'])): ?>
            <form method="POST" class="comment-form">
                <textarea name="comment" rows="4" placeholder="Add your comment..." required></textarea>
                
                <div class="rating">
                    <input type="radio" name="rating" value="5" id="star5"><label for="star5" class="fa fa-star"></label>
                    <input type="radio" name="rating" value="4" id="star4"><label for="star4" class="fa fa-star"></label>
                    <input type="radio" name="rating" value="3" id="star3"><label for="star3" class="fa fa-star"></label>
                    <input type="radio" name="rating" value="2" id="star2"><label for="star2" class="fa fa-star"></label>
                    <input type="radio" name="rating" value="1" id="star1"><label for="star1" class="fa fa-star"></label>
                </div>
                
                <button type="submit" class="btn submit-btn">Submit Comment</button>
            </form>
            <?php if (!empty($error_message)): ?>
                <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
            <?php endif; ?>
        <?php else: ?>
            <p>You must be logged in to comment.</p>
        <?php endif; ?>

        <div class="approved-comments">
            <?php while ($comment = $comments_result->fetch_assoc()): ?>
                <div class="comment">
                    <p><strong><?php echo htmlspecialchars($comment['username']); ?>:</strong> <?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>
                    <p>Rating: <?php echo str_repeat("⭐", $comment['rating']); ?></p>
                </div>
            <?php endwhile; ?>
        </div>
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
