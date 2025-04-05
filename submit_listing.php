<?php
session_start();  // Start the session to get user data

// Check if the user is logged in by verifying session
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit;
}
// Get the user ID from session
$user_id = $_SESSION['user_id'];  

// Database credentials
$host = "localhost";
$dbname = "furniturezz";
$dbuser = "root"; 
$dbpass = ""; 

// Establish connection
$conn = new mysqli($host, $dbuser, $dbpass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $furniture_make = $_POST['furniture_make'];
    $furniture_model = $_POST['furniture_model'];
    $furniture_colour = $_POST['furniture_colour'];
    $room_type = $_POST['room_type'];
    $furniture_type = $_POST['furniture_type'];
    $year = $_POST['year'];
    $location = $_POST['location'];
    $video_url = $_POST['video_url'];
    $image_url = $_POST['image_url'];

    // Prepare the SQL query to insert the new listing
    $stmt = $conn->prepare("INSERT INTO furniture_details (user_id, furniture_make, furniture_model, furniture_colour, room_type, furniture_type, year, location, video_url, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssssss", $user_id, $furniture_make, $furniture_model, $furniture_colour, $room_type, $furniture_type, $year, $location, $video_url, $image_url);

    if ($stmt->execute()) {
        // If listing is successful print this message
        echo "Listing successfully added!";
        // Redirect to seller hub
        header("Location: sellerhub.php");
        // If listing is NOT successful print this message 
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
