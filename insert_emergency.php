<?php
session_start();
include('dbconnection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id    = $_SESSION['user_id'];
    $phone      = trim($_POST['phone']);
    $email      = trim($_POST['email']);
    $dob        = trim($_POST['dob']);
    $bloodgroup = trim($_POST['bloodgroup']);
    $emergency1 = trim($_POST['emergency1']);
    $emergency2 = trim($_POST['emergency2']);
    $emergency3 = trim($_POST['emergency3']);
    $allergies  = trim($_POST['allergies']);
    $conditions = trim($_POST['conditions']);

    // Check if user already has emergency details
    $check = $conn->prepare("SELECT id FROM emergency_details WHERE user_id = ?");
    $check->bind_param("i", $user_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        // Update existing record
        $stmt = $conn->prepare("UPDATE emergency_details 
            SET phone=?, email=?, dob=?, bloodgroup=?, emergency1=?, emergency2=?, emergency3=?, allergies=?, conditions=?
            WHERE user_id=?");
        $stmt->bind_param("sssssssssi", 
            $phone, $email, $dob, $bloodgroup, 
            $emergency1, $emergency2, $emergency3, 
            $allergies, $conditions, $user_id
        );
    } else {
        // Insert new record
        $stmt = $conn->prepare("INSERT INTO emergency_details 
            (user_id, phone, email, dob, bloodgroup, emergency1, emergency2, emergency3, allergies, conditions)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssssss", 
            $user_id, $phone, $email, $dob, 
            $bloodgroup, $emergency1, $emergency2, 
            $emergency3, $allergies, $conditions
        );
    }

    if ($stmt->execute()) {
        header("Location: profile.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $check->close();
    $conn->close();
}
?>
