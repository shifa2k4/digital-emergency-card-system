<?php
include 'qr/qrlib.php';
include('dbconnection.php'); // Database connection
session_start();

// ✅ Check if user logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first!'); window.location='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// ✅ Fetch user info (optional)
$query = "SELECT username, phone, email FROM users WHERE id = $user_id LIMIT 1";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// ✅ Generate QR content (example: profile URL)
$profile_url = "http://localhost/stu_pros_php/MediTrust/profile.php?id=" . $user_id;

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
    if (!mysqli_query($conn, $update)) {
        echo "<p style='color:red;'>Error updating record: " . mysqli_error($conn) . "</p>";
    }
} else {
    // Insert new QR record
    $insert = "INSERT INTO qr_code (user_id, qr_data, qr_image_path)
               VALUES ($user_id, '$profile_url', '$file')";
    if (!mysqli_query($conn, $insert)) {
        echo "<p style='color:red;'>Error inserting record: " . mysqli_error($conn) . "</p>";
    }
}

// ✅ Display QR code to user
echo "<div style='text-align:center; margin-top:30px;'>";
echo "<h3>✅ Your QR Code</h3>";
echo "<img src='$file' style='width:200px; border:1px solid #ddd; padding:5px;'/><br><br>";
echo "<a href='profile.php?id=$user_id' class='btn btn-primary'>View Profile</a>";
echo "</div>";
?>
