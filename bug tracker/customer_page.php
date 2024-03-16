<?php
// Start session
session_start();

// Check if user is logged in and their role is customer
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'customer') {
    // Redirect unauthorized users to login page
    header("Location: login.php");
    exit();
}

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

// Fetch the current status of the test request
$sql = "SELECT status FROM test_requests WHERE customer_username = '".$_SESSION['username']."' ORDER BY created_at DESC LIMIT 1";
$result = $conn->query($sql);

// Check if the query was executed successfully
if ($result->num_rows > 0) {
    // Retrieve the status of the latest request
    $row = $result->fetch_assoc();
    $latestStatus = $row['status'];
} else {
    $latestStatus = "No requests found";
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Page</title>
</head>
<body>
<h2>Create New Test Request</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="url">URL:</label><br>
        <input type="text" id="url" name="url" required><br>
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br>
        <input type="submit" value="Submit">
    
    <h2>Current Test Request Status</h2>
    <label for="status">Status:</label><br>
    <input type="text" id="status" name="status" value="<?php echo $latestStatus; ?>" readonly><br><br>
</form>
</body>
</html>
