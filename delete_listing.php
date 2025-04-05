<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Database credentials
$host = "localhost";
$dbname = "furniturezz";
$dbuser = "root";
$dbpass = "";

// Establish connection
$conn = new mysqli($host, $dbuser, $dbpass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if furniture_id is set
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['furniture_id'])) {
    $furniture_id = $_POST['furniture_id'];

    // Delete the listing from furniture details table
    $stmt = $conn->prepare("DELETE FROM furniture_details WHERE furniture_id = ?");
    $stmt->bind_param("i", $furniture_id);
    $stmt->execute();
    $stmt->close();
}

// Redirect back to the Seller Hub
$conn->close();
header("Location: sellerhub.php");
exit;
?>
