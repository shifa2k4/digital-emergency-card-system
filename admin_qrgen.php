<?php
include 'qr/qrlib.php';
include('dbconnection.php'); // Database connection
session_start();

// ✅ Check if admin logged in
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
//     echo "<script>alert('Access denied!'); window.location='login.php';</script>";
//     exit();
// }

// ✅ Get user_id from GET
if (!isset($_GET['user_id'])) {
    echo "<p style='color:red;'>User ID not provided!</p>";
    exit();
}

$user_id = intval($_GET['user_id']); // sanitize input

// ✅ Fetch user info
$query = "SELECT username, phone, email FROM users WHERE id = $user_id LIMIT 1";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) == 0) {
    echo "<p style='color:red;'>User not found!</p>";
    exit();
}
$user = mysqli_fetch_assoc($result);

// ✅ Generate QR content (example: profile URL)
$profile_url = "http://localhost/stu_pros_php/MediTrust/admin_profile.php?id=" . $user_id;

// ✅ Folder to store QR images
$path = "qr_images/";
if (!file_exists($path)) {
    mkdir($path, 0777, true);
}

// ✅ File name for this user
$file = $path . "qr_" . $user_id . ".png";

// ✅ Generate QR image
QRcode::png($profile_url, $file, QR_ECLEVEL_L, 6);

// ✅ Check if QR already exists for user
$check = mysqli_query($conn, "SELECT id FROM qr_code WHERE user_id = $user_id");

if (mysqli_num_rows($check) > 0) {
    // Update existing QR
    $update = "UPDATE qr_code 
               SET qr_data = '$profile_url', qr_image_path = '$file', created_at = NOW()
               WHERE user_id = $user_id";
    mysqli_query($conn, $update);
} else {
    // Insert new QR record
    $insert = "INSERT INTO qr_code (user_id, qr_data, qr_image_path)
               VALUES ($user_id, '$profile_url', '$file')";
    mysqli_query($conn, $insert);
}

// ✅ Display QR code
echo "<div style='text-align:center; margin-top:30px;'>";
echo "<h3>QR Code for: ".htmlspecialchars($user['username'])."</h3>";
echo "<img src='$file' style='width:200px; border:1px solid #ddd; padding:5px;'/><br><br>";
echo "<a href='admin_profile.php?id=$user_id' class='btn btn-primary'>View Profile</a>";
echo "</div>";
?>
