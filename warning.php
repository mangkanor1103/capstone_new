<?php
// Start the session
session_start();

// Include the database connection
require_once 'dbConnection.php';

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("location:index.php");
    exit(); // Ensure no further code is executed
} else {
    $name = $_SESSION['name'];
    $email = $_SESSION['email']; // Initialize the email variable
}

// Store exam state when entering warning page
if (isset($_SESSION['exam_active']) && $_SESSION['exam_active'] === true && !isset($_SESSION['exam_return_url'])) {
    $_SESSION['exam_return_url'] = $_SERVER['HTTP_REFERER'] ?? 'account.php';
}

// Automatically save the warning when the page is loaded
$timestamp = date('Y-m-d H:i:s'); // Get the current timestamp

// Insert the warning into the database using a simple query
$sql = "INSERT INTO warning (timestamp, email) VALUES ('$timestamp', '$email')";

if ($con->query($sql) === TRUE) {
    $saveMessage = "Warning recorded successfully";
} else {
    $saveMessage = "Failed to record warning: " . $con->error;
}

// Get warning count for this user
$warning_query = mysqli_query($con, "SELECT COUNT(*) AS warning_count FROM warning WHERE email='$email'");
$warning_data = mysqli_fetch_assoc($warning_query);
$warning_count = $warning_data['warning_count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warning - Exam Violation</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-r from-red-800 to-red-600 min-h-screen">
    <div class="container mx-auto px-4 py-12 flex flex-col md:flex-row gap-8 items-center justify-center min-h-screen">
        <!-- Left Column (Warning Message) -->
        <div class="w-full md:w-1/2 bg-white bg-opacity-10 backdrop-blur-lg rounded-2xl p-8 shadow-2xl border border-red-300 text-white">
            <div class="flex items-center mb-6">
                <div class="rounded-full bg-white p-3 mr-4">
                    <i class="fas fa-exclamation-triangle text-2xl text-red-600"></i>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold">Exam Violation Detected</h1>
            </div>
            
            <div class="space-y-6">
                <div class="flex items-start space-x-3">
                    <i class="fas fa-user-slash mt-1 text-red-300"></i>
                    <p class="text-lg">Your face position has moved outside the allowed monitoring area.</p>
                </div>
                
                <div class="flex items-start space-x-3">
                    <i class="fas fa-exclamation-circle mt-1 text-red-300"></i>
                    <p class="text-lg">This counts as an exam violation and has been recorded.</p>
                </div>
                
                <div class="flex items-start space-x-3">
                    <i class="fas fa-clipboard-list mt-1 text-red-300"></i>
                    <p class="text-lg">Total violations: <span class="font-bold text-white bg-red-700 px-2 py-0.5 rounded-md"><?php echo $warning_count; ?></span></p>
                </div>
                
                <?php if ($warning_count >= 10) { ?>
                <div class="bg-red-900 bg-opacity-50 p-4 rounded-lg border border-red-400 mt-4">
                    <div class="flex items-center">
                        <i class="fas fa-shield-exclamation text-xl mr-3 text-red-300"></i>
                        <p class="font-medium">
                            <?php 
                            if ($warning_count >= 40) {
                                echo "CRITICAL LEVEL: Your account is at risk of immediate suspension.";
                            } elseif ($warning_count >= 30) {
                                echo "SEVERE LEVEL: You are approaching account suspension threshold.";
                            } elseif ($warning_count >= 20) {
                                echo "MAJOR LEVEL: Multiple serious violations detected.";
                            } elseif ($warning_count >= 10) {
                                echo "MODERATE LEVEL: Please adhere to exam rules carefully.";
                            }
                            ?>
                        </p>
                    </div>
                </div>
                <?php } ?>
                
                <div class="mt-4 flex flex-col sm:flex-row gap-4">
                    <button id="goBackButton" class="transition-all duration-300 bg-white hover:bg-gray-100 text-red-600 hover:text-red-700 font-bold py-3 px-6 rounded-lg flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2"></i> Return to Exam
                    </button>
                    <div id="saveMessage" class="text-sm text-white bg-black bg-opacity-30 py-2 px-4 rounded-lg flex items-center">
                        <i class="fas fa-circle-check mr-2"></i>
                        <?php echo htmlspecialchars($saveMessage); ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Column (Warning Image) -->
        <div class="w-full md:w-1/2 flex justify-center">
            <div class="relative w-full max-w-md">
                <div class="absolute inset-0 bg-red-600 rounded-2xl blur-md transform -rotate-3 scale-105 animate-pulse"></div>
                <div class="relative bg-black p-3 rounded-2xl shadow-xl overflow-hidden">
                    <h2 class="text-white text-lg font-bold mb-2 px-2 py-1 bg-red-800 rounded-lg inline-block">
                        <i class="fas fa-camera mr-2"></i> Violation Evidence
                    </h2>
                    <img id="warningImage" src="" alt="Violation Capture" class="w-full h-auto rounded-lg object-cover aspect-video">
                    <div id="noImageMessage" class="hidden p-8 text-center bg-gray-900 rounded-lg">
                        <i class="fas fa-triangle-exclamation text-red-500 text-4xl mb-4"></i>
                        <p class="text-gray-300 text-lg">No violation image is available at this time.</p>
                    </div>
                    <div class="absolute bottom-4 right-4 bg-black bg-opacity-70 px-2 py-1 rounded text-xs text-white">
                        <?php echo date('F j, Y - g:i:s A'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const imgElement = document.getElementById("warningImage");
            const noImageMessage = document.getElementById("noImageMessage");

            // Fetch and display the latest warning image
            fetch('http://127.0.0.1:5000/get_warning_image')
                .then(response => {
                    if (response.ok) {
                        return response.blob();
                    } else {
                        throw new Error("No warning image available.");
                    }
                })
                .then(blob => {
                    const imageUrl = URL.createObjectURL(blob);
                    imgElement.src = imageUrl;
                })
                .catch(error => {
                    console.error("Error loading warning image:", error);
                    imgElement.style.display = "none"; // Hide the image element
                    noImageMessage.style.display = "block"; // Show the fallback message
                });

            // Add event listener to the "Go Back" button
            const goBackButton = document.getElementById("goBackButton");
            goBackButton.addEventListener("click", () => {
                // Show loading state
                goBackButton.disabled = true;
                goBackButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Returning...';
                
                // First restart the Python session
                fetch('http://127.0.0.1:5000/restart', {
                    method: 'POST'
                })
                .then(response => response.json())
                .then(data => {
                    console.log("Python session restarted:", data);
                    
                    // Instead of history.back(), navigate directly to account.php with exam params
                    <?php if (isset($_SESSION['exam_active']) && $_SESSION['exam_active'] === true) { ?>
                        window.location.href = "<?php echo $_SESSION['exam_return_url']; ?>";
                    <?php } else { ?>
                        window.location.href = "account.php";
                    <?php } ?>
                })
                .catch(error => {
                    console.error("Failed to restart Python session:", error);
                    // Still navigate even if restart fails
                    <?php if (isset($_SESSION['exam_active']) && $_SESSION['exam_active'] === true) { ?>
                        window.location.href = "<?php echo $_SESSION['exam_return_url']; ?>";
                    <?php } else { ?>
                        window.location.href = "account.php";
                    <?php } ?>
                });
            });
            
            // Automatically hide the save message after 5 seconds
            setTimeout(() => {
                const saveMessage = document.getElementById("saveMessage");
                if (saveMessage) {
                    saveMessage.classList.add("opacity-0", "transition-opacity", "duration-500");
                    setTimeout(() => {
                        saveMessage.style.display = "none";
                    }, 500);
                }
            }, 5000);
        });
    </script>
</body>
</html>