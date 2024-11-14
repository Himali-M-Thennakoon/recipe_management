<?php
// Database connection
include 'config.php';
session_start();

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
        <link rel ="stylesheet" href="navbar.css">
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
        <a href="#" class="logo">Recipes</a>
        <i class="fa-solid fa-bars" id="menu-icon"></i>
       
        
        <!-- User Info Section -->
        <div class="user-data-div">
            <a href="#" id="user-avatar">
                <!-- Display user avatar or a default avatar if none is available -->
                <img src="<?php echo isset($_SESSION['avatar']) ? htmlspecialchars($_SESSION['avatar']) : 'assets/default.jpg'; ?>" alt="useravatar" class="user-avatar">
            </a>
            <span class="user-info"><?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?></span>
        </div>

        <!-- Slide-out Menu -->
        <div id="slide-menu" class="slide-menu">
            <div class="slide-menu-content">
                <img src="<?php echo isset($_SESSION['avatar']) ? htmlspecialchars($_SESSION['avatar']) : 'assets/default.jpg'; ?>" alt="useravatar" class="slide-avatar">
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
    </body>
</html>
