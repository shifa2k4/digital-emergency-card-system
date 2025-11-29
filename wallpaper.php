<?php
declare(strict_types=1);
session_start();
require_once 'dbconnection.php';
require_once 'qr/qrlib.php';

/**
 * Config (edit if needed)
 */
$QR_DIR = __DIR__ . '/qrcodes/';
$QR_PUBLIC_PATH = 'qrcodes/';
$QR_SIZE = 4;
$QR_EC = QR_ECLEVEL_L;

/* user id: prefer logged-in user, allow ?id= for admin/debug */
$user_id = 0;
if (!empty($_SESSION['user_id'])) {
    $user_id = (int) $_SESSION['user_id'];
} elseif (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $user_id = (int) $_GET['id'];
}

if ($user_id <= 0) {
    http_response_code(400);
    echo '<p style="color:red">Invalid user id. You must be logged in.</p>';
    exit;
}

/* Fetch user safely */
$sql = "SELECT u.id, u.username, u.phone, u.email,
               e.dob, e.bloodgroup, e.emergency1, e.emergency2, e.emergency3,
               e.allergies, e.conditions
        FROM users u
        LEFT JOIN emergency_details e ON u.id = e.user_id
        WHERE u.id = ? LIMIT 1";

$stmt = $conn->prepare($sql);
if (!$stmt) { http_response_code(500); echo '<p style="color:red">DB error.</p>'; exit; }
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) { http_response_code(404); echo '<p style="color:red">User not found.</p>'; exit; }

