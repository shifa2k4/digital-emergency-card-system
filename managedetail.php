<?php
session_start();
include('dbconnection.php');

// Check if user is admin
// if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
//     header("Location: login.php");
//     exit();
// }

// Handle delete action
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_stmt = $conn->prepare("DELETE FROM emergency_details WHERE id = ?");
    if ($delete_stmt) {
        $delete_stmt->bind_param("i", $delete_id);
        
        if ($delete_stmt->execute()) {
            $_SESSION['message'] = "Record deleted successfully!";
        } else {
            $_SESSION['error'] = "Error deleting record: " . $delete_stmt->error;
        }
        $delete_stmt->close();
    } else {
        $_SESSION['error'] = "Error preparing delete statement: " . $conn->error;
    }
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle edit action via POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_id'])) {
    $edit_id = $_POST['edit_id'];
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $dob = trim($_POST['dob'] ?? '');
    $bloodgroup = trim($_POST['bloodgroup'] ?? '');
    $emergency1 = trim($_POST['emergency1'] ?? '');
    $emergency2 = trim($_POST['emergency2'] ?? '');
    $emergency3 = trim($_POST['emergency3'] ?? '');
    $allergies = trim($_POST['allergies'] ?? '');
    $conditions = trim($_POST['conditions'] ?? '');

    if (empty($dob)) {
        $dob = NULL;
    }

    $update_stmt = $conn->prepare("UPDATE emergency_details SET 
        phone = ?, email = ?, dob = ?, bloodgroup = ?, 
        emergency1 = ?, emergency2 = ?, emergency3 = ?, 
        allergies = ?, conditions = ? 
        WHERE id = ?");

    if ($update_stmt) {
        $update_stmt->bind_param("sssssssssi", 
            $phone, $email, $dob, $bloodgroup,
            $emergency1, $emergency2, $emergency3, 
            $allergies, $conditions, $edit_id
        );

        if ($update_stmt->execute()) {
            $_SESSION['message'] = "Record updated successfully!";
        } else {
            $_SESSION['error'] = "Error updating record: " . $update_stmt->error;
        }
        $update_stmt->close();
    } else {
        $_SESSION['error'] = "Error preparing update statement: " . $conn->error;
    }
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch all emergency details
$sql = "SELECT * FROM emergency_details ";
$result = $conn->query($sql);

// Check if query was successful
if ($result === false) {
    $error = "Database error: " . $conn->error;
    $num_rows = 0;
    $result = false;
} else {
    $num_rows = $result->num_rows;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Emergency Details - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #74ebd5 0%, #9face6 100%);
            min-height: 100vh;
            padding-top: 80px;
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
    

        .card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            width: 1000px;
        }

        .table-container {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .table th {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: white;
            border: none;
            font-weight: 600;
        }

        .table td {
            vertical-align: middle;
            border-color: #e9ecef;
        }

        .btn-action {
            padding: 5px 12px;
            margin: 2px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-edit {
            background: #ffc107;
            border: none;
            color: #000;
        }

        .btn-edit:hover {
            background: #e0a800;
            transform: translateY(-2px);
        }

        .btn-delete {
            background: #dc3545;
            border: none;
            color: white;
        }

        .btn-delete:hover {
            background: #c82333;
            transform: translateY(-2px);
        }

        .badge-blood {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
        }

        .modal-header {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: white;
            border-radius: 15px 15px 0 0;
        }

        .modal-content {
            border-radius: 15px;
            border: none;
        }

        .alert {
            border-radius: 12px;
            border: none;
        }
        .ab{
            width:1200px;
            margin-left:190px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
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

    <div class="container mt-4">
        <!-- Messages -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?php echo $_SESSION['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?php echo $_SESSION['error']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-database me-2"></i><?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="table-container ab">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold text-primary">
                    <i class="fas fa-heartbeat me-2"></i>Emergency Details Management
                </h3>
                <span class="badge bg-primary fs-6">Total Records: <?php echo $num_rows; ?></span>
            </div>

            <?php if ($result && $num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <!-- <th>User ID</th> -->
                                 <th>Email</th>
                                <th>Phone</th>
                                <!-- <th>Email</th> -->
                                <th>Blood Group</th>
                                <th>Emergency Contacts</th>
                                <th>Medical Info</th>
                                <th>Date of Birth</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><strong>#<?php echo $row['id']; ?></strong></td>
                                    <!-- <td><?php echo htmlspecialchars($row['user_id']); ?></td> -->
                                      <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                   
                                    <td>
                                        <?php if (!empty($row['bloodgroup'])): ?>
                                            <span class="badge-blood"><?php echo $row['bloodgroup']; ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">Not set</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <small>
                                            <?php 
                                            $emergency_contacts = array_filter([
                                                $row['emergency1'],
                                                $row['emergency2'],
                                                $row['emergency3']
                                            ]);
                                            echo $emergency_contacts ? implode('<br>', $emergency_contacts) : 'No contacts';
                                            ?>
                                        </small>
                                    </td>
                                    <td>
                                        <small>
                                            <?php
                                            $medical_info = [];
                                            if (!empty($row['allergies'])) $medical_info[] = "<strong>Allergies:</strong> " . $row['allergies'];
                                            if (!empty($row['conditions'])) $medical_info[] = "<strong>Conditions:</strong> " . $row['conditions'];
                                            echo $medical_info ? implode('<br>', $medical_info) : 'No medical info';
                                            ?>
                                        </small>
                                    </td>
                                    <td>
                                        <small><?php echo !empty($row['dob']) ? date('M j, Y', strtotime($row['dob'])) : 'Not set'; ?></small>
                                    </td>
                                    <td>
                                        <button class="btn btn-action btn-edit" data-bs-toggle="modal" data-bs-target="#editModal" 
                                                data-id="<?php echo $row['id']; ?>"
                                                data-userid="<?php echo htmlspecialchars($row['user_id']); ?>"
                                                data-phone="<?php echo htmlspecialchars($row['phone']); ?>"
                                                data-email="<?php echo htmlspecialchars($row['email']); ?>"
                                                data-dob="<?php echo $row['dob']; ?>"
                                                data-bloodgroup="<?php echo $row['bloodgroup']; ?>"
                                                data-emergency1="<?php echo htmlspecialchars($row['emergency1']); ?>"
                                                data-emergency2="<?php echo htmlspecialchars($row['emergency2']); ?>"
                                                data-emergency3="<?php echo htmlspecialchars($row['emergency3']); ?>"
                                                data-allergies="<?php echo htmlspecialchars($row['allergies']); ?>"
                                                data-conditions="<?php echo htmlspecialchars($row['conditions']); ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-action btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" 
                                                data-id="<?php echo $row['id']; ?>"
                                                data-userid="<?php echo htmlspecialchars($row['user_id']); ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No Emergency Details Found</h4>
                    <p class="text-muted">No users have submitted their emergency details yet.</p>
                    <a href="admin_emergencydm.php" class="btn btn-primary mt-3">
                        <i class="fas fa-plus me-2"></i>Add Emergency Details
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">
                        <i class="fas fa-edit me-2"></i>Edit Emergency Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="">
                    <div class="modal-body">
                        <input type="hidden" name="edit_id" id="edit_id">
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">User ID</label>
                                <input type="text" class="form-control" id="edit_userid" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Date of Birth</label>
                                <input type="date" class="form-control" name="dob" id="edit_dob">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Phone Number *</label>
                                <input type="tel" class="form-control" name="phone" id="edit_phone" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Email ID *</label>
                                <input type="email" class="form-control" name="email" id="edit_email" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Blood Group</label>
                                <select class="form-select" name="bloodgroup" id="edit_bloodgroup">
                                    <option value="">Select Blood Group</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Emergency Contact 1</label>
                                <input type="tel" class="form-control" name="emergency1" id="edit_emergency1">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Emergency Contact 2</label>
                                <input type="tel" class="form-control" name="emergency2" id="edit_emergency2">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Emergency Contact 3</label>
                                <input type="tel" class="form-control" name="emergency3" id="edit_emergency3">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Allergies</label>
                                <input type="text" class="form-control" name="allergies" id="edit_allergies" placeholder="e.g., Penicillin, Nuts">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Medical Conditions</label>
                                <input type="text" class="form-control" name="conditions" id="edit_conditions" placeholder="e.g., Diabetes, Hypertension">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Details
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>Confirm Deletion
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the emergency details for user <strong id="delete_userid"></strong>?</p>
                    <p class="text-danger"><small>This action cannot be undone.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" id="confirmDelete" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Delete
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Edit Modal Script
        var editModal = document.getElementById('editModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            
            document.getElementById('edit_id').value = button.getAttribute('data-id');
            document.getElementById('edit_userid').value = button.getAttribute('data-userid');
            document.getElementById('edit_phone').value = button.getAttribute('data-phone');
            document.getElementById('edit_email').value = button.getAttribute('data-email');
            document.getElementById('edit_dob').value = button.getAttribute('data-dob');
            document.getElementById('edit_bloodgroup').value = button.getAttribute('data-bloodgroup');
            document.getElementById('edit_emergency1').value = button.getAttribute('data-emergency1');
            document.getElementById('edit_emergency2').value = button.getAttribute('data-emergency2');
            document.getElementById('edit_emergency3').value = button.getAttribute('data-emergency3');
            document.getElementById('edit_allergies').value = button.getAttribute('data-allergies');
            document.getElementById('edit_conditions').value = button.getAttribute('data-conditions');
        });

        // Delete Modal Script
        var deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var userId = button.getAttribute('data-userid');
            var id = button.getAttribute('data-id');
            
            document.getElementById('delete_userid').textContent = userId;
            document.getElementById('confirmDelete').href = '?delete_id=' + id;
        });
    </script>
</body>
</html>

<?php
// Close connection
if (isset($conn)) {
    $conn->close();
}
?>