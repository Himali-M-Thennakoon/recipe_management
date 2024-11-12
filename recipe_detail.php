<?php
include 'config.php';
session_start();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the recipe ID from the URL parameter
if (isset($_GET['id'])) {
    $recipe_id = $_GET['id'];

    // Query to fetch recipe details from the database
    $sql = "SELECT title, description, image, ingredients FROM recipes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $recipe_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if recipe is found
    if ($result->num_rows > 0) {
        $recipe = $result->fetch_assoc();
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
    $rating = (int) $_POST['rating']; // Capture the rating

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
            <a href="#contact">Contact</a>
            
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

                <button type="submit">Submit Comment</button>
            </form>
        <?php else: ?>
            <p>Please <a href="login.php">login</a> to leave a comment.</p>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>

        <div class="approved-comments">
            <?php if ($comments_result->num_rows > 0): ?>
                <?php while ($comment = $comments_result->fetch_assoc()): ?>
                    <div class="comment">
                        <p><strong><?php echo htmlspecialchars($comment['username']); ?>:</strong> <?php echo htmlspecialchars($comment['comment']); ?></p>
                        <div class="star-rating">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <span class="fa fa-star <?php echo $i <= $comment['rating'] ? 'checked' : ''; ?>"></span>
                            <?php endfor; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No approved comments yet.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
