<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Check if the user is an admin
    $checkAdminSql = "SELECT type FROM users WHERE id = $id";
    $result = $conn->query($checkAdminSql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        if ($row['type'] == 'admin') {
            // Admin deletion error message
            echo "<script type='text/javascript'>
                    alert('You cant delete admin from the System.');
                    window.location.href = 'listusers.php';
                  </script>";
            exit;
        } else {
            // Proceed with deletion for non-admin users
            $sql = "DELETE FROM users WHERE id = $id";
            
            if ($conn->query($sql) === TRUE) {
                header("Location: listusers.php");
                exit;
            } else {
                echo "Error deleting record: " . $conn->error;
            }
        }
    } else {
        echo "User not found.";
    }
}
?>
