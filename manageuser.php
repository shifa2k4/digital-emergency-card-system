<?php
session_start();
include('dbconnection.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Users - Admin Panel</title>
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
      margin: 0;
      padding: 0;
      min-height: 100vh;
    }
    
    /* Navbar */
    .navbar {
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      z-index: 1030;
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
      margin-top: 56px;
      min-height: calc(100vh - 56px);
    }
    
    /* Card Styling */
    .card {
      border-radius: 16px;
      background: #ffffff;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      border: none;
      overflow: hidden;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
      animation: fadeIn 0.7s ease-in-out;
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
      transform: translateY(-5px);
      box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
    }
    
    /* Page Header */
    .page-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 25px;
      padding-bottom: 15px;
      border-bottom: 1px solid rgba(0,0,0,0.1);
    }
    
    .page-title {
      color: var(--primary);
      font-weight: 700;
      margin: 0;
      position: relative;
    }
    
    .page-title:after {
      content: '';
      position: absolute;
      bottom: -15px;
      left: 0;
      width: 60px;
      height: 4px;
      background: linear-gradient(to right, var(--primary), var(--accent));
      border-radius: 2px;
    }
    
    /* Table Styling */
    .table-container {
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    
    .table thead {
      background: linear-gradient(90deg, var(--primary), var(--secondary));
      color: white;
    }
    
    .table thead th {
      border: none;
      padding: 15px 12px;
      font-weight: 600;
      text-align: center;
      vertical-align: middle;
    }
    
    .table tbody td {
      padding: 14px 12px;
      vertical-align: middle;
      text-align: center;
      border-color: #f1f5f9;
    }
    
    .table tbody tr {
      transition: all 0.2s ease;
    }
    
    .table tbody tr:hover {
      background-color: #f8fafc;
      transform: scale(1.002);
    }
    
    /* Button Styling */
    .btn-action {
      padding: 6px 12px;
      border-radius: 8px;
      font-weight: 500;
      transition: all 0.2s ease;
      border: none;
    }
    
    .btn-edit {
      background: linear-gradient(135deg, var(--accent), #2980b9);
      color: white;
    }
    
    .btn-edit:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 10px rgba(52, 152, 219, 0.3);
    }
    
    .btn-delete {
      background: linear-gradient(135deg, var(--danger), #c0392b);
      color: white;
    }
    
    .btn-delete:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 10px rgba(231, 76, 60, 0.3);
    }
    
    /* Search and Filter */
    .search-box {
      position: relative;
      max-width: 300px;
    }
    
    .search-box input {
      padding-left: 40px;
      border-radius: 50px;
      border: 1px solid #e1e5e9;
    }
    
    .search-box i {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #6c757d;
    }
    
    /* Modal Styling */
    .modal-header {
      background: linear-gradient(90deg, var(--primary), var(--secondary));
      color: white;
      border-bottom: none;
      padding: 20px 25px;
    }
    
    .modal-title {
      font-weight: 600;
    }
    
    .btn-close-white {
      filter: invert(1);
    }
    
    .modal-body {
      padding: 25px;
    }
    
    .form-label {
      font-weight: 600;
      color: var(--dark);
      margin-bottom: 8px;
    }
    
    .form-control {
      border-radius: 10px;
      padding: 12px 15px;
      border: 1px solid #e1e5e9;
      transition: all 0.3s ease;
    }
    
    .form-control:focus {
      border-color: var(--accent);
      box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
    }
    
    .btn-save {
      background: linear-gradient(135deg, var(--success), #27ae60);
      color: white;
      border: none;
      padding: 10px 25px;
      border-radius: 8px;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    
    .btn-save:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(46, 204, 113, 0.3);
    }
    
    /* Empty State */
    .empty-state {
      text-align: center;
      padding: 40px 20px;
      color: #6c757d;
    }
    
    .empty-state i {
      font-size: 4rem;
      margin-bottom: 15px;
      color: #dee2e6;
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
    
    /* Animations */
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
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
  </style>
</head>

<body>
  <!-- Top Navbar -->
  <nav class="navbar navbar-expand-lg shadow-sm fixed-top px-3"
    style="background: linear-gradient(to right, #6a11cb, #2575fc);">
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
    <a href="index.html" class="active"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
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
      <a href="#"><i class="bi bi-image me-2"></i>All Wallpapers</a>
    </div> -->
    <!-- <a class="dropdown-toggle" data-bs-toggle="collapse" href="#reportsMenu" role="button">
      <i class="bi bi-flag me-2"></i> Reports
    </a> -->
    <!-- <div class"#"><i class="bi bi-flag me-2"></i>Reported Cards</a>
      <a href="feedback2.html"><i class="bi bi-chat-left-text me-2"></i>Feedback</a>
    </div>="collapse ps-4" id="reportsMenu">
      <a href= -->
    <!-- <a href="#"><i class="bi bi-gear me-2"></i> Settings</a> -->
    <a href="adminlogout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
  </div>

  <!-- Main Content -->
  <div class="content">
    <div class="page-header">
      <h1 class="page-title">User Management</h1>
      <div class="d-flex gap-2">
        <div class="search-box">
          <i class="bi bi-search"></i>
          <input type="text" class="form-control" placeholder="Search users..." id="searchInput">
        </div>
        <a href="adduser.html" class="btn btn-primary d-flex align-items-center">
          <i class="bi bi-person-plus me-2"></i> Add User
        </a>
      </div>
    </div>

    <div class="card shadow-lg">
      <div class="card-body p-0">
        <div class="table-container">
          <div class="table-responsive">
            <table class="table table-hover mb-0" id="usersTable">
              <thead>
                <tr>
                  <th width="60">#</th>
                  <th>Username</th>
                  <th>Phone</th>
                  <th>Email</th>
                  <th width="150">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $sql = "SELECT * FROM users";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                  $count = 1;
                  while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td class='fw-semibold'>".$count."</td>";
                    echo "<td class='fw-medium'>".htmlspecialchars($row['username'])."</td>";
                    echo "<td>".htmlspecialchars($row['phone'])."</td>";
                    echo "<td>".htmlspecialchars($row['email'])."</td>";
                    echo "<td>
                            <div class='d-flex justify-content-center gap-2'>
                              <button class='btn btn-action btn-edit editBtn' 
                                      data-id='".$row['id']."' 
                                      data-username='".htmlspecialchars($row['username'])."' 
                                      data-phone='".htmlspecialchars($row['phone'])."' 
                                      data-email='".htmlspecialchars($row['email'])."'>
                                <i class='bi bi-pencil-square'></i> Edit
                              </button>
                              <a href='deleteuser.php?id=".$row['id']."' class='btn btn-action btn-delete' onclick='return confirmDelete()'>
                                <i class='bi bi-trash'></i> Delete
                              </a>
                            </div>
                          </td>";
                    echo "</tr>";
                    $count++;
                  }
                } else {
                  echo "<tr>
                          <td colspan='5'>
                            <div class='empty-state'>
                              <i class='bi bi-people'></i>
                              <h5>No Users Found</h5>
                              <p>There are no users registered in the system yet.</p>
                              <a href='adduser.html' class='btn btn-primary mt-2'>
                                <i class='bi bi-person-plus me-2'></i> Add First User
                              </a>
                            </div>
                          </td>
                        </tr>";
                }
                $conn->close();
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit User Modal -->
  <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form id="editUserForm" method="POST" action="updateuser.php">
          <div class="modal-header">
            <h5 class="modal-title">Edit User Details</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="id" id="edit_id">
            <div class="mb-3">
              <label class="form-label">Username</label>
              <input type="text" class="form-control" name="username" id="edit_username" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Phone Number</label>
              <input type="text" class="form-control" name="phone" id="edit_phone" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Email Address</label>
              <input type="email" class="form-control" name="email" id="edit_email" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-save">
              <i class="bi bi-check-lg me-2"></i> Save Changes
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Mobile sidebar toggle
      const sidebarToggler = document.querySelector('.sidebar-toggler');
      const sidebar = document.querySelector('.sidebar');
      
      if (sidebarToggler) {
        sidebarToggler.addEventListener('click', function() {
          sidebar.classList.toggle('active');
        });
      }
      
      // Edit User Modal
      const editButtons = document.querySelectorAll('.editBtn');
      const editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
      
      editButtons.forEach(btn => {
        btn.addEventListener('click', function () {
          document.getElementById('edit_id').value = this.dataset.id;
          document.getElementById('edit_username').value = this.dataset.username;
          document.getElementById('edit_phone').value = this.dataset.phone;
          document.getElementById('edit_email').value = this.dataset.email;
          editModal.show();
        });
      });
      
      // Search functionality
      const searchInput = document.getElementById('searchInput');
      if (searchInput) {
        searchInput.addEventListener('input', function() {
          const filter = this.value.toLowerCase();
          const rows = document.querySelectorAll('#usersTable tbody tr');
          
          rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
          });
        });
      }
    });
    
    // Delete confirmation
    function confirmDelete() {
      return confirm('Are you sure you want to delete this user? This action cannot be undone.');
    }
  </script>
</body>
</html>