<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Login</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

  <style>
    :root {
      --primary-color: #4361ee;
      --secondary-color: #3a0ca3;
      --gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    body {
      background: var(--gradient);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .login-container {
      background-color: #fff;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      overflow: hidden;
      width: 100%;
      max-width: 400px;
    }

    .login-header {
      background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
      color: #fff;
      padding: 30px 20px;
      text-align: center;
    }

    .login-header i {
      font-size: 3rem;
      margin-bottom: 15px;
    }

    .login-body {
      padding: 30px;
    }

    .input-group {
      align-items: center;
    }

    .input-group-text {
      background-color: #fff;
      border: 1px solid #ced4da;
      height: 45px;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0 14px;
    }

    .input-group .form-control {
      height: 45px;
      box-shadow: none;
      border-left: none;
    }

    .input-group .form-control:focus {
      box-shadow: none;
      border-color: #6c63ff;
    }

    .input-group-text i {
      font-size: 1rem;
      color: #6c757d;
    }

    .password-toggle {
      cursor: pointer;
    }

    .btn-login {
      height: 45px;
      font-weight: 600;
      background: linear-gradient(90deg, #6a11cb, #2575fc);
      border: none;
      color: white;
      width: 100%;
      border-radius: 8px;
      transition: 0.3s;
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(67, 97, 238, 0.4);
    }

    .form-check {
      margin-top: 10px;
    }
    
    .alert {
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="login-header">
      <i class="fas fa-lock"></i>
      <h2>Admin Login</h2>
      <p class="mb-0">Access the administration panel</p>
    </div>

    <div class="login-body">
      <?php
      session_start();
      
      // Define admin credentials
      $admin_username = "admin";
      $admin_password = "admin123";
      
      // Check if form is submitted
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        // Validate credentials
        if ($username === $admin_username && $password === $admin_password) {
          $_SESSION['admin_logged_in'] = true;
          $_SESSION['admin_username'] = $username;
          
          // Redirect to admin dashboard
          header("Location: admin dashboard.php");
          exit();
        } else {
          echo '<div class="alert alert-danger" role="alert">
                  Invalid username or password!
                </div>';
        }
      }
      ?>
      
      <form method="POST" action="">
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fas fa-user"></i></span>
            <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required />
          </div>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fas fa-key"></i></span>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required />
            <span class="input-group-text password-toggle" id="togglePassword">
              <i class="fas fa-eye"></i>
            </span>
          </div>
        </div>

        <div class="mb-3 form-check">
          <input type="checkbox" class="form-check-input" id="rememberMe" name="rememberMe" />
          <label class="form-check-label" for="rememberMe">Remember me</label>
        </div>

        <button type="submit" class="btn btn-login mb-3">
          <i class="fas fa-sign-in-alt me-2"></i> Login
        </button>
      </form>
      <center>  
        <a href="index.html" class="btn btn-home">
          <i class="fas fa-home me-2"></i> Back to Home
        </a>
      </center>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const togglePassword = document.getElementById("togglePassword");
      const passwordInput = document.getElementById("password");

      // Toggle password visibility
      togglePassword.addEventListener("click", function () {
        const type =
          passwordInput.getAttribute("type") === "password" ? "text" : "password";
        passwordInput.setAttribute("type", type);
        this.innerHTML =
          type === "password"
            ? '<i class="fas fa-eye"></i>'
            : '<i class="fas fa-eye-slash"></i>';
      });
    });
  </script>
</body>
</html>