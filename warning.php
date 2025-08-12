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
    $warning_id = $con->insert_id; // Get the ID of the inserted warning
} else {
    $saveMessage = "Failed to record warning: " . $con->error;
    $warning_id = null;
}

// Get warning count for this user
$warning_query = mysqli_query($con, "SELECT COUNT(*) AS warning_count FROM warning WHERE email='$email'");
$warning_data = mysqli_fetch_assoc($warning_query);
$warning_count = $warning_data['warning_count'];

// Determine violation level
$violation_level = "MINOR";
if ($warning_count >= 40) $violation_level = "CRITICAL";
elseif ($warning_count >= 30) $violation_level = "SEVERE";
elseif ($warning_count >= 20) $violation_level = "MAJOR";
elseif ($warning_count >= 10) $violation_level = "MODERATE";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FaceTrackED - Security Violation Detected</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3b82f6',     // Blue primary color
                        'primary-dark': '#1d4ed8', // Darker blue for hover states
                        'primary-light': '#93c5fd', // Light blue for subtle highlights
                        secondary: '#f59e0b',   // Amber secondary color
                        'secondary-dark': '#d97706', // Darker amber for hover states
                        accent: '#10b981',      // Green accent
                        'accent-dark': '#059669', // Darker green
                        danger: '#ef4444',      // Red for warnings
                        'danger-dark': '#dc2626', // Darker red
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'bounce-slow': 'bounce 3s infinite',
                        'gradient-x': 'gradient-x 15s ease infinite',
                        'blob': 'blob 20s infinite',
                        'shake': 'shake 0.5s ease-in-out infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        },
                        'gradient-x': {
                            '0%, 100%': {
                                'background-position': 'left center'
                            },
                            '50%': {
                                'background-position': 'right center'
                            },
                        },
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        },
                        shake: {
                            '0%, 100%': { transform: 'translateX(0)' },
                            '25%': { transform: 'translateX(-5px)' },
                            '75%': { transform: 'translateX(5px)' },
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(-45deg, #dc2626, #ef4444, #b91c1c, #991b1b);
            background-size: 400% 400%;
            animation: gradient-x 15s ease infinite;
        }
        
        .animated-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }
        
        .floating-icon {
            position: absolute;
            opacity: 0.3;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.15));
            z-index: -1;
            transition: all 0.3s ease;
            color: rgba(255, 255, 255, 0.6);
        }
        
        .floating-icon:hover {
            opacity: 0.5;
            transform: scale(1.2);
        }
        
        .blob {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            width: 300px;
            height: 300px;
            border-radius: 50%;
            animation: blob 20s infinite;
            z-index: -1;
            backdrop-filter: blur(10px);
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .warning-card {
            background: rgba(220, 38, 38, 0.2);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(239, 68, 68, 0.3);
            box-shadow: 0 25px 50px -12px rgba(220, 38, 38, 0.25);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            @apply text-white font-medium py-3 px-8 rounded-lg transform transition-all duration-300 hover:scale-105 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-primary focus:ring-opacity-50;
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            @apply text-white font-medium py-3 px-8 rounded-lg transform transition-all duration-300 hover:scale-105 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-danger focus:ring-opacity-50;
        }
        
        .btn-white {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            @apply text-red-600 font-semibold py-3 px-6 rounded-lg transform transition-all duration-300 hover:scale-105 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50;
            color: #dc2626 !important;
            border: 2px solid rgba(220, 38, 38, 0.3);
        }
        
        .btn-white:hover {
            background: rgba(255, 255, 255, 1);
            color: #991b1b !important;
            border-color: rgba(220, 38, 38, 0.5);
        }
        
        .violation-level {
            @apply px-4 py-3 rounded-lg border font-medium;
        }
        
        .violation-critical {
            background: rgba(153, 27, 27, 0.8);
            border-color: rgba(239, 68, 68, 0.5);
            @apply text-red-100;
        }
        
        .violation-severe {
            background: rgba(185, 28, 28, 0.7);
            border-color: rgba(239, 68, 68, 0.4);
            @apply text-red-100;
        }
        
        .violation-major {
            background: rgba(220, 38, 38, 0.6);
            border-color: rgba(239, 68, 68, 0.3);
            @apply text-red-100;
        }
        
        .violation-moderate {
            background: rgba(239, 68, 68, 0.5);
            border-color: rgba(248, 113, 113, 0.3);
            @apply text-red-100;
        }
        
        .evidence-frame {
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(239, 68, 68, 0.5);
        }
    </style>
</head>
<body class="min-h-screen overflow-x-hidden">
    <!-- Animated Background -->
    <div class="animated-bg">
        <div class="blob" style="top: 10%; left: 10%;"></div>
        <div class="blob" style="top: 60%; left: 80%;"></div>
        <div class="blob" style="top: 80%; left: 30%;"></div>
        
        <!-- Floating Warning Icons -->
        <i class="fas fa-exclamation-triangle floating-icon text-5xl animate-float" style="top: 15%; left: 10%;"></i>
        <i class="fas fa-shield-exclamation floating-icon text-4xl animate-pulse-slow" style="top: 30%; left: 85%;"></i>
        <i class="fas fa-eye-slash floating-icon text-5xl animate-bounce-slow" style="top: 70%; left: 15%;"></i>
        <i class="fas fa-ban floating-icon text-4xl animate-float" style="top: 80%; left: 80%; animation-delay: 2s;"></i>
        <i class="fas fa-user-slash floating-icon text-5xl animate-pulse-slow" style="top: 40%; left: 50%; animation-delay: 1s;"></i>
    </div>

    <!-- Header -->
    <header class="fixed w-full backdrop-blur-20 bg-black bg-opacity-30 shadow-xl z-50 border-b border-red-500/30">
        <div class="container mx-auto px-4 py-2 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-r from-danger to-red-600 shadow-lg animate-pulse">
                    <i class="fas fa-exclamation-triangle text-white text-lg"></i>
                </div>
                <div>
                    <h1 class="text-xl md:text-2xl font-bold text-white">
                        Face<span class="text-secondary">Track</span><span class="text-red-300">ED</span>
                    </h1>
                    <p class="text-xs text-red-200">Security Violation Alert</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-white opacity-90 text-sm">User: <span class="text-red-300 font-semibold"><?php echo htmlspecialchars($name); ?></span></span>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 pt-20 pb-4 flex flex-col lg:flex-row gap-6 items-stretch justify-center h-screen">
        <!-- Left Column (Warning Message) -->
        <div class="w-full lg:w-1/2 warning-card rounded-xl p-6 shadow-2xl text-white flex flex-col justify-between">
            <div>
                <div class="flex items-center mb-6">
                    <div class="rounded-full bg-white p-3 mr-4 animate-shake">
                        <i class="fas fa-shield-exclamation text-2xl text-red-600"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl md:text-3xl font-bold mb-1">Security Violation</h2>
                        <p class="text-red-200 text-base">Unauthorized behavior detected</p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="glass-card p-3 rounded-lg">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-camera-slash mt-1 text-red-300 text-lg"></i>
                            <div>
                                <h3 class="font-semibold text-white mb-1 text-sm">Face Position Violation</h3>
                                <p class="text-red-100 text-xs">Your face moved outside the authorized monitoring zone.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="glass-card p-3 rounded-lg">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-database mt-1 text-red-300 text-lg"></i>
                            <div>
                                <h3 class="font-semibold text-white mb-1 text-sm">Violation Recorded</h3>
                                <p class="text-red-100 text-xs">This security breach has been automatically logged.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="glass-card p-3 rounded-lg">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-chart-line mt-1 text-red-300 text-lg"></i>
                            <div>
                                <h3 class="font-semibold text-white mb-1 text-sm">Violation Count</h3>
                                <p class="text-red-100 text-xs">Total violations: 
                                    <span class="font-bold text-white bg-red-600 px-2 py-1 rounded-full ml-1"><?php echo $warning_count; ?></span>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <?php if ($warning_count >= 10) { ?>
                    <div class="<?php 
                        if ($warning_count >= 40) echo 'violation-critical';
                        elseif ($warning_count >= 30) echo 'violation-severe'; 
                        elseif ($warning_count >= 20) echo 'violation-major';
                        else echo 'violation-moderate';
                    ?> p-3">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-lg mr-3 text-red-200 animate-pulse"></i>
                            <div>
                                <h3 class="font-bold text-sm mb-1">
                                    <?php 
                                    if ($warning_count >= 40) echo "CRITICAL LEVEL";
                                    elseif ($warning_count >= 30) echo "SEVERE LEVEL";
                                    elseif ($warning_count >= 20) echo "MAJOR LEVEL";
                                    else echo "MODERATE LEVEL";
                                    ?>
                                </h3>
                                <p class="text-xs">
                                    <?php 
                                    if ($warning_count >= 40) {
                                        echo "Account at risk of suspension.";
                                    } elseif ($warning_count >= 30) {
                                        echo "Approaching violation threshold.";
                                    } elseif ($warning_count >= 20) {
                                        echo "Multiple serious violations detected.";
                                    } elseif ($warning_count >= 10) {
                                        echo "Elevated violation count detected.";
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
            
            <div class="mt-6 flex flex-col sm:flex-row gap-3">
                <button id="goBackButton" class="btn-white flex items-center justify-center text-sm py-3 px-6 w-full sm:w-auto">
                    <i class="fas fa-arrow-left mr-2"></i> 
                    <span>Return to Examination</span>
                </button>
                <div id="saveMessage" class="glass-card text-xs text-white py-2 px-3 rounded-lg flex items-center w-full sm:w-auto">
                    <i class="fas fa-check-circle mr-2 text-green-400"></i>
                    <span><?php echo htmlspecialchars($saveMessage); ?></span>
                </div>
            </div>
        </div>
        
        <!-- Right Column (Evidence Panel) -->
        <div class="w-full lg:w-1/2 flex justify-center">
            <div class="relative w-full max-w-md">
                <!-- Decorative background effects -->
                <div class="absolute inset-0 bg-gradient-to-r from-red-600 to-red-800 rounded-xl blur-lg transform -rotate-1 scale-105 animate-pulse opacity-50"></div>
                
                <!-- Main evidence container -->
                <div class="relative evidence-frame rounded-xl shadow-2xl overflow-hidden">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-red-600 to-red-800 p-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-red-400 rounded-full animate-pulse"></div>
                                <div class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse" style="animation-delay: 0.2s;"></div>
                                <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse" style="animation-delay: 0.4s;"></div>
                            </div>
                            <h3 class="text-white font-bold text-sm">
                                <i class="fas fa-video mr-2"></i> Security Camera
                            </h3>
                        </div>
                        <div class="mt-2 bg-black bg-opacity-30 px-2 py-1 rounded-full inline-block">
                            <span class="text-red-200 text-xs font-medium">
                                <i class="fas fa-circle text-red-400 mr-1 animate-pulse"></i>
                                VIOLATION RECORDED
                            </span>
                        </div>
                    </div>
                    
                    <!-- Evidence content -->
                    <div class="p-3 bg-gray-900">
                        <div class="relative">
                            <img id="warningImage" src="" alt="Security Violation Evidence" 
                                 class="w-full h-auto rounded-lg object-cover aspect-video border-2 border-red-500/50 shadow-xl max-h-48">
                            
                            <div id="noImageMessage" class="hidden p-8 text-center bg-gray-800 rounded-lg border-2 border-red-500/30 max-h-48 flex flex-col justify-center">
                                <div class="mb-3">
                                    <i class="fas fa-camera-slash text-red-400 text-3xl mb-3 animate-pulse"></i>
                                </div>
                                <h4 class="text-red-300 text-lg font-bold mb-1">No Evidence Available</h4>
                                <p class="text-gray-400 text-sm">Could not capture violation evidence.</p>
                                <div class="mt-2 text-xs text-gray-500">
                                    Camera access restricted or unavailable.
                                </div>
                            </div>
                            
                            <!-- Timestamp overlay -->
                            <div class="absolute bottom-2 right-2 bg-black bg-opacity-80 px-2 py-1 rounded">
                                <div class="text-white text-xs font-mono">
                                    <i class="fas fa-clock mr-1"></i>
                                    <?php echo date('M j - g:i A'); ?>
                                </div>
                            </div>
                            
                            <!-- Security watermark -->
                            <div class="absolute top-2 left-2 bg-red-600 bg-opacity-90 px-2 py-1 rounded text-xs text-white font-bold">
                                <i class="fas fa-shield-alt mr-1"></i>
                                SEC
                            </div>
                        </div>
                        
                        <!-- Technical details -->
                        <div class="mt-3 grid grid-cols-2 gap-3 text-xs">
                            <div class="bg-gray-800 p-2 rounded-lg border border-gray-700">
                                <div class="text-gray-400 mb-1">Type</div>
                                <div class="text-white font-medium text-xs">Face Position</div>
                            </div>
                            <div class="bg-gray-800 p-2 rounded-lg border border-gray-700">
                                <div class="text-gray-400 mb-1">Level</div>
                                <div class="text-red-400 font-medium text-xs">
                                    <?php 
                                    if ($warning_count >= 40) echo "CRITICAL";
                                    elseif ($warning_count >= 30) echo "SEVERE";
                                    elseif ($warning_count >= 20) echo "MAJOR";
                                    elseif ($warning_count >= 10) echo "MODERATE";
                                    else echo "MINOR";
                                    ?>
                                </div>
                            </div>
                        </div>
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
            console.log('Attempting to fetch warning image from Python service...');
            fetch('http://127.0.0.1:5000/get_warning_image')
                .then(response => {
                    console.log('Python service response status:', response.status);
                    if (response.ok) {
                        console.log('✓ Successfully received image from Python service');
                        return response.blob();
                    } else {
                        throw new Error("No warning image available. Status: " + response.status);
                    }
                })
                .then(blob => {
                    console.log('✓ Image blob received, size:', blob.size, 'bytes');
                    const imageUrl = URL.createObjectURL(blob);
                    imgElement.src = imageUrl;
                    console.log('✓ Image displayed in UI');
                    
                    // Save the image to database
                    console.log('→ Proceeding to save image to database...');
                    saveImageToDatabase(blob);
                })
                .catch(error => {
                    console.error("✗ Error loading warning image:", error);
                    imgElement.style.display = "none"; // Hide the image element
                    noImageMessage.style.display = "block"; // Show the fallback message
                });

            // Function to save image to database
            function saveImageToDatabase(imageBlob) {
                console.log('Starting image save process...');
                console.log('Image blob size:', imageBlob.size);
                
                const formData = new FormData();
                formData.append('image', imageBlob, 'warning_' + Date.now() + '.jpg');
                formData.append('warning_id', '<?php echo $warning_id ?? 0; ?>');
                formData.append('email', '<?php echo $email; ?>');
                formData.append('name', '<?php echo $name; ?>');
                formData.append('violation_count', '<?php echo $warning_count; ?>');
                formData.append('violation_level', '<?php echo $violation_level; ?>');
                
                // Debug: Log all form data
                for (let pair of formData.entries()) {
                    console.log(pair[0] + ': ' + pair[1]);
                }
                
                fetch('save_warning_image.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    console.log('Server response status:', response.status);
                    return response.text(); // Get text first to see what's returned
                })
                .then(text => {
                    console.log('Server response text:', text);
                    try {
                        const data = JSON.parse(text);
                        console.log('Image saved to database:', data);
                        if (data.success) {
                            console.log('✓ Image successfully saved with ID:', data.image_id);
                        } else {
                            console.error('✗ Image save failed:', data.message);
                        }
                    } catch (e) {
                        console.error('✗ Failed to parse JSON response:', e);
                        console.error('Raw response:', text);
                    }
                })
                .catch(error => {
                    console.error('✗ Network error saving image to database:', error);
                });
            }

            // Add event listener to the "Go Back" button
            const goBackButton = document.getElementById("goBackButton");
            goBackButton.addEventListener("click", () => {
                // Show loading state with better animation
                goBackButton.disabled = true;
                goBackButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-3"></i> <span>Reconnecting...</span>';
                goBackButton.classList.add('opacity-75', 'cursor-not-allowed');
                
                // First restart the Python session
                fetch('http://127.0.0.1:5000/restart', {
                    method: 'POST'
                })
                .then(response => response.json())
                .then(data => {
                    console.log("Python session restarted:", data);
                    
                    // Update button to show success
                    goBackButton.innerHTML = '<i class="fas fa-check mr-3"></i> <span>Redirecting...</span>';
                    
                    // Wait a moment then navigate
                    setTimeout(() => {
                        <?php if (isset($_SESSION['exam_active']) && $_SESSION['exam_active'] === true) { ?>
                            window.location.href = "<?php echo $_SESSION['exam_return_url']; ?>";
                        <?php } else { ?>
                            window.location.href = "account.php";
                        <?php } ?>
                    }, 1000);
                })
                .catch(error => {
                    console.error("Failed to restart Python session:", error);
                    // Show error state briefly
                    goBackButton.innerHTML = '<i class="fas fa-exclamation-triangle mr-3"></i> <span>Continuing anyway...</span>';
                    
                    // Still navigate even if restart fails
                    setTimeout(() => {
                        <?php if (isset($_SESSION['exam_active']) && $_SESSION['exam_active'] === true) { ?>
                            window.location.href = "<?php echo $_SESSION['exam_return_url']; ?>";
                        <?php } else { ?>
                            window.location.href = "account.php";
                        <?php } ?>
                    }, 1500);
                });
            });
            
            // Automatically hide the save message after 5 seconds with smooth animation
            setTimeout(() => {
                const saveMessage = document.getElementById("saveMessage");
                if (saveMessage) {
                    saveMessage.classList.add("opacity-0", "transition-opacity", "duration-1000", "transform", "scale-95");
                    setTimeout(() => {
                        saveMessage.style.display = "none";
                    }, 1000);
                }
            }, 4000);
        });
    </script>
</body>
</html>