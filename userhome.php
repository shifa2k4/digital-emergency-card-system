<?php
session_start();

// If user not logged in, redirect
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>userhome-ResQme</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>ResQMe </title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <style>
    body {
      font-family: "Poppins", Arial, sans-serif;
      background: #f4f7fb;
      color: #333;
    }

    .btn-login {
      border-radius: 30px;
      padding: 8px 20px;
      font-weight: 500;
    }

   /* ---------- Replace all existing hero + qr rules with this block ---------- */
:root { --header-h: 76px; } /* set to your header height (px) */

.hero {
  box-sizing: border-box;
  padding: calc(var(--header-h) + 48px) 20px 88px; /* keeps hero below fixed header */
  background: linear-gradient(120deg,#071e2c 0%, #4a0fb3 45%, #8b1cd0 100%);
  color: #f8f7ff;
  text-align: center;
  position: relative;
  overflow: hidden;
  min-height: 420px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: flex-start;
}

/* ensure container content sits above decorative layers */
.hero .container { position: relative; z-index: 3; max-width: 1100px; }

/* Title and subtitle */
.hero h1 {
  margin: 0 0 8px;
  font-size: 2.8rem;
  line-height: 1.06;
  font-weight: 800;
  background: linear-gradient(90deg,#ffffff,#d9b7ff);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  text-shadow: 0 6px 20px rgba(0,0,0,0.28);
  animation: heroFade .6s ease-out both;
}

.hero p {
  margin: 8px auto 18px;
  color: rgba(255,255,255,0.95);
  max-width: 760px;
  font-size: 1.02rem;
  animation: heroFade .8s ease-out both;
}

/* Decorative soft blobs (toned down) */
.hero::before,
.hero::after {
  content: "";
  position: absolute;
  z-index: 1;
  pointer-events: none;
  filter: blur(80px) saturate(1.05);
  opacity: .22;
}
.hero::before {
  width: 520px; height: 520px; border-radius:50%;
  right: -160px; top: -120px;
  background: linear-gradient(180deg, rgba(122,35,211,0.9), rgba(138,24,208,0.6));
}
.hero::after {
  width: 420px; height: 420px; border-radius:50%;
  left: -160px; bottom: -80px;
  background: linear-gradient(180deg, rgba(22,36,114,0.9), rgba(104,3,127,0.55));
}

/* QR wrapper and box: centered, constrained, protected from global img rules */
.qr-scan-wrapper {
  width: 100%;
  display:flex;
  justify-content:center;
  align-items:center;
  z-index:3;
  margin-top: 18px;
  padding-bottom: 6px;
}

/* Confine scan animation inside this box */
/* QR BOX */
.qr-box {
  position: relative;
  width: 150px;
  height: 150px;
  border-radius: 18px;
  overflow: hidden;
  padding: 8px;
  background: rgba(255,255,255,0.05);
  box-shadow:
    0 0 20px rgba(164,100,255,0.4),
    0 0 40px rgba(164,100,255,0.3),
    inset 0 0 20px rgba(164,100,255,0.4);
  border: 2px solid rgba(200,150,255,0.8);
  animation: borderGlow 3s ease-in-out infinite alternate;
}

.qr-box img {
  width: 100%;
  height: 100%;
  border-radius: 12px;
  position: relative;
  z-index: 1;
}

.qr-glow {
  position: absolute;
  inset: 0;
  border-radius: 12px;
  background: radial-gradient(circle, rgba(164,100,255,0.2) 0%, transparent 70%);
  z-index: 0;
  animation: glowPulse 2.5s ease-in-out infinite;
}

.scan-line {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 3px;
  background: linear-gradient(90deg, #a64aff, #ffffff, #a64aff);
  animation: scanMove 2.8s ease-in-out infinite;
  box-shadow: 0 0 8px rgba(155,95,255,0.6);
  z-index: 2;
}

@keyframes borderGlow {
  0% {
    box-shadow:
      0 0 15px rgba(164,100,255,0.4),
      0 0 30px rgba(164,100,255,0.3),
      inset 0 0 10px rgba(164,100,255,0.3);
  }
  100% {
    box-shadow:
      0 0 25px rgba(164,100,255,0.7),
      0 0 50px rgba(164,100,255,0.6),
      inset 0 0 20px rgba(164,100,255,0.6);
  }
}
.hero::before, .hero::after {
  content: "";
  position: absolute;
  width: 250px;
  height: 250px;
  background: radial-gradient(circle, rgba(155,95,255,0.35), transparent 70%);
  border-radius: 50%;
  animation: floatOrb 10s ease-in-out infinite alternate;
  filter: blur(80px);
  z-index: 0;
}
.hero::before { top: 20%; left: 15%; animation-delay: 0s; }
.hero::after { bottom: 15%; right: 20%; animation-delay: 2s; }

@keyframes floatOrb {
  0% { transform: translateY(0) scale(1); opacity: 0.6; }
  100% { transform: translateY(-30px) scale(1.1); opacity: 0.8; }
}
.particle {
  position: absolute;
  width: 4px;
  height: 4px;
  background: rgba(200,150,255,0.9);
  border-radius: 50%;
  animation: floatUp 6s linear infinite;
}
@keyframes floatUp {
  0% { transform: translateY(0) scale(1); opacity: 1; }
  100% { transform: translateY(-120px) scale(0.3); opacity: 0; }
}
.hero {
  background: linear-gradient(120deg, #090011, #20004b, #090011);
  background-size: 200% 200%;
  animation: gradientShift 10s ease infinite;
}
@keyframes gradientShift {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}
.scan-status {
  color: #cfaaff;
  margin-top: 15px;
  font-weight: 600;
  font-family: monospace;
  overflow: hidden;
  white-space: nowrap;
  border-right: 3px solid #a64aff;
  width: 15ch;
  animation: typing 3s steps(15) infinite alternate, blink 0.6s infinite;
}

@keyframes typing {
  from { width: 0; }
  to { width: 15ch; }
}

@keyframes blink {
  50% { border-color: transparent; }
}
.qr-box {
  transition: transform 0.5s ease, box-shadow 0.5s ease;
}
.qr-box:hover {
  transform: scale(1.05) rotate(2deg);
  box-shadow: 0 0 40px rgba(180,100,255,0.8);
}
.scan-status {
  color: #cfaaff;
  margin-top: 15px;
  font-weight: 600;
  font-family: monospace;
  border-right: 3px solid #a64aff;
  width: 15ch;
  white-space: nowrap;
  overflow: hidden;
  animation: typing 3s steps(15) infinite alternate, blink 0.6s infinite;
  
  /* Center Fix */
  text-align: center;
  margin-left: auto;
  margin-right: auto;
  display: block;
}
     /* Welcome Section */
    .welcome {
      padding: 80px 20px 40px;
      text-align: center;
    }
    .welcome h2 {
      font-weight: 700;
      color: #071e2c;
    }
    .welcome p {
      color: #666;
    }

  .btn-sub {
  display: block; 
  margin-left: 90px;
  width: 50%;
  padding: 14px;
  font-weight: bold;
  border-radius: 14px;
  background: linear-gradient(to right, #710be1a8, #6a11cb);
  color: #ffffff;
  border: none;
  font-size: 1rem;
  letter-spacing: 0.5px;
  transition: 0.3s ease;
}

.btn-sub:hover {
  background: linear-gradient(to right, #6a11cb, #2575fc);
  transform: translateY(-2px);
  box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
}

  </style>
</head>
<body>
<header id="header" class="header d-flex align-items-center fixed-top">
    <div
      class="header-container container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="index.html" class="logo d-flex align-items-center me-auto me-xl-0">
        <!-- Uncomment the line below if you also wish to use an image logo -->
         <img src="barcode_8399707.png" alt="barcode">
        <!---<svg class="my-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">--->
          <g id="bgCarrier" stroke-width="0"></g>
          <g id="tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
          <g id="iconCarrier">
            <path d="M22 22L2 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
            <path
              d="M17 22V6C17 4.11438 17 3.17157 16.4142 2.58579C15.8284 2 14.8856 2 13 2H11C9.11438 2 8.17157 2 7.58579 2.58579C7 3.17157 7 4.11438 7 6V22"
              stroke="currentColor" stroke-width="1.5"></path>
            <path opacity="0.5"
              d="M21 22V8.5C21 7.09554 21 6.39331 20.6629 5.88886C20.517 5.67048 20.3295 5.48298 20.1111 5.33706C19.6067 5 18.9045 5 17.5 5"
              stroke="currentColor" stroke-width="1.5"></path>
            <path opacity="0.5"
              d="M3 22V8.5C3 7.09554 3 6.39331 3.33706 5.88886C3.48298 5.67048 3.67048 5.48298 3.88886 5.33706C4.39331 5 5.09554 5 6.5 5"
              stroke="currentColor" stroke-width="1.5"></path>
            <path d="M12 22V19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
            <path opacity="0.5" d="M10 12H14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
            <path opacity="0.5" d="M5.5 11H7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
            <path opacity="0.5" d="M5.5 14H7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
            <path opacity="0.5" d="M17 11H18.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
            <path opacity="0.5" d="M17 14H18.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
            <path opacity="0.5" d="M5.5 8H7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
            <path opacity="0.5" d="M17 8H18.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
            <path opacity="0.5" d="M10 15H14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
            <path d="M12 9V5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            </path>
            <path d="M14 7L10 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
              stroke-linejoin="round"></path>
          </g>
        </svg>

        <h1 class="sitename">Res<span class="highlight-Q">Q</span>Me</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="userhome.php" class="active">Home</a></li>
          <li><a href="profile.php">Profile</a></li>
          <li><a href="emergency dm.php">Er-Details</a></li>
          <!-- <li><a href="departments.html">Departments</a></li> -->
          <li><a href="services 2.html">Services</a></li>
          <!-- <li><a href="doctors.html">Doctors</a></li> -->
          <li><a href="contact.html">Contact</a></li>
          <!-- <li class="nav-item"><a class="nav-link" href="adminlogin.php">Admin</a></li> -->
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <a class="btn-getstarted" href="index.html">Logout</a>

    </div>
  </header>

 <!-- Hero Section -->
  <section class="hero">
  <h1>Smart Health, <span>Quick Access</span></h1>
  <p>Your life-saving medical details, always accessible with a single QR scan.</p>

  <!-- Center the QR -->
  <div class="d-flex justify-content-center align-items-center mt-4">
    <div class="qr-box">
      <img src="https://api.qrserver.com/v1/create-qr-code/?data=ResQMe&size=140x140" alt="QR Code" />
      <div class="scan-line"></div>
      <div class="qr-glow"></div>
    </div>
  </div>

  <!-- Animated particles -->
  <div class="particles">
    <div class="particle" style="left:10%;animation-delay:0s"></div>
    <div class="particle" style="left:50%;animation-delay:1s"></div>
    <div class="particle" style="left:80%;animation-delay:2s"></div>
  </div>

  <!-- Scanning text UNDER the QR -->
  <div class="scan-status">Scanning...</div>
</section>

 <!-- Welcome -->
<section class="welcome text-center">
  <div class="container">
    <h2 class="welcome-title">
      👋 Hey <span class="highlight-text"><?php echo htmlspecialchars($username); ?></span>!  
    </h2>
    <p class="welcome-subtitle">
      Glad to have you back — your emergency details are always just a scan away.  
    </p>
  </div>
</section>

  <!-- Dashboard -->
 <section class="user-actions">
    <div class="container">
      <div class="row g-4">
        <div class="col-md-4">
          <div class="dash-box">
            <i class="bi bi-qr-code-scan"></i>
            <h5>My QR Code</h5>
            <p>View or download your emergency QR code.</p>
            <a href="wallpaper.php" class="btn btn-sm btn-sub">Generate</a>
          </div>
        </div>
        <div class="col-md-4">
          <div class="dash-box">
            <i class="bi bi-person-lines-fill"></i>
            <h5>Update Info</h5>
            <p>Manage your medical and emergency details.</p>
            <a href="emergency dm.php" class="btn btn-sm btn-sub">Update</a>
          </div>
        </div>
        <div class="col-md-4">
          <div class="dash-box">
            <i class="bi bi-camera-fill"></i>
            <h5>Gallery</h5>
            <p>Explore the full collection of QR card styles and layouts.</p>
            <a href="gallery.html" class="btn btn-sm btn-sub">View</a>
          </div>
        </div>
      </div>
    </div>
 </section>

  <!-- Features -->
  <section class="features">
    <div class="container">
      <div class="text-center mb-5">
        <h2><span>Why Choose </span><span class="sitename">Res<span class="highlight-Q">Q</span>Me ?</span></h2>
        <p class="text-muted">Empowering you with secure, quick, and reliable emergency healthcare access.</p>
      </div>
       <div class="row g-4">
      <div class="col-md-4">
        <div class="feature-box">
          <div class="icon-wrapper">
            <i class="bi bi-shield-lock"></i>
          </div>
            <h5>Secure</h5>
            <p>Data encrypted & safely stored for privacy protection.</p>
          </div>
        </div>
       <div class="col-md-4">
        <div class="feature-box">
          <div class="icon-wrapper">
            <i class="bi bi-phone-vibrate"></i>
          </div>
            <h5>Accessible</h5>
            <p>Instant access to emergency details from anywhere.</p>
          </div>
        </div>
       <div class="col-md-4">
        <div class="feature-box">
          <div class="icon-wrapper">
            <i class="bi bi-qr-code-scan"></i>
          </div>
            <h5>Quick Access</h5>

            <p>Scan QR to view life-saving health information instantly.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

 <footer id="footer" class="footer position-relative light-background">

    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-4 col-md-6 footer-about">
          <a href="index.html" class="logo d-flex align-items-center">
            <span class="sitename">ResQMe</span>
          </a>
          <div class="footer-contact pt-3">
            <p>A108 Adam Street</p>
            <p>New York, NY 535022</p>
            <p class="mt-3"><strong>Phone:</strong> <span>+1 5589 55488 55</span></p>
            <p><strong>Email:</strong> <span>ResQMe@example.com</span></p>
          </div>
          <div class="social-links d-flex mt-4">
            <a href=""><i class="bi bi-twitter-x"></i></a>
            <a href=""><i class="bi bi-facebook"></i></a>
            <a href=""><i class="bi bi-instagram"></i></a>
            <a href=""><i class="bi bi-linkedin"></i></a>
          </div>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Useful Links</h4>
          <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">About us</a></li>
            <li><a href="#">Services</a></li>
            <li><a href="#">Terms of service</a></li>
            <li><a href="#">Privacy policy</a></li>
          </ul>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Our Services</h4>
          <ul>
            <li><a href="#">Web Design</a></li>
            <li><a href="#">Web Development</a></li>
            <li><a href="#">Product Management</a></li>
            <li><a href="#">Marketing</a></li>
            <li><a href="#">Graphic Design</a></li>
          </ul>
        </div>

    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">MyWebsite</strong> <span>All Rights Reserved</span></p>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you've purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
      </div>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>
