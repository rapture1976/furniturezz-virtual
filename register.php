<?php
// Start the session
session_start();

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

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $title = $_POST['title'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $gender = $_POST['gender'];
    $address1 = $_POST['address1'];
    $address2 = $_POST['address2'];
    $address3 = $_POST['address3'];
    $postcode = $_POST['postcode'];
    $description = $_POST['description'];
    $telephone = $_POST['telephone'];

    // Insert new user into the database
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, title, first_name, last_name, gender, address1, address2, address3, postcode, description, telephone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssssss", $username, $email, $password, $title, $first_name, $last_name, $gender, $address1, $address2, $address3, $postcode, $description, $telephone);

    if ($stmt->execute()) {
        echo "Registration successful!";
        // Redirect to login page after registration
        header("Location: home.html"); 
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
