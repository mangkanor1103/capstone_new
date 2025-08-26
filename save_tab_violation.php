<?php
// save_tab_violation.php - Script to save tab switching violations to database
session_start();
require_once 'dbConnection.php';

// Set response content type to JSON
header('Content-Type: application/json');

// Debug: Log all request information
error_log("Tab violation request received - Method: " . $_SERVER['REQUEST_METHOD']);
error_log("Session email: " . (isset($_SESSION['email']) ? $_SESSION['email'] : 'Not set'));
error_log("Request headers: " . print_r(getallheaders(), true));

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    error_log("No session email found");
    echo json_encode(['success' => false, 'message' => 'Unauthorized access - no session']);
    exit();
}

// Handle both POST and GET for debugging (remove GET in production)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['test'])) {
    // Test endpoint for debugging
    echo json_encode([
        'success' => true, 
        'message' => 'Test endpoint working',
        'method' => 'GET',
        'session_email' => $_SESSION['email'],
        'session_active' => isset($_SESSION['exam_active']) ? $_SESSION['exam_active'] : false
    ]);
    exit();
}

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error_log("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
    echo json_encode([
        'success' => false, 
        'message' => 'Invalid request method: ' . $_SERVER['REQUEST_METHOD'] . '. Expected POST.',
        'debug' => [
            'method' => $_SERVER['REQUEST_METHOD'],
            'session_email' => isset($_SESSION['email']) ? $_SESSION['email'] : 'Not set',
            'test_url' => 'save_tab_violation.php?test=1'
        ]
    ]);
    exit();
}

// Get data from request - handle both JSON and form POST
$raw_input = file_get_contents('php://input');
error_log("Raw input data: " . $raw_input);
error_log("POST data: " . print_r($_POST, true));

$input = null;

// Try to get data from JSON first
if (!empty($raw_input)) {
    $input = json_decode($raw_input, true);
    error_log("Decoded JSON input: " . print_r($input, true));
}

// If JSON failed, try form POST data
if (!$input && !empty($_POST)) {
    error_log("Using POST form data");
    
    // Check if there's a json_data field (from fallback form)
    if (isset($_POST['json_data'])) {
        $input = json_decode($_POST['json_data'], true);
        error_log("Decoded form JSON data: " . print_r($input, true));
    } else {
        // Use POST data directly
        $input = $_POST;
        error_log("Using direct POST data: " . print_r($input, true));
    }
}

// Validate required data
if (!$input) {
    error_log("No valid input data found");
    echo json_encode([
        'success' => false, 
        'message' => 'No valid input data found',
        'raw_input' => $raw_input,
        'post_data' => $_POST,
        'json_error' => json_last_error_msg()
    ]);
    exit();
}

if (!isset($input['email'], $input['name'], $input['violation_type'])) {
    error_log("Missing required data in input");
    echo json_encode([
        'success' => false, 
        'message' => 'Missing required data',
        'received' => array_keys($input),
        'required' => ['email', 'name', 'violation_type']
    ]);
    exit();
}

// Sanitize input data
$email = mysqli_real_escape_string($con, $input['email']);
$name = mysqli_real_escape_string($con, $input['name']);
$violation_type = mysqli_real_escape_string($con, $input['violation_type']);
$violation_count = isset($input['violation_count']) ? intval($input['violation_count']) : 1;
$timestamp = isset($input['timestamp']) ? $input['timestamp'] : date('Y-m-d H:i:s');
$exam_id = isset($input['exam_id']) ? mysqli_real_escape_string($con, $input['exam_id']) : null;
$question_number = isset($input['question_number']) ? intval($input['question_number']) : null;

// Verify the email matches the session
if ($email !== $_SESSION['email']) {
    echo json_encode(['success' => false, 'message' => 'Email mismatch']);
    exit();
}

// Insert tab switching violation into database
$sql = "INSERT INTO tab_violations (email, name, violation_type, violation_count, timestamp, exam_id, question_number) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $con->prepare($sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Database prepare error: ' . $con->error]);
    exit();
}

// Bind parameters
$stmt->bind_param("sssissi", $email, $name, $violation_type, $violation_count, $timestamp, $exam_id, $question_number);

// Execute the statement
if ($stmt->execute()) {
    $violation_id = $stmt->insert_id;
    error_log("Violation saved successfully with ID: " . $violation_id);
    
    // Also insert into the general warning table for consistency (if it exists and has the right structure)
    $warning_table_check = mysqli_query($con, "SHOW TABLES LIKE 'warning'");
    if (mysqli_num_rows($warning_table_check) > 0) {
        // Check if violation_type column exists in warning table
        $warning_columns = mysqli_query($con, "SHOW COLUMNS FROM warning LIKE 'violation_type'");
        
        if (mysqli_num_rows($warning_columns) > 0) {
            // warning table has violation_type column
            $warning_sql = "INSERT INTO warning (timestamp, email, violation_type) VALUES (?, ?, ?)";
            $warning_stmt = $con->prepare($warning_sql);
            
            if ($warning_stmt) {
                $warning_stmt->bind_param("sss", $timestamp, $email, $violation_type);
                if ($warning_stmt->execute()) {
                    error_log("Also saved to warning table");
                } else {
                    error_log("Failed to save to warning table: " . $warning_stmt->error);
                }
                $warning_stmt->close();
            }
        } else {
            // warning table doesn't have violation_type column, use basic structure
            $warning_sql = "INSERT INTO warning (timestamp, email) VALUES (?, ?)";
            $warning_stmt = $con->prepare($warning_sql);
            
            if ($warning_stmt) {
                $warning_stmt->bind_param("ss", $timestamp, $email);
                if ($warning_stmt->execute()) {
                    error_log("Also saved to warning table (basic)");
                } else {
                    error_log("Failed to save to warning table (basic): " . $warning_stmt->error);
                }
                $warning_stmt->close();
            }
        }
    } else {
        error_log("Warning table does not exist, skipping warning insert");
    }
    
    echo json_encode([
        'success' => true, 
        'message' => 'Tab violation recorded successfully',
        'violation_id' => $violation_id,
        'violation_type' => $violation_type,
        'violation_count' => $violation_count,
        'timestamp' => $timestamp,
        'debug' => [
            'email' => $email,
            'exam_id' => $exam_id,
            'question_number' => $question_number
        ]
    ]);
} else {
    error_log("Failed to save violation: " . $stmt->error);
    echo json_encode(['success' => false, 'message' => 'Database insert error: ' . $stmt->error]);
}

// Close statement and connection
$stmt->close();
$con->close();
?>
