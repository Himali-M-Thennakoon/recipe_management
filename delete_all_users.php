<?php
include 'config.php';
$sql = "TRUNCATE TABLE users";

if($conn->query($sql) === TRUE) {
    echo "<script>
    alert('All users deleted successfully');
    window.location.href='register.php';</script>";
} else {
    echo "Error deleting users: " . $conn->error;
}
$conn->close();
?>