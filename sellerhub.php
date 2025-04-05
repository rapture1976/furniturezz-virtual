<?php
// Start the session
session_start();

// Check if the user is logged in and redirect to home page if not
if (!isset($_SESSION['username'])) {
    header("Location: home.html");
    exit;
}

// Get the logged-in user's username from the session
$username = $_SESSION['username'];

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

// Query to get the listings of the logged-in user
$stmt = $conn->prepare("
    SELECT * 
    FROM furniture_details 
    WHERE user_id = (SELECT user_id FROM users WHERE username = ?)
");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
?>

 <!-- sellerhub layout -->
<!DOCTYPE html>
<html>
<head>
    <title>Seller Hub</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <!-- top-bar labels and links -->
    <div class="top-bar">
        <div>
            <a href="marketplace.php">Marketplace</a>
            
            <!-- Logout button -->
            <form action="logout.php" method="POST" style="display:inline;">
                <input type="hidden" name="logout" value="1">
                <button type="submit" class="logout-btn">Logout</button>
            </form>            
        </div>
    </div>

    <!-- header for the seller-hub --> 
    <div class="seller-hub">
        <h1>My Listings</h1>

        <!-- display settings and labels for listing table --> 
        <table class="listing-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Video</th>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Colour</th>
                    <th>Type</th>
                    <th>Year</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
// Loop through the listings and display each row
while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['furniture_id'] . "</td>";
    echo "<td><img src='" . $row['image_url'] . "' alt='" . $row['furniture_model'] . "' class='listing-image'></td>";
    echo "<td><video class='listing-video' controls><source src='" . $row['video_url'] . "' type='video/mp4'>Your browser does not support the video tag.</video></td>";
    echo "<td>" . $row['furniture_make'] . "</td>";
    echo "<td>" . $row['furniture_model'] . "</td>";
    echo "<td>" . $row['furniture_colour'] . "</td>";
    echo "<td>" . $row['furniture_type'] . "</td>";
    echo "<td>" . $row['year'] . "</td>";
    echo "<td>
        <a href='edit_listing.php?id=" . $row['furniture_id'] . "' class='edit-btn'>Edit</a>
        <form method='POST' action='delete_listing.php' onsubmit='return confirm(\"Are you sure you want to delete this listing?\");' style='display:inline;'>
            <input type='hidden' name='furniture_id' value='" . $row['furniture_id'] . "'>
            <button type='submit' class='delete-btn'>Delete</button>
        </form>
    </td>";
    echo "</tr>";
}
?>
            </tbody>
        </table>

        <!-- create add-listing buttons -->
        <div class="add-listing">
            <a href="new_listing.html">
                <button class="add-listing-btn">Add New Listing</button>
            </a>
        </div>
    </div>

</body>
</html>

<?php
// Close the connection
$conn->close();
?>
