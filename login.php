<?php
// Start the session
session_start();

// Clear session data if any before loading the page
unset($_SESSION['username']);
unset($_SESSION['password']);
unset($_SESSION['user_id']);  // Clear the user_id as well

// Check if the user is already logged in
if (isset($_SESSION['username'])) {
    // Redirect to dashboard if the user is already logged in
    header("Location: account_dashboard.html");
    exit;
}

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

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query the database for the user information
    $stmt = $conn->prepare("SELECT user_id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($user_id, $stored_password);
    $stmt->fetch();

    if ($stored_password) {
        // Compare the password
        if ($password === $stored_password) {
            // If login successful - Store the username and user_id in session
            $_SESSION['username'] = $username;
            // Store the user ID in session
            $_SESSION['user_id'] = $user_id;
            // Redirect on successful login
            header("Location: account_dashboard.html"); 
            exit;
            //If password wrong display error message
        } else {
            echo "Invalid password.";
        }
        //If username wrong display error message
    } else {
        echo "No user found with that username.";
    }

    $stmt->close();
}

$conn->close();
?>


