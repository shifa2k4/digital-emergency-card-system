<?php
// session_start();
include('dbconnection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 🧠 Detect whether admin or user is logged in
    $user_id  = trim($_POST['user_id'] ?? null);   // for user
    $fullname =trim($_POST['fullname'] ?? null);  // for admin

    $phone      = trim($_POST['phone'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $dob        = trim($_POST['dob'] ?? '');
    $bloodgroup = trim($_POST['bloodgroup'] ?? '');
    $emergency1 = trim($_POST['emergency1'] ?? '');
    $emergency2 = trim($_POST['emergency2'] ?? '');
    $emergency3 = trim($_POST['emergency3'] ?? '');
    $allergies  = trim($_POST['allergies'] ?? '');
    $conditions = trim($_POST['conditions'] ?? '');

    if (empty($dob)) {
        $dob = NULL;
    }

    // 🧩 Choose SQL based on who is logged in
    if (!empty($user_id)) {
        // ✅ Regular user – use user_id
        $stmt = $conn->prepare("INSERT INTO emergency_details 
            (user_id, phone, email, dob, bloodgroup, emergency1, emergency2, emergency3, allergies, conditions)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->bind_param(
            "isssssssss",
            $user_id, $phone, $email, $dob, $bloodgroup,
            $emergency1, $emergency2, $emergency3, $allergies, $conditions
        );

    } elseif (!empty($fullname)) {
        // ✅ Admin – use fullname instead of user_id
        $stmt = $conn->prepare("INSERT INTO emergency_details 
            (user_id, phone, email, dob, bloodgroup, emergency1, emergency2, emergency3, allergies, conditions)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->bind_param(
            "ssssssssss",
            $fullname, $phone, $email, $dob, $bloodgroup,
            $emergency1, $emergency2, $emergency3, $allergies, $conditions
        );

    } else {
        echo "<script>alert('No valid session found! Please login first.'); window.location='login.php';</script>";
        exit();
    }

    // ✅ Execute query
    if ($stmt->execute()) {
        echo "<script>alert('Emergency details saved successfully!'); window.location='admin dashboard.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
