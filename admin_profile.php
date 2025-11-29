<?php
// session_start();
include('dbconnection.php');
include 'qr/qrlib.php';

// 1️⃣ Determine user ID from GET (QR scan) or session
$user_id = isset($_GET['id']) ? intval($_GET['id']) : ($_SESSION['admin_id'] ?? null);

if (!$user_id) {
    echo "<p style='color:red;'>User ID not provided!</p>";
    exit();
}

// 2️⃣ Fetch user + emergency details (✅ fixed: username used instead of fullname)
$sql = "SELECT u.username, u.phone, u.email,
               e.dob, e.bloodgroup, e.emergency1, e.emergency2, e.emergency3,
               e.allergies, e.conditions
        FROM users u
        LEFT JOIN emergency_details e ON u.id = e.user_id
        WHERE u.id = ?";

$stmt = $conn->prepare($sql);

// 🧩 Debug safety check
if (!$stmt) {
    die("SQL Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc() ?? [];

// 3️⃣ Build QR content (✅ Works now)
$qrData = "Name: " . ($admin['username'] ?? 'N/A') . "\n" .
          "Phone: " . ($admin['phone'] ?? 'N/A') . "\n" .
          "Email: " . ($admin['email'] ?? 'N/A') . "\n" .
          "DOB: " . ($admin['dob'] ?? 'N/A') . "\n" .
          "Blood Group: " . ($admin['bloodgroup'] ?? 'N/A') . "\n" .
          "Emergency 1: " . ($admin['emergency1'] ?? 'N/A') . "\n" .
          "Emergency 2: " . ($admin['emergency2'] ?? 'N/A') . "\n" .
          "Emergency 3: " . ($admin['emergency3'] ?? 'N/A') . "\n" .
          "Allergies: " . ($admin['allergies'] ?? 'N/A') . "\n" .
          "Medical Conditions: " . ($admin['conditions'] ?? 'N/A');

// 4️⃣ Generate QR code
$qrDir = "qrcodes/";
if (!file_exists($qrDir)) mkdir($qrDir, 0777, true);

$filename = $qrDir . 'user_' . $user_id . '.png';
QRcode::png($qrData, $filename, QR_ECLEVEL_L, 4);

// echo "<h3>QR Code Generated Successfully</h3>";
// echo "<img src='$filename' alt='QR Code'>";
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Profile -ResQme </title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
body { background:#f5f7fa; font-family:'Segoe UI', sans-serif; padding-bottom:2rem; }
.profile-card { background:#fff; border-radius:15px; box-shadow:0 10px 30px rgba(0,0,0,0.1); margin-top:2rem; overflow:hidden; }
.profile-header { background:linear-gradient(to right,#2c7fb8,#457b9d); color:white; padding:2rem; text-align:center; position:relative; }
.profile-header h2 { font-weight:700; margin-bottom:0.5rem; }
.profile-header p { opacity:0.9; }
.profile-body { padding:2rem; }
.info-section h5 { color:#2c7fb8; border-bottom:2px solid #7fcdbb; padding-bottom:0.5rem; margin-bottom:1.5rem; font-weight:600; }
.info-item { margin-bottom:1rem; padding:0.75rem; border-radius:8px; transition:0.3s; }
.info-item:hover { background-color: rgba(127,205,187,0.1); }
.info-item strong { color:#343a40; display:inline-block; width:180px; }
.qr-section { background:#f8f9fa; border-radius:10px; padding:1.5rem; text-align:center; margin:2rem 0; border-left:4px solid #e63946; }
.qr-section h5 { color:#e63946; margin-bottom:1rem; }
.badge-medical { background-color:#e63946; color:white; padding:0.3rem 0.8rem; border-radius:20px; font-size:0.8rem; margin-left:10px; }
@media(max-width:768px){ .info-item strong{ width:140px; } }
</style>
</head>
<body>

<div class="container">
  <div class="profile-card">
    <div class="profile-header">
      <h2><i class="fas fa-user-shield me-2"></i><?= htmlspecialchars($admin['username'] ?? 'User') ?> Profile</h2>
      <p>RESQME - Emergency Information</p>
    </div>

    <div class="profile-body">
      <div class="row">
        <div class="col-md-6">
          <div class="info-section">
            <h5><i class="fas fa-user"></i> Personal Information</h5>
            <div class="info-item"><strong>Full Name:</strong> <?= htmlspecialchars($admin['username'] ?? 'N/A') ?></div>
            <div class="info-item"><strong>Phone:</strong> <?= htmlspecialchars($admin['phone'] ?? 'N/A') ?></div>
            <div class="info-item"><strong>Email:</strong> <?= htmlspecialchars($admin['email'] ?? 'N/A') ?></div>
            <div class="info-item"><strong>Date of Birth:</strong> <?= htmlspecialchars($admin['dob'] ?? 'N/A') ?></div>
            <div class="info-item"><strong>Blood Group:</strong> <span class="badge-medical"><?= htmlspecialchars($admin['bloodgroup'] ?? 'N/A') ?></span></div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="info-section">
            <h5><i class="fas fa-ambulance"></i> Emergency Information</h5>
            <div class="info-item"><strong>Emergency Contact 1:</strong> <?= htmlspecialchars($admin['emergency1'] ?? 'N/A') ?></div>
            <div class="info-item"><strong>Emergency Contact 2:</strong> <?= htmlspecialchars($admin['emergency2'] ?? 'N/A') ?></div>
            <div class="info-item"><strong>Emergency Contact 3:</strong> <?= htmlspecialchars($admin['emergency3'] ?? 'N/A') ?></div>
            <div class="info-item"><strong>Allergies:</strong> <?= htmlspecialchars($admin['allergies'] ?? 'N/A') ?></div>
            <div class="info-item"><strong>Medical Conditions:</strong> <?= htmlspecialchars($admin['conditions'] ?? 'N/A') ?></div>
          </div>
        </div>
      </div>

      <div class="qr-section">
        <h5><i class="fas fa-qrcode"></i> Emergency QR Code</h5>
        <p class="text-muted mb-3">Scan this QR code to view all emergency info</p>
        <img src="<?= $filename ?>" alt="QR Code" class="img-fluid mt-3" style="max-width:200px; border:5px solid white; border-radius:10px; box-shadow:0 5px 15px rgba(0,0,0,0.1);">
      

        <center>  
        <a href="admin dashboard.php" class="btn btn-home">
          <i class="fas fa-home me-2"></i> Back to Home
        </a>
      </center>
      </div>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
