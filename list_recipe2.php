<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'recipe_management');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch recipe details
$sql = "SELECT id, title, description, image FROM recipes";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="list.css">
        <title>Recipe List</title>
        <script>
            // Function to confirm recipe deletion
            function confirmDelete() {
                return confirm('Are you sure you want to delete this recipe? This action cannot be undone.');
            }
        </script>
    </head>
    <body>
    <header class="header">
            <a href="#" class="logo">Delete Recipes</a>
            <i class="fa-solid fa-bars" id="menu-icon"></i>
            <nav class="navbar">
            <a href="admin_dashboard.php" class="active">Dash Board</a>
                
            </nav>
        </header>
        <div class="cards-container" id="cards-container">
            <?php
            // Display the recipes if any are found
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '
                    <div class="card">
                        <img src="uploads/' . $row['image'] . '" class="card-img" alt="' . $row['title'] . '" />
                        <div class="card-body">
                            <h5 class="card-title">' . $row['title'] . '</h5>
                            <p class="card-text">' . substr($row['description'], 0, 50) . '...</p>
                            
                            <!-- View Recipe Button -->
                            <form action="recipe_detail.php" method="GET" style="display:inline-block;">
                                <input type="hidden" name="id" value="' . $row['id'] . '">
                                <button type="submit" class="btn btn-info">View</button>
                            </form>
                            
                            <!-- Delete Recipe Button with Confirmation -->
                            <form action="delete_recipe.php" method="POST" style="display:inline-block;" onsubmit="return confirmDelete()">
                                <input type="hidden" name="id" value="' . $row['id'] . '">
                                <button type="submit" class="btn btn-primary-delete">Delete</button>
                            </form>
                        </div>
                    </div>';
                }
            } else {
                echo '<p>No recipes found.</p>';
            }
            $conn->close();
            ?>
        </div>
    </body>
</html>
