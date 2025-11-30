<?php
include 'qr/qrlib.php';
include('dbconnection.php');// DB connection

session_start();
$user_id = $_SESSION['user_id'];

// Fetch user + emergency details if needed (JOIN example)
$query = "SELECT u.username, u.phone, u.email, e.id AS emergency_id 
          FROM users u 
          JOIN emergency_details e ON u.id = e.user_id
          WHERE u.id = $user_id LIMIT 1";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

// QR content – profile URL
$profile_url = "http://localhost/stu_pros_php/MediTrust/profile.php?id=" . $user_id;

// Folder for QR images
$path = "qr_images/";
if (!file_exists($path)) { mkdir($path); }

// File name → e.g. qr_5.png
$file = $path . "qr_" . $user_id . ".png";

// Generate QR
QRcode::png($profile_url, $file, QR_ECLEVEL_L, 6);

// Save path in DB
$update = "UPDATE emergency_details SET qr_code_path = '$file' WHERE user_id = $user_id";
mysqli_query($conn, $update);

echo "<h3>✅ Your QR Code</h3>";
echo "<img src='$file' style='width:200px; border:1px solid #ddd; padding:5px;'/>";
echo "<br><a href='profile.php?id=$user_id'>View Profile</a>";
?>
