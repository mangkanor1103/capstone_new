<?php
include_once 'dbConnection.php';

// Set content type for JSON response
header('Content-Type: application/json');

try {
    $ref = @$_GET['q'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $id = uniqid();
    $date = date("Y-m-d");
    $time = date("h:i:sa");
    $feedback = $_POST['feedback'];
    
    // Sanitize inputs
    $name = mysqli_real_escape_string($con, $name);
    $email = mysqli_real_escape_string($con, $email);
    $subject = mysqli_real_escape_string($con, $subject);
    $feedback = mysqli_real_escape_string($con, $feedback);
    
    $query = "INSERT INTO feedback VALUES ('$id', '$name', '$email', '$subject', '$feedback', '$date', '$time')";
    $result = mysqli_query($con, $query);
    
    if ($result) {
        // Check if this is an AJAX request
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            echo json_encode(['success' => true, 'message' => 'Thank you for your valuable feedback']);
        } else {
            // Regular form submission - redirect
            header("location:$ref?q=Thank you for your valuable feedback");
        }
    } else {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            echo json_encode(['success' => false, 'message' => 'Failed to submit feedback']);
        } else {
            header("location:$ref?error=feedback_failed&message=" . urlencode("Failed to submit feedback. Please try again."));
        }
    }
} catch (Exception $e) {
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        echo json_encode(['success' => false, 'message' => 'Server error occurred']);
    } else {
        header("location:$ref?error=server_error&message=" . urlencode("Server error occurred. Please try again."));
    }
}
?>