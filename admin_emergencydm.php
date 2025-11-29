<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Emergency Details - Admin Panel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

  <style>
    :root {
      --primary: #0a3d62;
      --secondary: #1e5799;
      --accent: #3498db;
      --success: #2ecc71;
      --warning: #f39c12;
      --danger: #e74c3c;
      --light: #eef2f3;
      --dark: #2c3e50;
    }

    body {
      min-height: 100vh;
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      background-image: url(emde.jpg);
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      margin: 0;
      padding: 0;
    }

    /* Layout */
    .main-container {
      display: flex;
      min-height: 100vh;
    }

    /* Sidebar */
    .sidebar {
      height: 100vh;
        background: linear-gradient(to right, #6a11cb, #2575fc);
      color: white;
      padding-top: 20px;
      position: fixed;
      width: 270px;
      overflow-y: auto;
      box-shadow: 4px 0 12px rgba(0,0,0,0.2);
      transition: all 0.3s ease-in-out;
      
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
    .main-content {
      flex: 1;
      margin-left: 280px;
      padding: 30px;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      min-height: 100vh;
    }

    /* Form Container */
    .form-container {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(15px);
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
      width: 100%;
      max-width: 800px;
      animation: slideInUp 0.8s ease-out;
      border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .form-header {
      text-align: center;
      margin-bottom: 35px;
      position: relative;
    }

    .form-header h2 {
      font-weight: 700;
      font-size: 2rem;
      background: linear-gradient(135deg, var(--primary), var(--accent));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      margin-bottom: 10px;
    }

    .form-header::after {
      content: '';
      position: absolute;
      bottom: -15px;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 4px;
      background: linear-gradient(to right, var(--primary), var(--accent));
      border-radius: 2px;
    }

    /* Profile Section */
    .profile-section {
      text-align: center;
      margin-bottom: 30px;
    }

    .profile-wrapper {
      position: relative;
      display: inline-block;
    }

    .profile-pic {
      width: 140px;
      height: 140px;
      border-radius: 50%;
      object-fit: cover;
      border: 5px solid white;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
      transition: all 0.3s ease;
    }

    .profile-pic:hover {
      transform: scale(1.05);
    }

    .edit-icon {
      position: absolute;
      bottom: 10px;
      right: 10px;
      background: linear-gradient(135deg, var(--accent), var(--primary));
      color: white;
      border-radius: 50%;
      width: 36px;
      height: 36px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      border: 3px solid white;
      transition: all 0.3s ease;
      font-size: 14px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .edit-icon:hover {
      transform: scale(1.1) rotate(15deg);
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.25);
    }

    input[type="file"] {
      display: none;
    }

    /* Form Styling */
    .form-label {
      font-weight: 600;
      color: var(--dark);
      margin-bottom: 8px;
      font-size: 0.95rem;
    }

    .form-control,
    .form-select {
      border-radius: 12px;
      padding: 12px 16px;
      border: 1.5px solid #e1e5e9;
      transition: all 0.3s ease;
      font-size: 0.95rem;
    }

    .form-control:focus,
    .form-select:focus {
      border-color: var(--accent);
      box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.15);
      transform: translateY(-2px);
    }

    .form-group {
      margin-bottom: 20px;
    }

    /* Button Styling */
    .btn-submit {
      width: 100%;
      padding: 15px;
      font-weight: 600;
      border-radius: 12px;
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      color: white;
      border: none;
      font-size: 1.1rem;
      letter-spacing: 0.5px;
      transition: all 0.3s ease;
      margin-top: 10px;
      box-shadow: 0 6px 20px rgba(10, 61, 98, 0.3);
      position: relative;
      overflow: hidden;
    }

    .btn-submit:hover {
      background: linear-gradient(135deg, var(--secondary), var(--primary));
      transform: translateY(-3px);
      box-shadow: 0 10px 25px rgba(10, 61, 98, 0.4);
    }

    .btn-submit:active {
      transform: translateY(-1px);
    }

    .btn-submit::after {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: 0.5s;
    }

    .btn-submit:hover::after {
      left: 100%;
    }

    /* Section Headers */
    .section-header {
      font-weight: 600;
      color: var(--primary);
      margin: 25px 0 15px 0;
      padding-bottom: 8px;
      border-bottom: 2px solid #f1f5f9;
      font-size: 1.1rem;
    }

    /* Animations */
    @keyframes slideInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
      }
      to {
        opacity: 1;
      }
    }

    /* Responsive Design */
    @media (max-width: 992px) {
      .sidebar {
        transform: translateX(-100%);
        width: 260px;
      }

      .sidebar.active {
        transform: translateX(0);
      }

      .main-content {
        margin-left: 0;
        padding: 20px;
      }

      .form-container {
        padding: 30px 20px;
      }

      .navbar-toggler {
        display: block !important;
      }
    }

    @media (max-width: 768px) {
      .form-header h2 {
        font-size: 1.6rem;
      }

      .profile-pic {
        width: 120px;
        height: 120px;
      }
    }

    /* Custom Scrollbar */
    .sidebar::-webkit-scrollbar {
      width: 6px;
    }

    .sidebar::-webkit-scrollbar-track {
      background: rgba(255, 255, 255, 0.1);
    }

    .sidebar::-webkit-scrollbar-thumb {
      background: rgba(255, 255, 255, 0.3);
      border-radius: 3px;
    }

    .sidebar::-webkit-scrollbar-thumb:hover {
      background: rgba(255, 255, 255, 0.5);
    }

    /* Form Row Spacing */
    .form-row {
      margin-bottom: 10px;
    }
  </style>
