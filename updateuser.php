<?php
include('dbconnection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $sql = "UPDATE users SET username='$username', phone='$phone', email='$email' WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
       echo "<script>alert('✅ User updated successfully!'); window.location='manageuser.php';</script>";
    } else {
        echo "❌ Error updating user: " . $conn->error;
    }

    $conn->close();
}
?>
