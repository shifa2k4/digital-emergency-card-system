<?php
session_start();
include('dbconnection.php');
include 'qr/qrlib.php';

// ✅ Check login
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first!'); window.location='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// ✅ Fetch user + emergency details
$sql = "SELECT u.username, u.phone, u.email,
               e.dob, e.bloodgroup, e.emergency1, e.emergency2, e.emergency3,
               e.allergies, e.conditions
        FROM users u
        LEFT JOIN emergency_details e ON u.id = e.user_id
        WHERE u.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Build QR content
$qrData = "Name: " . ($user['username'] ?? 'N/A') . "\n" .
          "Phone: " . ($user['phone'] ?? 'N/A') . "\n" .
          "Email: " . ($user['email'] ?? 'N/A') . "\n" .
          "DOB: " . ($user['dob'] ?? 'N/A') . "\n" .
          "Blood Group: " . ($user['bloodgroup'] ?? 'N/A') . "\n" .
          "Emergency 1: " . ($user['emergency1'] ?? 'N/A') . "\n" .
          "Emergency 2: " . ($user['emergency2'] ?? 'N/A') . "\n" .
          "Emergency 3: " . ($user['emergency3'] ?? 'N/A') . "\n" .
          "Allergies: " . ($user['allergies'] ?? 'N/A') . "\n" .
          "Medical Conditions: " . ($user['conditions'] ?? 'N/A');

// Generate QR

$qrDir = "qrcodes/";
if (!file_exists($qrDir)) {
    mkdir($qrDir);
}
$filename = $qrDir . 'user_' . $user_id . '.png';
QRcode::png($qrData, $filename, QR_ECLEVEL_L, 5);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile - Insta Med Card</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow-lg p-4">
    <h2 class="text-center mb-4">My Profile</h2>

    <div class="row">
      <div class="col-md-6">
        <h5>👤 Personal Info</h5>
        <p><strong>Full Name:</strong> <?= htmlspecialchars($user['username']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Date of Birth:</strong> <?= htmlspecialchars($user['dob']) ?></p>
        <p><strong>Blood Group:</strong> <?= htmlspecialchars($user['bloodgroup']) ?></p>
      </div>

      <div class="col-md-6">
        <h5>🚑 Emergency Info</h5>
        <p><strong>Emergency Contact 1:</strong> <?= htmlspecialchars($user['emergency1']) ?></p>
        <p><strong>Emergency Contact 2:</strong> <?= htmlspecialchars($user['emergency2']) ?></p>
        <p><strong>Emergency Contact 3:</strong> <?= htmlspecialchars($user['emergency3']) ?></p>
        <p><strong>Allergies:</strong> <?= htmlspecialchars($user['allergies']) ?></p>
        <p><strong>Medical Conditions:</strong> <?= htmlspecialchars($user['conditions']) ?></p>
      </div>
    </div>

    <hr>

    <div class="text-center">
      <h5>📱 My Emergency QR nbCode</h5>
      <img src="<?= $filename ?>" alt="QR Code" class="img-fluid mt-3" style="max-width:200px;">
    </div>

    <div class="text-center mt-4">
      <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
  </div>
</div>

</body>
</html>
