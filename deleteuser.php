<?php
include('dbconnection.php');

// Check if 'id' is provided in URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // sanitize input

    $sql = "DELETE FROM users WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('✅ User deleted successfully!'); window.location='manageuser.php';</script>";
        exit;
    } else {
        echo "<script>alert('❌ Error deleting user: ".$conn->error."'); window.location='manageuser.php';</script>";
    }
} else {
    // If no ID provided
    echo "<script>alert('❌ Invalid request!'); window.location='manageuser.php';</script>";
}
$conn->close();
?>
