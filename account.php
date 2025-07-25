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
    <title>RS Online Exam System</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#28a745',     // Green color 
                        'primary-dark': '#218838', // Darker green for hover states
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
        /* Base styling */
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
            background: linear-gradient(120deg, #dff8e7 0%, #b6e6c4 100%);
            overflow: hidden;
        }
        
        .floating-icon {
            position: absolute;
            opacity: 0.5;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.15));
            z-index: -1;
            transition: all 0.3s ease;
            color: #28a745;
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
            background: rgba(40, 167, 69, 0.18);
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
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.25);
            outline: none;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #374151;
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
        
        .btn-register {
            background-color: #b30000 !important;
            color: white !important;
            transition: background-color 0.3s, transform 0.3s;
        }
        
        .btn-register:hover {
            background-color: #218838 !important;
            transform: scale(1.05);
        }
                
        /* Modal styling */
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
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
            position: relative;
        }
        
        .camera-section {
            width: 200px;
            height: 200px;
            background: #f8f9fa;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
            position: absolute;
            bottom: 50px;
            right: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .camera-section img {
            width: 100%;
            height: auto;
            border-radius: 10px;
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
            <div class="flex items-center space-x-4">
                <span class="text-gray-700">Hello, <span class="text-primary font-semibold"><?php echo htmlspecialchars($name); ?></span></span>
                <a href="logout.php?q=account.php" class="btn-secondary flex items-center space-x-2 text-sm">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Navigation Menu -->
    <nav class="fixed top-20 w-full bg-white bg-opacity-90 backdrop-filter backdrop-blur-lg shadow-md z-40 transition-all duration-300">
        <div class="container mx-auto px-4">
            <div class="flex justify-start items-center">
                <a class="nav-item <?php if(@$_GET['q']==1) echo 'active'; ?>" href="account.php?q=1">
                    <i class="fas fa-home mr-2"></i> Home
                </a>
            </div>
        </div>
    </nav>

<!-- Main Content -->
    <main class="flex-grow pt-40 pb-16">
        <div class="container mx-auto px-4 py-8">

            <?php if (@$_GET['q'] == 1) { ?>
                <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden transform transition-all duration-500 hover:shadow-2xl">
                    <div class="p-8 md:p-12 bg-gradient-to-br from-white to-green-50">
                        <h2 class="text-2xl md:text-3xl font-bold text-center text-primary mb-4">Welcome, <?php echo htmlspecialchars($name); ?>!</h2>
                        <h3 class="text-xl font-medium text-center text-gray-600 mb-6">Available Tests</h3>
                        
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
                            <table class="min-w-full bg-white border-collapse">
                                <thead>
                                    <tr class="bg-gray-50 border-b-2 border-gray-200">
                                        <th class="px-4 py-3 text-left">S.N.</th>
                                        <th class="px-4 py-3 text-left">Topic</th>
                                        <th class="px-4 py-3 text-center">Questions</th>
                                        <th class="px-4 py-3 text-center">Marks</th>
                                        <th class="px-4 py-3 text-center">Time</th>
                                        <th class="px-4 py-3 text-center">Actions</th>
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
                <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden transform transition-all duration-500 hover:shadow-2xl">
                    <div class="quiz-container">
                        <div class="quiz-section">
                            <h2 class="text-2xl font-bold text-primary mb-4 pb-2 border-b-2 border-primary">Question <?php echo $sn; ?></h2>
                            
                            <?php
                            $row = mysqli_fetch_array($q);
                            $qns = $row['qns'];
                            $qid = $row['qid'];
                            ?>
                            
                            <p class="text-lg mb-6"><?php echo $qns; ?></p>
                            
                            <form action="update.php?q=quiz&step=2&eid=<?php echo $eid; ?>&n=<?php echo $sn; ?>&t=<?php echo $total; ?>&qid=<?php echo $qid; ?>" method="POST" class="space-y-4">
                                <?php
                                $q = mysqli_query($con, "SELECT * FROM options WHERE qid='$qid' ORDER BY RAND()");
                                $optionLabels = ['A', 'B', 'C', 'D']; 
                                $index = 0;
                                
                                while ($row = mysqli_fetch_array($q)) {
                                    $option = $row['option'];
                                    $optionid = $row['optionid'];
                                    ?>
                                    
                                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                        <input type="radio" name="ans" value="<?php echo $optionid; ?>" class="h-5 w-5 text-primary">
                                        <span class="ml-3 text-gray-700">
                                            <span class="font-medium text-primary mr-2"><?php echo $optionLabels[$index++]; ?>.</span>
                                            <?php echo $option; ?>
                                        </span>
                                    </label>
                                    
                                <?php } ?>
                                
                                <div class="pt-6">
                                    <button type="submit" class="btn-primary inline-flex items-center justify-center space-x-2">
                                        <i class="fas fa-paper-plane"></i>
                                        <span>Submit Answer</span>
                                    </button>
                                </div>
                            </form>
                            
                            <!-- Camera Section -->
                            <div class="camera-section">
                                <img src="http://127.0.0.1:5000/video_feed" alt="Video Feed" class="rounded-lg">
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php if (@$_GET['q'] == 'result' && @$_GET['eid']) {
                $eid = @$_GET['eid'];
                $q = mysqli_query($con, "SELECT * FROM history WHERE eid='$eid' AND email='$email' ") or die('Error157');
            ?>
                <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden transform transition-all duration-500 hover:shadow-2xl">
                    <div class="p-8 md:p-12 bg-gradient-to-br from-white to-green-50">
                        <h2 class="text-2xl md:text-3xl font-bold text-center text-primary mb-8">Exam Results</h2>
                          <div class="bg-white p-6 rounded-xl shadow-md">
                            <table class="min-w-full bg-white border-collapse">
                                <thead>
                                    <tr class="bg-gray-50 border-b-2 border-gray-200">
                                        <th class="px-4 py-3 text-left">Metric</th>
                                        <th class="px-4 py-3 text-center">Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                while ($row = mysqli_fetch_array($q)) {
                                    $r = $row['sahi']; // Right answers
                                    $qa = $row['level']; // Total questions
                                    
                                    // Update the rank field with the right answers count
                                    mysqli_query($con, "UPDATE history SET score='$r' WHERE eid='$eid' AND email='$email'") or die('Error updating score');
                                ?>
                                    <tr class="border-b border-gray-200">
                                        <td class="px-4 py-4 font-medium text-primary">Right Answers (Score)</td>
                                        <td class="px-4 py-4 text-center text-green-600 font-bold"><?php echo $r; ?></td>
                                    </tr>
                                    <tr class="border-b border-gray-200">
                                        <td class="px-4 py-4 font-medium text-primary">Total Questions</td>
                                        <td class="px-4 py-4 text-center text-blue-600 font-bold"><?php echo $qa; ?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                            
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
    <footer class="bg-primary text-white py-6 mt-auto">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
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
                <div class="group">
                    <a href="#" class="inline-flex justify-center items-center space-x-2 hover:text-green-200 transition-colors duration-300">
                        <i class="fas fa-question-circle text-xl"></i>
                        <span>Help</span>
                    </a>
                </div>
            </div>
            <div class="text-center mt-6 text-sm text-green-100">
                &copy; <?php echo date('Y'); ?> RS Online Exam System | All Rights Reserved
            </div>
        </div>
    </footer>

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
                        <h4 class="text-xl font-bold text-primary mb-1">Kian A. Rodriguez</h4>
                        <p class="flex items-center justify-center md:justify-start text-gray-600 mb-1">
                            <i class="fas fa-phone-alt mr-2 text-primary"></i>
                            +917785068889
                        </p>
                        <p class="flex items-center justify-center md:justify-start text-gray-600">
                            <i class="fas fa-envelope mr-2 text-primary"></i>
                            kianr664@gmail.com
                        </p>
                        <p class="flex items-center justify-center md:justify-start text-gray-600 mt-1">
                            <i class="fas fa-university mr-2 text-primary"></i>
                            MinSU
                        </p>
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