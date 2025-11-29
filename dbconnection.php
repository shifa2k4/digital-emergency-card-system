<?php
// dbconnection.php

$servername = "localhost";   // usually 'localhost'
$username   = "root";        // your MySQL username
$password   = "root";            // your MySQL password
$dbname     = "digicard";     // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Optional success message (comment out later)
// echo "Database Connected Successfully!";
?>
