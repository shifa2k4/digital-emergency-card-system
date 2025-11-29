<?php
session_start();
include('dbconnection.php'); // Make sure your DB connection is here

// ✅ Fetch all users
$sql = "SELECT id, username FROM users";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code View</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --success-color: #4cc9f0;
            --border-radius: 12px;
            --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--dark-color);
            min-height: 100vh;
            padding-bottom: 2rem;
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




        .container {
            margin-top:280px;
            max-width: 900px;
            margin-left:360px;
        }
        
        .page-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 2rem 1.5rem;
            border-radius: var(--border-radius);
            margin: 2rem 0;
            box-shadow: var(--box-shadow);
            position: relative;
            overflow: hidden;
        }
        
        .page-header::before {
            content: "";
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(30deg);
        }
        
        .page-header h3 {
            font-weight: 700;
            margin-bottom: 0.5rem;
            position: relative;
        }
        
        .page-header p {
            opacity: 0.9;
            margin-bottom: 0;
            position: relative;
        }
        
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            overflow: hidden;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            font-weight: 600;
            padding: 1rem 1.5rem;
            border-bottom: none;
        }
        
        .table-container {
            background-color: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 1rem 1.5rem;
            font-weight: 600;
        }
        
        .table tbody td {
            padding: 1.2rem 1.5rem;
            vertical-align: middle;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .table tbody tr:hover {
            background-color: rgba(67, 97, 238, 0.05);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1.2rem;
            font-weight: 600;
            transition: var(--transition);
            box-shadow: 0 4px 8px rgba(67, 97, 238, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(67, 97, 238, 0.4);
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
        }
        
        .no-users {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }
        
        .no-users i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #dee2e6;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--accent-color), var(--success-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            margin-right: 10px;
        }
        
        .user-info {
            display: flex;
            align-items: center;
        }
        
        .badge {
            background: linear-gradient(135deg, var(--accent-color), var(--success-color));
            padding: 0.5rem 0.8rem;
            border-radius: 20px;
            font-weight: 600;
        }
        
        .footer {
            text-align: center;
            margin-top: 3rem;
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .table thead {
                display: none;
            }
            
            .table tbody tr {
                display: block;
                margin-bottom: 1rem;
                border: 1px solid #dee2e6;
                border-radius: var(--border-radius);
            }
            
            .table tbody td {
                display: block;
                text-align: right;
                padding: 0.75rem 1rem;
                border-bottom: 1px solid #dee2e6;
            }
            
            .table tbody td:before {
                content: attr(data-label);
                float: left;
                font-weight: bold;
                text-transform: uppercase;
                color: var(--primary-color);
            }
            
            .table tbody td:last-child {
                border-bottom: none;
            }
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
    <a href="admin dashboard.php" ><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
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
      <a href="admin_qrdetail.php" class="active"><i class="bi bi-qr-code-scan me-2"></i>Generated QR Codes</a>
    </div>
    <!-- <a class="dropdown-toggle" data-bs-toggle="collapse" href="#cardsMenu" role="button">
      <i class="bi bi-card-image me-2"></i> Digital Cards/Wallpapers
    </a>
    <div class="collapse ps-4" id="cardsMenu">
      <a href="#"><i class="bi bi-card-text me-2"></i>All Cards</a>
      <a href="#"><i class="bi bi-image me-2"></i>All Wallpapers</a>
    </div> -->
    <!-- <a class="dropdown-toggle" data-bs-toggle="collapse" href="#reportsMenu" role="button">
      <i class="bi bi-flag me-2"></i> Reports
    </a> -->
    <!-- <div class="collapse ps-4" id="reportsMenu">
      <a href="#"><i class="bi bi-flag me-2"></i>Reported Cards</a>
      <a href="feedback2.html"><i class="bi bi-chat-left-text me-2"></i>Feedback</a>
    </div> -->
    <!-- <a href="#"><i class="bi bi-gear me-2"></i> Settings</a> -->
    <a href="adminlogout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
  </div>


<div class="container mt-5">
    <!-- <div class="page-header d-flex justify-content-center">
        <h3><i class="fas fa-qrcode me-2"></i>Users & QR Codes</h3><br>
        <p>Manage and generate QR codes for all users</p>
    </div> -->
    
    <div class="table-container d-flex justify-content-center mt-5">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Username</th>
                    <th>QR Code</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if(mysqli_num_rows($result) > 0){
                    $i = 1;
                    while($row = mysqli_fetch_assoc($result)){
                        $initial = strtoupper(substr($row['username'], 0, 1));
                        echo "<tr>";
                        echo "<td data-label='#'><span class='badge'>".$i++."</span></td>";
                        echo "<td data-label='Username'><div class='user-info'><div class='user-avatar'>".$initial."</div><div>".htmlspecialchars($row['username'])."</div></div></td>";
                        echo "<td data-label='QR Code'>
                                <a href='admin_qrgen.php?user_id=".$row['id']."' class='btn btn-primary btn-sm'><i class='fas fa-qrcode me-1'></i> Generate QR</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'><div class='no-users'><i class='fas fa-users'></i><h5>No users found</h5><p>Add users to see them listed here</p></div></td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    
    <div class="footer">
        <p>&copy; <?php echo date("Y"); ?> QR Code Management System</p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>