<?php
session_start(); 


include 'config.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

  
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

       
        if (password_verify($password, $user['password'])) {
            
            $_SESSION['username'] = $user['username'];
            $_SESSION['type'] = $user['type'];

        
            if ($user['type'] == 'admin') {
                header('Location: admin_dashboard.php');
                exit();
            } else {
                header("Location: view_recipe.php");
                exit();
            }
        } else {
            $error_message = "Incorrect password!";
        }
    } else {
        $error_message = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Management System</title>
    <link rel="stylesheet" href="login_register.css">
</head>
<body>
    <div class="container">
        <div class="login-box">
            <div class="left-box">
                <div class="logo">
                    <h2>Recipe Management System</h2>
                </div>
                <form action="" method="POST">
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit">LOGIN</button>
                </form>
                <?php if (isset($error_message)): ?>
                    <p style="color: red;"><?= $error_message ?></p>
                <?php endif; ?>
              
            </div>
            <div class="right-box">
                <img src="./assets/bowl.jpg" alt="Bowl of Salad">
            </div>
        </div>
    </div>
</body>
</html>
