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
    } else {
        echo "Recipe not found";
        exit;
    }
}

// Update the recipe
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $ingredients = mysqli_real_escape_string($conn, $_POST['ingredients']);

    $image = $recipe['image']; // Default to the existing image

    // Check if a new image is uploaded
    if ($_FILES['image']['name']) {
        $target_dir = "uploads/";
        $image = $_FILES['image']['name'];
        $target_file = $target_dir . basename($image);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $valid_extensions = array("jpg", "jpeg", "png", "gif");

        if (in_array($imageFileType, $valid_extensions)) {
            move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
        } else {
            echo "Invalid file format. Please upload JPG, JPEG, PNG, or GIF files.";
        }
    }

    // Update recipe query
    $sql = "UPDATE recipes SET title='$title', description='$description', ingredients='$ingredients', image='$image' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Recipe updated successfully";
        header("Location: listrecipe.php"); // Redirect back to the recipe list
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
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
                    <button type="submit">Update Recipe</button>
                </form>
            </div>
            <div class="right-box">
                <img src="uploads/<?php echo $recipe['image']; ?>" alt="Current Recipe Image">
            </div>
        </div>
    </div>
</body>
</html>
