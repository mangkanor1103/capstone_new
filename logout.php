<?php
session_start();
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

// Set a flag to indicate logout
file_put_contents('logout_flag.txt', '1'); // Create a flag file
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FaceTrackED - Logging Out</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
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
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'bounce-slow': 'bounce 3s infinite',
                        'gradient-x': 'gradient-x 15s ease infinite',
                        'blob': 'blob 20s infinite',
                        'fadeOut': 'fadeOut 0.5s ease-in-out',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        },
                        'gradient-x': {
                            '0%, 100%': {
                                'background-size': '200% 200%',
                                'background-position': 'left center'
                            },
                            '50%': {
                                'background-size': '200% 200%',
                                'background-position': 'right center'
                            },
                        },
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        },
                        fadeOut: {
                            '0%': { opacity: '1', transform: 'scale(1)' },
                            '100%': { opacity: '0', transform: 'scale(0.95)' },
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
            background: linear-gradient(-45deg, #667eea, #10b981, #3b82f6, #059669);
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
            opacity: 0.4;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.15));
            z-index: -1;
            transition: all 0.3s ease;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .floating-icon:hover {
            opacity: 0.7;
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
        
        .hero-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .logout-animation {
            animation: fadeOut 2s ease-in-out 2s forwards;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col justify-center items-center">
    <!-- Animated Background -->
    <div class="animated-bg">
        <div class="blob" style="top: 10%; left: 10%;"></div>
        <div class="blob" style="top: 60%; left: 80%;"></div>
        <div class="blob" style="top: 80%; left: 30%;"></div>
        <div class="blob" style="top: 20%; left: 60%;"></div>
        
        <!-- Floating Icons -->
        <i class="fas fa-sign-out-alt floating-icon text-5xl animate-float" style="top: 15%; left: 10%;"></i>
        <i class="fas fa-user-check floating-icon text-4xl animate-pulse-slow" style="top: 30%; left: 85%;"></i>
        <i class="fas fa-shield-check floating-icon text-5xl animate-bounce-slow" style="top: 70%; left: 15%;"></i>
        <i class="fas fa-lock floating-icon text-4xl animate-float" style="top: 80%; left: 80%; animation-delay: 2s;"></i>
        <i class="fas fa-check-circle floating-icon text-5xl animate-pulse-slow" style="top: 40%; left: 50%; animation-delay: 1s;"></i>
        <i class="fas fa-home floating-icon text-4xl animate-float" style="top: 60%; left: 20%; animation-delay: 3s;"></i>
    </div>

    <!-- Main Logout Card -->
    <div class="hero-card rounded-2xl shadow-2xl p-8 md:p-12 text-center max-w-md mx-4 logout-animation">
        <div class="mb-6">
            <div class="flex items-center justify-center w-20 h-20 bg-gradient-to-r from-primary to-accent rounded-full mx-auto mb-4 animate-pulse-slow">
                <i class="fas fa-sign-out-alt text-3xl text-white"></i>
            </div>
            <div class="flex items-center justify-center space-x-3 mb-4">
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-r from-primary to-accent shadow-lg">
                    <i class="fas fa-eye text-white text-lg"></i>
                </div>
                <h1 class="text-2xl font-bold text-white">
                    Face<span class="text-secondary">Track</span><span class="text-accent">ED</span>
                </h1>
            </div>
        </div>
        
        <h2 class="text-2xl font-bold text-white mb-3">Logging Out</h2>
        <p class="text-white/80 mb-6">Thank you for using FaceTrackED</p>
        
        <div class="flex items-center justify-center space-x-3 mb-6">
            <div class="w-3 h-3 bg-accent rounded-full animate-bounce"></div>
            <div class="w-3 h-3 bg-primary rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
            <div class="w-3 h-3 bg-secondary rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
        </div>
        
        <div class="text-white/70 text-sm">
            <i class="fas fa-info-circle mr-2"></i>
            Redirecting to login page...
        </div>
    </div>
    <script>
        // Clear any client-side storage
        sessionStorage.clear();
        localStorage.removeItem('examInProgress');
        
        // Show modern logout message with SweetAlert2
        Swal.fire({
            title: 'Logout Successful!',
            html: '<div class="text-center">' +
                  '<div class="mb-4">' +
                  '<i class="fas fa-check-circle text-4xl text-green-500 mb-2"></i>' +
                  '</div>' +
                  '<p class="text-lg font-medium mb-2">You have been successfully logged out</p>' +
                  '<p class="text-gray-600 mb-4">Thank you for using FaceTrackED Advanced Examination System</p>' +
                  '<div class="flex items-center justify-center space-x-2 mb-4">' +
                  '<div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-green-500 rounded-full flex items-center justify-center">' +
                  '<i class="fas fa-eye text-white"></i>' +
                  '</div>' +
                  '<span class="text-lg font-bold text-gray-800">Face<span class="text-orange-500">Track</span><span class="text-green-500">ED</span></span>' +
                  '</div>' +
                  '<p class="text-sm text-gray-500">Redirecting to login page...</p>' +
                  '</div>',
            icon: 'success',
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            willClose: () => {
                window.location.href = "index.php";
            },
            customClass: {
                popup: 'rounded-xl shadow-2xl border-2 border-green-100',
                title: 'text-xl font-bold text-gray-800',
                htmlContainer: 'text-base'
            },
            background: '#fff',
            iconColor: '#10b981'
        });
        
        // Redirect anyway after 3.5 seconds (as a fallback)
        setTimeout(() => {
            window.location.href = "index.php";
        }, 3500);
    </script>
</body>
</html>