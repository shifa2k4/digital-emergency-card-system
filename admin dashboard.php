<?php
include('dbconnection.php');

// ⿡ Total Users
$totalUsersQuery = "SELECT COUNT(*) AS total_users FROM users";
$totalUsersResult = mysqli_query($conn, $totalUsersQuery);
$totalUsers = mysqli_fetch_assoc($totalUsersResult)['total_users'] ?? 0;

// ⿢ Total Emergency Records
$totalEmergencyQuery = "SELECT COUNT(*) AS total_emergency FROM emergency_details";
$totalEmergencyResult = mysqli_query($conn, $totalEmergencyQuery);
$totalEmergency = mysqli_fetch_assoc($totalEmergencyResult)['total_emergency'] ?? 0;

// ⿣ Total QR Codes
$totalQrQuery = "SELECT COUNT(*) AS total_qr FROM qr_code";
$totalQrResult = mysqli_query($conn, $totalQrQuery);
$totalQr = mysqli_fetch_assoc($totalQrResult)['total_qr'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Digital Emergency Card - Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root {
      --primary: #0a3d62;
      --secondary: #1e5799;
      --accent: #3498db;
      --light: #eef2f3;
      --success: #2ecc71;
      --warning: #f39c12;
      --danger: #e74c3c;
      --dark: #2c3e50;
    }
    
    body {
      background: linear-gradient(135deg, #eef2f3, #d9e4f5);
      font-family: "Segoe UI", sans-serif;
      min-height: 100vh;
    }
    
    /* Navbar */
    .navbar {
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      z-index: 1030;
        background: linear-gradient(to right, #6a11cb, #2575fc);
    }
    
    .navbar-brand {
      font-weight: 600;
      letter-spacing: 0.5px;
    }
    
    /* Sidebar */
    .sidebar {
      height: 100vh;
        background: linear-gradient(to right, #6a11cb, #2575fc);
      color: white;
      padding-top: 20px;
      position: fixed;
      width: 250px;
      overflow-y: auto;
      box-shadow: 4px 0 12px rgba(0,0,0,0.2);
      transition: all 0.3s ease-in-out;
      top: 56px;
      z-index: 1020;
    }
    
    .sidebar h4 {
      font-weight: bold;
      padding: 0 15px;
      margin-bottom: 25px;
      position: relative;
    }
    
    .sidebar h4:after {
      content: '';
      position: absolute;
      bottom: -8px;
      left: 20px;
      right: 20px;
      height: 2px;
      background: rgba(255,255,255,0.3);
    }
    
    .sidebar a {
      color: white;
      text-decoration: none;
      display: block;
      padding: 12px 20px;
      transition: all 0.3s ease-in-out;
      border-radius: 8px;
      margin: 2px 10px;
      font-weight: 500;
    }
    
    .sidebar a:hover {
      background: rgba(255, 255, 255, 0.15);
      transform: translateX(5px);
    }
    
    .sidebar a.active {
      background: rgba(255, 255, 255, 0.2);
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .sidebar .collapse a {
      padding: 10px 20px 10px 30px;
      margin: 2px 10px;
      font-size: 0.9rem;
      background: rgba(0,0,0,0.1);
    }
    
    .sidebar .dropdown-toggle::after {
      float: right;
      margin-top: 8px;
    }
    
    /* Main Content */
    .content {
      margin-left: 250px;
      padding: 25px;
      animation: fadeIn 1s ease-in-out;
      margin-top: 56px;
      min-height: calc(100vh - 56px);
    }
    
    /* Cards */
    .card {
      border-radius: 12px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      border: none;
      overflow: hidden;
      position: relative;
    }
    
    .card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, var(--primary), var(--accent));
    }
    
    .card:hover {
      transform: translateY(-8px);
      box-shadow: 0 12px 25px rgba(0,0,0,0.15);
    }
    
    .stats-card {
      background: linear-gradient(135deg, #ffffff, #f8fafc);
      padding: 25px 15px;
      text-align: center;
    }
    
    .stats-card h5 {
      color: var(--dark);
      font-weight: 600;
      margin-bottom: 15px;
    }
    
    .stats-card h3 {
      color: var(--primary);
      font-weight: 700;
      font-size: 2.5rem;
      margin: 0;
    }
    
    /* Animations */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes fadeInDown {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    .animate-delay-1 {
      animation-delay: 0.1s;
    }
    
    .animate-delay-2 {
      animation-delay: 0.2s;
    }
    
    .animate-delay-3 {
      animation-delay: 0.3s;
    }
    
    /* Table Styling */
    table th {
      background: var(--primary);
      color: white;
      font-weight: 600;
      padding: 12px 15px;
    }
    
    table td {
      padding: 12px 15px;
      vertical-align: middle;
    }
    
    /* Responsive */
    @media (max-width: 992px) {
      .sidebar {
        width: 220px;
        transform: translateX(-100%);
      }
      
      .sidebar.active {
        transform: translateX(0);
      }
      
      .content {
        margin-left: 0;
      }
      
      .sidebar-toggler {
        display: block !important;
      }
    }
    
    /* Custom Scrollbar */
    .sidebar::-webkit-scrollbar {
      width: 6px;
    }
    
    .sidebar::-webkit-scrollbar-track {
      background: rgba(255,255,255,0.1);
    }
    
    .sidebar::-webkit-scrollbar-thumb {
      background: rgba(255,255,255,0.3);
      border-radius: 3px;
    }
    
    .sidebar::-webkit-scrollbar-thumb:hover {
      background: rgba(255,255,255,0.5);
    }
  </style>
</head>
<body>

  <!-- Top Navbar -->
  <nav class="navbar navbar-expand-lg shadow-sm fixed-top px-3" 
     style="  background: linear-gradient(to right, #6a11cb, #2575fc);">
    <div class="container-fluid">
      <!-- Sidebar Toggler (for mobile) -->
      <button class="navbar-toggler sidebar-toggler d-lg-none me-2" type="button" 
              style="display: none; border: none; color: white; font-size: 1.5rem;">
        <i class="bi bi-list"></i>
      </button>
      
      <!-- Welcome Section -->
      <span class="navbar-brand d-flex align-items-center text-white fw-bold fs-5">
        <i class="bi bi-shield-lock-fill me-2"></i>
        <span style="text-shadow: 1px 1px 6px rgba(0,0,0,0.4);">Digital Emergency Card - Admin</span>
      </span>

      <ul class="navbar-nav ms-auto align-items-center">
        <!-- Notifications -->
        <!-- <li class="nav-item dropdown me-3">
          <a class="nav-link dropdown-toggle text-white" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown">
            <i class="bi bi-bell"></i> <span class="d-none d-md-inline">Notifications</span> <span class="badge bg-danger">3</span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown">
            <li><a class="dropdown-item" href="#">New User Registered</a></li>
            <li><a class="dropdown-item" href="#">Emergency Record Added</a></li>
            <li><a class="dropdown-item" href="#">System Alert</a></li>
          </ul>
        </li> -->

        <!-- Admin Profile -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-white" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown">
            <i class="bi bi-person-circle me-1"></i> <span class="d-none d-md-inline">Home</span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
            <!-- <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>View Profile</a></li>
            <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Settings</a></li> -->
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="adminlogout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>

  <!-- Sidebar -->
  <div class="sidebar">
    <h4 class="text-center mb-4"><i class="bi bi-shield-check me-2"></i>Admin Panel</h4>
    <a href="admin dashboard.php" class="active"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
    <a class="dropdown-toggle" data-bs-toggle="collapse" href="#usersMenu" role="button">
      <i class="bi bi-people me-2"></i> User Management
    </a>
    <div class="collapse ps-4" id="usersMenu">
      <a href="adduser.html"><i class="bi bi-person-plus me-2"></i>Add User</a>
      <a href="manageuser.php"><i class="bi bi-gear me-2"></i>Manage User</a>
    </div>
    <a class="dropdown-toggle" data-bs-toggle="collapse" href="#emergencyMenu" role="button">
      <i class="bi bi-heart-pulse me-2"></i> Emergency Details
    </a>
    <div class="collapse ps-4" id="emergencyMenu">
      <a href="admin_emergencydm.php"><i class="bi bi-plus-circle me-2"></i>Add Details</a>
      <a href="managedetail.php"><i class="bi bi-list-check me-2"></i>Manage Details</a>
    </div>
    <a class="dropdown-toggle" data-bs-toggle="collapse" href="#qrMenu" role="button">
      <i class="bi bi-qr-code me-2"></i> QR Codes
    </a>
    <div class="collapse ps-4" id="qrMenu">
      <a href="admin_qrdetail.php"><i class="bi bi-qr-code-scan me-2"></i>Generated QR Codes</a>
    </div>
    <!-- <a class="dropdown-toggle" data-bs-toggle="collapse" href="#cardsMenu" role="button">
      <i class="bi bi-card-image me-2"></i> Digital Cards/Wallpapers
    </a>
    <div class="collapse ps-4" id="cardsMenu">
      <a href="#"><i class="bi bi-card-text me-2"></i>All Cards</a>
      <a href="wallpaper.php"><i class="bi bi-image me-2"></i>All Wallpapers</a>
    </div> -->
    <!-- <a class="dropdown-toggle" data-bs-toggle="collapse" href="#reportsMenu" role="button">
      <i class="bi bi-flag me-2"></i> Reports
    </a> -->
    <div class="collapse ps-4" id="reportsMenu">
      <a href="#"><i class="bi bi-flag me-2"></i>Reported Cards</a>
      <a href="feedback2.html"><i class="bi bi-chat-left-text me-2"></i>Feedback</a>
    </div>
    <!-- <a href="#"><i class="bi bi-gear me-2"></i> Settings</a> -->
    <a href="adminlogout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
  </div>

  <!-- Main Content -->
  <div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0">Dashboard Overview</h2>
      <div class="text-muted">
        <i class="bi bi-calendar-check me-1"></i>
        <span id="current-date"><?php echo date('F j, Y'); ?></span>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
      <div class="col-md-4 mb-3">
        <div class="card stats-card shadow-sm animate_animated animate_fadeInUp">
          <div class="card-body">
            <div class="d-flex justify-content-center align-items-center mb-3">
              <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                <i class="bi bi-people-fill text-primary" style="font-size: 1.8rem;"></i>
              </div>
              <div>
                <h5 class="mb-0">Total Users</h5>
                <h3 class="mb-0"><?= $totalUsers ?></h3>
              </div>
            </div>
            <div class="text-muted small">
              <i class="bi bi-arrow-up-right text-success me-1"></i>
              <span>Registered in system</span>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-3">
        <div class="card stats-card shadow-sm animate_animated animate_fadeInUp animate-delay-1">
          <div class="card-body">
            <div class="d-flex justify-content-center align-items-center mb-3">
              <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                <i class="bi bi-heart-pulse text-warning" style="font-size: 1.8rem;"></i>
              </div>
              <div>
                <h5 class="mb-0">Emergency Records</h5>
                <h3 class="mb-0"><?= $totalEmergency ?></h3>
              </div>
            </div>
            <div class="text-muted small">
              <i class="bi bi-arrow-up-right text-success me-1"></i>
              <span>Active records</span>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-3">
        <div class="card stats-card shadow-sm animate_animated animate_fadeInUp animate-delay-2">
          <div class="card-body">
            <div class="d-flex justify-content-center align-items-center mb-3">
              <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                <i class="bi bi-qr-code text-success" style="font-size: 1.8rem;"></i>
              </div>
              <div>
                <h5 class="mb-0">QR Codes</h5>
                <h3 class="mb-0"><?= $totalQr ?></h3>
              </div>
            </div>
            <div class="text-muted small">
              <i class="bi bi-arrow-up-right text-success me-1"></i>
              <span>Generated codes</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="row">
      <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
          <div class="card-header bg-white">
            <h5 class="card-title mb-0"><i class="bi bi-clock-history me-2"></i>Recent Activity</h5>
          </div>
          <div class="card-body">
            <div class="list-group list-group-flush">
              <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                <div>
                  <i class="bi bi-person-plus text-success me-2"></i>
                  <span>New user registered</span>
                </div>
                <small class="text-muted">2 hours ago</small>
              </div>
              <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                <div>
                  <i class="bi bi-qr-code text-primary me-2"></i>
                  <span>QR code generated</span>
                </div>
                <small class="text-muted">5 hours ago</small>
              </div>
              <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                <div>
                  <i class="bi bi-heart-pulse text-warning me-2"></i>
                  <span>Emergency record updated</span>
                </div>
                <small class="text-muted">Yesterday</small>
              </div>
              <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                <div>
                  <i class="bi bi-shield-check text-info me-2"></i>
                  <span>System backup completed</span>
                </div>
                <small class="text-muted">2 days ago</small>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="col-lg-4">
        <div class="card shadow-sm">
          <div class="card-header bg-white">
            <h5 class="card-title mb-0"><i class="bi bi-info-circle me-2"></i>System Status</h5>
          </div>
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <span>Database</span>
              <span class="badge bg-success">Online</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
              <span>Server Load</span>
              <span class="badge bg-success">Normal</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
              <span>Security</span>
              <span class="badge bg-success">Active</span>
            </div>
            <div class="d-flex justify-content-between align-items-center">
              <span>Last Backup</span>
              <span class="text-muted small"><?php echo date('M j, H:i'); ?></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Mobile sidebar toggle
    document.addEventListener('DOMContentLoaded', function() {
      const sidebarToggler = document.querySelector('.sidebar-toggler');
      const sidebar = document.querySelector('.sidebar');
      
      if (sidebarToggler) {
        sidebarToggler.addEventListener('click', function() {
          sidebar.classList.toggle('active');
        });
      }
      
      // Update current date
      const options = { year: 'numeric', month: 'long', day: 'numeric' };
      document.getElementById('current-date').textContent = new Date().toLocaleDateString('en-US', options);
    });
  </script>
</body>
</html>
