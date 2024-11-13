<?php
session_start();
include 'config.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Image Upload
    $target_dir = "uploads/";
    $image = $_FILES['image']['name'];
    $target_file = $target_dir . basename($image);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $valid_extensions = array("jpg", "jpeg", "png", "gif");

    // Check if the image is a valid file type
    if (in_array($imageFileType, $valid_extensions)) {
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $avatar_path = $target_file;
        } else {
            echo "<script>alert('Error uploading avatar. Please try again.'); window.location.href='register.php';</script>";
            exit;
        }
    } else {
        echo "<script>alert('Invalid file type. Please upload a JPG, JPEG, PNG, or GIF image.'); window.location.href='register.php';</script>";
        exit;
    }

    // Check for existing username
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Username is already taken. Please use another.'); window.location.href='register.php';</script>";
    } else {
        $userType = 'user';
        
        // Insert user data with avatar path
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, avatar, type) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('sssss', $username, $email, $hashedPassword, $avatar_path, $userType);

        if ($stmt->execute()) {
            echo "<script>alert('Registration successful! You can now log in.'); window.location.href='login.php';</script>";
        } else {
            echo "<script>alert('Registration failed. Please try again.'); window.location.href='register.php';</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MixBowls Registration</title>
    <link rel="stylesheet" href="login_register.css">
</head>
<body>
    <div class="container">
        <div class="login-box">
            <div class="left-box">
                <div class="logo">
                    <h2>Register User</h2>
                </div>
                <form action="register.php" method="POST" enctype="multipart/form-data">
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <input type="file" name="image" accept="image/*" required>
                    <button type="submit">REGISTER</button>
                </form>
                <p>Already have an account? <a href="login.php">Login here</a></p>
            </div>
            <div class="right-box">
                <img src="assets/bowl.jpg" alt="Bowl of Salad">
            </div>
        </div>
    </div>
</body>
</html>
