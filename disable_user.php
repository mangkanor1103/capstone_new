<?php
include_once 'dbConnection.php';
session_start();

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Decode the JSON payload
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['email'])) {
        $email = $data['email'];

        // Update the user's status to disabled (0)
        $disableQuery = "UPDATE user SET status = 0 WHERE email = '$email'";
        if (mysqli_query($con, $disableQuery)) {
            http_response_code(200); // Success
            echo json_encode(["message" => "User disabled successfully"]);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(["error" => "Failed to disable user"]);
        }
    } else {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "Email not provided"]);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["error" => "Invalid request method"]);
}
?>