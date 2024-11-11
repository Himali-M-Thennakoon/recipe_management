<?php

$conn = new mysqli('localhost', 'root', '', 'recipe_management');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $ingredients = mysqli_real_escape_string($conn, $_POST['ingredients']);
    
    

    $target_dir = "uploads/";  
    $image = $_FILES['image']['name'];  
    $target_file = $target_dir . basename($image); 


    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $valid_extensions = array("jpg", "jpeg", "png", "gif");
    if (in_array($imageFileType, $valid_extensions)) {
       
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            
            $sql = "INSERT INTO recipes (title, description, ingredients, image) 
                    VALUES ('$title', '$description', '$ingredients', '$image')";

            if ($conn->query($sql) === TRUE) {
               
                echo "<script type='text/javascript'>
                alert('New recipe added successfully!');
                window.location.href = 'add_recipe.php';
              </script>";
              exit;
        exit;
            } else {
                
                echo "<script type='text/javascript'>
                alert('Failled to add recipe!.$sql .<br>.$conn->error');
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
                    <button type="submit">Add Recipe</button>
                    
                    <button type="button" onclick="window.location.href='admin_dashboard.php?'">Go to Dashboard</button>
                    
                </form>
                

                
               
            </div>
            <div class="right-box">
                <img src="./assets/bowl.jpg" alt="Bowl of Salad">
            </div>
        </div>
    </div>
</body>
</html>
