<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "DELETE FROM users WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: listusers.php");
        exit;
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}


?>