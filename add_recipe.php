<?php
$conn = new mysqli('localhost', 'root', '', 'recipe_management');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch categories from the database (optional step if dynamic fetching is needed)
$category_query = "SELECT * FROM categories";  
$category_result = $conn->query($category_query);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $ingredients = mysqli_real_escape_string($conn, $_POST['ingredients']);
    $video = mysqli_real_escape_string($conn, $_POST['video']);
    $difficulty = mysqli_real_escape_string($conn, $_POST['difficulty']);
    
 

    $categories = isset($_POST['category']) ? implode(',', $_POST['category']) : '';
    $tags = mysqli_real_escape_string($conn, $_POST['tags']);


    $target_dir = "uploads/";
    $image = $_FILES['image']['name'];
    $target_file = $target_dir . basename($image);

    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $valid_extensions = array("jpg", "jpeg", "png", "gif");

    if (in_array($imageFileType, $valid_extensions)) {
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $sql = "INSERT INTO recipes (title, description, ingredients, image, video, difficulty, category,tags) 
                    VALUES ('$title', '$description', '$ingredients', '$image', '$video', '$difficulty', '$categories','$tags')";

            if ($conn->query($sql) === TRUE) {
                echo "<script type='text/javascript'>
                alert('New recipe added successfully!');
                window.location.href = 'add_recipe.php';
              </script>";
                exit;
            } else {
                echo "<script type='text/javascript'>
                alert('Failed to add recipe! " . $conn->error . "');
                window.location.href = 'add_recipe.php';
              </script>";
                exit;
            }
        } else {
            echo "<script type='text/javascript'>
                alert('Sorry, there was an error uploading your image.');
                window.location.href = 'add_recipe.php';
              </script>";
            exit;
        }
    } else {
        echo "<script type='text/javascript'>
                alert('Invalid file format. Please upload JPG, JPEG, PNG, or GIF files.');
                window.location.href = 'add_recipe.php';
              </script>";
        exit;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Recipe | Recipe Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles-add.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</head>
<body>
    <div class="container">
        <div class="login-box">
            <div class="left-box">
                <div class="logo">
                    <h2>Add a New Recipe</h2>
                </div>
                <form action="add_recipe.php" method="POST" enctype="multipart/form-data">
                    <input type="text" name="title" placeholder="Recipe Title" required>
                    <textarea name="description" placeholder="Recipe Description" rows="4" required></textarea>
                    <textarea name="ingredients" placeholder="Recipe Ingredients" rows="4" required></textarea>
                    <input type="file" name="image" accept="image/*" required> <!-- New image input -->
                    <input type="text" name="video" placeholder="Video Link" required> <!-- New video link input -->
                    <input type="text" name="difficulty" placeholder="Difficulty Level (1 to 5)" required>

                    
                    <!-- Multi-Select Dropdown -->
                    <!-- Multi-Select Dropdown for Categories -->
                    <div class="form-group">
                        <label for="category">Select Category:</label>
                        <select name="category" id="category" class="form-control" required>
                            <option value="">Select a Category</option>
                            <option value="Breakfast">Breakfast</option>
                            <option value="Lunch">Lunch</option>
                            <option value="Dinner">Dinner</option>
                            <option value="Dessert">Dessert</option>
                            <option value="Snack">Snack</option>
                            <option value="Salad">Salad</option>
                            <option value="Beverage">Beverage</option>
                            <option value="Vegan">Vegan</option>
                        </select>
                    </div>
                    <textarea name="tags" placeholder="tags(hit enter to separate)" rows="4" required></textarea>
                    




                    <!-- Submit and Dashboard buttons -->
                    <button type="submit">Add Recipe</button>
                    <button type="button" onclick="window.location.href='admin_dashboard.php'">Go to Dashboard</button>
                </form>
            </div>

            <div class="right-box">
                <img src="./assets/bowl.jpg" alt="Bowl of Salad">
            </div>
        </div>
    </div>
</body>
</html>
