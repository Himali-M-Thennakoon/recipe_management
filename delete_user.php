<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    
    $checkAdminSql = "SELECT type FROM users WHERE id = $id";
    $result = $conn->query($checkAdminSql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        if ($row['type'] == 'admin') {
           
            echo "<script type='text/javascript'>
                    alert('You cant delete admin from the System.');
                    window.location.href = 'listusers.php';
                  </script>";
            exit;
        } else {
            
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
