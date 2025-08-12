<?php
// test_image_save.php - Test script to debug image saving
session_start();
require_once 'dbConnection.php';

// Set some test session data if not logged in
if (!isset($_SESSION['email'])) {
    $_SESSION['email'] = 'test@test.com';
    $_SESSION['name'] = 'Test User';
}

echo "<h2>Testing Image Save Functionality</h2>";

// Check if warning_images table exists
$result = $con->query("SHOW TABLES LIKE 'warning_images'");
if ($result->num_rows > 0) {
    echo "<p style='color: green;'>✓ warning_images table exists</p>";
} else {
    echo "<p style='color: red;'>✗ warning_images table does not exist</p>";
    echo "<p>Creating table...</p>";
    
    $sql = "CREATE TABLE IF NOT EXISTS `warning_images` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `warning_id` int(11) NOT NULL,
      `email` varchar(50) NOT NULL,
      `name` varchar(100) NOT NULL,
      `image_data` longblob NOT NULL,
      `image_name` varchar(255) NOT NULL,
      `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `violation_count` int(11) NOT NULL DEFAULT 0,
      `violation_level` varchar(50) NOT NULL DEFAULT 'MINOR',
      PRIMARY KEY (`id`),
      KEY `warning_id` (`warning_id`),
      KEY `email` (`email`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
    
    if ($con->query($sql) === TRUE) {
        echo "<p style='color: green;'>✓ warning_images table created successfully</p>";
    } else {
        echo "<p style='color: red;'>✗ Error creating table: " . $con->error . "</p>";
    }
}

// Check if save_warning_image.php exists
if (file_exists('save_warning_image.php')) {
    echo "<p style='color: green;'>✓ save_warning_image.php exists</p>";
} else {
    echo "<p style='color: red;'>✗ save_warning_image.php does not exist</p>";
}

// Test database connection
if ($con->ping()) {
    echo "<p style='color: green;'>✓ Database connection is active</p>";
} else {
    echo "<p style='color: red;'>✗ Database connection failed</p>";
}

// Show current session data
echo "<h3>Session Data:</h3>";
echo "<p>Email: " . ($_SESSION['email'] ?? 'Not set') . "</p>";
echo "<p>Name: " . ($_SESSION['name'] ?? 'Not set') . "</p>";

// Check if Python service is running
$python_status = @file_get_contents('http://127.0.0.1:5000/status');
if ($python_status !== false) {
    echo "<p style='color: green;'>✓ Python service is running</p>";
} else {
    echo "<p style='color: orange;'>⚠ Python service may not be running</p>";
}

echo "<h3>Test Form:</h3>";
echo '<form action="save_warning_image.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="warning_id" value="1">
    <input type="hidden" name="email" value="' . $_SESSION['email'] . '">
    <input type="hidden" name="name" value="' . $_SESSION['name'] . '">
    <input type="hidden" name="violation_count" value="1">
    <input type="hidden" name="violation_level" value="MINOR">
    <input type="file" name="image" accept="image/*" required>
    <input type="submit" value="Test Save Image">
</form>';

$con->close();
?>
