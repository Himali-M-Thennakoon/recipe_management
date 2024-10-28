<?php
include 'config.php';

$sql = "DELETE * FROM recipes";

if($conn->query($sql) === TRUE) {
    echo "<script>
    alert('All recipes deleted successfully');
    window.location.href='admin_dashboard.php';"
} else {
    echo "Error deleting recipes: " . $conn->error;
}
$conn->close();

?>