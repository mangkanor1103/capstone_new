<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RS Online Exam System</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- jQuery (needed for modals) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#28a745',     // Green color matching account.php
                        'primary-dark': '#218838', // Darker green for hover states (also from account.php)
                        'primary-light': '#9be3b0', // Light green for subtle highlights
                        secondary: '#dc3545',   // Red secondary color
                        'secondary-dark': '#bd2130', // Darker red for hover states
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'bounce-slow': 'bounce 3s infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        },
                    }
                }
            }
        }
    </script>
    
    <style>
        /* Updated color-related styles to match account.php */
        body {
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
            text-align: center;
        }
        
        .animated-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: linear-gradient(120deg, #dff8e7 0%, #b6e6c4 100%);  /* Lighter gradient to match account.php */
            overflow: hidden;
        }
        
        .floating-icon {
            position: absolute;
            opacity: 0.5;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.15));
            z-index: -1;
            transition: all 0.3s ease;
            color: #28a745; /* Updated color */
        }
        
        .floating-icon:hover {
            opacity: 0.7;
            transform: scale(1.2);
        }
        
        @keyframes blob {
            0%, 100% { border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%; }
            25% { border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%; }
            50% { border-radius: 50% 60% 30% 40% / 40% 30% 70% 60%; }
            75% { border-radius: 40% 30% 70% 60% / 60% 40% 30% 70%; }
        }
        
        .blob {
            position: absolute;
            background: rgba(40, 167, 69, 0.18);  /* Updated to match account.php green */
            width: 300px;
            height: 300px;
            animation: blob 15s linear infinite alternate;
            z-index: -1;
        }
        
        .form-input {
            @apply w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-300;
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        }
        
        .form-input:hover {
            @apply border-gray-400;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
        }
        
        .form-input:focus {
            @apply border-primary;
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.25);  /* Updated to match account.php green */
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
            color: #28a745;  /* Updated to match account.php green */
            font-size: 1rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;  /* Slightly bolder */
            color: #2d3748;  /* Darker for better contrast */
            font-size: 0.875rem;
        }
        
        .btn-primary {
            @apply bg-primary hover:bg-primary-dark text-white font-medium py-2 px-6 rounded-lg transform transition-all duration-300 hover:scale-105 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-primary focus:ring-opacity-50;
        }
        
        .btn-secondary {
            @apply bg-secondary hover:bg-secondary-dark text-white font-medium py-2 px-6 rounded-lg transform transition-all duration-300 hover:scale-105 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-secondary focus:ring-opacity-50;
        }
        
        .btn-outline {
            @apply border border-gray-400 text-gray-700 font-medium py-2 px-6 rounded-lg transform transition-all duration-300 hover:scale-105 hover:shadow-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50;
        }
        
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 50;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            padding: 1rem;
        }
        
        .modal-content {
            background-color: white;
            border-radius: 0.75rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            width: 100%;
            max-width: 28rem;
            transform: scale(0.95);
            transition: transform 0.3s ease-in-out;
            text-align: center;
        }
        
        .modal-content > div:first-child {
            background-color: #28a745;  /* Matching account.php green */
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
        
        /* Register button styling to match account.php */
        .btn-register {
            background-color: #b30000 !important; /* Red color from account.php */
            color: white !important;
            transition: background-color 0.3s, transform 0.3s;
        }
        
        .btn-register:hover {
            background-color: #218838 !important; /* Green on hover from account.php */
            transform: scale(1.05);
        }
        
        /* Footer styling to match account.php */
        .footer {
            background-color: #28a745; /* Green footer */
            color: white;
        }
        
        .footer a {
            color: white;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer a:hover {
            color: #f8f9fa; /* Light color on hover */
        }
    </style>
</head>

<body class="min-h-screen flex flex-col">
    <!-- Animated Background -->
    <div class="animated-bg">
        <div class="blob" style="top: 10%; left: 10%;"></div>
        <div class="blob" style="top: 60%; left: 80%;"></div>
        <div class="blob" style="top: 80%; left: 30%;"></div>
        
        <!-- Floating Icons -->
        <i class="fas fa-graduation-cap floating-icon text-5xl animate-float" style="top: 15%; left: 10%;"></i>
        <i class="fas fa-book floating-icon text-4xl animate-pulse-slow" style="top: 30%; left: 85%;"></i>
        <i class="fas fa-laptop floating-icon text-5xl animate-bounce-slow" style="top: 70%; left: 15%;"></i>
        <i class="fas fa-pencil-alt floating-icon text-4xl animate-float" style="top: 80%; left: 80%; animation-delay: 2s;"></i>
        <i class="fas fa-users floating-icon text-5xl animate-pulse-slow" style="top: 40%; left: 50%; animation-delay: 1s;"></i>
    </div>

    <!-- Header -->
    <header class="fixed w-full bg-white bg-opacity-90 backdrop-filter backdrop-blur-lg shadow-md z-50 transition-all duration-300">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <img src="image/rslogo.jpg" alt="RS Logo" class="h-12 w-12 rounded-full shadow-md transition-transform duration-300 hover:scale-110">
                <h1 class="text-xl md:text-2xl font-bold text-primary">RS Online Exam</h1>
            </div>
            <button id="loginBtn" class="btn-secondary flex items-center space-x-2">
                <i class="fa fa-sign-in-alt"></i>
                <span>Login</span>
            </button>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow pt-24 pb-16">
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden transform transition-all duration-500 hover:shadow-2xl">
                <div class="p-8 md:p-12 bg-gradient-to-br from-white to-green-50">
                    <h2 class="text-2xl md:text-3xl font-bold text-center text-primary mb-8">Register Now</h2>
                    
                    <form class="space-y-6" name="form" action="sign.php?q=account.php" onSubmit="return validateForm()" method="POST" enctype="multipart/form-data">
                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Full Name -->
                            <div class="space-y-2">
                                <label for="name" class="form-label">Full Name</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fa fa-user input-icon"></i>
                                    </div>
                                    <input id="name" name="name" placeholder="Enter your full name" class="form-input pl-10" type="text" required>
                                </div>
                            </div>
                            
                            <!-- Gender -->
                            <div class="space-y-2">
                                <label for="gender" class="form-label">Gender</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fa fa-venus-mars input-icon"></i>
                                    </div>
                                    <select id="gender" name="gender" class="form-input pl-10" required>
                                        <option value="">Select Gender</option>
                                        <option value="M">Male</option>
                                        <option value="F">Female</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Year and Section -->
                            <div class="space-y-2">
                                <label for="college" class="form-label">Year and Section</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fa fa-school input-icon"></i>
                                    </div>
                                    <select id="college" name="college" class="form-input pl-10" required>
                                        <option value="">Select Year and Section</option>
                                        <option value="Entrance Exam">Entrance Exam</option>
                                        <option value="Grade 7 - Section A">Grade 7 - Section A</option>
                                        <option value="Grade 7 - Section B">Grade 7 - Section B</option>
                                        <option value="Grade 8 - Section A">Grade 8 - Section A</option>
                                        <option value="Grade 8 - Section B">Grade 8 - Section B</option>
                                        <option value="Grade 9 - Section A">Grade 9 - Section A</option>
                                        <option value="Grade 9 - Section B">Grade 9 - Section B</option>
                                        <option value="Grade 10 - Section A">Grade 10 - Section A</option>
                                        <option value="Grade 10 - Section B">Grade 10 - Section B</option>
                                        <option value="Grade 11 - Section A">Grade 11 - Section A</option>
                                        <option value="Grade 11 - Section B">Grade 11 - Section B</option>
                                        <option value="Grade 12 - Section A">Grade 12 - Section A</option>
                                        <option value="Grade 12 - Section B">Grade 12 - Section B</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Email -->
                            <div class="space-y-2">
                                <label for="email" class="form-label">Email ID</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fa fa-envelope input-icon"></i>
                                    </div>
                                    <input id="email" name="email" placeholder="Enter your email" class="form-input pl-10" type="email" required>
                                </div>
                            </div>
                            
                            <!-- Contact Number -->
                            <div class="space-y-2">
                                <label for="mob" class="form-label">Contact Number</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fa fa-phone input-icon"></i>
                                    </div>
                                    <input id="mob" name="mob" placeholder="Enter contact number" class="form-input pl-10" type="number" required>
                                </div>
                            </div>
                            
                            <!-- Password -->
                            <div class="space-y-2">
                                <label for="password" class="form-label">Password</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fa fa-lock input-icon"></i>
                                    </div>
                                    <input id="password" name="password" placeholder="Create a password" class="form-input pl-10" type="password" required>
                                </div>
                            </div>
                            
                            <!-- Confirm Password -->
                            <div class="space-y-2">
                                <label for="cpassword" class="form-label">Confirm Password</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fa fa-check-circle input-icon"></i>
                                    </div>
                                    <input id="cpassword" name="cpassword" placeholder="Confirm your password" class="form-input pl-10" type="password" required>
                                </div>
                            </div>
                            
                            <!-- Profile Photo -->
                            <div class="space-y-2 mx-auto md:col-span-2">
                                <label class="form-label text-center block">Profile Photo</label>
                                <div class="space-y-3 flex flex-col items-center">
                                    <video id="camera" autoplay playsinline class="w-full max-w-xs rounded-lg border-2 border-gray-300 hidden"></video>
                                    <button type="button" class="btn-outline flex items-center space-x-2" onclick="startCamera()">
                                        <i class="fas fa-camera"></i>
                                        <span>Upload Photo</span>
                                    </button>
                                    <button type="button" id="captureBtn" class="hidden btn-primary flex items-center space-x-2" onclick="capture()">
                                        <i class="fas fa-check"></i>
                                        <span>Capture</span>
                                    </button>
                                    <canvas id="snapshot" class="hidden"></canvas>
                                    <input type="hidden" name="photo_data" id="photo_data">
                                    <img id="preview" src="#" alt="Captured photo" class="hidden max-w-xs w-full h-auto rounded-lg border-2 border-primary mt-2">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="text-center pt-6">
                            <button type="submit" class="btn-primary px-8 py-3 text-lg">
                                <span class="flex items-center justify-center space-x-2">
                                    <i class="fas fa-user-plus"></i>
                                    <span>Register</span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer py-6 mt-auto">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                <div class="group">
                    <a href="#" id="adminLoginBtn" class="inline-flex justify-center items-center space-x-2 hover:text-green-200 transition-colors duration-300">
                        <i class="fas fa-user-shield text-xl"></i>
                        <span>Admin Login</span>
                    </a>
                </div>
                <div class="group">
                    <a href="#" id="developersBtn" class="inline-flex justify-center items-center space-x-2 hover:text-green-200 transition-colors duration-300">
                        <i class="fas fa-code text-xl"></i>
                        <span>Developers</span>
                    </a>
                </div>
                <div class="group">
                    <a href="feedback.php" target="_blank" class="inline-flex justify-center items-center space-x-2 hover:text-green-200 transition-colors duration-300">
                        <i class="fas fa-comments text-xl"></i>
                        <span>Feedback</span>
                    </a>
                </div>
            </div>
            <div class="text-center mt-6 text-sm text-green-100">
                &copy; 2025 RS Online Exam System | All Rights Reserved
            </div>
        </div>
    </footer>

    <!-- Login Modal -->
    <div id="loginModal" class="modal">
        <div class="modal-content w-full max-w-md">
            <div class="bg-primary text-white px-6 py-4 rounded-t-xl flex justify-between items-center">
                <h3 class="text-xl font-bold">User Login</h3>
                <button class="closeModal text-white text-2xl hover:text-green-200 transition-colors">&times;</button>
            </div>
            <div class="p-6 space-y-4">
                <form class="space-y-4" action="login.php?q=index.php" method="POST">
                    <div class="space-y-2">
                        <label for="login-email" class="form-label">Email</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa fa-envelope input-icon"></i>
                            </div>
                            <input id="login-email" name="email" placeholder="Enter your email" class="form-input pl-10" type="email" required>
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
                        <button type="button" class="closeModal btn-outline">Close</button>
                        <button type="submit" class="btn-primary">Login</button>
                    </div>
                </form>
                
                <!-- Face Login Button -->
                <div class="text-center pt-4 border-t border-gray-200 w-full">
                    <p class="text-sm text-gray-600 mb-3">Or login using face recognition</p>
                    <button class="btn-outline flex items-center space-x-2 mx-auto" onclick="openFaceLoginModal()">
                        <i class="fas fa-camera"></i>
                        <span>Login with Face</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Face Login Modal -->
    <div id="faceLoginModal" class="modal">
        <div class="modal-content w-full max-w-md">
            <div class="bg-primary text-white px-6 py-4 rounded-t-xl flex justify-between items-center">
                <h3 class="text-xl font-bold">Face Login</h3>
                <button class="closeModal text-white text-2xl hover:text-green-200 transition-colors">&times;</button>
            </div>
            <div class="p-6">
                <div class="text-center space-y-4 w-full">
                    <div class="bg-yellow-50 p-6 rounded-lg border border-yellow-300">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-tools text-5xl text-yellow-500 mb-3"></i>
                            <h3 class="text-xl font-bold text-yellow-700 mb-2">Under Development</h3>
                            <p class="text-yellow-600">This feature is currently being developed and will be available soon.</p>
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
            <div class="bg-primary text-white px-6 py-4 rounded-t-xl flex justify-between items-center">
                <h3 class="text-xl font-bold">Admin Login</h3>
                <button class="closeModal text-white text-2xl hover:text-green-200 transition-colors">&times;</button>
            </div>
            <div class="p-6 space-y-4">
                <form class="space-y-4" method="post" action="admin.php?q=index.php">
                    <div class="space-y-2">
                        <label class="form-label">Username</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa fa-user input-icon"></i>
                            </div>
                            <input type="text" name="uname" maxlength="20" placeholder="Admin Username" class="form-input pl-10" required/>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="form-label">Password</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa fa-lock input-icon"></i>
                            </div>
                            <input type="password" name="password" maxlength="15" placeholder="Password" class="form-input pl-10" required/>
                        </div>
                    </div>
                    <div class="flex justify-between pt-2">
                        <button type="button" class="closeModal btn-outline">Close</button>
                        <button type="submit" name="login" class="btn-primary">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Developers Modal -->
    <div id="developersModal" class="modal">
        <div class="modal-content w-full max-w-md">
            <div class="bg-primary text-white px-6 py-4 rounded-t-xl flex justify-between items-center">
                <h3 class="text-xl font-bold">Developers</h3>
                <button class="closeModal text-white text-2xl hover:text-green-200 transition-colors">&times;</button>
            </div>
            <div class="p-6">
                <div class="flex flex-col md:flex-row items-center md:space-x-4 space-y-4 md:space-y-0">
                    <div class="flex-shrink-0">
                        <img src="image/CAM00121.jpg" class="h-24 w-24 object-cover rounded-full shadow-lg border-2 border-primary transition-transform duration-300 hover:scale-105" alt="Developer">
                    </div>
                    <div class="text-center md:text-left">
                        <h4 class="text-xl font-bold text-primary mb-1">Kian A. Rodrigez</h4>
                        <p class="flex items-center justify-center md:justify-start text-gray-600 mb-1">
                            <i class="fas fa-phone-alt mr-2 input-icon"></i>
                            +9366717240
                        </p>
                        <p class="flex items-center justify-center md:justify-start text-gray-600">
                            <i class="fas fa-envelope mr-2 input-icon"></i>
                            kianr664@gmail.com
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function startCamera() {
            const video = document.getElementById('camera');
            const captureBtn = document.getElementById('captureBtn');

            navigator.mediaDevices.getUserMedia({ video: true })
                .then(stream => {
                    video.srcObject = stream;
                    video.classList.remove('hidden');
                    captureBtn.classList.remove('hidden');
                })
                .catch(err => {
                    alert("Camera access denied: " + err);
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
        }

        document.addEventListener('DOMContentLoaded', function() {
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
                            
                            setTimeout(() => {
                                statusMessage.textContent = "Scanning...";
                                setTimeout(() => {
                                    statusMessage.textContent = "Face recognized! Logging you in...";
                                    setTimeout(() => {
                                        statusMessage.textContent = "Authentication complete!";
                                    }, 1000);
                                }, 2000);
                            }, 1500);
                        })
                        .catch(err => {
                            console.error("Camera error:", err);
                            statusMessage.textContent = "Unable to access camera.";
                        });
                } else {
                    statusMessage.textContent = "Camera not supported in this browser.";
                }
            }
        });
        
        function validateForm() {
            const password = document.getElementById('password').value;
            const cpassword = document.getElementById('cpassword').value;
            
            if (password !== cpassword) {
                alert("Passwords do not match!");
                return false;
            }
            
            return true;
        }
    </script>
</body>
</html>