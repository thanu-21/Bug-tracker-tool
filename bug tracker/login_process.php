<?php
// Start session
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydatabase";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from form
$username = $_POST['username'];
$password = $_POST['password'];
$role = $_POST['role']; // Fetch the role from the form

// Retrieve user from database with username, password, and role check
$sql = "SELECT * FROM users WHERE username='$username' AND role='$role'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password'])) { // Check password
        // Set session variables
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;
        
        // Redirect based on role
        switch ($role) {
            case 'customer':
                header("Location: customer_page.php");
                break;
            case 'project_manager':
                header("Location: project_manager_page.php");
                break;
            case 'tester':
                header("Location: tester_page.php");
                break;
            default:
                // Handle any other roles or unknown cases
                echo "<script>alert('Invalid role');</script>";
        }
        exit();
    } else {
        echo "<script>alert('Invalid password');window.location.href='signup.html';</script>"; // Incorrect password
    }
} else {
    echo "<script>alert('User not found');window.location.href='signup.html';</script>"; // User not found
}

$conn->close();
?>

