<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

   
    $getUsernameSql = "SELECT username FROM users WHERE id = $id";
    $result = $conn->query($getUsernameSql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $username = $row['username'];

        
        if ($row['type'] == 'admin') {
            echo "<script type='text/javascript'>
                    alert('You can\'t delete an admin from the system.');
                    window.location.href = 'listusers.php';
                  </script>";
            exit;
        } else {
            
            $deleteCommentsSql = "DELETE FROM comments WHERE username = ?";
            $stmt = $conn->prepare($deleteCommentsSql);
            $stmt->bind_param("s", $username);

            if ($stmt->execute()) {
               
                $deleteUserSql = "DELETE FROM users WHERE id = ?";
                $stmt = $conn->prepare($deleteUserSql);
                $stmt->bind_param("i", $id);

                if ($stmt->execute()) {
                    header("Location: listusers.php");
                    exit;
                } else {
                    echo "Error deleting user: " . $conn->error;
                }
            } else {
                echo "Error deleting comments: " . $conn->error;
            }
        }
    } else {
        echo "User not found.";
    }
}
?>
