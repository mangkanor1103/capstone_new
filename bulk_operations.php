<?php
// filepath: c:\Users\rheam\XAMPP\htdocs\venv39\bulk_operations.php
session_start();
include_once 'dbConnection.php';

if (!isset($_SESSION['email'])) {
    header("location: index.php");
    exit();
}

if ($_POST['action'] == 'bulk_delete') {
    $emails = $_POST['emails'];
    $deleted_count = 0;
    
    foreach ($emails as $email) {
        $email = mysqli_real_escape_string($con, $email);
        $result = mysqli_query($con, "DELETE FROM user WHERE email='$email'");
        if ($result) {
            $deleted_count++;
        }
    }
    
    echo "<script>alert('$deleted_count users deleted successfully!'); window.location.href='dash.php?q=1';</script>";
}

if ($_POST['action'] == 'bulk_status_change') {
    $emails = $_POST['emails'];
    $new_status = $_POST['new_status'];
    $updated_count = 0;
    
    // Map status values
    $status_value = 1; // Default active
    if ($new_status == 'deactivate' || $new_status == 'suspend') {
        $status_value = 0;
    }
    
    foreach ($emails as $email) {
        $email = mysqli_real_escape_string($con, $email);
        $result = mysqli_query($con, "UPDATE user SET status='$status_value' WHERE email='$email'");
        if ($result) {
            $updated_count++;
        }
    }
    
    echo "<script>alert('$updated_count users status updated successfully!'); window.location.href='dash.php?q=1';</script>";
}
?>