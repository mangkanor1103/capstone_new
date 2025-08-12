<?php
// save_warning_image.php - Script to save warning images to database
session_start();
require_once 'dbConnection.php';

// Set response content type to JSON
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

// Check if image file is uploaded
if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'No image uploaded or upload error']);
    exit();
}

// Get form data
$warning_id = isset($_POST['warning_id']) ? intval($_POST['warning_id']) : 0;
$email = isset($_POST['email']) ? mysqli_real_escape_string($con, $_POST['email']) : '';
$name = isset($_POST['name']) ? mysqli_real_escape_string($con, $_POST['name']) : '';
$violation_count = isset($_POST['violation_count']) ? intval($_POST['violation_count']) : 0;
$violation_level = isset($_POST['violation_level']) ? mysqli_real_escape_string($con, $_POST['violation_level']) : 'MINOR';

// Validate required data
if ($warning_id <= 0 || empty($email) || empty($name)) {
    echo json_encode(['success' => false, 'message' => 'Missing required data']);
    exit();
}

// Get image data
$image_tmp_path = $_FILES['image']['tmp_name'];
$image_name = $_FILES['image']['name'];
$image_size = $_FILES['image']['size'];

// Validate image size (max 5MB)
if ($image_size > 5 * 1024 * 1024) {
    echo json_encode(['success' => false, 'message' => 'Image size too large (max 5MB)']);
    exit();
}

// Read image data
$image_data = file_get_contents($image_tmp_path);
if ($image_data === false) {
    echo json_encode(['success' => false, 'message' => 'Failed to read image data']);
    exit();
}

// Prepare SQL statement to insert image data
$timestamp = date('Y-m-d H:i:s');
$stmt = $con->prepare("INSERT INTO warning_images (warning_id, email, name, image_data, image_name, timestamp, violation_count, violation_level) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Database prepare error: ' . $con->error]);
    exit();
}

// Bind parameters
$stmt->bind_param("isssssiss", $warning_id, $email, $name, $image_data, $image_name, $timestamp, $violation_count, $violation_level);

// Execute the statement
if ($stmt->execute()) {
    $image_id = $stmt->insert_id;
    echo json_encode([
        'success' => true, 
        'message' => 'Warning image saved successfully',
        'image_id' => $image_id,
        'warning_id' => $warning_id,
        'violation_level' => $violation_level,
        'violation_count' => $violation_count
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database insert error: ' . $stmt->error]);
}

// Close statement and connection
$stmt->close();
$con->close();
?>
