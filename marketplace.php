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

// Query to fetch all listings along with seller information
$query = "SELECT 
            furniture_details.furniture_id,
            furniture_details.image_url,
            furniture_details.video_url,
            furniture_details.furniture_make,
            furniture_details.furniture_model,
            furniture_details.furniture_colour,
            furniture_details.furniture_type,
            furniture_details.year,
            users.email AS seller_email
          FROM furniture_details
          JOIN users ON furniture_details.user_id = users.user_id";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Marketplace</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- top-bar labels and links -->
    <div class="top-bar">
        <div>
            <a href="sellerhub.php">Seller Hub</a>

              <!-- Logout button -->
              <form action="logout.php" method="POST" style="display:inline;">
                <input type="hidden" name="logout" value="1">
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>
    <div class="seller-hub">
         <!-- header for the marketplace -->
        <h1>Furniture for Sale</h1>
         <!-- generate furniture for sale table -->
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
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['furniture_id']) ?></td>
                        <td>
                            <img src="<?= htmlspecialchars($row['image_url']) ?>" alt="Listing Image" class="listing-image">
                        </td>
                        <td>
                            <video class="listing-video" controls>
                                <source src="<?= htmlspecialchars($row['video_url']) ?>" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </td>
                        <td><?= htmlspecialchars($row['furniture_make']) ?></td>
                        <td><?= htmlspecialchars($row['furniture_model']) ?></td>
                        <td><?= htmlspecialchars($row['furniture_colour']) ?></td>
                        <td><?= htmlspecialchars($row['furniture_type']) ?></td>
                        <td><?= htmlspecialchars($row['year']) ?></td>
                        <td>
                             <!-- contact seller button to generate email -->
                            <a href="mailto:<?= htmlspecialchars($row['seller_email']) ?>" class="contact-seller-btn">Contact Seller</a>
                
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php
$conn->close();
?>
