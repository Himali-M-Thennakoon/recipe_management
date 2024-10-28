<?php
include 'config.php';
$sql = "SELECT id, username, email, type FROM users";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List | Recipe Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="listusers.css">
    <script>
        function confirmDelete() {
            return confirm('Are you sure you want to delete this user? This action cannot be undone.');
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>User List</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>".$row['username']."</td>
                            <td>".$row['email']."</td>
                            <td>".$row['type']."</td>
                            <td>
                                <a href='update_user.php?id=".$row['id']."' class='edit-btn'>Edit</a>
                                <a href='delete_user.php?id=".$row['id']."' class='delete-btn' onclick='return confirmDelete();'>Delete</a>
                            </td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No users found</td></tr>";
            }
            ?>
            </tbody>
        </table>
        <a href='admin_dashboard.php?' class='edit-btn'>Dashboard</a>
    </div>
</body>
</html>
