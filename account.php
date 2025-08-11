<?php
include_once 'dbConnection.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("location:index.php");
    exit(); // Ensure no further code is executed
} else {
    $name = $_SESSION['name'];
    $email = $_SESSION['email']; // Initialize the email variable
}

// Track if the user is currently taking an exam
if (!isset($_SESSION['exam_active'])) {
    $_SESSION['exam_active'] = false; // Default to inactive
}

// Track if welcome message should be shown
if (!isset($_SESSION['welcomed'])) {
    $_SESSION['welcomed'] = false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FaceTrackED - Dashboard</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
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
        
        .hero-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
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
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        
        .btn-outline {
            @apply border-2 border-white text-white font-medium py-3 px-8 rounded-lg transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:bg-white hover:text-gray-800 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.1);
        }
        
        .btn-start {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }
        
        .btn-start:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }
        
        .btn-restart {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }
        
        .btn-restart:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
        }
                
        /* Modal styling */
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
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .header-glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .nav-glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
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
        
        .nav-item {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .nav-item:hover, .nav-item.active {
            background: rgba(59, 130, 246, 0.3);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        /* Special styling for quiz container */
        .quiz-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            padding: 20px;
            width: 100%;
            max-width: 800px; 
            position: relative;
        }
        
        .quiz-section {
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .camera-section {
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 10px;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            position: absolute;
            bottom: 50px;
            right: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .camera-section img {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }
        
        .table-glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
        }
        
        .exam-timer {
            background: rgba(59, 130, 246, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(59, 130, 246, 0.2);
            padding: 1rem;
            border-radius: 0.75rem;
            margin-bottom: 1rem;
            color: #1d4ed8;
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
            <div class="flex items-center space-x-4">
                <div class="hero-card rounded-lg px-4 py-2">
                    <span class="text-white opacity-90 text-sm">Welcome,</span>
                    <span class="text-white font-semibold ml-1"><?php echo htmlspecialchars($name); ?></span>
                </div>
                <a href="logout.php?q=account.php" class="btn-secondary flex items-center space-x-2 text-sm py-2 px-4">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Navigation Menu -->
    <nav class="fixed top-20 w-full nav-glass shadow-lg z-40 transition-all duration-300">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-start items-center">
                <span class="text-white font-semibold text-lg px-6 py-3">
                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                </span>
            </div>
        </div>
    </nav>

<!-- Main Content -->
    <main class="flex-grow pt-40 pb-16">
        <div class="container mx-auto px-4 py-8">

            <?php if (@$_GET['q'] == 1) { ?>
                <div class="max-w-6xl mx-auto hero-card rounded-2xl shadow-2xl overflow-hidden transform transition-all duration-500 hover:shadow-3xl">
                    <div class="p-8 md:p-12">
                        <div class="text-center mb-8">
                            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                                Welcome Back, <?php echo htmlspecialchars($name); ?>!
                            </h2>
                            <h3 class="text-xl font-medium text-white opacity-90 mb-6">Available Examinations</h3>
                        </div>
                        
                        <?php
                        // Get user's college/section from session or database
                        $user_college = $_SESSION['college'] ?? '';
                        
                        // If college is not in session, get it from database
                        if (empty($user_college)) {
                            $user_query = mysqli_query($con, "SELECT college FROM user WHERE email='$email'") or die('Error fetching user data');
                            if (mysqli_num_rows($user_query) > 0) {
                                $user_data = mysqli_fetch_assoc($user_query);
                                $user_college = $user_data['college'];
                                $_SESSION['college'] = $user_college; // Store in session for future use
                            }
                        }
                        
                        $result = mysqli_query($con, "SELECT * FROM quiz ORDER BY date DESC") or die('Error');
                        
                        if (mysqli_num_rows($result) > 0) {
                            echo '<div class="table-glass overflow-hidden rounded-xl shadow-xl">
                            <table class="min-w-full border-collapse">
                                <thead>
                                    <tr class="bg-gradient-to-r from-primary/20 to-accent/20 border-b border-white/30">
                                        <th class="px-6 py-4 text-left text-white font-semibold">S.N.</th>
                                        <th class="px-6 py-4 text-left text-white font-semibold">Topic</th>
                                        <th class="px-6 py-4 text-center text-white font-semibold">Questions</th>
                                        <th class="px-6 py-4 text-center text-white font-semibold">Marks</th>
                                        <th class="px-6 py-4 text-center text-white font-semibold">Time</th>
                                        <th class="px-6 py-4 text-center text-white font-semibold">Actions</th>
                                    </tr>
                                </thead>
                                <tbody style="background: rgba(255, 255, 255, 0.95);">';
                            
                            $c = 1;
                            $available_exams = 0; // Counter for available exams
                            
                            while ($row = mysqli_fetch_array($result)) {
                                $title = $row['title'];
                                $total = $row['total'];
                                $sahi = $row['sahi'];
                                $time = $row['time'];
                                $eid = $row['eid'];
                                $allowed_sections = $row['allowed_sections'];
                                
                                // Check if user can access this exam
                                $can_access = true;
                                
                                if (!empty($allowed_sections)) {
                                    $allowed_sections_array = json_decode($allowed_sections, true);
                                    if (is_array($allowed_sections_array) && !in_array($user_college, $allowed_sections_array)) {
                                        $can_access = false;
                                    }
                                }
                                
                                // Only display exam if user can access it
                                if ($can_access) {
                                    $available_exams++;
                                    
                                    $q12 = mysqli_query($con, "SELECT score FROM history WHERE eid='$eid' AND email='$email'") or die('Error98');
                                    $rowcount = mysqli_num_rows($q12);
                                    
                                    // Check if restart is allowed for this quiz directly from the quiz table
                                    $restart_query = mysqli_query($con, "SELECT allow_restart FROM quiz WHERE eid='$eid'") or die('Error checking restart permission');
                                    $restart_allowed = false;
                                    
                                    if (mysqli_num_rows($restart_query) > 0) {
                                        $restart_data = mysqli_fetch_assoc($restart_query);
                                        $restart_allowed = (bool)$restart_data['allow_restart'];
                                    }
                                    
                                    if ($rowcount == 0) {
                                        echo '<tr class="border-b border-gray-200 hover:bg-blue-50 transition-all duration-300">
                                            <td class="px-6 py-4 text-gray-800 font-semibold">' . $c++ . '</td>
                                            <td class="px-6 py-4 font-semibold text-gray-800">' . $title . '</td>
                                            <td class="px-6 py-4 text-center text-gray-800 font-medium">' . $total . '</td>
                                            <td class="px-6 py-4 text-center text-gray-800 font-medium">' . $sahi * $total . '</td>
                                            <td class="px-6 py-4 text-center text-gray-800 font-medium">' . $time . ' min</td>
                                            <td class="px-6 py-4 text-center">
                                                <a href="account.php?q=quiz&step=2&eid=' . $eid . '&n=1&t=' . $total . '" 
                                                   class="btn-start inline-flex items-center justify-center space-x-2 py-2 px-4 rounded-lg">
                                                    <i class="fas fa-play"></i>
                                                    <span>Start Exam</span>
                                                </a>
                                            </td>
                                        </tr>';
                                    } else {
                                        echo '<tr class="border-b border-gray-200 hover:bg-blue-50 transition-all duration-300">
                                            <td class="px-6 py-4 text-gray-800 font-semibold">' . $c++ . '</td>
                                            <td class="px-6 py-4 font-semibold text-gray-800">' . $title . ' <i class="fas fa-check-circle text-accent ml-2" title="This exam has been completed"></i></td>
                                            <td class="px-6 py-4 text-center text-gray-800 font-medium">' . $total . '</td>
                                            <td class="px-6 py-4 text-center text-gray-800 font-medium">' . $sahi * $total . '</td>
                                            <td class="px-6 py-4 text-center text-gray-800 font-medium">' . $time . ' min</td>
                                            <td class="px-6 py-4 text-center">';
                                            
                                        if ($restart_allowed) {
                                            echo '<a href="update.php?q=quizre&step=25&eid=' . $eid . '&n=1&t=' . $total . '" 
                                                   class="btn-restart inline-flex items-center justify-center space-x-2 py-2 px-4 rounded-lg">
                                                    <i class="fas fa-redo-alt"></i>
                                                    <span>Restart</span>
                                                  </a>';
                                        } else {
                                            echo '<button disabled 
                                                   class="opacity-50 cursor-not-allowed bg-gray-600 text-white inline-flex items-center justify-center space-x-2 py-2 px-4 rounded-lg">
                                                    <i class="fas fa-lock"></i>
                                                    <span>Locked</span>
                                                  </button>
                                                  <span class="block text-xs text-gray-600 mt-1 font-medium">Contact admin</span>';
                                        }
                                        
                                        echo '</td>
                                        </tr>';
                                    }
                                }
                            }
                            
                            echo '</tbody></table></div>';
                            
                            // If no exams are available for this user
                            if ($available_exams == 0) {
                                echo '<div class="text-center p-8 hero-card rounded-xl border border-yellow-400/30 mt-6">
                                    <i class="fas fa-lock text-5xl text-yellow-400 mb-4"></i>
                                    <h3 class="text-xl font-bold text-white mb-2">No Examinations Available</h3>
                                    <p class="text-white/80 mb-2">Your organization: <span class="font-semibold text-yellow-400">' . htmlspecialchars($user_college) . '</span></p>
                                    <p class="text-white/70 text-sm">Contact your administrator if you believe this is an error.</p>
                                </div>';
                            }
                        } else {
                            echo '<div class="text-center p-8 hero-card rounded-xl">
                                <i class="fas fa-info-circle text-5xl text-blue-400 mb-4"></i>
                                <h3 class="text-xl font-bold text-white mb-2">No Examinations</h3>
                                <p class="text-white/80">No examinations are currently available in the system.</p>
                            </div>';
                        }
                        ?>
                    </div>
                </div>
            <?php } ?>

            <?php if (@$_GET['q'] == 'quiz' && @$_GET['step'] == 2) { 
                $eid = @$_GET['eid'];
                $sn = @$_GET['n'];
                $total = @$_GET['t'];
                $q = mysqli_query($con, "SELECT * FROM questions WHERE eid='$eid' ORDER BY RAND()");
            ?>
                <div class="flex justify-center items-center min-h-[calc(100vh-180px)] py-4">
                    <div class="w-full max-w-5xl mx-auto grid grid-cols-1 lg:grid-cols-4 gap-4 items-start">
                        <!-- Main Quiz Content -->
                        <div class="lg:col-span-3 hero-card rounded-2xl shadow-2xl overflow-hidden">
                            <div class="p-6">
                                <!-- Timer and Question Header -->
                                <div class="flex justify-between items-center mb-4 pb-4 border-b border-gray-200">
                                    <div id="exam-timer" class="exam-timer text-lg font-bold">
                                        <!-- Timer will be inserted here by JavaScript -->
                                    </div>
                                    <h2 class="text-xl font-bold text-gray-800">
                                        Question <?php echo $sn; ?> of <?php echo $total; ?>
                                    </h2>
                                </div>
                                
                                <?php
                                $row = mysqli_fetch_array($q);
                                $qns = $row['qns'];
                                $qid = $row['qid'];
                                ?>
                                
                                <!-- Question -->
                                <div class="bg-gradient-to-r from-blue-50 to-green-50 p-4 rounded-xl mb-6 border border-blue-200 text-center">
                                    <p class="text-lg font-medium text-gray-800 leading-relaxed"><?php echo $qns; ?></p>
                                </div>
                                
                                <!-- Options Form -->
                                <form id="quiz-form" action="update.php?q=quiz&step=2&eid=<?php echo $eid; ?>&n=<?php echo $sn; ?>&t=<?php echo $total; ?>&qid=<?php echo $qid; ?>" method="POST" class="space-y-3">
                                    <?php
                                    $q = mysqli_query($con, "SELECT * FROM options WHERE qid='$qid' ORDER BY RAND()");
                                    $optionLabels = ['A', 'B', 'C', 'D']; 
                                    $index = 0;
                                    
                                    while ($row = mysqli_fetch_array($q)) {
                                        $option = $row['option'];
                                        $optionid = $row['optionid'];
                                        ?>
                                        
                                        <label class="flex items-center p-3 bg-white/90 backdrop-blur-lg rounded-lg hover:bg-white/95 cursor-pointer transition-all duration-300 border border-gray-200 shadow-sm hover:shadow-md">
                                            <input type="radio" name="ans" value="<?php echo $optionid; ?>" class="h-4 w-4 text-primary mr-3">
                                            <span class="inline-flex items-center justify-center w-7 h-7 bg-primary text-white rounded-full font-semibold mr-3 text-sm">
                                                <?php echo $optionLabels[$index++]; ?>
                                            </span>
                                            <span class="text-gray-800 font-medium flex-1"><?php echo $option; ?></span>
                                        </label>
                                        
                                    <?php } ?>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Camera Section -->
                        <div class="lg:col-span-1">
                            <div class="bg-white/95 backdrop-filter backdrop-blur-20 p-4 rounded-xl shadow-xl border border-gray-200 sticky top-44">
                                <div class="text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-eye text-primary text-xl"></i>
                                        <h3 class="text-sm font-semibold text-gray-700 mt-1">Face Monitoring</h3>
                                    </div>
                                    <img src="http://127.0.0.1:5000/video_feed" alt="Face Monitoring" class="w-full h-32 object-cover rounded-lg border border-primary/30 mb-2">
                                    <p class="text-xs text-gray-600 font-medium">Monitoring Active</p>
                                    <div class="mt-3 pt-3 border-t border-gray-200">
                                        <div class="flex items-center justify-center space-x-1 mb-4">
                                            <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                                            <span class="text-xs text-gray-600">Live</span>
                                        </div>
                                        
                                        <!-- Progress Section -->
                                        <div class="mb-4">
                                            <div class="flex items-center justify-center space-x-2 mb-2">
                                                <span class="text-gray-700 font-semibold text-sm">Progress:</span>
                                                <span class="text-gray-700 font-bold text-sm"><?php echo $sn; ?>/<?php echo $total; ?></span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-3">
                                                <div class="bg-gradient-to-r from-primary to-accent h-3 rounded-full transition-all duration-300" 
                                                     style="width: <?php echo ($sn / $total) * 100; ?>%"></div>
                                            </div>
                                        </div>
                                        
                                        <!-- Submit Button -->
                                        <button type="submit" form="quiz-form" class="w-full btn-primary inline-flex items-center justify-center space-x-2 px-4 py-3 text-sm">
                                            <i class="fas fa-paper-plane"></i>
                                            <span>Submit Answer</span>
                                            <i class="fas fa-arrow-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php if (@$_GET['q'] == 'result' && @$_GET['eid']) {
                $eid = @$_GET['eid'];
                $q = mysqli_query($con, "SELECT * FROM history WHERE eid='$eid' AND email='$email' ") or die('Error157');
            ?>
                <div class="flex justify-center items-center min-h-[calc(100vh-180px)] py-4">
                    <div class="w-full max-w-4xl mx-auto hero-card rounded-2xl shadow-2xl overflow-hidden">
                        <div class="p-6">
                            <div class="text-center mb-6">
                                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-accent to-primary rounded-full mb-3">
                                    <i class="fas fa-chart-bar text-2xl text-white"></i>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-800 mb-2">Examination Results</h2>
                                <p class="text-gray-600">Your performance summary</p>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                <?php
                                while ($row = mysqli_fetch_array($q)) {
                                    $r = $row['sahi']; // Right answers
                                    $qa = $row['level']; // Total questions
                                    $percentage = ($r / $qa) * 100;
                                    
                                    // Update the rank field with the right answers count
                                    mysqli_query($con, "UPDATE history SET score='$r' WHERE eid='$eid' AND email='$email'") or die('Error updating score');
                                ?>
                                    <!-- Correct Answers Card -->
                                    <div class="bg-white/90 backdrop-blur-lg rounded-xl p-4 text-center shadow-lg border border-gray-200">
                                        <div class="inline-flex items-center justify-center w-12 h-12 bg-accent rounded-full mb-3">
                                            <i class="fas fa-check text-white text-lg"></i>
                                        </div>
                                        <h3 class="text-sm font-semibold text-gray-700 mb-1">Correct Answers</h3>
                                        <p class="text-2xl font-bold text-gray-800"><?php echo $r; ?></p>
                                    </div>
                                    
                                    <!-- Total Questions Card -->
                                    <div class="bg-white/90 backdrop-blur-lg rounded-xl p-4 text-center shadow-lg border border-gray-200">
                                        <div class="inline-flex items-center justify-center w-12 h-12 bg-primary rounded-full mb-3">
                                            <i class="fas fa-list text-white text-lg"></i>
                                        </div>
                                        <h3 class="text-sm font-semibold text-gray-700 mb-1">Total Questions</h3>
                                        <p class="text-2xl font-bold text-gray-800"><?php echo $qa; ?></p>
                                    </div>
                                    
                                    <!-- Score Percentage Card -->
                                    <div class="bg-white/90 backdrop-blur-lg rounded-xl p-4 text-center shadow-lg border border-gray-200">
                                        <div class="inline-flex items-center justify-center w-12 h-12 bg-secondary rounded-full mb-3">
                                            <i class="fas fa-percent text-white text-lg"></i>
                                        </div>
                                        <h3 class="text-sm font-semibold text-gray-700 mb-1">Score Percentage</h3>
                                        <p class="text-2xl font-bold text-gray-800"><?php echo number_format($percentage, 1); ?>%</p>
                                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                            <div class="bg-gradient-to-r from-accent to-primary h-2 rounded-full transition-all duration-300" 
                                                 style="width: <?php echo $percentage; ?>%"></div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            
                            <div class="text-center">
                                <a href="account.php?q=1" class="btn-primary inline-flex items-center justify-center space-x-2 px-6 py-3">
                                    <i class="fas fa-home"></i>
                                    <span>Back to Dashboard</span>
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            
        </div>
    </main>

<!-- Footer -->
    <footer class="footer-glass py-8 mt-auto">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center md:text-left">
                <div class="group">
                    <a href="#" id="developersBtn" class="inline-flex justify-center md:justify-start items-center space-x-2 hover:text-accent transition-all duration-300">
                        <i class="fas fa-code text-2xl"></i>
                        <div>
                            <div class="font-medium">Development Team</div>
                            <div class="text-sm opacity-70">Meet the Creators</div>
                        </div>
                    </a>
                </div>
                <div class="group">
                    <a href="feedback.php" target="_blank" class="inline-flex justify-center md:justify-start items-center space-x-2 hover:text-secondary transition-all duration-300">
                        <i class="fas fa-comments text-2xl"></i>
                        <div>
                            <div class="font-medium">Feedback</div>
                            <div class="text-sm opacity-70">Share Your Experience</div>
                        </div>
                    </a>
                </div>
                <div class="group">
                    <a href="#" class="inline-flex justify-center md:justify-start items-center space-x-2 hover:text-primary transition-all duration-300">
                        <i class="fas fa-question-circle text-2xl"></i>
                        <div>
                            <div class="font-medium">Help & Support</div>
                            <div class="text-sm opacity-70">Get Assistance</div>
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
                &copy; <?php echo date('Y'); ?> FaceTrackED | Advanced Examination System | All Rights Reserved
            </div>
        </div>
    </footer>

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
                        <h4 class="text-2xl font-bold text-primary mb-2">Kian A. Rodriguez</h4>
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
                            <p class="flex items-center justify-center md:justify-start text-gray-600">
                                <i class="fas fa-university mr-3 text-primary"></i>
                                MinSU - Computer Science
                            </p>
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-sm text-gray-500 italic">
                                "Building the future of secure examination systems with AI-powered face tracking technology"
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
if (@$_GET['q'] == 'quiz' && @$_GET['step'] == 2) {
    // User starts the quiz
    $_SESSION['exam_active'] = true;
    $eid = @$_GET['eid'];
    $sn = @$_GET['n'];
    $total = @$_GET['t'];
    // Fetch questions and display the quiz interface
} else {
    // User is not taking a quiz
    $_SESSION['exam_active'] = false;
}
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Display welcome message with warning information if applicable
    <?php if (!$_SESSION['welcomed']) { 
        // Get user's warning count from database
        $warning_query = mysqli_query($con, "SELECT COUNT(*) AS warning_count FROM warning WHERE email='$email'");
        $warning_data = mysqli_fetch_assoc($warning_query);
        $warning_count = $warning_data['warning_count'];
        
        // Determine warning status
        $warning_message = "";
        $warning_icon = "success";
        
        if ($warning_count >= 40) {
            $warning_message = "⚠️ CRITICAL: You have $warning_count violations. Account suspension imminent!";
            $warning_icon = "error";
        } elseif ($warning_count >= 30) {
            $warning_message = "🚨 SEVERE: You have $warning_count violations. Final warning before suspension.";
            $warning_icon = "warning";
        } elseif ($warning_count >= 20) {
            $warning_message = "⚠️ MAJOR: You have $warning_count violations. Please review examination guidelines.";
            $warning_icon = "warning";
        } elseif ($warning_count >= 10) {
            $warning_message = "⚡ MODERATE: You have $warning_count violations. Be extra careful during exams.";
            $warning_icon = "info";
        } elseif ($warning_count > 0) {
            $warning_message = "ℹ️ MINOR: You have $warning_count violations. Please follow exam protocols.";
            $warning_icon = "info";
        }
    ?>
        Swal.fire({
            title: 'Welcome to FaceTrackED!',
            html: '<div class="text-center">' +
                  '<div class="mb-4">' +
                  '<i class="fas fa-eye text-4xl text-blue-500 mb-2"></i>' +
                  '</div>' +
                  '<p class="text-lg font-medium mb-2">Hello, <span class="text-blue-600"><?php echo htmlspecialchars($name); ?></span>!</p>' +
                  '<p class="text-gray-600 mb-4">You have successfully logged into the advanced examination system.</p>' + 
                  <?php if ($warning_count > 0) { ?> 
                  '<div class="mt-4 p-4 rounded-lg ' + 
                  '<?php echo ($warning_count >= 30) ? "bg-red-50 border-2 border-red-200" : 
                          (($warning_count >= 20) ? "bg-orange-50 border-2 border-orange-200" : 
                          (($warning_count >= 10) ? "bg-yellow-50 border-2 border-yellow-200" : 
                          "bg-blue-50 border-2 border-blue-200")) ?>">' + 
                  '<div class="flex items-center justify-center mb-2">' +
                  '<i class="fas <?php echo ($warning_count >= 30) ? "fa-exclamation-triangle text-red-500" : 
                                (($warning_count >= 20) ? "fa-exclamation-circle text-orange-500" : 
                                (($warning_count >= 10) ? "fa-exclamation text-yellow-600" : 
                                "fa-info-circle text-blue-500")) ?> text-2xl mr-2"></i>' +
                  '</div>' +
                  '<p class="font-bold text-center"><?php echo $warning_message; ?></p>' +
                  '</div>'
                  <?php } else { ?>
                  '<div class="mt-4 p-4 bg-green-50 border-2 border-green-200 rounded-lg">' +
                  '<div class="flex items-center justify-center mb-2">' +
                  '<i class="fas fa-shield-check text-green-500 text-2xl mr-2"></i>' +
                  '</div>' +
                  '<p class="font-medium text-green-700">✅ Clean Record - No violations detected</p>' +
                  '</div>'
                  <?php } ?> +
                  '</div>',
            icon: '<?php echo ($warning_count >= 30) ? "warning" : "success"; ?>',
            timer: <?php echo ($warning_count > 0) ? "8000" : "4000"; ?>,
            timerProgressBar: true,
            showConfirmButton: true,
            confirmButtonText: 'Continue',
            background: '#fff',
            iconColor: '<?php echo ($warning_count >= 30) ? "#dc3545" : "#3b82f6"; ?>',
            customClass: {
                popup: 'rounded-xl shadow-2xl border-2 <?php echo ($warning_count >= 30) ? "border-red-100" : "border-blue-100"; ?>',
                confirmButton: 'bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition-colors'
            }
        });
        <?php 
        // Mark as welcomed in the session
        $_SESSION['welcomed'] = true;
        ?>
    <?php } ?>

    // Timer functionality for exams
    <?php if (@$_GET['q'] == 'quiz' && @$_GET['step'] == 2) { 
        $eid = @$_GET['eid'];
        $sn = @$_GET['n'];
        $total = @$_GET['t'];
        
        // Fetch the time limit for this question/exam
        $time_query = mysqli_query($con, "SELECT time FROM quiz WHERE eid='$eid'");
        $time_data = mysqli_fetch_assoc($time_query);
        $time_limit = $time_data['time']; // Time in minutes
        
        // Calculate time per question (in seconds)
        $time_per_question = ($time_limit * 60) / $total;
    ?>
        // Initialize timer variables
        let timePerQuestion = <?php echo $time_per_question; ?>; // Time per question in seconds
        let timeRemaining = timePerQuestion;
        let timerElement = document.createElement('div');
        timerElement.id = 'exam-timer';
        timerElement.className = 'text-xl font-bold';
        
        // Insert timer at the top of the quiz section
        let quizSection = document.querySelector('.quiz-section');
        quizSection.insertBefore(timerElement, quizSection.firstChild);
        
        let timerInterval;

        // Format time as MM:SS
        function formatTime(seconds) {
            let minutes = Math.floor(seconds / 60);
            let remainingSeconds = seconds % 60;
            return `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
        }

        // Update timer display
        function updateTimer() {
            if (timeRemaining <= 0) {
                clearInterval(timerInterval);
                
                Swal.fire({
                    title: 'Time\'s Up!',
                    text: 'Moving to next question...',
                    icon: 'warning',
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true
                }).then(() => {
                    // Move to next question without submitting an answer
                    let currentQuestion = <?php echo $sn; ?>;
                    let totalQuestions = <?php echo $total; ?>;
                    let examId = '<?php echo $eid; ?>';
                    
                    if (currentQuestion < totalQuestions) {
                        // Go to next question
                        window.location.href = `account.php?q=quiz&step=2&eid=${examId}&n=${currentQuestion+1}&t=${totalQuestions}`;
                    } else {
                        // End of exam, go to results
                        window.location.href = `account.php?q=result&eid=${examId}`;
                    }
                });
            } else {
                timeRemaining--;
                timerElement.textContent = `Time Remaining: ${formatTime(timeRemaining)}`;
                
                // Change color when time is running out
                if (timeRemaining < 10) { // Last 10 seconds
                    timerElement.className = 'text-xl font-bold text-red-600 animate-pulse';
                } else if (timeRemaining < 30) { // Last 30 seconds
                    timerElement.className = 'text-xl font-bold text-orange-500';
                }
            }
        }

        // Start the timer
        timerInterval = setInterval(updateTimer, 1000);
        updateTimer(); // Initial call to set the display
    <?php } ?>

    // Get all modals
    const modalElements = document.querySelectorAll('.modal');
    
    // Open modal function
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        
        if (modal) {
            // Hide all modals first
            modalElements.forEach(m => {
                m.classList.remove('active');
            });
            
            // Show the selected modal
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }
    
    // Set up click listeners for open buttons
    const developersBtn = document.getElementById('developersBtn');
    if (developersBtn) {
        developersBtn.addEventListener('click', (e) => {
            e.preventDefault();
            openModal('developersModal');
        });
    }
    
    // Close modal when clicking close button
    document.querySelectorAll('.closeModal').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const modal = btn.closest('.modal');
            if (modal) {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    });
    
    // Close modal when clicking outside
    modalElements.forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    });

    // Face detection functionality
    let faceDetectionInterval;

    function checkFacePosition() {
        fetch('http://127.0.0.1:5000/face_position')
            .then(response => {
                if (!response.ok) throw new Error('Server not reachable');
                return response.json();
            })
            .then(data => {
                console.log("Face Position:", data.position);
                console.log("Gaze Direction:", data.gaze);
                console.log("Mouth Status:", data.mouth);

                if (data.position === "LEFT" || data.position === "RIGHT" ||
                    data.gaze === "LEFT" || data.gaze === "RIGHT" ||
                    data.mouth === "OPEN") {

                    let warnings = parseInt(sessionStorage.getItem("warnings")) || 0;
                    warnings++;
                    sessionStorage.setItem("warnings", warnings);

                    if (warnings >= 4) {
                        // Stop the current check interval first
                        clearInterval(faceDetectionInterval);
                        faceDetectionInterval = null;
                        sessionStorage.clear(); // Clear warnings
                        
                        // First restart the Python session
                        fetch('http://127.0.0.1:5000/restart', { 
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            }
                        })
                        .then(restartResponse => {
                            console.log("Python service variables reset");
                            
                            // Then proceed to disable the user
                            return fetch('disable_user.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify({ email: '<?php echo $_SESSION["email"]; ?>' }),
                            });
                        })
                        .then(disableResponse => {
                            if (disableResponse.ok) {
                                console.log("User disabled successfully");
                                // Finally logout the user
                                window.location.href = 'logout.php';
                            } else {
                                throw new Error("Failed to disable user");
                            }
                        })
                        .catch(error => console.error("Error in cleanup sequence:", error));
                    } else {
                        window.location.href = "warning.php";
                    }
                }
            })
            .catch(error => {
                console.error("Error fetching face position:", error);
                setTimeout(checkFacePosition, 5000); // Retry after 5 seconds
            });
    }

    function startFaceDetection() {
        if (!faceDetectionInterval) {
            faceDetectionInterval = setInterval(checkFacePosition, 1000);
        }
    }

    function stopFaceDetection() {
        clearInterval(faceDetectionInterval);
        faceDetectionInterval = null;
    }

    // Start/stop face detection based on session state
    <?php if ($_SESSION['exam_active']) { ?>
        startFaceDetection();
    <?php } else { ?>
        stopFaceDetection();
    <?php } ?>
});
</script>

</body>
</html>