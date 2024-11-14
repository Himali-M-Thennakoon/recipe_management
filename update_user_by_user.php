<?php
session_start();
include('config.php');


$username = $_SESSION['username']; 


$sql = "SELECT * FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

  
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    }else{
        $hashedPassword = $user['password'];
    }

   
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
      
        $avatar_name = $_FILES['avatar']['name'];
        $avatar_tmp = $_FILES['avatar']['tmp_name'];
        $avatar_path = 'uploads/' . basename($avatar_name);
        move_uploaded_file($avatar_tmp, $avatar_path);

    
        $sql = "UPDATE users SET avatar = '$avatar_path', email = '$email', password = '$hashedPassword' WHERE username = '$username'";
    } else {
        
        if (!empty($password)) {
            $sql = "UPDATE users SET email = '$email', password = '$hashedPassword' WHERE username = '$username'";
        } else {
            $sql = "UPDATE users SET email = '$email' WHERE username = '$username'";
        }
    }

    if (mysqli_query($conn, $sql)) {
        header("Location: view_recipe.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="style_user.css">
</head>
<body>
    <div class="profile-update-container">
        <div class="profile-update-card">
            <h2>Update Your Profile</h2>
            <form action="update_user_by_user.php" method="POST" enctype="multipart/form-data">
                <div class="profile-avatar">
                    <!-- Display the current avatar or a default image if not set -->
                    <img src="<?php echo !empty($user['avatar']) ? $user['avatar'] : 'assets/default.jpg'; ?>" alt="Avatar" class="avatar-img">
                    <input type="file" name="avatar" accept="image/*">
                </div>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter new password (leave empty to keep current)">

                <button type="submit">Update Profile</button>
            </form>
        </div>
    </div>
</body>
</html>
