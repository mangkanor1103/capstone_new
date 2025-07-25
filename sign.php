<?php
include_once 'dbConnection.php';
session_start();
ob_start();

$name = ucwords(strtolower(trim($_POST['name'])));
$gender = trim($_POST['gender']);
$email = trim($_POST['email']);
$college = trim($_POST['college']);
$mob = trim($_POST['mob']);
$password = $_POST['password'];
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Determine the photo source
$photoPath = '';
$photoData = $_POST['photo_data'] ?? '';

if (!empty($photoData)) {
    // Webcam capture (Base64 image)
    $photoBase64 = explode(',', $photoData)[1]; // Strip the metadata
    $photoDecoded = base64_decode($photoBase64);
    $photoName = 'user_' . uniqid() . '.png';
    $photoPath = 'uploads/' . $photoName;

    if (!file_exists('uploads')) {
        mkdir('uploads', 0777, true);
    }

    file_put_contents($photoPath, $photoDecoded);
} elseif (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
    // File upload
    $photo = $_FILES['photo'];
    $photoName = $photo['name'];
    $photoTmp = $photo['tmp_name'];
    $photoExt = strtolower(pathinfo($photoName, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($photoExt, $allowed)) {
        $newPhotoName = uniqid('user_', true) . '.' . $photoExt;
        $photoPath = 'uploads/' . $newPhotoName;

        if (!file_exists('uploads')) {
            mkdir('uploads', 0777, true);
        }

        move_uploaded_file($photoTmp, $photoPath);
    } else {
        header("location:index.php?q7=Invalid photo format.");
        ob_end_flush();
        exit();
    }
} else {
    header("location:index.php?q7=No photo uploaded or captured.");
    ob_end_flush();
    exit();
}

// Check if email already exists
$checkQuery = $con->prepare("SELECT * FROM user WHERE email = ?");
$checkQuery->bind_param("s", $email);
$checkQuery->execute();
$checkResult = $checkQuery->get_result();

if ($checkResult->num_rows > 0) {
    header("location:index.php?q7=Email Already Registered!!!");
    ob_end_flush();
    exit();
}

// Insert into database
$insertQuery = $con->prepare("INSERT INTO user (name, gender, college, email, mob, password, photo) VALUES (?, ?, ?, ?, ?, ?, ?)");
$insertQuery->bind_param("sssssss", $name, $gender, $college, $email, $mob, $hashedPassword, $photoPath);

if ($insertQuery->execute()) {
    $_SESSION["email"] = $email;
    $_SESSION["name"] = $name;
    header("location:account.php?q=1");
} else {
    header("location:index.php?q7=Registration Failed. Try Again!");
}

ob_end_flush();
?>
