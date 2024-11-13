<?php
include 'config.php';
session_start();

if (!isset($_SESSION['username']) || $_SESSION['type'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Get total counts for recipes, users, and pending comments
$recipe_count_query = "SELECT COUNT(*) as total_recipes FROM recipes";
$recipe_result = $conn->query($recipe_count_query);
$recipe_count = ($recipe_result && $recipe_result->num_rows > 0) ? $recipe_result->fetch_assoc()['total_recipes'] : 0;

$user_count_query = "SELECT COUNT(*) as total_users FROM users";
$user_result = $conn->query($user_count_query);
$user_count = ($user_result && $user_result->num_rows > 0) ? $user_result->fetch_assoc()['total_users'] : 0;

$comment_count_query = "SELECT COUNT(*) as total_comments FROM comments WHERE status = 'pending'";
$comment_result = $conn->query($comment_count_query);
$comment_count = ($comment_result && $comment_result->num_rows > 0) ? $comment_result->fetch_assoc()['total_comments'] : 0;


$pending_comments_query = "SELECT id, username, comment, rating, recipeid FROM comments WHERE status = 'pending'";
$pending_comments_result = $conn->query($pending_comments_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles-dashboard.css">

    <script>
    function showSection(section) {
        document.querySelectorAll('.section').forEach(sec => sec.style.display = 'none');
        document.getElementById(section).style.display = 'block';
    }

   
    window.onload = function() {
        const hash = window.location.hash.substring(1);
        if (hash) {
            showSection(hash); 
        } else {
            showSection('dashboard');  
        }
    }

    function manageComment(action, commentId) {
        if (confirm(`Are you sure you want to ${action} this comment?`)) {
            window.location.href = `manage_comment.php?action=${action}&id=${commentId}`;
        }
    }
</script>

</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <nav class="sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="#" onclick="showSection('dashboard')">Dashboard</a></li>
                <li><a href="#" onclick="showSection('recipe-management')">Manage Recipes</a></li>
                <li><a href="#" onclick="showSection('user-management')">Manage Users</a></li>
                <li><a href="#" onclick="showSection('comment-management')">Manage Comments</a></li>
                <li><a href="#" onclick="showSection('settings')">Settings</a></li>
            </ul>
            <a href="index.html" class="btn logout-btn">Logout</a>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <header class="header">
                <h1>Welcome, Admin <?= htmlspecialchars($_SESSION['username']); ?>!</h1>
            </header>

            <!-- Dashboard Section -->
            <section class="section" id="dashboard">
                <p>Use the sidebar to manage recipes and users.</p>
                <div class="card-grid">
                    <div class="card">
                        <h3>Total Recipes</h3>
                        <p>Total: <?= $recipe_count; ?> recipes</p>
                        <button onclick="showSection('recipe-management')">Manage Recipes</button>
                    </div>
                    <div class="card">
                        <h3>Total Users</h3>
                        <p>Total: <?= $user_count; ?> users</p>
                        <button onclick="showSection('user-management')">Manage Users</button>
                    </div>
                    <div class="card">
                        <h3>Pending Comments</h3>
                        <p>Total: <?= $comment_count; ?> comments</p>
                        <button onclick="showSection('comment-management')">Manage Comments</button>
                    </div>
                </div>
            </section>

            <!-- Recipe Management Section -->
            <section class="section" id="recipe-management" style="display: none;">
                <h2>Recipe Management</h2>
                <div class="card-grid">
                    <div class="card">
                        <h3>Add Recipe</h3>
                        <p>create a new recipe</p>
                        <button onclick="window.location.href='add_recipe.php'">Add Recipe</button>
                    </div>
                    <div class="card">
                        <h3>Update Recipe</h3>
                        <p>Edit an existing recipe.</p>
                        <button onclick="window.location.href='listrecipe.php'">Update Recipe</button>
                    </div>
                    <div class="card">
                        <h3>Delete Recipe</h3>
                        <p>Remove a recipe from the system.</p>
                        <button onclick="window.location.href='list_recipe2.php'">Delete Recipe</button>
                    </div>
                </div>
            </section>

            <!-- User Management Section -->
            <section class="section" id="user-management" style="display: none;">
                <h2>User Management</h2>
                <div class="card-grid">
                    <div class="card">
                        <h3>Add User</h3>
                        <p>Create a new user account.</p>
                        <button onclick="window.location.href='register.php'">Add User</button>
                    </div>
                    <div class="card">
                        <h3>Update or Delete User</h3>
                        <p>Edit user details or roles.</p>
                        <button onclick="window.location.href='listusers.php'">Update User</button>
                    </div>
                </div>
            </section>

            <!-- Comment Management Section -->
            <section class="section" id="comment-management" style="display: none;">
                    <h2>Comment Management</h2>
                    <?php if ($pending_comments_result && $pending_comments_result->num_rows > 0): ?>
                        <div class="table-container">
                            <table class="comment-table">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Comment</th>
                                        <th>Rating</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($comment = $pending_comments_result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($comment['username']); ?></td>
                                            <td><?= htmlspecialchars($comment['comment']); ?></td>
                                            <td><?= $comment['rating']; ?> / 5</td>
                                            <td>
                                                <button class="approve-btn" onclick="manageComment('approve', <?= $comment['id']; ?>)">Approve</button>
                                                <button class="delete-btn" onclick="manageComment('delete', <?= $comment['id']; ?>)">Delete</button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                <p>No pending comments to display.</p>
                <?php endif; ?>
            </section>

            <!-- Settings Section -->
            <section class="section" id="settings" style="display: none;">
                <h2>Settings</h2>
                <div class="card-grid">
                    <div class="card">
                        <h3>Delete All Recipes</h3>
                        <button onclick="confirmDelete('delete_all_recipes.php', 'Are you sure you want to delete all recipes?')">Delete All Recipes</button>
                    </div>
                    <div class="card">
                        <h3>Delete All Users</h3>
                        <button onclick="confirmDelete('delete_all_users.php', 'Are you sure you want to delete all users?')">Delete All Users</button>
                    </div>
                </div>
            </section>

        </main>
    </div>
</body>
</html>
