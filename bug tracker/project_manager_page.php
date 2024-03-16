<?php
session_start();

// Check if user is logged in and their role is project manager
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'project_manager') {
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

// Update status if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['status']) && isset($_POST['request_id'])) {
    $status = $_POST['status'];
    $request_id = $_POST['request_id'];

    $sql = "UPDATE test_requests SET status='$status' WHERE id=$request_id";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Status updated successfully');</script>";
    } else {
        echo "<script>alert('Error updating status');</script>";
    }
}

// Get notifications for new test requests
$sql = "SELECT * FROM notifications WHERE project_manager_username = '".$_SESSION['username']."'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Display notifications
        echo "New Test Request Notification: ".$row['message']."<br>";
    }
} else {
    echo "No new notifications.<br>";
}

// View customer actions
echo "<h3>Customer</h3>";
echo "<table border='1'>";
echo "<tr><th>Test Request ID</th><th>Customer Username</th><th>URL</th><th>Credentials</th><th>Status</th><th>Action</th></tr>";

$sql = "SELECT * FROM test_requests";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>".$row['id']."</td>";
        echo "<td>".$row['customer_username']."</td>";
        echo "<td>".$row['url']."</td>";
        echo "<td>".$row['credentials']."</td>";
        echo "<td>".$row['status']."</td>";
        echo "<td>";
        echo "<form method='post'>";
        echo "<input type='hidden' name='request_id' value='".$row['id']."'>";
        echo "<select name='status'>";
        echo "<option value='Testing In Progress'>Testing In Progress</option>";
        echo "<option value='Testing Completed'>Testing Completed</option>";
        echo "</select>";
        echo "<input type='submit' value='Update Status'>";
        echo "</form>";
        echo "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>No test requests found.</td></tr>";
}
echo "</table>";

// View tester actions
echo "<h3>Tester</h3>";
echo "<table border='1'>";
echo "<tr><th>Bug ID</th><th>Title</th><th>Description</th></tr>";

$sql = "SELECT * FROM bugs";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>".$row['id']."</td>";
        echo "<td>".$row['title']."</td>";
        echo "<td>".$row['description']."</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='3'>No bugs found.</td></tr>";
}
echo "</table>";

$conn->close();
?>
