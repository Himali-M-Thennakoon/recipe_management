<?php
include 'config.php';
session_start();

if (!isset($_SESSION['username']) || $_SESSION['type'] !== 'admin') {
    header('Location: login.php');
    exit();
}

if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $comment_id = (int)$_GET['id'];

    if ($action === 'approve') {
        $sql = "UPDATE comments SET status = 'approved' WHERE id = ?";
    } elseif ($action === 'delete') {
        $sql = "DELETE FROM comments WHERE id = ?";
    } else {
        header('Location: admin_dashboard.php');
        exit();
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $comment_id);
    $stmt->execute();
}


header('Location: admin_dashboard.php#comment-management');
exit();
?>
