<?php

$conn = new mysqli('localhost', 'root', '', 'recipe_management');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['id'])) {
    $id = $_POST['id'];

   
    $sql = "DELETE FROM recipes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Recipe deleted successfully!";
        header("Location: list_recipe2.php"); 
        exit;
    } else {
        echo "Error deleting recipe: " . $conn->error;
    }
}

$conn->close();
?>
