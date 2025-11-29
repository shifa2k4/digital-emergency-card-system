<?php
session_start();

// Destroy all session data
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Logout</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    body {
      background: linear-gradient(135deg, #667eea, #764ba2);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .logout-box {
      background: rgba(255, 255, 255, 0.1);
      padding: 40px;
      border-radius: 15px;
      text-align: center;
      backdrop-filter: blur(10px);
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
      max-width: 400px;
      width: 90%;
    }

    .logout-box i {
      font-size: 3rem;
      margin-bottom: 15px;
      color: #ffe082;
    }

    .btn-login {
      background: linear-gradient(90deg, #6a11cb, #2575fc);
      border: none;
      color: white;
      font-weight: 600;
      padding: 10px 25px;
      border-radius: 8px;
      text-decoration: none;
      display: inline-block;
      transition: 0.3s;
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(67, 97, 238, 0.4);
    }

    .fade-in {
      animation: fadeIn 0.8s ease-in-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <div class="logout-box fade-in">
    <i class="fas fa-door-open"></i>
    <h2>You have been logged out</h2>
    <p>Redirecting you to login page...</p>
    <a href="adminlogin.php" class="btn-login mt-3">Go to Login</a>
  </div>

  <script>
    // Auto redirect after 3 seconds
    setTimeout(() => {
      window.location.href = "adminlogin.php";
    }, 3000);
  </script>
</body>
</html>
