<?php
// Database connection
include 'dbConnection.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to check user credentials
    $query = "SELECT * FROM user WHERE email = ? AND status = 1";
    $stmt = $con->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $stored_password = $row['password'];
        
        // Verify password - try both password_verify (for hashed) and direct comparison (fallback)
        if (password_verify($password, $stored_password) || 
            md5($password) === $stored_password || 
            $password === $stored_password) {
            
            // Login successful
            session_start();
            $_SESSION['email'] = $email;
            $_SESSION['name'] = $row['name']; // Set the user's name in the session
            header("location:account.php?q=1");
            exit();
        } else {
            // Invalid password
            echo "<script>alert('Invalid email or password. Please try again.'); window.location.href='index.php';</script>";
        }
    } else {
        // Email not found or user disabled
        echo "<script>alert('Invalid email or password. Please try again.'); window.location.href='index.php';</script>";
    }

    $stmt->close();
    $con->close();
}
?>