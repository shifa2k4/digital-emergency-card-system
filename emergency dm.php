<?php
session_start();
include('dbconnection.php');

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first!'); window.location='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch fullname, email, phone from users table
$sql = "SELECT username AS fullname, email, phone 
        FROM users 
        WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>ResQMe-Contact</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: MediTrust
  * Template URL: https://bootstrapmade.com/meditrust-bootstrap-hospital-website-template/
  * Updated: Jul 04 2025 with Bootstrap v5.3.7
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
  <style>
    body {
      min-height: 100vh;
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #74ebd5 0%, #9face6 100%);
      background-image: url(emdpurplee.jpg);
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      padding-top: 100px;
      justify-content: flex-end;
      /* offset for navbar */
    }


    .content-wrapper {
      display: flex;
      justify-content: flex-end;
      align-items: flex-start;
      padding-right: 10px;
    }

   .form-container {
      background: rgba(255, 254, 255, 0.84);
      padding: 40px 35px;
      border-radius: 20px;
      box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
      width: 100%;
      max-width: 650px;
      margin-right: 90px ;
      align-content:right;
      animation: fadeIn 0.8s ease-in-out;
      margin-top: 20px;
    }


    .form-header {
      text-align: center;
      margin-bottom: 30px;
    }

    .form-header h2 {
      font-weight: 600;
      font-size: 1.8rem;
      color: rgba(160, 13, 201, 1);
    }


    /* Profile Section */
    .profile-wrapper {
      position: relative;
      display: inline-block;
      margin-bottom: 20px;
    }

    .profile-pic {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
      border: 4px solid #6a11cb;
      box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.15);
    }

    .edit-icon {
      position: absolute;
      bottom: 10px;
      right: 10px;
      background: #6a11cb;
      color: white;
      border-radius: 50%;
      padding: 6px;
      cursor: pointer;
      border: 2px solid white;
      transition: 0.3s;
      font-size: 14px;
    }
    .edit-icon:hover {
      background: #2575fc;
      transform: scale(1.1);
    }

    input[type="file"] {
      display: none;
    }

    .form-label {
      font-weight: bold;
      color: #af2eb8;
    }

    .form-control,
    .form-select {
      border-radius: 12px;
      padding: 10px 14px;
      border: 1px solid #ddd;
      transition: all 0.3s ease;
    }

    .form-control:focus,
    .form-select:focus {
      border-color: #6a11cb;
      box-shadow: 0 0 8px rgba(106, 17, 203, 0.2);
    }

    .btn-submit {
      width: 100%;
      padding: 14px;
      font-weight: bold;
      border-radius: 14px;
      background: linear-gradient(to right, #710be1a8, #6a11cb);
      color: #fff;
      border: none;
      font-size: 1rem;
      letter-spacing: 0.5px;
      transition: 0.3s ease;
    }

    .btn-submit:hover {
      background: linear-gradient(to right, #7633db, #6a11cb);
      transform: translateY(-2px);
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
  </style>
</head>

<body class="contact-page">

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="header-container container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="index.html" class="logo d-flex align-items-center me-auto me-xl-0">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <img src="barcode_8399707.png" alt="">
        <!--<svg class="my-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">-->
          <g id="bgCarrier" stroke-width="0"></g>
          <g id="tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
          <g id="iconCarrier">
            <path d="M22 22L2 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
            <path d="M17 22V6C17 4.11438 17 3.17157 16.4142 2.58579C15.8284 2 14.8856 2 13 2H11C9.11438 2 8.17157 2 7.58579 2.58579C7 3.17157 7 4.11438 7 6V22" stroke="currentColor" stroke-width="1.5"></path>
            <path opacity="0.5" d="M21 22V8.5C21 7.09554 21 6.39331 20.6629 5.88886C20.517 5.67048 20.3295 5.48298 20.1111 5.33706C19.6067 5 18.9045 5 17.5 5" stroke="currentColor" stroke-width="1.5"></path>
            <path opacity="0.5" d="M3 22V8.5C3 7.09554 3 6.39331 3.33706 5.88886C3.48298 5.67048 3.67048 5.48298 3.88886 5.33706C4.39331 5 5.09554 5 6.5 5" stroke="currentColor" stroke-width="1.5"></path>
            <path d="M12 22V19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
            <path opacity="0.5" d="M10 12H14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
            <path opacity="0.5" d="M5.5 11H7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
            <path opacity="0.5" d="M5.5 14H7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
            <path opacity="0.5" d="M17 11H18.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
            <path opacity="0.5" d="M17 14H18.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
            <path opacity="0.5" d="M5.5 8H7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
            <path opacity="0.5" d="M17 8H18.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
            <path opacity="0.5" d="M10 15H14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
            <path d="M12 9V5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            <path d="M14 7L10 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
          </g>
        </svg>

        <h1 class="sitename">Res<span class="highlight-Q">Q</span>Me</h1>
      </a>

        <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="userhome.php">Home</a></li>
          <li><a href="profile.php">Profile</a></li>
          <li><a href="emergency dm.php" class="active">Er-Details</a></li>
          <!-- <li><a href="departments.html">Departments</a></li> -->
          <li><a href="services 2.html">Services</a></li>
          <!-- <li><a href="doctors.html">Doctors</a></li> -->
          <li><a href="contact.html">Contact</a></li>
          <!-- <li><a href="adminlogin.php">Admin</a></li> -->
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>


      <a class="btn-getstarted" href="logiin.html">Logout</a>

    </div>
  </header>
  
  <div class="content-wrapper">
    <div class="form-container">
      <div class="form-header">
        <h2><i class="fas fa-ambulance text-danger"></i> Emergency Details</h2>
      </div>

     <form action="insert_emergency.php" method="POST">

  <!-- Full Name + DOB -->
  <div class="row mb-3">
    <div class="col">
      <label class="form-label">Full Name</label>
      <input type="text" class="form-control" name="fullname" placeholder="Enter full name" value="<?php echo htmlspecialchars($user['fullname']); ?>" readonly>
    </div>
    <div class="col">
      <label class="form-label">Date of Birth</label>
      <input type="date" class="form-control" name="dob">
    </div>
  </div>

  <!-- Blood Group + Phone -->
  <div class="row mb-3">
    <div class="col">
      <label class="form-label">Blood Group</label>
      <select class="form-select" name="bloodgroup">
        <option value="">Select Blood Group</option>
        <option>A+</option>
        <option>A-</option>
        <option>B+</option>
        <option>B-</option>
        <option>O+</option>
        <option>O-</option>
        <option>AB+</option>
        <option>AB-</option>
      </select>
    </div>
    <div class="col">
      <label class="form-label">Phone Number</label>
      <input type="tel" class="form-control" name="phone" placeholder="Enter phone number" value="<?php echo htmlspecialchars($user['phone']); ?>" readonly>
    </div>
  </div>

  <!-- Email + Emergency Contact -->
  <div class="row mb-3">
    <div class="col">
      <label class="form-label">Email ID</label>
      <input type="email" class="form-control" name="email" placeholder="Enter email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
    </div>
    <div class="col">
      <label class="form-label">Emergency Contact 1</label>
      <input type="tel" class="form-control" name="emergency1" placeholder="Enter emergency contact" required
         pattern="[6-9][0-9]{9}"
         minlength="10" maxlength="10">
    </div>
  </div>

  <div class="row mb-3">
    <div class="col">
      <label class="form-label">Emergency Contact 2</label>
      <input type="tel" class="form-control" name="emergency2" placeholder="Enter emergency contact" pattern="[6-9][0-9]{9}"
           minlength="10" maxlength="10">
    </div>
    <div class="col">
      <label class="form-label">Emergency Contact 3</label>
      <input type="tel" class="form-control" name="emergency3" placeholder="Enter emergency contact"pattern="[6-9][0-9]{9}"
           minlength="10" maxlength="10">
    </div>
  </div>

  <!-- Allergies + Medical Conditions -->
  <div class="row mb-4">
    <div class="col">
      <label class="form-label">Allergies</label>
      <input type="text" class="form-control" name="allergies" placeholder="e.g. Penicillin, Nuts">
    </div>
    <div class="col">
      <label class="form-label">Medical Conditions</label>
      <input type="text" class="form-control" name="conditions" placeholder="Enter medical conditions">
    </div>
  </div>

  <button type="submit" class="btn-submit">DONE</button>
</form>

    </div>
  </div>

  <script>
    function previewImage(event) {
      const reader = new FileReader();
      reader.onload = function () {
        const output = document.getElementById('preview');
        output.src = reader.result;
      };
      reader.readAsDataURL(event.target.files[0]);
    }
  </script>

  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>