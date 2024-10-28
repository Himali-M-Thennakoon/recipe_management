<?php
// Establish connection to the database
$conn = new mysqli('localhost', 'root', '', 'recipe_management');

// Check if connection is successful
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

$conn->close();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport", content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="recipe_detail.css">
        <title><?php echo htmlspecialchars($recipe['title']); ?> - Recipe Details</title>
    </head>
    <body>
        <!-- Header -->
        <header class="header">
            <a href="#" class="logo">Recipe Details</a>
            <i class="fa-solid fa-bars" id="menu-icon"></i>
            <nav class="navbar">
                <a href="index.php">Home</a>
                <a href="#about">About</a>
                <a href="#contact">Contact</a>
            </nav>
        </header>

        <div class="recipe-detail-container">
            <div class="recipe-detail-card">
                <!-- Display Recipe Image -->
                <img src="uploads/<?php echo htmlspecialchars($recipe['image']); ?>" class="recipe-detail-img" alt="<?php echo htmlspecialchars($recipe['title']); ?>" />
                <div class="recipe-detail-body">
                    <!-- Recipe Title -->
                    <h1><?php echo htmlspecialchars($recipe['title']); ?></h1>
                    
                    <!-- Recipe Description -->
                    <p class="recipe-description">
                        <?php echo nl2br(htmlspecialchars($recipe['description'])); ?>
                    </p>
                    
                    <!-- Recipe Ingredients -->
                    <h2>Ingredients:</h2>
                    <p class="recipe-ingredients">
                        <?php echo nl2br(htmlspecialchars($recipe['ingredients'])); ?>
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
