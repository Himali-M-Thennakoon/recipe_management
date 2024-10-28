<?php
include 'config.php';
if (isset($_GET['id'])) {
    //fetch the user details
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM users WHERE id = $id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "User not found";
        exit;
    }
}
//update the user details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $password = $_POST['password'];
    $id = $_POST['id'];

    
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET username='$username', email='$email', type='$type', password='$hashedPassword' WHERE id=$id";
    } else {
        
        $sql = "UPDATE users SET username='$username', email='$email', type='$type' WHERE id=$id";
    }

    if ($conn->query($sql) === TRUE) {
        echo "User updated successfully";
        header("Location: listusers.php");
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
    <title>Edit User | Recipe Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles-add.css">
</head>
<body>
    <div class="container">
        <h2>Edit User</h2>
        <form action="update_user.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $user['username']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo $user['email']; ?>" required>
            </div>
            <div class="form-group">
                <label for="type">Type</label>
                <select name="type" class="form-control" required>
                    <option value="user" <?php if ($user['type'] == 'user') echo 'selected'; ?>>User</option>
                    <option value="admin" <?php if ($user['type'] == 'admin') echo 'selected'; ?>>Admin</option>
                </select>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control">
            </div>
            <a href="listusers.php" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update User</button>
        </form>
    </div>
</body>
</html>

