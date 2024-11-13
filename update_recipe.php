<?php
// Include the config file for the database connection
include 'config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Fetch the recipe details
    $sql = "SELECT * FROM recipes WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $recipe = $result->fetch_assoc();
        $recipe_category = $recipe['category'];  // Single category as a string
    } else {
        echo "<script type='text/javascript'>
                alert('Recipe not found.');
                window.location.href = 'update_recipe.php';
              </script>";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $ingredients = mysqli_real_escape_string($conn, $_POST['ingredients']);
    $video = mysqli_real_escape_string($conn, $_POST['video']);
    $difficulty = mysqli_real_escape_string($conn, $_POST['difficulty']);
    $image = $recipe['image'];


    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $tags = mysqli_real_escape_string($conn, $_POST['tags']);

    // Check if a new image file was uploaded
    if ($_FILES['image']['name']) {
        $target_dir = "uploads/";
        $image = $_FILES['image']['name'];
        $target_file = $target_dir . basename($image);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $valid_extensions = array("jpg", "jpeg", "png", "gif");

        if (in_array($imageFileType, $valid_extensions)) {
            move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
        } else {
            echo "<script type='text/javascript'>
                alert('Invalid file format. Please upload JPG, JPEG, PNG, or GIF files.');
                window.location.href = 'update_recipe.php';
              </script>";
            exit;
        }
    }

    // Update the SQL query to include the video, difficulty, and category columns
    $sql = "UPDATE recipes SET title='$title', description='$description', ingredients='$ingredients', 
            image='$image', video='$video', difficulty='$difficulty', category='$category', tags='$tags' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "<script type='text/javascript'>
        alert('Recipe updated successfully!');
        window.location.href = 'listrecipe.php';
      </script>";
        exit;
    } else {
        echo "<script type='text/javascript'>
        alert('Failed to update recipe! " . $conn->error . "');
        window.location.href = 'update_recipe.php';
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
    <title>Update Recipe | Recipe Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles-add.css">
</head>
<body>
    <div class="container">
        <div class="login-box">
            <div class="left-box">
                <div class="logo">
                    <h2>Update Recipe</h2>
                </div>
                <form action="update_recipe.php?id=<?php echo $recipe['id']; ?>" method="POST" enctype="multipart/form-data">
                    <input type="text" name="title" placeholder="Recipe Title" value="<?php echo $recipe['title']; ?>" required>
                    <textarea name="description" placeholder="Recipe Description" rows="4" required><?php echo $recipe['description']; ?></textarea>
                    <textarea name="ingredients" placeholder="Recipe Ingredients" rows="4" required><?php echo $recipe['ingredients']; ?></textarea>
                    <input type="file" name="image" accept="image/*"> <!-- Optional new image -->
                    <input type="text" name="video" placeholder="Video Link" value="<?php echo $recipe['video']; ?>"> <!-- New video link input -->
                    <input type="text" name="difficulty" placeholder="Difficulty Level" value="<?php echo $recipe['difficulty']; ?>" required>

                    <!-- Single-Select Dropdown for Category -->
                    <div class="form-group">
                        <label for="category">Select Category:</label>
                        <select name="category" id="category" class="form-control" required>
                            <option value="">Select a Category</option>
                            <option value="Breakfast" <?php echo ($recipe_category == 'Breakfast') ? 'selected' : ''; ?>>Breakfast</option>
                            <option value="Lunch" <?php echo ($recipe_category == 'Lunch') ? 'selected' : ''; ?>>Lunch</option>
                            <option value="Dinner" <?php echo ($recipe_category == 'Dinner') ? 'selected' : ''; ?>>Dinner</option>
                            <option value="Dessert" <?php echo ($recipe_category == 'Dessert') ? 'selected' : ''; ?>>Dessert</option>
                            <option value="Snack" <?php echo ($recipe_category == 'Snack') ? 'selected' : ''; ?>>Snack</option>
                            <option value="Salad" <?php echo ($recipe_category == 'Salad') ? 'selected' : ''; ?>>Salad</option>
                            <option value="Beverage" <?php echo ($recipe_category == 'Beverage') ? 'selected' : ''; ?>>Beverage</option>
                            <option value="Vegan" <?php echo ($recipe_category == 'Vegan') ? 'selected' : ''; ?>>Vegan</option>
                        </select>
                    </div>
                    <textarea name="tags" placeholder="tags" rows="4" required><?php echo $recipe['tags']; ?></textarea>
                    <button type="submit">Update Recipe</button>
                    <button type="button" onclick="window.location.href='listrecipe.php'">Go to Recipe List</button>
                </form>
            </div>
            <div class="right-box">
                <img src="uploads/<?php echo $recipe['image']; ?>" alt="Current Recipe Image">
            </div>
        </div>
    </div>
</body>
</html>
