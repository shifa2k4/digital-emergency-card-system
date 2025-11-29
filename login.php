<?php
session_start();
include('dbconnection.php'); // DB connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['pswd']);

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Remove hidden spaces from DB password too
        $db_password = trim($row['password']);

        if ($password === $db_password) {
            $_SESSION['user_id']  = $row['id'];
            $_SESSION['username'] = $row['username'];

            header("Location: userhome.php");
            exit();
        } else {
            echo "<script>alert('Incorrect password!'); window.location='login.html';</script>";
        }
    } else {
        echo "<script>alert('No such user found!'); window.location='login.html';</script>";
    }
}
?>
