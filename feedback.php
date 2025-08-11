<?php
// Start session at the very beginning before any output
include_once 'dbConnection.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FaceTrackED - Feedback</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- jQuery -->
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
                        'fadeIn': 'fadeIn 0.5s ease-in-out',
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
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(-20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
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
            min-height: 100vh;
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
        
        .hero-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
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
            color: #10b981;
            text-shadow: 0 0 10px rgba(16, 185, 129, 0.5);
        }
        
        .form-input {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 0.75rem;
            padding: 0.875rem 1rem;
            width: 100%;
            transition: all 0.3s ease;
            color: #1f2937;
            font-weight: 500;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
            background: rgba(255, 255, 255, 1);
        }
        
        .form-input::placeholder {
            color: #6b7280;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            border: none;
            border-radius: 0.75rem;
            color: white;
            font-weight: 600;
            padding: 1rem 2rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border: none;
            border-radius: 0.75rem;
            color: white;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(245, 158, 11, 0.4);
        }
        
        .btn-accent {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            border-radius: 0.75rem;
            color: white;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .btn-accent:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
        }
        
        .btn-outline {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 0.75rem;
            color: white;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .btn-outline:hover {
            background: rgba(255, 255, 255, 1);
            color: #1f2937;
            transform: translateY(-2px);
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
        
        .modal.active {
            display: flex !important;
            animation: fadeIn 0.3s ease-out forwards;
        }
        
        .modal.active .modal-content {
            transform: scale(1);
        }
        
        @keyframes fadeInModal {
            from { opacity: 0; }
            to { opacity: 1; }
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
        <i class="fas fa-comments floating-icon text-5xl animate-float" style="top: 15%; left: 10%;"></i>
        <i class="fas fa-heart floating-icon text-4xl animate-pulse-slow" style="top: 30%; left: 85%;"></i>
        <i class="fas fa-star floating-icon text-5xl animate-bounce-slow" style="top: 70%; left: 15%;"></i>
        <i class="fas fa-lightbulb floating-icon text-4xl animate-float" style="top: 80%; left: 80%; animation-delay: 2s;"></i>
        <i class="fas fa-thumbs-up floating-icon text-5xl animate-pulse-slow" style="top: 40%; left: 50%; animation-delay: 1s;"></i>
        <i class="fas fa-envelope floating-icon text-4xl animate-float" style="top: 60%; left: 20%; animation-delay: 3s;"></i>
    </div>

    <!-- Header -->
    <header class="fixed w-full header-glass shadow-xl z-50 transition-all duration-300">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-r from-primary to-accent shadow-lg">
                    <i class="fas fa-comments text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white">
                        Face<span class="text-secondary">Track</span><span class="text-accent">ED</span>
                    </h1>
                    <p class="text-sm text-white opacity-80">Feedback & Support</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <?php
                if (!isset($_SESSION['email'])) {
                    echo '<button onclick="openModal(\'loginModal\')" class="btn-outline text-sm py-2 px-4">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                          </button>';
                } else {
                    echo '<a href="logout.php?q=feedback.php" class="btn-secondary text-sm py-2 px-4">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                          </a>';
                }
                ?>
                <a href="index.php" class="btn-primary text-sm py-2 px-4">
                    <i class="fas fa-home mr-2"></i>Home
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center pt-24 pb-16 px-4">
        <div class="w-full max-w-2xl">
            <div class="hero-card rounded-2xl shadow-2xl overflow-hidden animate-fadeIn">
                <div class="p-8 md:p-12">
                    <div class="text-center mb-8">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-accent to-primary rounded-full mb-4">
                            <i class="fas fa-comments text-3xl text-white"></i>
                        </div>
                        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Share Your Feedback</h2>
                        <p class="text-white/80">Help us improve FaceTrackED by sharing your thoughts and suggestions</p>
                    </div>
                    
                    <div class="text-white">
                        <?php if (@$_GET['q']) { ?>
                            <div class="text-center p-8">
                                <div class="inline-flex items-center justify-center w-16 h-16 bg-accent rounded-full mb-4">
                                    <i class="fas fa-check text-2xl text-white"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-white mb-2">Thank You!</h3>
                                <p class="text-lg text-white/90"><?php echo htmlspecialchars(@$_GET['q']); ?></p>
                                <a href="feedback.php" class="btn-primary inline-flex items-center space-x-2 mt-6">
                                    <i class="fas fa-arrow-left"></i>
                                    <span>Send Another Feedback</span>
                                </a>
                            </div>
                        <?php } else { ?>
                            <form method="post" action="feed.php?q=feedback.php" class="space-y-6">
                                <div class="space-y-2">
                                    <label for="name" class="block text-white font-semibold mb-2">
                                        <i class="fas fa-user mr-2 text-primary"></i>Full Name
                                    </label>
                                    <input id="name" name="name" placeholder="Enter your full name" class="form-input" type="text" required>
                                </div>
                                
                                <div class="space-y-2">
                                    <label for="subject" class="block text-white font-semibold mb-2">
                                        <i class="fas fa-tag mr-2 text-secondary"></i>Subject
                                    </label>
                                    <input id="subject" name="subject" placeholder="What is this feedback about?" class="form-input" type="text" required>
                                </div>
                                
                                <div class="space-y-2">
                                    <label for="email" class="block text-white font-semibold mb-2">
                                        <i class="fas fa-envelope mr-2 text-accent"></i>Email Address
                                    </label>
                                    <input id="email" name="email" placeholder="Enter your email address" class="form-input" type="email" required>
                                </div>
                                
                                <div class="space-y-2">
                                    <label for="feedback" class="block text-white font-semibold mb-2">
                                        <i class="fas fa-comment-dots mr-2 text-primary"></i>Your Feedback
                                    </label>
                                    <textarea rows="6" name="feedback" class="form-input resize-none" placeholder="Share your thoughts, suggestions, or report any issues you've encountered..." required></textarea>
                                </div>
                                
                                <div class="text-center pt-4">
                                    <button type="submit" name="submit" class="btn-primary inline-flex items-center justify-center space-x-3 px-8 py-4 text-lg">
                                        <i class="fas fa-paper-plane"></i>
                                        <span>Send Feedback</span>
                                        <i class="fas fa-heart"></i>
                                    </button>
                                </div>
                            </form>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer-glass py-8 mt-auto">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center md:text-left">
                <div class="group">
                    <button onclick="openModal('adminModal')" class="inline-flex justify-center md:justify-start items-center space-x-2 hover:text-secondary transition-all duration-300 bg-transparent border-none cursor-pointer">
                        <i class="fas fa-user-shield text-2xl"></i>
                        <div>
                            <div class="font-medium">Admin Portal</div>
                            <div class="text-sm opacity-70">System Access</div>
                        </div>
                    </button>
                </div>
                <div class="group">
                    <button onclick="openModal('developersModal')" class="inline-flex justify-center md:justify-start items-center space-x-2 hover:text-accent transition-all duration-300 bg-transparent border-none cursor-pointer">
                        <i class="fas fa-code text-2xl"></i>
                        <div>
                            <div class="font-medium">Development Team</div>
                            <div class="text-sm opacity-70">Meet the Creators</div>
                        </div>
                    </button>
                </div>
                <div class="group">
                    <a href="feedback.php" class="inline-flex justify-center md:justify-start items-center space-x-2 hover:text-primary transition-all duration-300">
                        <i class="fas fa-comments text-2xl"></i>
                        <div>
                            <div class="font-medium">Feedback</div>
                            <div class="text-sm opacity-70">Current Page</div>
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
                <h3 class="text-xl font-bold">User Login</h3>
                <button onclick="closeModal('loginModal')" class="text-white text-2xl hover:text-blue-200 transition-colors">&times;</button>
            </div>
            <div class="p-6 space-y-4">
                <form method="post" action="login.php?q=feedback.php" class="space-y-4">
                    <div class="space-y-2">
                        <label class="block text-gray-700 font-semibold">Email Address</label>
                        <input type="email" name="email" placeholder="Enter your email" class="form-input" required/>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-gray-700 font-semibold">Password</label>
                        <input type="password" name="password" placeholder="Enter your password" class="form-input" required/>
                    </div>
                    <div class="flex justify-between pt-2">
                        <button type="button" onclick="closeModal('loginModal')" class="btn-outline">Cancel</button>
                        <button type="submit" name="login" class="btn-primary">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Admin Login Modal -->
    <div id="adminModal" class="modal">
        <div class="modal-content w-full max-w-md">
            <div class="bg-gradient-to-r from-secondary to-secondary-dark text-white px-6 py-4 rounded-t-xl flex justify-between items-center">
                <h3 class="text-xl font-bold">Administrator Access</h3>
                <button onclick="closeModal('adminModal')" class="text-white text-2xl hover:text-yellow-200 transition-colors">&times;</button>
            </div>
            <div class="p-6 space-y-4">
                <form method="post" action="admin.php?q=feedback.php" class="space-y-4">
                    <div class="space-y-2">
                        <label class="block text-gray-700 font-semibold">Admin Username</label>
                        <input type="text" name="uname" maxlength="20" placeholder="Enter admin username" class="form-input" required/>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-gray-700 font-semibold">Admin Password</label>
                        <input type="password" name="password" maxlength="15" placeholder="Enter admin password" class="form-input" required/>
                    </div>
                    <div class="flex justify-between pt-2">
                        <button type="button" onclick="closeModal('adminModal')" class="btn-outline">Cancel</button>
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
                <button onclick="closeModal('developersModal')" class="text-white text-2xl hover:text-blue-200 transition-colors">&times;</button>
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

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
            document.body.style.overflow = '';
        }
        
        // Close modal when clicking outside
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });
        });
    </script>

</body>
</html>