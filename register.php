<?php
session_start();
include 'config.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
  

  
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      
        echo "<script>alert('this Username is unvailable use Another.'); window.location.href='register.php';</script>";
    } else {
        $userType = 'user';

    
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, type) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssss', $username, $email, $hashedPassword, $userType);

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
                    <h2>MixBowls</h2>
                </div>
                <form action="register.php" method="POST">
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    
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