function sanitize_for_qr(string $s): string { return preg_replace('/[^\PC\s]/u', '', $s); }
function e($v): string { return htmlspecialchars((string)($v ?? 'N/A'), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }

/* Build QR content */
$fields = [
    'Name' => $user['username'] ?? 'N/A',
    'Phone' => $user['phone'] ?? 'N/A',
    'Email' => $user['email'] ?? 'N/A',
    'DOB' => $user['dob'] ?? 'N/A',
    'Blood Group' => $user['bloodgroup'] ?? 'N/A',
    'Primary Contact' => $user['emergency1'] ?? 'N/A',
    'Secondary Contact' => $user['emergency2'] ?? 'N/A',
    'Tertiary Contact' => $user['emergency3'] ?? 'N/A',
    'Allergies' => $user['allergies'] ?? 'None',
    'Conditions' => $user['conditions'] ?? 'None',
];

$lines = [];
foreach ($fields as $k => $v) $lines[] = "{$k}: " . sanitize_for_qr((string)$v);
$qrData = implode("\n", $lines);

/* Ensure QR dir and file */
if (!is_dir($QR_DIR)) { mkdir($QR_DIR, 0755, true); }
if (!is_writable($QR_DIR)) { http_response_code(500); echo '<p style="color:red">QR dir not writable.</p>'; exit; }

$hash = substr(hash('sha256', $qrData . '|' . $user_id), 0, 12);
$qrFilename = $QR_DIR . 'u' . $user_id . '_' . $hash . '.png';
$qrPublic = $QR_PUBLIC_PATH . basename($qrFilename);

if (!file_exists($qrFilename)) {
    QRcode::png($qrData, $qrFilename, $QR_EC, $QR_SIZE, 2);
    @chmod($qrFilename, 0644);
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>ResQMe — Card (Front/Back, Print, Download)</title>

<!-- icons -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<!-- html2canvas for Download -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<style>
:root{
  --card-w: 740px;
  --card-h: 220px;
  --radius: 14px;
  --bg: #f4f7ff;
  --card-bg: linear-gradient(180deg,#ffffff,#fbfdff);
  --accent: #8624bbff;
  --muted: #6b7280ff;
  --shadow: 0 18px 50px rgba(10,30,80,0.08);
  --font: Inter, system-ui, -apple-system, "Segoe UI", Roboto, Arial;
  --print-scale: 1;
}
*{box-sizing:border-box}
html,body{height:100%;margin:0;font-family:var(--font);background:var(--bg);display:flex;align-items:center;justify-content:center;padding:24px}
.controls {
  width:100%;
  display:flex;
  justify-content:center;
  gap:12px;
  margin-top:18px;
}
.btn{display:inline-flex;align-items:center;gap:8px;padding:9px 14px;border-radius:10px;border:none;cursor:pointer;font-weight:700;box-shadow:0 8px 22px rgba(10,30,80,0.06);border:1px solid rgba(8,40,120,0.04)}
.btn--primary{background:var(--accent);color:#fff}
.btn--primary:hover {color:white; background:rgb(7, 40, 117); transform:translateY(-2px); box-shadow:0 4px 12px rgba(0,0,0,0.1); }
.btn--ghost{background:#fff;border:1px solid rgba(8,40,120,0.06);color:#07325a}
.container{width:100%;max-width:var(--card-w)}
.card-stage{perspective:1200px}
.card{
  width:100%;
  height:auto;
  background:transparent;
  transform-style:preserve-3d;
  transition:transform .7s cubic-bezier(.2,.9,.3,1);
  margin:0 auto;
}
.card.is-flipped{transform:rotateY(180deg)}
.card-face{
  width:100%;
  min-height:var(--card-h);
  border-radius:var(--radius);
  box-shadow:var(--shadow);
  background:var(--card-bg);
  border:1px solid rgba(8,40,120,0.04);
  overflow:hidden;
  backface-visibility:hidden;
  display:flex;
  align-items:stretch;
}

/* front / back split */
.face-left{flex:1.05;padding:18px;display:flex;flex-direction:column;gap:10px}
.face-right{width:260px;padding:18px;border-left:1px dashed rgba(8,40,120,0.03);display:flex;flex-direction:column;align-items:center;justify-content:center}
.brand{display:flex;gap:12px;align-items:center}
.logo {
  width: 48px;
  height: 48px;
  border-radius: 10px;
  overflow: hidden;      /* required */
  display: flex;
  align-items: center;
  justify-content: center;
}

.logo img {
  width: 100%;
  height: 100%;
  object-fit: contain;   /* or "cover" if you want full fill */
}

.title{font-size:16px;font-weight:700;color:#07325a}
.highlight-Q {
  color: rgb(184, 32, 230);
  font-weight: bold;
}
.subtitle{color:var(--muted);font-size:12px;margin-top:2px}
.profile{display:flex;gap:14px;align-items:center;margin-top:6px}
.avatar{width:72px;height:72px;border-radius:10px;background:#f3f6ff;display:flex;align-items:center;justify-content:center;border:1px solid rgba(8,40,120,0.03)}
.name{font-size:18px;font-weight:700;color:#07325a;margin-bottom:4px}
.meta{color:var(--muted);font-size:13px}
.chips{display:flex;gap:8px;margin-top:8px;flex-wrap:wrap}
.chip{background:#fff;border:1px solid rgba(8,40,120,0.04);padding:6px 10px;border-radius:999px;font-size:13px;color:rgb(184, 32, 230);display:flex;align-items:center;gap:8px}
.bottom{display:flex;gap:12px;margin-top:10px}
.small-card{flex:1;background:linear-gradient(180deg,#ffffff,#fbfdff);border-radius:10px;padding:10px;border:1px solid rgba(8,40,120,0.03);font-size:13px}
.small-card .label{color:var(--muted);font-size:12px}
.qr-frame{width:150px;height:150px;border-radius:12px;background:#fff;padding:10px;display:flex;align-items:center;justify-content:center;border:1px solid rgba(8,40,120,0.04);box-shadow:0 8px 22px rgba(10,30,80,0.04)}
.qr-frame img{width:130px;height:130px;display:block}
.qr-note{font-size:13px;color:var(--muted);text-align:center}

/* back face styling (rotate) */
.card-back{
  position:relative;
  transform:rotateY(180deg);
}

/* printable friendly */
@media print{
  body{background:#fff}
  .controls{display:none}
  .card-stage{transform:none}
  .card-face{box-shadow:none;border:1px solid #ccc}
  @page { size: A4; margin: 10mm; }
  html,body{padding:0}
}

/* responsive */
@media (max-width:760px){
  :root{--card-w:360px}
  .face-right{width:100%;border-left:none;border-top:1px dashed rgba(8,40,120,0.03)}
  .card-face{flex-direction:column}
  .face-left{padding:12px}
  .face-right{padding:12px}
  .qr-frame{width:120px;height:120px}
}
</style>
</head>
<body>

<div class="controls">
  <button class="btn btn--ghost" id="flipBtn" title="Flip card"><i class="fas fa-sync-alt"></i> Flip</button>
  <button class="btn btn--ghost" id="printBtn" title="Print card"><i class="fas fa-print"></i> Print</button>
  <button class="btn btn--primary" id="downloadBtn" title="Download card as PNG"><i class="fas fa-download"></i> Download PNG</button>
</div>

<div class="container">
  <div class="card-stage" aria-hidden="false">
    <div id="card" class="card" role="group" aria-label="Emergency ID card">
      <!-- FRONT -->
      <div class="card-face card-front" id="card-front" style="display:flex">
        <div class="face-left">
          <div class="brand">
            <div class="logo">
                  <img src="barcode_8399707.png" alt="barcode">
             </div>

            <div>
              <div class="title">Res<span class="highlight-Q">Q</span>Me</div>
              <div class="subtitle">Digital Emergency Card</div>
            </div>
          </div>

          <div class="profile">
            <div class="avatar" aria-hidden="true"><i class="fas fa-user" style="font-size:32px;color:#9aa4b2"></i></div>
            <div>
              <div class="name"><?= e($user['username'] ?? '') ?></div>
              <div class="meta">DOB: <?= e($user['dob'] ?? 'N/A') ?> • Blood: <strong><?= e($user['bloodgroup'] ?? 'N/A') ?></strong></div>

              <div class="chips" style="margin-top:8px">
                <div class="chip"><i class="fas fa-phone"></i> <?= e($user['phone'] ?? 'N/A') ?></div>
                <div class="chip"><i class="fas fa-envelope"></i> <?= e($user['email'] ?? 'N/A') ?></div>
              </div>
            </div>
          </div>

          <div class="bottom">
            <div class="small-card">
              <div class="label">Ambulance</div>
              <div style="margin-top:6px;font-weight:600">
                <p>102</p>
                <p>108</p>
              </div>
            </div>

            <div class="small-card">
              <div class="label">Helpline</div>
              <div style="margin-top:6px;font-weight:600">
                <p>112</p>
                <p>100</p>
              </div>
            </div>
          </div>
        </div>

        <div class="face-right">
          <div class="qr-frame">
            <img src="<?= e($qrPublic) ?>" alt="QR code for <?= e($user['username'] ?? '') ?>">
          </div>
          <div class="qr-note">Scan to reveal full medical details</div>
        </div>
      </div>

      <!-- BACK -->
      <div class="card-face card-back" id="card-back">
        <div style="display:flex;flex:1;gap:0;align-items:stretch">
          <div style="flex:1;padding:18px">
            <div style="font-weight:700;font-size:16px;color:#07325a;margin-bottom:6px">Emergency Information</div>
            <div style="color:var(--muted);font-size:14px;line-height:1.45">
              <strong>Primary:</strong> <?= e($user['emergency1'] ?: 'N/A') ?><br>
              <strong>Secondary:</strong> <?= e($user['emergency2'] ?: 'N/A') ?><br>
              <strong>Tertiary:</strong> <?= e($user['emergency3'] ?: 'N/A') ?><br><br>

              <strong>Allergies:</strong> <?= e($user['allergies'] ?: 'None') ?><br>
              <strong>Medical Conditions:</strong> <?= e($user['conditions'] ?: 'None') ?><br><br>

              <strong>Notes:</strong><br>
              Keep this card updated. In emergency call numbers above and scan QR for full details.
            </div>
          </div>

          <div style="width:260px;padding:18px;border-left:1px dashed rgba(8,40,120,0.03);display:flex;flex-direction:column;align-items:center;justify-content:center">
            <div style="font-weight:700;margin-bottom:8px;color:#07325a">Card Info</div>
            <div style="font-size:13px;color:var(--muted);text-align:center">
              ID: <?= e((string)$user_id) ?><br>
              Generated: <?= date('Y-m-d') ?>
            </div>
          </div>
        </div>
      </div>
    </div> <!-- /card -->
  </div> <!-- /stage -->
</div> <!-- /container -->


<script>
// Elements
const card = document.getElementById('card');
const flipBtn = document.getElementById('flipBtn');
const printBtn = document.getElementById('printBtn');
const downloadBtn = document.getElementById('downloadBtn');

flipBtn.addEventListener('click', () => {
  card.classList.toggle('is-flipped');
});

/* Print - opens print dialog showing card only */
printBtn.addEventListener('click', () => {
  // Ensure front face is visible when printing unless flipped intentionally
  const wasFlipped = card.classList.contains('is-flipped');
  // Temporarily remove transform for reliable print layout on some browsers
  window.print();
});

/* Download PNG using html2canvas */
downloadBtn.addEventListener('click', () => {
  // temporarily remove 3D transform to capture front view (if flipped capture current)
  const capturingFlipped = card.classList.contains('is-flipped');
  const target = capturingFlipped ? document.getElementById('card-back') : document.getElementById('card-front');

  // clone the target into a plain element to capture exact size and styles
  const clone = target.cloneNode(true);
  clone.style.transform = 'none';
  clone.style.backfaceVisibility = 'visible';
  clone.style.width = getComputedStyle(target).width;
  clone.style.height = getComputedStyle(target).height;
  const tempWrap = document.createElement('div');
  tempWrap.style.position = 'fixed';
  tempWrap.style.left = '-9999px';
  tempWrap.style.top = '0';
  tempWrap.appendChild(clone);
  document.body.appendChild(tempWrap);

  html2canvas(clone, { scale: 2, useCORS: true, backgroundColor: null }).then(canvas => {
    const link = document.createElement('a');
    link.download = 'resqme_card_<?= $user_id ?>' + (capturingFlipped ? '_back' : '_front') + '.png';
    link.href = canvas.toDataURL('image/png');
    link.click();
    tempWrap.remove();
  }).catch(err => {
    tempWrap.remove();
    alert('Download failed. Open console for details.');
    console.error(err);
  });
});
</script>
</body>
</html>