</head>

<body>
  <!-- Mobile Navbar Toggle -->
  <nav class="navbar navbar-expand-lg navbar-dark d-lg-none fixed-top" 
       style="background: linear-gradient(90deg, var(--primary), var(--secondary));">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" id="sidebarToggle">
        <span class="navbar-toggler-icon"></span>
      </button>
      <span class="navbar-brand mx-auto">
        <i class="bi bi-shield-check me-2"></i>Emergency Card Admin
      </span>
    </div>
  </nav>

  <div class="main-container">
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

    <!-- Main Content -->
    <div class="main-content">
      <div class="form-container">
        <div class="form-header">
          <h2><i class="bi bi-heart-pulse me-2"></i> Emergency Details Form</h2>
          <p class="text-muted mb-0">Add emergency medical information for users</p>
        </div>

        <!-- Profile Photo Section
        <div class="profile-section">
          <div class="profile-wrapper">
            <img src="https://via.placeholder.com/140" alt="Profile" class="profile-pic" id="preview">
            <label for="file-upload" class="edit-icon">
              <i class="bi bi-camera"></i>
            </label>
            <input id="file-upload" type="file" accept="image/*" onchange="previewImage(event)">
          </div>
        </div> -->
        <form action="admin_eminsert.php" method="POST">
          <!-- Personal Information Section -->
          <div class="section-header">
            <i class="bi bi-person me-2"></i> Personal Information
          </div>

          <div class="row form-row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" class="form-control" name="fullname" placeholder="Enter full name" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Date of Birth</label>
                <input type="date" class="form-control" name="dob" required>
              </div>
            </div>
          </div>

          <div class="row form-row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Blood Group</label>
                <select class="form-select" name="bloodgroup" required>
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
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="tel" class="form-control" name="phone" placeholder="Enter phone number" required>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Email Address</label>
            <input type="email" class="form-control" name="email" placeholder="Enter email address" required>
          </div>

          <!-- Emergency Contacts Section -->
          <div class="section-header">
            <i class="bi bi-telephone me-2"></i> Emergency Contacts
          </div>

          <div class="row form-row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Emergency Contact 1</label>
                <input type="tel" class="form-control" name="emergency1" placeholder="Primary contact" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Emergency Contact 2</label>
                <input type="tel" class="form-control" name="emergency2" placeholder="Secondary contact">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Emergency Contact 3</label>
                <input type="tel" class="form-control" name="emergency3" placeholder="Tertiary contact">
              </div>
            </div>
          </div>

          <!-- Medical Information Section -->
          <div class="section-header">
            <i class="bi bi-clipboard2-pulse me-2"></i> Medical Information
          </div>

          <div class="row form-row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Allergies</label>
                <input type="text" class="form-control" name="allergies" placeholder="e.g., Penicillin, Nuts, etc.">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Medical Conditions</label>
                <input type="text" class="form-control" name="conditions" placeholder="e.g., Diabetes, Asthma, etc.">
              </div>
            </div>
          </div>

          <!-- Additional Notes -->
          <!-- <div class="form-group">
            <label class="form-label">Additional Medical Notes</label>
            <textarea class="form-control" name="notes" rows="3" placeholder="Any additional medical information..."></textarea>
          </div> -->

          <button type="submit" class="btn-submit">
            <i class="bi bi-check-circle me-2"></i> Save Emergency Details
          </button>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function previewImage(event) {
      const reader = new FileReader();
      reader.onload = function() {
        const output = document.getElementById('preview');
        output.src = reader.result;
      };
      reader.readAsDataURL(event.target.files[0]);
    }

    // Mobile sidebar toggle
    document.addEventListener('DOMContentLoaded', function() {
      const sidebarToggle = document.getElementById('sidebarToggle');
      const sidebar = document.getElementById('sidebar');

      if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
          sidebar.classList.toggle('active');
        });
      }

      // Auto-expand active menu items
      const currentUrl = window.location.href;
      const sidebarLinks = document.querySelectorAll('.sidebar a');
      
      sidebarLinks.forEach(link => {
        if (link.href === currentUrl) {
          link.classList.add('active');
          
          const collapse = link.closest('.collapse');
          if (collapse) {
            const parentToggle = document.querySelector(`[href="#${collapse.id}"]`);
            if (parentToggle) {
              parentToggle.setAttribute('aria-expanded', 'true');
              collapse.classList.add('show');
            }
          }
        }
      });
    });

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
      const requiredFields = this.querySelectorAll('[required]');
      let valid = true;

      requiredFields.forEach(field => {
        if (!field.value.trim()) {
          valid = false;
          field.classList.add('is-invalid');
        } else {
          field.classList.remove('is-invalid');
        }
      });

      if (!valid) {
        e.preventDefault();
        alert('Please fill in all required fields.');
      }
    });
  </script>
</body>

</html>