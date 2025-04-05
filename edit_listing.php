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

// Get listing ID
$furniture_id = $_GET['id'];

// Fetch the listing details
$stmt = $conn->prepare("SELECT * FROM furniture_details WHERE furniture_id = ?");
$stmt->bind_param("i", $furniture_id);
$stmt->execute();
$result = $stmt->get_result();
$listing = $result->fetch_assoc();
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $make = $_POST['furniture_make'];
    $model = $_POST['furniture_model'];
    $colour = $_POST['furniture_colour'];
    $type = $_POST['furniture_type'];
    $year = $_POST['year'];

    // Update the database with edited values
    $update_stmt = $conn->prepare("UPDATE furniture_details SET furniture_make = ?, furniture_model = ?, furniture_colour = ?, furniture_type = ?, year = ? WHERE furniture_id = ?");
    $update_stmt->bind_param("ssssii", $make, $model, $colour, $type, $year, $furniture_id);
    $update_stmt->execute();
    $update_stmt->close();

    // Redirect back to the Seller Hub
    header("Location: sellerhub.php");
    exit;
}

$conn->close();
?>
<!-- edit listing form needs more work on style-->
<!DOCTYPE html>
<html>
<head>
    <title>Edit Listing</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Edit Listing</h1>
    <form method="POST">
        <label for="furniture_make">Make:</label>
        <input type="text" id="furniture_make" name="furniture_make" value="<?php echo htmlspecialchars($listing['furniture_make']); ?>" required><br>
        <label for="furniture_model">Model:</label>
        <input type="text" id="furniture_model" name="furniture_model" value="<?php echo htmlspecialchars($listing['furniture_model']); ?>" required><br>
        <label for="furniture_colour">Colour:</label>
        <input type="text" id="furniture_colour" name="furniture_colour" value="<?php echo htmlspecialchars($listing['furniture_colour']); ?>" required><br>
        <label for="furniture_type">Type:</label>
        <input type="text" id="furniture_type" name="furniture_type" value="<?php echo htmlspecialchars($listing['furniture_type']); ?>" required><br>
        <label for="year">Year:</label>
        <input type="number" id="year" name="year" value="<?php echo htmlspecialchars($listing['year']); ?>" required><br>
        <button type="submit">Update</button>
    </form>
</body>
</html>
