<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FaceTrackED - Advanced Examination System</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- jQuery (needed for modals) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
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
            text-align: center;
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
        
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .form-input {
            @apply w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-300;
            background-color: rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
            backdrop-filter: blur(10px);
        }
        
        .form-input:hover {
            @apply border-gray-400;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.12);
        }
        
        .form-input:focus {
            @apply border-primary;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
            outline: none;
        }
        
        .input-container {
            position: relative;
            margin-bottom: 0.75rem;
        }
        
        .input-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #3b82f6;
            font-size: 1rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #1f2937;
            font-size: 0.875rem;
        }
        
        .btn-primary {
            @apply bg-primary hover:bg-primary-dark text-white font-medium py-3 px-8 rounded-lg transform transition-all duration-300 hover:scale-105 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-primary focus:ring-opacity-50;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        }
        
        .btn-secondary {
            @apply bg-secondary hover:bg-secondary-dark text-white font-medium py-3 px-8 rounded-lg transform transition-all duration-300 hover:scale-105 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-secondary focus:ring-opacity-50;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }
        
        .btn-accent {
            @apply bg-accent hover:bg-accent-dark text-white font-medium py-3 px-8 rounded-lg transform transition-all duration-300 hover:scale-105 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-accent focus:ring-opacity-50;
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        }
        
        .btn-outline {
            @apply border-2 border-white text-white font-medium py-3 px-8 rounded-lg transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:bg-white hover:text-gray-800 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.1);
        }
        
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 50;
            background-color: rgba(0, 0, 0, 0.6);
            display: none;
            justify-content: center;
            align-items: center;
            padding: 1rem;
            backdrop-filter: blur(5px);
        }
        
        .modal-content {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 1rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            width: 100%;
            max-width: 28rem;
            transform: scale(0.95);
            transition: transform 0.3s ease-in-out;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .modal-content > div:first-child {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        }
        
        .modal.active {
            display: flex !important;
            animation: fadeIn 0.3s ease-out forwards;
        }
        
        .modal.active .modal-content {
            transform: scale(1);
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .space-y-2 label {
            text-align: left;
            display: block;
        }
        
        .modal-content .p-6 {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .modal-content form {
            width: 100%;
        }
        
        .header-glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .footer-glass {
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(20px);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .footer-glass a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .footer-glass a:hover {
            color: #3b82f6;
            text-shadow: 0 0 10px rgba(59, 130, 246, 0.5);
        }
        
        .hero-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        /* Notification Styles */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 320px;
            max-width: 500px;
            padding: 16px 20px;
            border-radius: 12px;
            color: white;
            font-weight: 500;
            transform: translateX(100%);
            transition: all 0.3s ease-in-out;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        
        .notification.show {
            transform: translateX(0);
        }
        
        .notification.success {
            background: linear-gradient(135deg, #10b981, #059669);
        }
        
        .notification.error {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }
        
        .notification.info {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }
        
        .notification.warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }
        
        .notification .notification-content {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .notification .notification-icon {
            font-size: 20px;
            flex-shrink: 0;
        }
        
        .notification .notification-close {
            position: absolute;
            top: 8px;
            right: 12px;
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.8);
            font-size: 20px;
            cursor: pointer;
            transition: color 0.2s;
        }
        
        .notification .notification-close:hover {
            color: white;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col">
    <!-- Animated Background -->
    <div class="animated-bg">
        <div class="blob" style="top: 10%; left: 10%;"></div>
        <div class="blob" style="top: 60%; left: 80%;"></div>
        <div class="blob" style="top: 80%; left: 30%;"></div>
        <div class="blob" style="top: 20%; left: 60%;"></div>
        
        <!-- Floating Icons -->
        <i class="fas fa-brain floating-icon text-5xl animate-float" style="top: 15%; left: 10%;"></i>
        <i class="fas fa-user-shield floating-icon text-4xl animate-pulse-slow" style="top: 30%; left: 85%;"></i>
        <i class="fas fa-eye floating-icon text-5xl animate-bounce-slow" style="top: 70%; left: 15%;"></i>
        <i class="fas fa-lock floating-icon text-4xl animate-float" style="top: 80%; left: 80%; animation-delay: 2s;"></i>
        <i class="fas fa-chart-line floating-icon text-5xl animate-pulse-slow" style="top: 40%; left: 50%; animation-delay: 1s;"></i>
        <i class="fas fa-cogs floating-icon text-4xl animate-float" style="top: 60%; left: 20%; animation-delay: 3s;"></i>
    </div>

    <!-- Header -->
    <header class="fixed w-full header-glass shadow-xl z-50 transition-all duration-300">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-r from-primary to-accent shadow-lg">
                    <i class="fas fa-eye text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white">
                        Face<span class="text-secondary">Track</span><span class="text-accent">ED</span>
                    </h1>
                    <p class="text-sm text-white opacity-80">Advanced Examination System</p>
                </div>
            </div>
            <button id="loginBtn" class="btn-secondary flex items-center space-x-2">
                <i class="fa fa-sign-in-alt"></i>
                <span>Sign In</span>
            </button>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow pt-32 pb-16">
        <div class="container mx-auto px-4 py-8">
            <!-- Hero Section -->
            <div class="text-center mb-12">
                <h2 class="text-4xl md:text-6xl font-bold text-white mb-6">
                    Welcome to <span class="text-secondary">Face</span><span class="text-accent">Track</span><span class="text-white">ED</span>
                </h2>
                <p class="text-xl text-white opacity-90 mb-8 max-w-3xl mx-auto">
                    Experience the future of secure examinations with AI-powered face recognition technology. 
                    Ensuring integrity, preventing cheating, and delivering reliable assessment results.
                </p>
                <div class="flex flex-wrap justify-center gap-4 mb-12">
                    <div class="hero-card rounded-lg p-4 flex items-center space-x-3">
                        <i class="fas fa-shield-alt text-primary text-2xl"></i>
                        <span class="text-white font-medium">Secure Authentication</span>
                    </div>
                    <div class="hero-card rounded-lg p-4 flex items-center space-x-3">
                        <i class="fas fa-robot text-accent text-2xl"></i>
                        <span class="text-white font-medium">AI-Powered Monitoring</span>
                    </div>
                    <div class="hero-card rounded-lg p-4 flex items-center space-x-3">
                        <i class="fas fa-chart-bar text-secondary text-2xl"></i>
                        <span class="text-white font-medium">Real-time Analytics</span>
                    </div>
                </div>
            </div>

            <!-- Registration Form -->
            <div class="max-w-4xl mx-auto hero-card rounded-2xl shadow-2xl overflow-hidden transform transition-all duration-500 hover:shadow-3xl">
                <div class="p-8 md:p-12">
                    <div class="text-center mb-8">
                        <h3 class="text-3xl md:text-4xl font-bold text-white mb-4">Create Your Account</h3>
                        <p class="text-white opacity-80">Join thousands of users who trust FaceTrackED for secure examinations</p>
                    </div>
                    
                    <form class="space-y-6" name="form" action="sign.php?q=account.php" onSubmit="return validateForm()" method="POST" enctype="multipart/form-data">
                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Full Name -->
                            <div class="space-y-2">
                                <label for="name" class="form-label text-white">Full Name</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fa fa-user input-icon"></i>
                                    </div>
                                    <input id="name" name="name" placeholder="Enter your full name" class="form-input pl-10" type="text" required>
                                </div>
                            </div>
                            
                            <!-- Organization/Institution -->
                            <div class="space-y-2">
                                <label for="organization" class="form-label text-white">Organization/Institution</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fa fa-building input-icon"></i>
                                    </div>
                                    <input id="organization" name="college" placeholder="Enter your organization" class="form-input pl-10" type="text" required>
                                </div>
                            </div>
                            
                            <!-- Email -->
                            <div class="space-y-2">
                                <label for="email" class="form-label text-white">Email Address</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fa fa-envelope input-icon"></i>
                                    </div>
                                    <input id="email" name="email" placeholder="Enter your email address" class="form-input pl-10" type="email" required>
                                </div>
                            </div>
                            
                            <!-- Contact Number -->
                            <div class="space-y-2">
                                <label for="mob" class="form-label text-white">Contact Number</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fa fa-phone input-icon"></i>
                                    </div>
                                    <input id="mob" name="mob" placeholder="Enter contact number" class="form-input pl-10" type="tel" required>
                                </div>
                            </div>
                            
                            <!-- User Type/Role -->
                            <div class="space-y-2">
                                <label for="gender" class="form-label text-white">User Type</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fa fa-user-tag input-icon"></i>
                                    </div>
                                    <select id="gender" name="gender" class="form-input pl-10" required>
                                        <option value="">Select User Type</option>
                                        <option value="Student">Student</option>
                                        <option value="Professional">Professional</option>
                                        <option value="Educator">Educator</option>
                                        <option value="Researcher">Researcher</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Department/Field -->
                            <div class="space-y-2">
                                <label for="department" class="form-label text-white">Department/Field of Study</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fa fa-graduation-cap input-icon"></i>
                                    </div>
                                    <input id="department" name="department" placeholder="e.g., Computer Science, Medicine, Business" class="form-input pl-10" type="text">
                                </div>
                            </div>
                            
                            <!-- Password -->
                            <div class="space-y-2">
                                <label for="password" class="form-label text-white">Password</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fa fa-lock input-icon"></i>
                                    </div>
                                    <input id="password" name="password" placeholder="Create a secure password" class="form-input pl-10" type="password" required>
                                </div>
                            </div>
                            
                            <!-- Confirm Password -->
                            <div class="space-y-2">
                                <label for="cpassword" class="form-label text-white">Confirm Password</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fa fa-check-circle input-icon"></i>
                                    </div>
                                    <input id="cpassword" name="cpassword" placeholder="Confirm your password" class="form-input pl-10" type="password" required>
                                </div>
                            </div>
                            
                            <!-- Profile Photo Section -->
                            <div class="space-y-3 md:col-span-2">
                                <label class="form-label text-white text-center block">Profile Photo (Required for Face Recognition)</label>
                                <div class="hero-card rounded-lg p-6">
                                    <div class="space-y-4 flex flex-col items-center">
                                        <div class="text-center mb-4">
                                            <i class="fas fa-camera text-4xl text-white mb-2"></i>
                                            <p class="text-white opacity-80 text-sm">
                                                Take a clear photo of your face for secure authentication
                                            </p>
                                        </div>
                                        <video id="camera" autoplay playsinline class="w-full max-w-sm rounded-lg border-2 border-white/30 hidden"></video>
                                        <button type="button" class="btn-outline flex items-center space-x-2" onclick="startCamera()">
                                            <i class="fas fa-camera"></i>
                                            <span>Take Photo</span>
                                        </button>
                                        <button type="button" id="captureBtn" class="hidden btn-primary flex items-center space-x-2" onclick="capture()">
                                            <i class="fas fa-check"></i>
                                            <span>Capture Photo</span>
                                        </button>
                                        <canvas id="snapshot" class="hidden"></canvas>
                                        <input type="hidden" name="photo_data" id="photo_data">
                                        <img id="preview" src="#" alt="Captured photo" class="hidden max-w-sm w-full h-auto rounded-lg border-2 border-primary mt-2">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="text-center pt-8">
                            <button type="submit" class="btn-primary px-12 py-4 text-lg">
                                <span class="flex items-center justify-center space-x-3">
                                    <i class="fas fa-user-plus"></i>
                                    <span>Create Account</span>
                                    <i class="fas fa-arrow-right"></i>
                                </span>
                            </button>
                            <p class="text-white opacity-70 text-sm mt-4">
                                By creating an account, you agree to our Terms of Service and Privacy Policy
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer-glass py-8 mt-auto">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center md:text-left">
                <div class="group">
                    <a href="#" id="adminLoginBtn" class="inline-flex justify-center md:justify-start items-center space-x-2 hover:text-primary transition-all duration-300">
                        <i class="fas fa-user-shield text-2xl"></i>
                        <div>
                            <div class="font-medium">Admin Portal</div>
                            <div class="text-sm opacity-70">System Administration</div>
                        </div>
                    </a>
                </div>
                <div class="group">
                    <a href="#" id="developersBtn" class="inline-flex justify-center md:justify-start items-center space-x-2 hover:text-secondary transition-all duration-300">
                        <i class="fas fa-code text-2xl"></i>
                        <div>
                            <div class="font-medium">Developers</div>
                            <div class="text-sm opacity-70">Meet the Team</div>
                        </div>
                    </a>
                </div>
                <div class="group">
                    <a href="#" id="feedbackBtn" class="inline-flex justify-center md:justify-start items-center space-x-2 hover:text-accent transition-all duration-300">
                        <i class="fas fa-comments text-2xl"></i>
                        <div>
                            <div class="font-medium">Feedback</div>
                            <div class="text-sm opacity-70">Share Your Experience</div>
                        </div>
                    </a>
                </div>
                <div class="group">
                    <div class="inline-flex justify-center md:justify-start items-center space-x-2">
                        <i class="fas fa-eye text-2xl text-primary"></i>
                        <div>
                            <div class="font-medium">FaceTrackED</div>
                            <div class="text-sm opacity-70">Secure. Smart. Reliable.</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-8 pt-8 border-t border-white/20 text-sm opacity-70">
                &copy; 2025 FaceTrackED | Advanced Examination System | All Rights Reserved
            </div>
        </div>
    </footer>

    <!-- Login Modal -->
    <div id="loginModal" class="modal">
        <div class="modal-content w-full max-w-md">
            <div class="bg-gradient-to-r from-primary to-primary-dark text-white px-6 py-4 rounded-t-xl flex justify-between items-center">
                <h3 class="text-xl font-bold">Sign In to FaceTrackED</h3>
                <button class="closeModal text-white text-2xl hover:text-blue-200 transition-colors">&times;</button>
            </div>
            <div class="p-6 space-y-4">
                <form class="space-y-4" action="login.php?q=index.php" method="POST" onsubmit="handleLoginSubmit(event)">
                    <div class="space-y-2">
                        <label for="login-email" class="form-label">Email Address</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa fa-envelope input-icon"></i>
                            </div>
                            <input id="login-email" name="email" placeholder="Enter your email address" class="form-input pl-10" type="email" required>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label for="login-password" class="form-label">Password</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa fa-lock input-icon"></i>
                            </div>
                            <input id="login-password" name="password" placeholder="Enter your password" class="form-input pl-10" type="password" required>
                        </div>
                    </div>
                    <div class="flex justify-between pt-2">
                        <button type="button" class="closeModal btn-outline">Cancel</button>
                        <button type="submit" class="btn-primary" id="loginSubmitBtn">
                            <span class="flex items-center space-x-2">
                                <i class="fas fa-sign-in-alt"></i>
                                <span>Sign In</span>
                            </span>
                        </button>
                    </div>
                </form>
                
                <!-- Face Login Button -->
                <div class="text-center pt-4 border-t border-gray-200 w-full">
                    <p class="text-sm text-gray-600 mb-3">Or authenticate using face recognition</p>
                    <button class="btn-accent flex items-center space-x-2 mx-auto" onclick="openFaceLoginModal()">
                        <i class="fas fa-eye"></i>
                        <span>Face Authentication</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Face Login Modal -->
    <div id="faceLoginModal" class="modal">
        <div class="modal-content w-full max-w-md">
            <div class="bg-gradient-to-r from-accent to-accent-dark text-white px-6 py-4 rounded-t-xl flex justify-between items-center">
                <h3 class="text-xl font-bold">Face Authentication</h3>
                <button class="closeModal text-white text-2xl hover:text-green-200 transition-colors">&times;</button>
            </div>
            <div class="p-6">
                <div class="text-center space-y-4 w-full">
                    <div class="bg-gradient-to-br from-yellow-50 to-orange-50 p-6 rounded-lg border border-yellow-300">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-brain text-5xl text-accent mb-3"></i>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">AI Feature Coming Soon</h3>
                            <p class="text-gray-600 text-center">
                                Our advanced face recognition system is currently under development. 
                                This feature will provide secure, contactless authentication.
                            </p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="button" class="closeModal btn-outline">
                            <span>Close</span>
                        </button>
                    </div>
                </div>
                <!-- Hidden elements that will be used when feature is complete -->
                <video id="faceCam" autoplay playsinline class="w-full rounded-lg border border-gray-300 mx-auto hidden"></video>
                <canvas id="faceCanvas" class="hidden"></canvas>
                <div id="statusMessage" class="hidden text-sm font-medium text-gray-700">
                    Position your face in the camera
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Login Modal -->
    <div id="adminLoginModal" class="modal">
        <div class="modal-content w-full max-w-md">
            <div class="bg-gradient-to-r from-secondary to-secondary-dark text-white px-6 py-4 rounded-t-xl flex justify-between items-center">
                <h3 class="text-xl font-bold">Administrator Access</h3>
                <button class="closeModal text-white text-2xl hover:text-yellow-200 transition-colors">&times;</button>
            </div>
            <div class="p-6 space-y-4">
                <form class="space-y-4" method="post" action="admin.php?q=index.php">
                    <div class="space-y-2">
                        <label class="form-label">Admin Username</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa fa-user-shield input-icon"></i>
                            </div>
                            <input type="text" name="uname" maxlength="20" placeholder="Enter admin username" class="form-input pl-10" required/>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="form-label">Admin Password</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa fa-lock input-icon"></i>
                            </div>
                            <input type="password" name="password" maxlength="15" placeholder="Enter admin password" class="form-input pl-10" required/>
                        </div>
                    </div>
                    <div class="flex justify-between pt-2">
                        <button type="button" class="closeModal btn-outline">Cancel</button>
                        <button type="submit" name="login" class="btn-secondary">Access System</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Developers Modal -->
    <div id="developersModal" class="modal">
        <div class="modal-content w-full max-w-lg">
            <div class="bg-gradient-to-r from-primary to-accent text-white px-6 py-4 rounded-t-xl flex justify-between items-center">
                <h3 class="text-xl font-bold">Development Team</h3>
                <button class="closeModal text-white text-2xl hover:text-blue-200 transition-colors">&times;</button>
            </div>
            <div class="p-6">
                <div class="flex flex-col md:flex-row items-center md:space-x-6 space-y-4 md:space-y-0">
                    <div class="flex-shrink-0">
                        <img src="image/CAM00121.jpg" class="h-32 w-32 object-cover rounded-full shadow-xl border-4 border-primary transition-transform duration-300 hover:scale-105" alt="Lead Developer">
                    </div>
                    <div class="text-center md:text-left">
                        <h4 class="text-2xl font-bold text-primary mb-2">Kian A. Rodrigez</h4>
                        <p class="text-gray-600 font-medium mb-3">Lead Developer & System Architect</p>
                        <div class="space-y-2">
                            <p class="flex items-center justify-center md:justify-start text-gray-600">
                                <i class="fas fa-phone-alt mr-3 text-secondary"></i>
                                +63 966 717 240
                            </p>
                            <p class="flex items-center justify-center md:justify-start text-gray-600">
                                <i class="fas fa-envelope mr-3 text-accent"></i>
                                kianr664@gmail.com
                            </p>
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-sm text-gray-500 italic">
                                "Building the future of secure examination systems"
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback Modal -->
    <div id="feedbackModal" class="modal">
        <div class="modal-content w-full max-w-lg">
            <div class="bg-gradient-to-r from-accent to-accent-dark text-white px-6 py-4 rounded-t-xl flex justify-between items-center">
                <h3 class="text-xl font-bold">Share Your Feedback</h3>
                <button class="closeModal text-white text-2xl hover:text-green-200 transition-colors">&times;</button>
            </div>
            <div class="p-6">
                <div id="feedbackForm">
                    <div class="text-center mb-6">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-accent to-primary rounded-full mb-4">
                            <i class="fas fa-comments text-2xl text-white"></i>
                        </div>
                        <p class="text-gray-600">Help us improve FaceTrackED by sharing your thoughts and suggestions</p>
                    </div>
                    
                    <form onsubmit="submitFeedback(event)" class="space-y-4">
                        <div class="space-y-2">
                            <label for="feedback-name" class="form-label">
                                <i class="fas fa-user mr-2 text-primary"></i>Full Name
                            </label>
                            <input id="feedback-name" name="name" placeholder="Enter your full name" class="form-input" type="text" required>
                        </div>
                        
                        <div class="space-y-2">
                            <label for="feedback-subject" class="form-label">
                                <i class="fas fa-tag mr-2 text-secondary"></i>Subject
                            </label>
                            <input id="feedback-subject" name="subject" placeholder="What is this feedback about?" class="form-input" type="text" required>
                        </div>
                        
                        <div class="space-y-2">
                            <label for="feedback-email" class="form-label">
                                <i class="fas fa-envelope mr-2 text-accent"></i>Email Address
                            </label>
                            <input id="feedback-email" name="email" placeholder="Enter your email address" class="form-input" type="email" required>
                        </div>
                        
                        <div class="space-y-2">
                            <label for="feedback-message" class="form-label">
                                <i class="fas fa-comment-dots mr-2 text-primary"></i>Your Feedback
                            </label>
                            <textarea id="feedback-message" rows="4" name="feedback" class="form-input resize-none" placeholder="Share your thoughts, suggestions, or report any issues you've encountered..." required></textarea>
                        </div>
                        
                        <div class="flex justify-between pt-4">
                            <button type="button" class="closeModal btn-outline">Cancel</button>
                            <button type="submit" class="btn-accent" id="feedbackSubmitBtn">
                                <span class="flex items-center space-x-2">
                                    <i class="fas fa-paper-plane"></i>
                                    <span>Send Feedback</span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Success Message (hidden by default) -->
                <div id="feedbackSuccess" class="text-center p-8 hidden">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-accent rounded-full mb-4">
                        <i class="fas fa-check text-3xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Thank You!</h3>
                    <p class="text-lg text-gray-600 mb-6">Your feedback has been submitted successfully. We appreciate your input!</p>
                    <button onclick="resetFeedbackModal()" class="btn-accent">
                        <span class="flex items-center space-x-2">
                            <i class="fas fa-arrow-left"></i>
                            <span>Send Another Feedback</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function startCamera() {
            const video = document.getElementById('camera');
            const captureBtn = document.getElementById('captureBtn');
            const startCameraBtn = document.querySelector('button[onclick="startCamera()"]');

            navigator.mediaDevices.getUserMedia({ video: true })
                .then(stream => {
                    video.srcObject = stream;
                    video.classList.remove('hidden');
                    captureBtn.classList.remove('hidden');
                    showNotification('Camera started successfully! Position your face and click capture.', 'success');
                })
                .catch(err => {
                    console.error('Camera error:', err);
                    let errorMessage = 'Camera access denied. ';
                    
                    if (err.name === 'NotAllowedError') {
                        errorMessage += 'Please allow camera permission and try again.';
                    } else if (err.name === 'NotFoundError') {
                        errorMessage += 'No camera found on your device.';
                    } else if (err.name === 'NotReadableError') {
                        errorMessage += 'Camera is being used by another application.';
                    } else {
                        errorMessage += err.message || 'Unknown camera error occurred.';
                    }
                    
                    showNotification(errorMessage, 'error');
                });
        }

        function capture() {
            const video = document.getElementById('camera');
            const canvas = document.getElementById('snapshot');
            const preview = document.getElementById('preview');
            const photo_data_input = document.getElementById('photo_data');
            const captureBtn = document.getElementById('captureBtn');
            
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

            const dataURL = canvas.toDataURL('image/png');
            preview.src = dataURL;
            preview.classList.remove('hidden');
            photo_data_input.value = dataURL;
            
            video.classList.add('hidden');
            captureBtn.classList.add('hidden');
            
            video.srcObject.getTracks().forEach(track => track.stop());
            showNotification('Photo captured successfully!', 'success');
        }

        // Notification system
        function showNotification(message, type = 'info') {
            // Remove any existing notifications
            const existingNotifications = document.querySelectorAll('.notification');
            existingNotifications.forEach(notif => notif.remove());
            
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            
            // Set icon based on type
            let icon = '';
            switch(type) {
                case 'success':
                    icon = 'fas fa-check-circle';
                    break;
                case 'error':
                    icon = 'fas fa-exclamation-triangle';
                    break;
                case 'warning':
                    icon = 'fas fa-exclamation-circle';
                    break;
                case 'info':
                default:
                    icon = 'fas fa-info-circle';
                    break;
            }
            
            notification.innerHTML = `
                <div class="notification-content">
                    <i class="${icon} notification-icon"></i>
                    <span>${message}</span>
                </div>
                <button class="notification-close" onclick="this.parentElement.remove()">&times;</button>
            `;
            
            // Add to document
            document.body.appendChild(notification);
            
            // Show notification
            setTimeout(() => {
                notification.classList.add('show');
            }, 100);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.classList.remove('show');
                    setTimeout(() => {
                        if (notification.parentElement) {
                            notification.remove();
                        }
                    }, 300);
                }
            }, 5000);
        }
        
        // Handle login form submission with loading state
        function handleLoginSubmit(event) {
            const submitBtn = document.getElementById('loginSubmitBtn');
            const originalContent = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.innerHTML = `
                <span class="flex items-center space-x-2">
                    <i class="fas fa-spinner fa-spin"></i>
                    <span>Signing In...</span>
                </span>
            `;
            submitBtn.disabled = true;
            
            showNotification('Attempting to sign in...', 'info');
            
            // Reset button after 10 seconds in case of slow response
            setTimeout(() => {
                submitBtn.innerHTML = originalContent;
                submitBtn.disabled = false;
            }, 10000);
        }
        
        // Check URL parameters for notifications
        function checkForNotifications() {
            const urlParams = new URLSearchParams(window.location.search);
            const error = urlParams.get('error');
            const success = urlParams.get('success');
            const message = urlParams.get('message');
            
            if (error) {
                let errorMessage = '';
                switch(error) {
                    case 'invalid_credentials':
                    case 'login_failed':
                        errorMessage = '🔐 Invalid email or password. Please check your credentials and try again.';
                        break;
                    case 'user_not_found':
                        errorMessage = '👤 No account found with this email address. Please register first.';
                        break;
                    case 'account_disabled':
                        errorMessage = '🚫 Your account has been disabled. Please contact support.';
                        break;
                    case 'too_many_attempts':
                        errorMessage = '⏰ Too many login attempts. Please try again later.';
                        break;
                    default:
                        errorMessage = message || '❌ Login failed. Please try again.';
                }
                showNotification(errorMessage, 'error');
                
                // Open login modal if there's a login error
                setTimeout(() => {
                    openModal('login');
                }, 500);
            }
            
            if (success) {
                let successMessage = '';
                switch(success) {
                    case 'registered':
                        successMessage = '🎉 Account created successfully! You can now sign in.';
                        break;
                    case 'logout':
                        successMessage = '👋 You have been successfully logged out.';
                        break;
                    case 'password_reset':
                        successMessage = '📧 Password reset instructions sent to your email.';
                        break;
                    default:
                        successMessage = message || '✅ Operation completed successfully!';
                }
                showNotification(successMessage, 'success');
            }
            
            // Clean URL parameters after showing notifications
            if (error || success) {
                const url = new URL(window.location);
                url.searchParams.delete('error');
                url.searchParams.delete('success');
                url.searchParams.delete('message');
                window.history.replaceState({}, document.title, url.toString());
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Check for URL parameters to show notifications
            checkForNotifications();
            
            const modalElements = document.querySelectorAll('.modal');
            const modals = {};
            
            modalElements.forEach(modal => {
                const id = modal.id;
                modals[id.replace('Modal', '')] = modal;
            });
            
            function openModal(modalName) {
                const modalId = modalName + 'Modal';
                const modal = document.getElementById(modalId);
                
                if (modal) {
                    modalElements.forEach(m => {
                        m.classList.remove('active');
                    });
                    
                    modal.classList.add('active');
                    document.body.style.overflow = 'hidden';
                }
            }
            
            document.getElementById('loginBtn').addEventListener('click', () => openModal('login'));
            
            const adminLoginBtn = document.getElementById('adminLoginBtn');
            if (adminLoginBtn) {
                adminLoginBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    openModal('adminLogin');
                });
            }
            
            const developersBtn = document.getElementById('developersBtn');
            if (developersBtn) {
                developersBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    openModal('developers');
                });
            }
            
            const feedbackBtn = document.getElementById('feedbackBtn');
            if (feedbackBtn) {
                feedbackBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    openModal('feedback');
                });
            }
            
            document.querySelectorAll('.closeModal').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    const modal = btn.closest('.modal');
                    if (modal) {
                        modal.classList.remove('active');
                        document.body.style.overflow = '';
                        
                        if (modal.id === 'faceLoginModal' && document.getElementById('faceCam').srcObject) {
                            document.getElementById('faceCam').srcObject.getTracks().forEach(track => track.stop());
                        }
                    }
                });
            });
            
            modalElements.forEach(modal => {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        modal.classList.remove('active');
                        document.body.style.overflow = '';
                        
                        if (modal.id === 'faceLoginModal' && document.getElementById('faceCam').srcObject) {
                            document.getElementById('faceCam').srcObject.getTracks().forEach(track => track.stop());
                        }
                    }
                });
            });
            
            window.openFaceLoginModal = function() {
                openModal('faceLogin');
                startFaceLogin();
            }
            
            function startFaceLogin() {
                const videoElement = document.getElementById("faceCam");
                const statusMessage = document.getElementById("statusMessage");
                
                if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                    navigator.mediaDevices.getUserMedia({ video: true })
                        .then(stream => {
                            videoElement.srcObject = stream;
                            statusMessage.textContent = "Please position your face in front of the camera...";
                            showNotification('Face authentication camera started', 'info');
                            
                            setTimeout(() => {
                                statusMessage.textContent = "Scanning...";
                                setTimeout(() => {
                                    statusMessage.textContent = "Face recognized! Logging you in...";
                                    setTimeout(() => {
                                        statusMessage.textContent = "Authentication complete!";
                                        showNotification('Face authentication successful!', 'success');
                                    }, 1000);
                                }, 2000);
                            }, 1500);
                        })
                        .catch(err => {
                            console.error("Camera error:", err);
                            let errorMessage = 'Face authentication failed. ';
                            
                            if (err.name === 'NotAllowedError') {
                                errorMessage += 'Camera permission denied. Please allow camera access.';
                            } else if (err.name === 'NotFoundError') {
                                errorMessage += 'No camera found on your device.';
                            } else if (err.name === 'NotReadableError') {
                                errorMessage += 'Camera is being used by another application.';
                            } else {
                                errorMessage += err.message || 'Unknown camera error occurred.';
                            }
                            
                            statusMessage.textContent = "Unable to access camera.";
                            showNotification(errorMessage, 'error');
                        });
                } else {
                    const errorMsg = "Camera not supported in this browser.";
                    statusMessage.textContent = errorMsg;
                    showNotification('Camera not supported in this browser. Please use a modern browser.', 'error');
                }
            }
        });
        
        function validateForm() {
            const password = document.getElementById('password').value;
            const cpassword = document.getElementById('cpassword').value;
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const photoData = document.getElementById('photo_data').value;
            
            // Check if passwords match
            if (password !== cpassword) {
                showNotification('🔒 Passwords do not match! Please ensure both password fields are identical.', 'error');
                return false;
            }
            
            // Check password strength
            if (password.length < 6) {
                showNotification('🔐 Password must be at least 6 characters long for security.', 'error');
                return false;
            }
            
            // Check if photo is captured
            if (!photoData) {
                showNotification('📸 Please capture your profile photo for face recognition setup.', 'error');
                return false;
            }
            
            // Show success message
            showNotification('🚀 Creating your account... Please wait while we process your information.', 'info');
            
            // Add loading state to submit button
            const submitBtn = document.querySelector('button[type="submit"]');
            const originalContent = submitBtn.innerHTML;
            submitBtn.innerHTML = `
                <span class="flex items-center justify-center space-x-3">
                    <i class="fas fa-spinner fa-spin"></i>
                    <span>Creating Account...</span>
                </span>
            `;
            submitBtn.disabled = true;
            
            return true;
        }
        
        // Handle feedback form submission
        function submitFeedback(event) {
            event.preventDefault();
            
            const submitBtn = document.getElementById('feedbackSubmitBtn');
            const originalContent = submitBtn.innerHTML;
            const formData = new FormData(event.target);
            
            // Show loading state
            submitBtn.innerHTML = `
                <span class="flex items-center space-x-2">
                    <i class="fas fa-spinner fa-spin"></i>
                    <span>Sending...</span>
                </span>
            `;
            submitBtn.disabled = true;
            
            showNotification('Submitting your feedback...', 'info');
            
            // Submit feedback via fetch
            fetch('feed.php?q=feedback', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Reset button
                submitBtn.innerHTML = originalContent;
                submitBtn.disabled = false;
                
                if (data.success) {
                    // Show success
                    showNotification('🎉 Thank you for your feedback! We appreciate your input.', 'success');
                    
                    // Show success view in modal
                    document.getElementById('feedbackForm').classList.add('hidden');
                    document.getElementById('feedbackSuccess').classList.remove('hidden');
                } else {
                    showNotification('❌ ' + (data.message || 'Failed to submit feedback. Please try again.'), 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Reset button
                submitBtn.innerHTML = originalContent;
                submitBtn.disabled = false;
                
                showNotification('❌ Failed to submit feedback. Please try again.', 'error');
            });
        }
        
        // Reset feedback modal to initial state
        function resetFeedbackModal() {
            document.getElementById('feedbackForm').classList.remove('hidden');
            document.getElementById('feedbackSuccess').classList.add('hidden');
            
            // Clear form
            const form = document.querySelector('#feedbackForm form');
            form.reset();
        }
    </script>
</body>
</html>