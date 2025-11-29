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

/* Get user id */
$user_id = isset($_GET['id']) ? (int)$_GET['id'] : (int)($_SESSION['admin_id'] ?? 0);
if ($user_id <= 0) {
    http_response_code(400);
    echo '<p style="color:red">Invalid user id.</p>';
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

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<style>
:root{
  --card-w: 740px;
  --card-h: 220px;
  --radius: 14px;
  --bg: #f4f7ff;
  --card-bg: linear-gradient(180deg,#ffffff,#fbfdff);
  --accent: #0b6cff;
  --muted: #6b7280;
  --shadow: 0 18px 50px rgba(10,30,80,0.08);
  --font: Inter, system-ui, -apple-system, "Segoe UI", Roboto, Arial;
}
*{box-sizing:border-box}
html,body{height:100%;margin:0;font-family:var(--font);background:var(--bg)}*{box-sizing:border-box}
html,body{height:100%;margin:0;font-family:var(--font);background:var(--bg);display:flex;align-items:center;justify-content:center;padding:24px}
.controls{width:100%;max-width:var(--card-w);display:flex;justify-content:flex-end;gap:10px;margin-bottom:14px}
.btn{display:inline-flex;align-items:center;gap:8px;padding:8px 12px;border-radius:8px;border:none;cursor:pointer;font-weight:600}
.btn--primary{background:var(--accent);color:#fff}
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
 
/* page layout: center everything horizontally */
.page { width:100%; max-width:1300px; margin:0 auto; padding:28px 24px 120px; position:relative; }

/* card container centered horizontally */
.card-wrap { width:100%; display:flex; justify-content:center; align-items:flex-start; gap:0; }

/* stage holds the 3D card */
.card-stage {
  width: var(--card-w);
  height: var(--card-h);
  perspective: 1400px;
  position: relative;
  margin: 0 0 18px 0;
} 


/* the 3D card that flips on Y axis */
.card {
  width:100%;
  height:100%;
  background:transparent;
  position: relative;
  transform-style: preserve-3d;
  transition: transform .7s cubic-bezier(.2,.9,.3,1);
  transform-origin: center center;
}

/* front/back faces - stacked absolutely */
.face {
  position:absolute;
  inset:0;
  width:100%;
  height:100%;
  border-radius:var(--radius);
  background:var(--card-bg);
  box-shadow:var(--shadow);
  border:1px solid rgba(8,40,120,0.04);
  overflow:hidden;
  display:flex;
  align-items:stretch;
  backface-visibility: hidden;
}

/* front face sits normally */
.face.front { transform: rotateY(0deg); z-index:2; }

/* back face rotated 180deg so when card rotates it becomes visible */
.face.back { transform: rotateY(180deg); z-index:1; }

/* flipped state - rotate the whole card 180deg on Y (side-to-side) */
.card.is-flipped { transform: rotateY(180deg); }

/* internal split (left content + right QR) kept same as your original */
.face-left{flex:1.05;padding:18px;display:flex;flex-direction:column;gap:10px}
.face-right{width:260px;padding:18px;border-left:1px dashed rgba(8,40,120,0.03);display:flex;flex-direction:column;align-items:center;justify-content:center}
.brand{display:flex;gap:12px;align-items:center}
.logo{width:48px;height:48px;border-radius:10px;background:var(--accent);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700}
.title{font-size:16px;font-weight:700;color:#07325a}
.subtitle{color:var(--muted);font-size:12px;margin-top:2px}
.profile{display:flex;gap:14px;align-items:center;margin-top:6px}
.avatar{width:72px;height:72px;border-radius:10px;background:#f3f6ff;display:flex;align-items:center;justify-content:center;border:1px solid rgba(8,40,120,0.03)}
.name{font-size:18px;font-weight:700;color:#0f1724;margin-bottom:4px}
.meta{color:var(--muted);font-size:13px}
.chips{display:flex;gap:8px;margin-top:8px;flex-wrap:wrap}
.chip{background:#fff;border:1px solid rgba(8,40,120,0.04);padding:6px 10px;border-radius:999px;font-size:13px;color:#0b4bd8;display:flex;align-items:center;gap:8px}
.bottom{display:flex;gap:12px;margin-top:10px}
.small-card{flex:1;background:linear-gradient(180deg,#ffffff,#fbfdff);border-radius:10px;padding:10px;border:1px solid rgba(8,40,120,0.03);font-size:13px}
.small-card .label{color:var(--muted);font-size:12px}
.qr-frame{width:150px;height:150px;border-radius:12px;background:#fff;padding:10px;display:flex;align-items:center;justify-content:center;border:1px solid rgba(8,40,120,0.04);box-shadow:0 8px 22px rgba(10,30,80,0.04)}
.qr-frame img{width:130px;height:130px;display:block}
.qr-note{font-size:13px;color:var(--muted);text-align:center}

/* controls centered below the card */
.controls {
  width:100%;
  display:flex;
  justify-content:center;
  gap:12px;
  margin-top:18px;
}
.btn {
  display:inline-flex;align-items:center;gap:8px;padding:9px 14px;border-radius:10px;border:none;cursor:pointer;font-weight:700;background:#fff;color:#0b4bd8;box-shadow:0 8px 22px rgba(10,30,80,0.06);border:1px solid rgba(8,40,120,0.04)
}
.btn.primary{background:var(--accent);color:#fff;box-shadow:0 10px 26px rgba(11,108,255,0.18)}

/* printable: hide controls */
@media print{ .controls{display:none} }

/* small screens: stack card and allow back/front view through JS toggle */
@media (max-width:760px){
  .card-stage { width: calc(100% - 48px); }
  .face-right{width:100%;border-left:none;border-top:1px dashed rgba(8,40,120,0.03)}
  .card { transform: none !important; } /* disable 3d for small */
  .face { position:relative; transform:none; margin-bottom:12px; }
}
</style>
</head>
<body>
  <div class="page">
    <div class="card-wrap">
      <div class="card-stage" aria-hidden="false">
        <div id="card" class="card" role="group" aria-label="Emergency ID card">
          <!-- FRONT (keeps original content) -->
          <section class="face front" id="card-front" aria-label="Card front">
            <div class="face-left">
              <div class="brand">
                <div class="logo">RQ</div>
                <div>
                  <div class="title">ResQMe</div>
                  <div class="subtitle">Emergency Medical ID</div>
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
                  <div class="label">Emergency contacts</div>
                  <div style="margin-top:6px;font-weight:600">
                    <?= e($user['emergency1'] ?: 'N/A') ?><br>
                    <?= e($user['emergency2'] ?: 'N/A') ?>
                  </div>
                </div>

                <div class="small-card">
                  <div class="label">Allergies / Conditions</div>
                  <div style="margin-top:6px;font-weight:600">
                    <?= e($user['allergies'] ?: 'None') ?><br>
                    <?= e($user['conditions'] ?: 'None') ?>
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
          </section>

          <!-- BACK -->
          <section class="face back" id="card-back" aria-label="Card back">
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
                <div style="margin-top:12px" class="qr-frame"><img src="<?= e($qrPublic) ?>" alt="QR"></div>
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>

    <!-- centered buttons -->
    <div class="controls">
      <button id="flipBtn" class="btn" title="Flip card"><i class="fas fa-sync-alt"></i> Flip</button>
      <button id="printBtn" class="btn" title="Print card"><i class="fas fa-print"></i> Print</button>
      <button id="downloadBtn" class="btn primary" title="Download card as PNG"><i class="fas fa-download"></i> Download PNG</button>
    </div>
  </div>

<script>
// elements
const card = document.getElementById('card');
const flipBtn = document.getElementById('flipBtn');
const printBtn = document.getElementById('printBtn');
const downloadBtn = document.getElementById('downloadBtn');

let isFlipped = false;

// flip side-to-side on click
flipBtn.addEventListener('click', () => {
  isFlipped = !isFlipped;
  if (isFlipped) card.classList.add('is-flipped');
  else card.classList.remove('is-flipped');
});

// print
printBtn.addEventListener('click', () => {
  window.print();
});

// download using html2canvas - capture the currently visible face
downloadBtn.addEventListener('click', () => {
  // we want the visible face. For 3D flip we capture the appropriate face element
  const visibleFace = isFlipped ? document.getElementById('card-back') : document.getElementById('card-front');

  // clone and render offscreen
  const clone = visibleFace.cloneNode(true);
  clone.style.position = 'fixed';
  clone.style.left = '-9999px';
  clone.style.top = '0';
  clone.style.transform = 'none';
  clone.style.backfaceVisibility = 'visible';
  document.body.appendChild(clone);

  html2canvas(clone, { scale: 2, useCORS: true, backgroundColor: null }).then(canvas => {
    const link = document.createElement('a');
    link.download = 'resqme_card_<?= $user_id ?>' + (isFlipped ? '_back' : '_front') + '.png';
    link.href = canvas.toDataURL('image/png');
    link.click();
    clone.remove();
  }).catch(err => {
    clone.remove();
    console.error(err);
    alert('Download failed. See console.');
  });
});
</script>
</body>
</html>
