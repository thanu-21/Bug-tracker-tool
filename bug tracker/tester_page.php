<?php
session_start();

// Check if user is logged in and their role is tester
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'tester') {
    header("Location: login.php"); // Redirect unauthorized users to login page
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydatabase";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get notifications for new assignments
$sql = "SELECT * FROM notifications WHERE tester_username = '".$_SESSION['username']."'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Display notifications
        echo "<script>alert('".$row['message']."');</script>";
    }
}

$conn->close();
?>
