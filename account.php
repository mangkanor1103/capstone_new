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
    <title>FaceTrackED - Advanced Examination System</title>
    
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
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        /* Base styling */
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
        
        .btn-danger {
            @apply bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-6 rounded-lg transform transition-all duration-300 hover:scale-105 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }
        
        .nav-item {
            @apply px-6 py-3 text-gray-700 font-medium transition-all duration-300 hover:text-primary hover:bg-white hover:bg-opacity-80 rounded-lg;
            backdrop-filter: blur(10px);
        }
        
        .nav-item.active {
            @apply text-primary bg-white bg-opacity-90 shadow-md;
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
        
        /* Special styling for quiz container */
        .quiz-container {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 20px;
            padding: 20px;
            width: 100%;
            max-width: 1200px;
            height: calc(100vh - 180px);
            position: relative;
        }
        
        /* Hide footer during quiz mode */
        .quiz-mode footer {
            display: none !important;
        }
        
        /* Adjust main content padding during quiz mode */
        .quiz-mode main {
            padding-bottom: 20px !important;
        }
        
        .quiz-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 15px;
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.2);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            overflow: hidden;
        }
        
        .camera-sidebar {
            display: flex;
            flex-direction: column;
            gap: 20px;
            height: 100%;
        }
        
        .camera-section {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 15px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 250px;
        }
        
        .camera-section img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
        }
        
        .timer-section {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            text-align: center;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .quiz-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        
        .question-section {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .submit-section {
            padding-top: 15px;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            margin-top: auto;
        }
        
        @media (max-width: 768px) {
            .quiz-container {
                grid-template-columns: 1fr;
                grid-template-rows: auto auto;
                height: auto;
            }
            
            .camera-sidebar {
                flex-direction: row;
                height: auto;
            }
            
            .camera-section {
                height: 180px;
                flex: 1;
            }
            
            .timer-section {
                flex: 1;
                height: 180px;
            }
        }

        .exam-table {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 1rem;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .exam-table th {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            font-weight: 600;
            padding: 1rem;
            text-align: center;
        }

        .exam-table td {
            padding: 1rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            text-align: center;
        }

        .exam-table tr:hover {
            background: rgba(59, 130, 246, 0.05);
        }

        .content-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 1.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
    </style>
</head>

<body class="min-h-screen flex flex-col<?php if (@$_GET['q'] == 'quiz' && @$_GET['step'] == 2) echo ' quiz-mode'; ?>">
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
                <span class="text-white opacity-90">Hello, <span class="text-secondary font-semibold"><?php echo htmlspecialchars($name); ?></span></span>
                <a href="logout.php?q=account.php" class="btn-danger flex items-center space-x-2 text-sm">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Navigation Menu -->
    <nav class="fixed top-20 w-full header-glass shadow-md z-40 transition-all duration-300">
        <div class="container mx-auto px-4">
            <div class="flex justify-start items-center">
                <a class="nav-item <?php if(@$_GET['q']==1) echo 'active'; ?>" href="account.php?q=1">
                    <i class="fas fa-home mr-2"></i> Dashboard
                </a>
            </div>
        </div>
    </nav>

<!-- Main Content -->
    <main class="flex-grow pt-40 pb-16">
        <div class="container mx-auto px-4 py-8">

            <?php if (@$_GET['q'] == 1) { ?>
                <div class="max-w-4xl mx-auto content-card overflow-hidden transform transition-all duration-500 hover:shadow-2xl">
                    <div class="p-8 md:p-12">
                        <div class="text-center mb-8">
                            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Welcome, <?php echo htmlspecialchars($name); ?>!</h2>
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
                            echo '<div class="overflow-x-auto">
                            <table class="min-w-full exam-table">
                                <thead>
                                    <tr>
                                        <th class="py-3 px-4">S.N.</th>
                                        <th class="py-3 px-4">Exam Title</th>
                                        <th class="py-3 px-4">Questions</th>
                                        <th class="py-3 px-4">Marks</th>
                                        <th class="py-3 px-4">Time Limit</th>
                                        <th class="py-3 px-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>';
                            
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
                                        echo '<tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="px-4 py-4">' . $c++ . '</td>
                                            <td class="px-4 py-4 font-medium">' . $title . '</td>
                                            <td class="px-4 py-4 text-center">' . $total . '</td>
                                            <td class="px-4 py-4 text-center">' . $sahi * $total . '</td>
                                            <td class="px-4 py-4 text-center">' . $time . ' min</td>
                                            <td class="px-4 py-4 text-center">
                                                <a href="account.php?q=quiz&step=2&eid=' . $eid . '&n=1&t=' . $total . '" 
                                                   class="btn-register inline-flex items-center justify-center space-x-1 py-1 px-4 rounded-lg">
                                                    <i class="fas fa-play"></i>
                                                    <span>Start</span>
                                                </a>
                                            </td>
                                        </tr>';
                                    } else {
                                        echo '<tr class="border-b border-gray-200 hover:bg-gray-50 text-primary">
                                            <td class="px-4 py-4">' . $c++ . '</td>
                                            <td class="px-4 py-4 font-medium">' . $title . ' <i class="fas fa-check-circle" title="This exam has been already solved by you"></i></td>
                                            <td class="px-4 py-4 text-center">' . $total . '</td>
                                            <td class="px-4 py-4 text-center">' . $sahi * $total . '</td>
                                            <td class="px-4 py-4 text-center">' . $time . ' min</td>
                                            <td class="px-4 py-4 text-center">';
                                            
                                        if ($restart_allowed) {
                                            echo '<a href="update.php?q=quizre&step=25&eid=' . $eid . '&n=1&t=' . $total . '" 
                                                   class="btn-register inline-flex items-center justify-center space-x-1 py-1 px-4 rounded-lg">
                                                    <i class="fas fa-redo-alt"></i>
                                                    <span>Restart</span>
                                                  </a>';
                                        } else {
                                            echo '<button disabled 
                                                   class="opacity-50 cursor-not-allowed bg-gray-400 text-white inline-flex items-center justify-center space-x-1 py-1 px-4 rounded-lg">
                                                    <i class="fas fa-lock"></i>
                                                    <span>Restart</span>
                                                  </button>
                                                  <span class="block text-xs text-gray-500 mt-1">Contact admin to enable</span>';
                                        }
                                        
                                        echo '</td>
                                        </tr>';
                                    }
                                }
                            }
                            
                            echo '</tbody></table></div>';
                            
                            // If no exams are available for this user
                            if ($available_exams == 0) {
                                echo '<div class="text-center p-8 bg-yellow-50 rounded-lg border border-yellow-200 mt-4">
                                    <i class="fas fa-lock text-4xl text-yellow-500 mb-3"></i>
                                    <p class="text-yellow-700 font-medium mb-2">No exams available for your section</p>
                                    <p class="text-yellow-600 text-sm">Your section: <span class="font-semibold">' . htmlspecialchars($user_college) . '</span></p>
                                    <p class="text-yellow-600 text-sm mt-1">Contact your administrator if you think this is an error.</p>
                                </div>';
                            }
                        } else {
                            echo '<div class="text-center p-8 bg-gray-50 rounded-lg">
                                <i class="fas fa-info-circle text-4xl text-gray-400 mb-3"></i>
                                <p class="text-gray-500">No exams are currently available.</p>
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
                <div class="max-w-7xl mx-auto">
                    <div class="quiz-container">
                        <!-- Main Quiz Section -->
                        <div class="quiz-section">
                            <div class="quiz-content">
                                <!-- Timer and Progress Header -->
                                <div class="text-center mb-4">
                                    <div id="exam-timer" class="text-xl font-bold text-primary mb-2"></div>
                                    <h2 class="text-xl font-bold text-primary mb-2">Question <?php echo $sn; ?> of <?php echo $total; ?></h2>
                                    <div class="w-full bg-gray-200 rounded-full h-2 mb-3">
                                        <div class="bg-primary h-2 rounded-full transition-all duration-300" style="width: <?php echo ($sn / $total) * 100; ?>%"></div>
                                    </div>
                                </div>
                                
                                <!-- Question and Options -->
                                <div class="question-section">
                                    <?php
                                    $row = mysqli_fetch_array($q);
                                    $qns = $row['qns'];
                                    $qid = $row['qid'];
                                    ?>
                                    
                                    <div class="bg-blue-50 p-3 rounded-lg mb-4 border-l-4 border-primary">
                                        <p class="text-base font-medium text-gray-800"><?php echo $qns; ?></p>
                                    </div>
                                    
                                    <form action="update.php?q=quiz&step=2&eid=<?php echo $eid; ?>&n=<?php echo $sn; ?>&t=<?php echo $total; ?>&qid=<?php echo $qid; ?>" method="POST" class="space-y-2">
                                        <?php
                                        $q = mysqli_query($con, "SELECT * FROM options WHERE qid='$qid' ORDER BY RAND()");
                                        $optionLabels = ['A', 'B', 'C', 'D']; 
                                        $index = 0;
                                        
                                        while ($row = mysqli_fetch_array($q)) {
                                            $option = $row['option'];
                                            $optionid = $row['optionid'];
                                            ?>
                                            
                                            <label class="flex items-center p-2 border-2 border-gray-200 rounded-lg hover:bg-blue-50 hover:border-primary cursor-pointer transition-all duration-300 group">
                                                <input type="radio" name="ans" value="<?php echo $optionid; ?>" class="h-4 w-4 text-primary">
                                                <span class="ml-3 text-gray-700 group-hover:text-primary text-sm">
                                                    <span class="font-bold text-primary mr-2"><?php echo $optionLabels[$index++]; ?>.</span>
                                                    <?php echo $option; ?>
                                                </span>
                                            </label>
                                            
                                        <?php } ?>
                                        
                                        <!-- Submit Section -->
                                        <div class="submit-section">
                                            <!-- Debug Test Section (Remove in production) -->
                                            <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-3">
                                                <p class="text-xs text-red-600 mb-2">Debug: Tab Detection Test</p>
                                                <button type="button" onclick="testTabDetection()" class="bg-red-500 text-white px-3 py-1 rounded text-xs">
                                                    Test Violation
                                                </button>
                                                <span id="violationCount" class="text-xs text-red-600 ml-2">Violations: 0</span>
                                            </div>
                                            
                                            <button type="submit" class="btn-primary w-full inline-flex items-center justify-center space-x-2 py-3">
                                                <i class="fas fa-paper-plane"></i>
                                                <span>Submit Answer</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Camera and Timer Sidebar -->
                        <div class="camera-sidebar">
                            <!-- Camera Section -->
                            <div class="camera-section">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Live Monitoring</h4>
                                <img src="http://127.0.0.1:5000/video_feed" alt="Video Feed" class="rounded-lg">
                            </div>
                            
                            <!-- Additional Info Section -->
                            <div class="timer-section">
                                <div class="text-center">
                                    <i class="fas fa-shield-alt text-4xl text-primary mb-3"></i>
                                    <h4 class="font-bold text-gray-700 mb-2">Secure Exam Mode</h4>
                                    <p class="text-sm text-gray-600 mb-3">Face tracking is active</p>
                                    <div class="bg-green-100 text-green-800 text-xs px-3 py-2 rounded-full inline-block">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Monitoring Active
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
                $was_terminated = isset($_GET['terminated']) && $_GET['terminated'] == '1';
            ?>
                <div class="max-w-4xl mx-auto content-card overflow-hidden transform transition-all duration-500 hover:shadow-2xl">
                    <div class="p-8 md:p-12">
                        <div class="text-center mb-8">
                            <?php if ($was_terminated) { ?>
                                <i class="fas fa-exclamation-triangle text-6xl text-red-500 mb-4"></i>
                                <h2 class="text-3xl md:text-4xl font-bold text-red-600 mb-4">Exam Terminated</h2>
                                <p class="text-red-500 opacity-90">Your exam was terminated due to security violations</p>
                            <?php } else { ?>
                                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Exam Results</h2>
                                <p class="text-white opacity-90">Your performance summary</p>
                            <?php } ?>
                        </div>
                        <div class="bg-white bg-opacity-95 backdrop-blur-20 p-6 rounded-xl shadow-md">
                            <?php if ($was_terminated) { ?>
                                <!-- Termination Notice -->
                                <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
                                    <div class="flex items-start">
                                        <i class="fas fa-exclamation-triangle text-red-500 text-xl mr-3 mt-1"></i>
                                        <div>
                                            <h3 class="text-lg font-semibold text-red-800 mb-2">Exam Terminated Due to Security Violations</h3>
                                            <p class="text-red-700 mb-3">Your exam was automatically terminated because of repeated attempts to:</p>
                                            <ul class="list-disc list-inside text-red-600 space-y-1 mb-3">
                                                <li>Switch browser tabs or windows</li>
                                                <li>Access developer tools or view source</li>
                                                <li>Exit fullscreen mode</li>
                                                <li>Use prohibited keyboard shortcuts</li>
                                            </ul>
                                            <?php
                                            // Get violation details
                                            $violation_query = mysqli_query($con, "SELECT COUNT(*) as count FROM tab_violations WHERE email='$email' AND exam_id='$eid'");
                                            if ($violation_query && mysqli_num_rows($violation_query) > 0) {
                                                $violation_data = mysqli_fetch_assoc($violation_query);
                                                $violation_count = $violation_data['count'];
                                                echo "<p class='text-red-700'><strong>Total violations recorded:</strong> $violation_count</p>";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            
                            <table class="min-w-full exam-table">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3">Metric</th>
                                        <th class="px-4 py-3">Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                while ($row = mysqli_fetch_array($q)) {
                                    $r = $row['sahi']; // Right answers
                                    $qa = $row['level']; // Total questions
                                    $terminated = isset($row['terminated']) ? $row['terminated'] : 0;
                                    $termination_reason = isset($row['termination_reason']) ? $row['termination_reason'] : '';
                                    
                                    // Update the rank field with the right answers count only if not terminated
                                    if (!$terminated) {
                                        mysqli_query($con, "UPDATE history SET score='$r' WHERE eid='$eid' AND email='$email'") or die('Error updating score');
                                    }
                                ?>
                                    <tr class="border-b border-gray-200">
                                        <td class="px-4 py-4 font-medium text-primary">Status</td>
                                        <td class="px-4 py-4 text-center">
                                            <?php if ($terminated) { ?>
                                                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">
                                                    <i class="fas fa-times-circle mr-1"></i>Terminated
                                                </span>
                                            <?php } else { ?>
                                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                                    <i class="fas fa-check-circle mr-1"></i>Completed
                                                </span>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <tr class="border-b border-gray-200">
                                        <td class="px-4 py-4 font-medium text-primary">Right Answers (Score)</td>
                                        <td class="px-4 py-4 text-center <?php echo $terminated ? 'text-gray-500' : 'text-green-600'; ?> font-bold"><?php echo $r; ?></td>
                                    </tr>
                                    <tr class="border-b border-gray-200">
                                        <td class="px-4 py-4 font-medium text-primary">Questions Attempted</td>
                                        <td class="px-4 py-4 text-center <?php echo $terminated ? 'text-gray-500' : 'text-blue-600'; ?> font-bold"><?php echo $qa; ?></td>
                                    </tr>
                                    <?php if ($terminated && $termination_reason) { ?>
                                    <tr class="border-b border-gray-200">
                                        <td class="px-4 py-4 font-medium text-primary">Termination Reason</td>
                                        <td class="px-4 py-4 text-center text-red-600 font-medium"><?php echo htmlspecialchars($termination_reason); ?></td>
                                    </tr>
                                    <?php } ?>
                                <?php } ?>
                                </tbody>
                            </table>
                            
                            <?php if ($was_terminated) { ?>
                                <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <div class="flex items-start">
                                        <i class="fas fa-info-circle text-yellow-600 text-lg mr-3 mt-1"></i>
                                        <div>
                                            <h4 class="font-semibold text-yellow-800 mb-1">Important Notice</h4>
                                            <p class="text-yellow-700 text-sm">This incident has been logged. Please contact your instructor if you believe this termination was in error. Future exam attempts may be restricted.</p>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            
                            <div class="mt-8 text-center">
                                <a href="account.php?q=1" class="btn-primary inline-flex items-center justify-center space-x-2">
                                    <i class="fas fa-home"></i>
                                    <span>Back to Home</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            
        </div>
    </main>

<!-- Footer -->
    <footer class="footer-glass text-white py-8 mt-auto">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center md:text-left">
                <div class="group">
                    <a href="#" id="developersBtn" class="inline-flex justify-center md:justify-start items-center space-x-2 hover:text-primary transition-all duration-300">
                        <i class="fas fa-code text-2xl"></i>
                        <div>
                            <span class="font-medium">Developers</span>
                            <p class="text-sm opacity-80">Meet our team</p>
                        </div>
                    </a>
                </div>
                <div class="group">
                    <a href="feedback.php" target="_blank" class="inline-flex justify-center md:justify-start items-center space-x-2 hover:text-secondary transition-all duration-300">
                        <i class="fas fa-comments text-2xl"></i>
                        <div>
                            <span class="font-medium">Feedback</span>
                            <p class="text-sm opacity-80">Share your thoughts</p>
                        </div>
                    </a>
                </div>
                <div class="group">
                    <a href="#" class="inline-flex justify-center md:justify-start items-center space-x-2 hover:text-accent transition-all duration-300">
                        <i class="fas fa-question-circle text-2xl"></i>
                        <div>
                            <span class="font-medium">Help & Support</span>
                            <p class="text-sm opacity-80">Get assistance</p>
                        </div>
                    </a>
                </div>
                <div class="group">
                    <div class="inline-flex justify-center md:justify-start items-center space-x-2">
                        <i class="fas fa-eye text-2xl text-primary"></i>
                        <div>
                            <span class="font-medium">FaceTrackED</span>
                            <p class="text-sm opacity-80">Secure Examinations</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-8 pt-8 border-t border-white/20 text-sm opacity-70">
                &copy; 2025 FaceTrackED | Advanced Examination System | All Rights Reserved
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
                        <h4 class="text-2xl font-bold text-primary mb-2">Kian A. Rodrigez</h4>
                        <p class="text-gray-600 font-medium mb-3">Lead Developer & System Architect</p>
                        <div class="space-y-2">
                            <p class="flex items-center justify-center md:justify-start text-gray-600">
                                <i class="fas fa-graduation-cap mr-2 text-primary"></i>
                                Computer Science Student
                            </p>
                            <p class="flex items-center justify-center md:justify-start text-gray-600">
                                <i class="fas fa-code mr-2 text-accent"></i>
                                Full-Stack Developer
                            </p>
                            <p class="flex items-center justify-center md:justify-start text-gray-600">
                                <i class="fas fa-brain mr-2 text-secondary"></i>
                                AI & Machine Learning Enthusiast
                            </p>
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-sm text-gray-500 italic">
                                "Passionate about creating secure and innovative examination solutions."
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
            $warning_message = "Critical Violation Level: You have received $warning_count warnings. Your account is at risk of permanent suspension.";
            $warning_icon = "error";
        } elseif ($warning_count >= 30) {
            $warning_message = "Severe Violation Level: You have received $warning_count warnings. Further violations may result in account suspension.";
            $warning_icon = "warning";
        } elseif ($warning_count >= 20) {
            $warning_message = "Major Violation Level: You have received $warning_count warnings. Please review exam guidelines.";
            $warning_icon = "warning";
        } elseif ($warning_count >= 10) {
            $warning_message = "Moderate Violation Level: You have received $warning_count warnings. Be careful during exams.";
            $warning_icon = "info";
        } elseif ($warning_count > 0) {
            $warning_message = "Minor Violation Level: You have received $warning_count warnings. Please follow exam rules carefully.";
            $warning_icon = "info";
        }
    ?>
        Swal.fire({
            title: 'Welcome, <?php echo htmlspecialchars($name); ?>!',
            html: 'You have successfully logged in to the RS Online Exam System.' + 
                  <?php if ($warning_count > 0) { ?> 
                  '<br><br><div class="mt-3 p-3 rounded-lg ' + 
                  '<?php echo ($warning_count >= 30) ? "bg-red-50 border border-red-200" : 
                          (($warning_count >= 20) ? "bg-orange-50 border border-orange-200" : 
                          (($warning_count >= 10) ? "bg-yellow-50 border border-yellow-200" : 
                          "bg-blue-50 border border-blue-200")) ?>">' + 
                  '<i class="fas <?php echo ($warning_count >= 30) ? "fa-exclamation-triangle text-red-500" : 
                                (($warning_count >= 20) ? "fa-exclamation-circle text-orange-500" : 
                                (($warning_count >= 10) ? "fa-exclamation text-yellow-600" : 
                                "fa-info-circle text-blue-500")) ?> mr-2"></i>' +
                  '<span class="font-medium"><?php echo $warning_message; ?></span></div>'
                  <?php } else { ?>
                  ''
                  <?php } ?>,
            icon: '<?php echo ($warning_count >= 30) ? "warning" : "success"; ?>',
            timer: <?php echo ($warning_count > 0) ? "6000" : "3000"; ?>,
            timerProgressBar: true,
            showConfirmButton: false,
            background: '#fff',
            iconColor: '<?php echo ($warning_count >= 30) ? "#dc3545" : "#28a745"; ?>',
            customClass: {
                popup: 'rounded-xl shadow-xl border <?php echo ($warning_count >= 30) ? "border-red-100" : "border-green-100"; ?>'
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
        let timerElement = document.getElementById('exam-timer');
        
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

    // Tab switching prevention and detection
    let tabSwitchWarnings = 0;
    let examStartTime = null;
    let isExamActive = <?php echo ($_SESSION['exam_active'] ? 'true' : 'false'); ?>;
    let tabSwitchDetectionEnabled = false;

    function enableTabSwitchDetection() {
        console.log('Enabling tab switch detection, isExamActive:', isExamActive);
        
        if (!isExamActive) {
            console.log('Exam not active, skipping detection setup');
            return;
        }
        
        tabSwitchDetectionEnabled = true;
        examStartTime = Date.now();
        
        console.log('Tab switch detection enabled');
        
        // Show initial notification about anti-cheating measures
        Swal.fire({
            title: 'Exam Security Active',
            html: `
                <div class="text-left space-y-3">
                    <p class="flex items-center"><i class="fas fa-shield-alt text-blue-500 mr-2"></i> Tab switching detection enabled</p>
                    <p class="flex items-center"><i class="fas fa-expand text-blue-500 mr-2"></i> Fullscreen mode required</p>
                    <p class="flex items-center"><i class="fas fa-eye text-blue-500 mr-2"></i> Face monitoring active</p>
                    <p class="text-sm text-red-600 mt-3"><strong>Warning:</strong> 3 violations will terminate your exam</p>
                </div>
            `,
            icon: 'info',
            confirmButtonText: 'Start Exam',
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then(() => {
            // Continue with exam start
            requestFullscreen();
        });
        
        // Prevent right-click context menu
        document.addEventListener('contextmenu', handleContextMenu);
        
        // Prevent key combinations that could be used for cheating
        document.addEventListener('keydown', handleKeyDown);
        
        // Detect when user switches tabs or minimizes window
        document.addEventListener('visibilitychange', handleVisibilityChange);
        
        // Detect when window loses focus
        window.addEventListener('blur', handleWindowBlur);
        
        // Detect when window regains focus
        window.addEventListener('focus', handleWindowFocus);
        
        // Detect fullscreen changes
        document.addEventListener('fullscreenchange', handleFullscreenChange);
    }
    
    function handleContextMenu(e) {
        if (isExamActive && tabSwitchDetectionEnabled) {
            e.preventDefault();
            console.log('Context menu attempt blocked');
            recordTabSwitchViolation('Context menu attempted');
        }
    }
    
    function handleKeyDown(e) {
        if (isExamActive && tabSwitchDetectionEnabled) {
            console.log('Key pressed:', e.key, 'Ctrl:', e.ctrlKey, 'Shift:', e.shiftKey, 'Alt:', e.altKey);
            
            // F12 - Developer Tools
            if (e.key === 'F12') {
                e.preventDefault();
                console.log('F12 blocked');
                recordTabSwitchViolation('Developer tools attempt (F12)');
                return false;
            }
            
            // Ctrl+Shift+I - Developer Tools
            if (e.ctrlKey && e.shiftKey && e.key === 'I') {
                e.preventDefault();
                console.log('Ctrl+Shift+I blocked');
                recordTabSwitchViolation('Developer tools attempt (Ctrl+Shift+I)');
                return false;
            }
            
            // Ctrl+U - View Source
            if (e.ctrlKey && e.key === 'u') {
                e.preventDefault();
                console.log('Ctrl+U blocked');
                recordTabSwitchViolation('View source attempt (Ctrl+U)');
                return false;
            }
            
            // Ctrl+Shift+C - Element Inspector
            if (e.ctrlKey && e.shiftKey && e.key === 'C') {
                e.preventDefault();
                console.log('Ctrl+Shift+C blocked');
                recordTabSwitchViolation('Element inspector attempt (Ctrl+Shift+C)');
                return false;
            }
            
            // Alt+Tab - Window switching
            if (e.altKey && e.key === 'Tab') {
                e.preventDefault();
                console.log('Alt+Tab blocked');
                recordTabSwitchViolation('Window switching attempt (Alt+Tab)');
                return false;
            }
            
            // Ctrl+Tab - Tab switching
            if (e.ctrlKey && e.key === 'Tab') {
                e.preventDefault();
                console.log('Ctrl+Tab blocked');
                recordTabSwitchViolation('Tab switching attempt (Ctrl+Tab)');
                return false;
            }
            
            // Windows key
            if (e.key === 'Meta' || e.key === 'OS') {
                e.preventDefault();
                console.log('Windows key blocked');
                recordTabSwitchViolation('Windows key pressed');
                return false;
            }
            
            // Ctrl+Shift+J - Console
            if (e.ctrlKey && e.shiftKey && e.key === 'J') {
                e.preventDefault();
                console.log('Ctrl+Shift+J blocked');
                recordTabSwitchViolation('Console access attempt (Ctrl+Shift+J)');
                return false;
            }
            
            // Ctrl+R or F5 - Page refresh
            if ((e.ctrlKey && e.key === 'r') || e.key === 'F5') {
                e.preventDefault();
                console.log('Page refresh blocked');
                recordTabSwitchViolation('Page refresh attempt');
                return false;
            }
        }
    }
    
    function handleVisibilityChange() {
        if (isExamActive && tabSwitchDetectionEnabled) {
            console.log('Visibility changed, document.hidden:', document.hidden);
            if (document.hidden) {
                console.log('Tab switched or window minimized');
                recordTabSwitchViolation('Tab switched or window minimized');
            } else {
                // User returned to the tab
                console.log('User returned to tab');
                showTabReturnWarning();
            }
        }
    }
    
    function handleWindowBlur() {
        if (isExamActive && tabSwitchDetectionEnabled) {
            console.log('Window lost focus');
            recordTabSwitchViolation('Window lost focus');
        }
    }
    
    function handleWindowFocus() {
        if (isExamActive && tabSwitchDetectionEnabled && tabSwitchWarnings > 0) {
            console.log('Window regained focus');
            showTabReturnWarning();
        }
    }
    
    function handleFullscreenChange() {
        if (isExamActive && tabSwitchDetectionEnabled && !document.fullscreenElement) {
            console.log('Exited fullscreen mode');
            recordTabSwitchViolation('Exited fullscreen mode');
            // Force return to fullscreen after a delay
            setTimeout(() => requestFullscreen(), 1000);
        }
    }

    function requestFullscreen() {
        if (isExamActive && document.documentElement.requestFullscreen) {
            document.documentElement.requestFullscreen().catch(err => {
                console.log('Fullscreen request failed:', err);
                // Show warning if fullscreen fails
                Swal.fire({
                    title: 'Fullscreen Required',
                    text: 'Please allow fullscreen mode for exam security.',
                    icon: 'warning',
                    confirmButtonText: 'Try Again'
                }).then(() => {
                    requestFullscreen();
                });
            });
        }
    }

    function recordTabSwitchViolation(violationType) {
        console.log('Recording violation:', violationType, 'isExamActive:', isExamActive, 'tabSwitchDetectionEnabled:', tabSwitchDetectionEnabled);
        
        if (!isExamActive) {
            console.log('Exam not active, skipping violation recording');
            return;
        }
        
        tabSwitchWarnings++;
        console.log('Violation count increased to:', tabSwitchWarnings);
        
        // Update display
        updateViolationDisplay();
        
        // Save violation to database
        saveTabSwitchViolation(violationType);
        
        // Show immediate warning
        showImmediateWarning(violationType);
        
        // Check if maximum violations reached
        if (tabSwitchWarnings >= 3) {
            console.log('Maximum violations reached, terminating exam');
            forceExamTermination();
        }
    }

    function saveTabSwitchViolation(violationType) {
        console.log('Saving violation to database:', violationType);
        
        const violationData = {
            email: '<?php echo $email; ?>',
            name: '<?php echo $name; ?>',
            violation_type: violationType,
            violation_count: tabSwitchWarnings,
            timestamp: new Date().toISOString(),
            exam_id: '<?php echo @$_GET['eid']; ?>',
            question_number: '<?php echo @$_GET['n']; ?>'
        };
        
        console.log('Violation data:', violationData);
        
        // Use XMLHttpRequest as a more reliable alternative to fetch
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'save_tab_violation.php', true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.setRequestHeader('Accept', 'application/json');
        
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                console.log('XHR Response status:', xhr.status);
                console.log('XHR Response text:', xhr.responseText);
                
                if (xhr.status === 200) {
                    try {
                        const data = JSON.parse(xhr.responseText);
                        console.log('Tab violation recorded successfully:', data);
                        if (!data.success) {
                            console.error('Server reported error:', data.message);
                        }
                    } catch (e) {
                        console.error('Failed to parse JSON response:', e);
                        console.error('Response was:', xhr.responseText);
                    }
                } else {
                    console.error('HTTP error:', xhr.status, xhr.statusText);
                    console.error('Response:', xhr.responseText);
                }
            }
        };
        
        xhr.onerror = function() {
            console.error('Network error occurred');
        };
        
        try {
            xhr.send(JSON.stringify(violationData));
            console.log('Violation data sent via XHR');
        } catch (error) {
            console.error('Error sending violation data:', error);
            
            // Fallback: try with a simple form POST as last resort
            console.log('Trying fallback form submission...');
            saveViolationViaForm(violationType, violationData);
        }
    }
    
    function saveViolationViaForm(violationType, violationData) {
        // Create a hidden form and submit it as fallback
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'save_tab_violation.php';
        form.style.display = 'none';
        
        // Add JSON data as a hidden field
        const jsonField = document.createElement('input');
        jsonField.type = 'hidden';
        jsonField.name = 'json_data';
        jsonField.value = JSON.stringify(violationData);
        form.appendChild(jsonField);
        
        // Add individual fields for compatibility
        Object.keys(violationData).forEach(key => {
            const field = document.createElement('input');
            field.type = 'hidden';
            field.name = key;
            field.value = violationData[key];
            form.appendChild(field);
        });
        
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
        
        console.log('Fallback form submitted');
    }

    function showImmediateWarning(violationType) {
        const warningMessages = [
            'First Warning: Stay focused on your exam. Tab switching detected.',
            'Second Warning: Another violation detected. One more violation will terminate your exam.',
            'Final Warning: Exam will be terminated due to repeated violations.'
        ];
        
        Swal.fire({
            title: 'Security Violation Detected!',
            html: `
                <div class="text-left">
                    <p class="mb-3"><strong>Violation:</strong> ${violationType}</p>
                    <p class="mb-3"><strong>Warning ${tabSwitchWarnings}/3:</strong></p>
                    <p class="${tabSwitchWarnings >= 3 ? 'text-red-600 font-bold' : 'text-orange-600'}">${warningMessages[Math.min(tabSwitchWarnings - 1, 2)]}</p>
                    ${tabSwitchWarnings >= 2 ? '<p class="text-sm text-gray-500 mt-2">Stay in this tab and focus on your exam.</p>' : ''}
                </div>
            `,
            icon: 'warning',
            confirmButtonText: 'I Understand',
            allowOutsideClick: false,
            allowEscapeKey: false,
            customClass: {
                popup: 'rounded-xl shadow-2xl border border-red-200',
                confirmButton: 'bg-red-600 hover:bg-red-700'
            }
        });
    }

    function showTabReturnWarning() {
        if (tabSwitchWarnings > 0) {
            Swal.fire({
                title: 'Welcome Back',
                text: `You have ${tabSwitchWarnings} violation(s). Please focus on your exam to avoid termination.`,
                icon: 'info',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        }
    }

    function forceExamTermination() {
        // Stop all detection
        disableTabSwitchDetection();
        
        Swal.fire({
            title: 'Exam Terminated',
            html: `
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle text-6xl text-red-500 mb-4"></i>
                    <p class="text-lg mb-3">Your exam has been terminated due to repeated security violations.</p>
                    <p class="text-sm text-gray-600">Total violations: ${tabSwitchWarnings}</p>
                    <p class="text-sm text-gray-600">You will be redirected to the dashboard.</p>
                </div>
            `,
            icon: 'error',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true
        }).then(() => {
            // Redirect to dashboard
            window.location.href = 'account.php?q=1';
        });
        
        // Submit current answers if any and mark exam as terminated
        submitExamWithViolation();
    }

    function submitExamWithViolation() {
        // Get current form if exists
        const currentForm = document.querySelector('form[action*="update.php"]');
        if (currentForm) {
            // Add a hidden field to mark as terminated
            const terminatedField = document.createElement('input');
            terminatedField.type = 'hidden';
            terminatedField.name = 'exam_terminated';
            terminatedField.value = 'true';
            currentForm.appendChild(terminatedField);
            
            // Submit the form
            currentForm.submit();
        } else {
            // If no form, just redirect
            window.location.href = 'account.php?q=1';
        }
    }

    function disableTabSwitchDetection() {
        tabSwitchDetectionEnabled = false;
        isExamActive = false;
        if (document.fullscreenElement) {
            document.exitFullscreen();
        }
    }
    
    // Test function for debugging (remove in production)
    function testTabDetection() {
        console.log('Test button clicked');
        recordTabSwitchViolation('Manual test violation');
        updateViolationDisplay();
    }
    
    function updateViolationDisplay() {
        const display = document.getElementById('violationCount');
        if (display) {
            display.textContent = `Violations: ${tabSwitchWarnings}`;
        }
    }

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
        console.log('Exam is active - starting monitoring');
        startFaceDetection();
        enableTabSwitchDetection();
    <?php } else { ?>
        console.log('Exam is not active');
        stopFaceDetection();
        disableTabSwitchDetection();
    <?php } ?>
    
    // Also check URL parameters for exam state
    const urlParams = new URLSearchParams(window.location.search);
    const isQuizStep2 = urlParams.get('q') === 'quiz' && urlParams.get('step') === '2';
    
    if (isQuizStep2) {
        console.log('Quiz step 2 detected from URL - enabling tab monitoring');
        isExamActive = true;
        enableTabSwitchDetection();
    }
});
</script>

</body>
</html>