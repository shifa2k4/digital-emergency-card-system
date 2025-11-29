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

// ✅ Build QR content
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

// ✅ Generate QR
$qrDir = "qrcodes/";
if (!file_exists($qrDir)) mkdir($qrDir);
$filename = $qrDir . 'user_' . $user_id . '.png';
QRcode::png($qrData, $filename, QR_ECLEVEL_L, 4);  // smaller size
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Profile - RESQME</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
:root {
  --primary: #8624bbff;
  --primary-600: #5a25b8;
  --primary-100: #f3eefe;
  --primary-light:#d5bddf8f;
  --muted: #6e6a72;
  --text: #1f2430;
  --bg: linear-gradient(135deg,#f7f9fb 0%, #eef6fb 100%);
  --card-shadow: 0 14px 36px rgba(25,20,40,0.06);
  --accent: linear-gradient(90deg,#8a2be2,#6b2fd6);
  --radius-lg: 12px;
  --radius-md: 8px;
  --glass: rgba(255,255,255,0.75);
  --ease: 180ms;
  --gap: 14px;
  --max-width: 980px;
}
body{
  margin:0;
  font-family: "Inter",-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial;
  background:var(--bg);
  color:var(--text);
  -webkit-font-smoothing:antialiased;
  -moz-osx-font-smoothing:grayscale;
  line-height:1.45;
  padding:28px 12px 48px;
  display:flex;
  justify-content:center;
}
/* container */
.preview-wrap{ width:100%; max-width:var(--max-width); }

.medical-card {
  background:white;
  border-radius:14px;
  box-shadow:0 6px 20px rgba(0,0,0,0.08);
  overflow:hidden;
  border:1px solid rgba(20,18,35,0.03);
  /* border:none; */
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.medical-card:hover {
  transform:translateY(-6px);
  box-shadow:0 26px 50px rgba(20,18,35,0.08)
  }
.medical-header {
  background: linear-gradient(135deg, var(--primary) 0%, #27033cff 100%);
  color:#fff;
  padding:20px;
  text-align:center;
  position:relative;
}
.medical-header::after {
  content:'';
  position:absolute;
  bottom:-10px;
  left:0;
  width:100%;
  height:20px;
  background:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1200 120' preserveAspectRatio='none'%3E%3Cpath d='M1200 120L0 16.48 0 0 1200 0 1200 120z' fill='%23ffffff'%3E%3C/path%3E%3C/svg%3E");
  background-size: cover;
}
.profile-avatar {
   width:70px;
  height:70px;
  border-radius:50%;
  background: rgba(255,255,255,0.08);
  display:flex;
  align-items:center;
  justify-content:center;
  margin:0 auto 0.8rem;
  border:3px solid #ffffffba;
  box-shadow:0 3px 8px rgba(0,0,0,0.08); 
} 
.profile-avatar i { font-size:1.8rem; color: white; }
.info-section { margin-bottom:1.5rem; }
.section-title {
  color: var(--primary);
  border-bottom:2px solid var(--primary-light);
  padding-bottom:0.4rem;
  margin-bottom:10px;
  font-weight:700;
  display:flex;
  align-items:center;
  font-size:0.95rem;
}
.section-title i { margin-right:0.4rem; font-size:1.02rem; }
/* info items */
.info-item{ padding:10px 12px; border-radius:10px; background:transparent; transition:background var(--ease) ease, transform var(--ease) ease; }
.info-item:hover{ background:var(--primary-100); transform:translateY(-3px) }
.info-label{ display:block; font-size:0.78rem; color:var(--muted); font-weight:600; margin-bottom:6px }
.info-value{ font-size:0.96rem; color:var(--text) }

/* QR area */
.qr-section{
  background: linear-gradient(180deg, rgba(138, 87, 226, 0.14), rgba(139,87,226,0.02));
  border-radius:12px; padding:14px; text-align:center; box-shadow: 0 8px 26px rgba(107,47,214,0.03);
}
.qr-section h5{ margin:6px 0 8px; font-weight:700; font-size:1rem; display:flex; gap:10px; align-items:center; justify-content:center }
.qr-section p{ margin:0 0 10px; color:var(--muted); font-size:0.88rem }
.qr-code{ max-width:140px; border-radius:12px; border:8px solid #ffffffff; box-shadow: 0 12px 30px rgba(16,18,35,0.06); }

.medical-badge{ display:inline-block; padding:6px 10px; border-radius:999px; font-weight:700; font-size:0.8rem; margin-right:8px; margin-bottom:8px; box-shadow:0 6px 18px rgba(16,18,35,0.04) }
.badge-blood{ background: rgba(231,76,60,0.08); color: #bf2e33; border:1px solid rgba(231,76,60,0.12) }
.badge-allergy{ background: rgba(243,156,18,0.08); color:#b66b18; border:1px solid rgba(243,156,18,0.12) }
.badge-condition{ background: rgba(52,152,219,0.08); color:var(--primary-600); border:1px solid rgba(52,152,219,0.12) }

/* emergency contact */
.emergency-contact{
  padding:12px 14px; border-radius:10px; background:#fff; border-left:4px solid var(--primary-600);
  box-shadow:0 8px 22px rgba(16,18,35,0.03); margin-bottom:12px;
}
.emergency-contact .info-label{ font-size:0.8rem; color:var(--muted); margin-bottom:6px; font-weight:700 }
.emergency-contact .info-value{ font-size:0.95rem; color:var(--text) }


.btn-medical { background: var(--primary); color:white; border-radius:50px; padding:0.4rem 1rem; font-weight:600; border:none; font-size:0.85rem; transition:transform var(--ease) ease, box-shadow var(--ease) ease; }
.btn-medical:hover {color:white; background:rgb(7, 40, 117); transform:translateY(-2px); box-shadow:0 4px 12px rgba(0,0,0,0.1); }
.qr-buttons {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 10px;
  margin-top: 14px;
}

/* Keep the same style for .btn-back */
.btn-back {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  background: transparent;
  border: 2px solid #6b2fd6;
  color: #6b2fd6;
  border-radius: 40px;
  padding: 10px 20px;
  font-weight: 600;
  font-size: 0.9rem;
  box-shadow: 0 4px 10px rgba(107,47,214,0.08);
  transition: all 0.25s ease;
  line-height: 1;
}

.btn-back i {
  font-size: 0.95rem;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  margin-top: 1px;
}

.btn-back:hover {
  background: #6b2fd6;
  color: #fff;
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(107,47,214,0.15);
}
.stethoscope-icon { position:absolute; top:10px; right:10px; opacity:0.1; font-size:4rem; color:white; }
@media(max-width:768px){
  .medical-header{padding:0.8rem;}
  .profile-avatar{width:60px;height:60px;}
  .profile-avatar i{font-size:1.5rem;}
  .qr-code { max-width:120px; }
}
</style>
</head>
<body>

<div class="container mt-3">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10 col-sm-12">
      <div class="medical-card">
        <div class="medical-header">
          <i class="fas fa-stethoscope stethoscope-icon"></i>
          <div class="profile-avatar"><i class="fas fa-user-md"></i></div>
          <h2 class="text-center mb-0" style="font-size:1.3rem;">My Profile</h2>
          <p class="text-center mb-0 opacity-75" style="font-size:0.85rem;">ResQMe</p>
        </div>

        <div class="card-body p-3">
          <div class="row">
            <!-- Personal Info -->
            <div class="col-lg-6">
              <div class="info-section">
                <h4 class="section-title"><i class="fas fa-user-circle"></i> Personal Information</h4>
                <div class="info-item"><div class="info-label">Full Name</div><div class="info-value"><?= htmlspecialchars($user['username']) ?></div></div>
                <div class="info-item"><div class="info-label">Phone Number</div><div class="info-value"><?= htmlspecialchars($user['phone']) ?></div></div>
                <div class="info-item"><div class="info-label">Email Address</div><div class="info-value"><?= htmlspecialchars($user['email']) ?></div></div>
                <div class="info-item"><div class="info-label">Date of Birth</div><div class="info-value"><?= htmlspecialchars($user['dob']) ?></div></div>
                <div class="info-item"><div class="info-label">Blood Group</div><div class="medical-badge badge-blood"><?= htmlspecialchars($user['bloodgroup']) ?></div></div>
              </div>
            </div>

            <!-- Medical & Emergency Info -->
            <div class="col-lg-6">
              <div class="info-section">
                <h4 class="section-title"><i class="fas fa-first-aid"></i> Medical Details</h4>
                <div class="info-item">
                  <div class="info-label">Allergies</div>
                  <div class="info-value">
                    <?php if (!empty($user['allergies'])): ?>
                      <?php foreach(explode(',', $user['allergies']) as $a): ?>
                        <span class="medical-badge badge-allergy"><?= htmlspecialchars(trim($a)) ?></span>
                      <?php endforeach; ?>
                    <?php else: ?><span class="text-muted">No known allergies</span><?php endif; ?>
                  </div>
                </div>
                <div class="info-item">
                  <div class="info-label">Medical Conditions</div>
                  <div class="info-value">
                    <?php if (!empty($user['conditions'])): ?>
                      <?php foreach(explode(',', $user['conditions']) as $c): ?>
                        <span class="medical-badge badge-condition"><?= htmlspecialchars(trim($c)) ?></span>
                      <?php endforeach; ?>
                    <?php else: ?><span class="text-muted">No medical conditions reported</span><?php endif; ?>
                  </div>
                </div>
              </div>

              <div class="info-section">
                <h4 class="section-title"><i class="fas fa-phone-alt"></i> Emergency Contacts</h4>
                <?php if (!empty($user['emergency1'])): ?><div class="emergency-contact"><div class="info-label">Primary</div><div class="info-value"><?= htmlspecialchars($user['emergency1']) ?></div></div><?php endif; ?>
                <?php if (!empty($user['emergency2'])): ?><div class="emergency-contact"><div class="info-label">Secondary</div><div class="info-value"><?= htmlspecialchars($user['emergency2']) ?></div></div><?php endif; ?>
                <?php if (!empty($user['emergency3'])): ?><div class="emergency-contact"><div class="info-label">Tertiary</div><div class="info-value"><?= htmlspecialchars($user['emergency3']) ?></div></div><?php endif; ?>
              </div>
            </div>
          </div>

          <hr class="my-3">

          <div class="row align-items-center">
  <div class="col-md-6">
    <div class="qr-section">
      <h5 class="mb-2"><i class="fas fa-qrcode"></i> Emergency QR Code</h5>
      <p class="text-muted small mb-2">Scan this code in case of emergency</p>
      <img src="<?= htmlspecialchars($filename) ?>" alt="QR Code" class="img-fluid qr-code">

      <!-- ✅ Print Button -->
      <button class="btn btn-medical mt-2" onclick="printQRCode()">
        <i class="fas fa-print me-2"></i>Print QR Code
      </button>
    </div>
  </div>

  <div class="col-md-6 text-center mt-3 mt-md-0">
    <div class="qr-buttons">
       <a href="userhome.php" class="btn btn-back mb-2"><i class="fas fa-edit me-2"></i>Back to home</a>
       <a href="logout.php" class="btn btn-back"><i class="fas fa-sign-out-alt me-2"></i>Logout</a> 
    </div>
  </div>
</div>


        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function printQRCode() {
  var qrContent = document.querySelector('.qr-section').innerHTML;
  var originalContent = document.body.innerHTML;

  document.body.innerHTML = qrContent;

  window.print();

  document.body.innerHTML = originalContent;
  location.reload(); // restore JS functionality
}
</script>

</body>
</html>
