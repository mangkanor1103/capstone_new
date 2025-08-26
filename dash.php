<?php
// Start session at the very beginning of the file
include_once 'dbConnection.php';
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FaceTrackED - Admin Dashboard</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Google Font - Inter for modern look -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- SweetAlert2 for modern alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3b82f6',        // Blue
                        'primary-dark': '#1e40af',
                        'primary-light': '#93c5fd',
                        secondary: '#f59e0b',      // Amber
                        'secondary-dark': '#d97706',
                        'secondary-light': '#fde68a',
                        accent: '#10b981',         // Green
                        'accent-dark': '#047857',
                        'accent-light': '#a7f3d0',
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'bounce-slow': 'bounce 3s infinite',
                        'gradient': 'gradient 8s ease infinite',
                        'blob': 'blob 7s infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        },
                        gradient: {
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
                            '0%, 100%': { 
                                borderRadius: '60% 40% 30% 70% / 60% 30% 70% 40%',
                                transform: 'rotate(0deg)'
                            },
                            '25%': { 
                                borderRadius: '30% 60% 70% 40% / 50% 60% 30% 60%',
                                transform: 'rotate(90deg)'
                            },
                            '50%': { 
                                borderRadius: '50% 60% 30% 40% / 40% 30% 70% 60%',
                                transform: 'rotate(180deg)'
                            },
                            '75%': { 
                                borderRadius: '40% 30% 70% 60% / 60% 40% 30% 70%',
                                transform: 'rotate(270deg)'
                            },
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        /* Base styling for modern look */
        body {
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }
        
        .animated-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: linear-gradient(-45deg, #3b82f6, #1e40af, #10b981, #059669);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            opacity: 0.03;
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px rgba(59, 130, 246, 0.15);
        }
        
        .floating-icon {
            position: absolute;
            opacity: 0.4;
            filter: drop-shadow(0 4px 6px rgba(59, 130, 246, 0.3));
            z-index: -1;
            transition: all 0.3s ease;
            color: #3b82f6;
        }
        
        .floating-icon:hover {
            opacity: 0.6;
            transform: scale(1.2);
        }
        
        .blob {
            position: absolute;
            background: linear-gradient(45deg, rgba(59, 130, 246, 0.1), rgba(16, 185, 129, 0.1));
            width: 300px;
            height: 300px;
            animation: blob 15s linear infinite alternate;
            z-index: -1;
            filter: blur(40px);
        }
        
        .form-input {
            @apply w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-300;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .form-input:hover {
            @apply border-gray-400;
            box-shadow: 0 4px 8px rgba(59, 130, 246, 0.1);
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
            color: #374151;
            font-size: 0.875rem;
            text-align: left;
        }
        
        .btn-primary {
            @apply bg-primary hover:bg-primary-dark text-white font-semibold py-3 px-6 rounded-xl transform transition-all duration-300 hover:scale-105 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-primary focus:ring-opacity-50;
            background: linear-gradient(135deg, #3b82f6, #1e40af);
        }
        
        .btn-secondary {
            @apply bg-secondary hover:bg-secondary-dark text-white font-semibold py-3 px-6 rounded-xl transform transition-all duration-300 hover:scale-105 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-secondary focus:ring-opacity-50;
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }
        
        .btn-accent {
            @apply bg-accent hover:bg-accent-dark text-white font-semibold py-3 px-6 rounded-xl transform transition-all duration-300 hover:scale-105 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-accent focus:ring-opacity-50;
            background: linear-gradient(135deg, #10b981, #047857);
        }
        
        .btn-outline {
            @apply border-2 border-gray-300 text-gray-700 font-semibold py-3 px-6 rounded-xl transform transition-all duration-300 hover:scale-105 hover:shadow-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
        }
        
        .nav-item {
            @apply relative px-6 py-4 flex items-center text-gray-700 hover:text-primary transition-all duration-300 rounded-lg;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            margin: 0 2px;
        }
        
        .nav-item.active {
            @apply text-white font-semibold;
            background: linear-gradient(135deg, #3b82f6, #1e40af);
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
        }
        
        .nav-item:hover {
            background: rgba(59, 130, 246, 0.1);
            transform: translateY(-2px);
        }
        
        .panel-title {
            @apply text-3xl font-bold mb-6 pb-3;
            background: linear-gradient(135deg, #3b82f6, #10b981);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            border-bottom: 3px solid #3b82f6;
        }
        
        .action-btn {
            transition: all 0.3s ease;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
        }
        
        /* Enhanced Modal styling */
        .modal {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            z-index: 99999 !important;
            background: rgba(0, 0, 0, 0.8) !important;
            backdrop-filter: blur(10px) !important;
            display: none !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 1rem !important;
            overflow-y: auto !important;
        }
        
        .modal-content {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(20px) !important;
            border-radius: 1.5rem !important;
            box-shadow: 0 25px 50px -12px rgba(59, 130, 246, 0.3) !important;
            width: 100% !important;
            max-width: 28rem !important;
            max-height: 85vh !important;
            overflow-y: auto !important;
            transform: scale(0.9) !important;
            transition: all 0.3s ease-in-out !important;
            text-align: left !important;
            position: relative !important;
            margin: auto !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
        }
        
        .modal.active {
            display: flex !important;
            animation: fadeIn 0.3s ease-out forwards !important;
        }
        
        .modal.active .modal-content {
            transform: scale(1) !important;
        }
        
        @keyframes fadeIn {
            from { 
                opacity: 0;
                transform: scale(0.9);
            }
            to { 
                opacity: 1;
                transform: scale(1);
            }
        }
        
        /* Ensure modal appears above everything */
        .modal * {
            box-sizing: border-box;
        }
        
        body.modal-open {
            overflow: hidden !important;
        }
        
        /* Header glass effect */
        .header-glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px rgba(59, 130, 246, 0.15);
        }
        
        /* Table enhancements */
        .table-glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(59, 130, 246, 0.1);
        }
        
        /* Card enhancements */
        .card-glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 1.5rem;
            box-shadow: 0 8px 32px rgba(59, 130, 246, 0.15);
            transition: all 0.3s ease;
        }
        
        .card-glass:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 48px rgba(59, 130, 246, 0.2);
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
        <i class="fas fa-eye floating-icon text-5xl animate-float" style="top: 15%; left: 10%;"></i>
        <i class="fas fa-shield-alt floating-icon text-4xl animate-pulse-slow" style="top: 30%; left: 85%;"></i>
        <i class="fas fa-chart-line floating-icon text-5xl animate-bounce-slow" style="top: 70%; left: 15%;"></i>
        <i class="fas fa-users floating-icon text-4xl animate-float" style="top: 80%; left: 80%; animation-delay: 2s;"></i>
        <i class="fas fa-graduation-cap floating-icon text-5xl animate-pulse-slow" style="top: 40%; left: 50%; animation-delay: 1s;"></i>
    </div>

    <!-- Header -->
    <header class="fixed w-full header-glass z-50 transition-all duration-300">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <i class="fas fa-eye text-4xl text-primary"></i>
                    <div class="absolute -top-1 -right-1 h-3 w-3 bg-accent rounded-full animate-pulse"></div>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold">
                        <span class="bg-gradient-to-r from-primary to-accent bg-clip-text text-transparent">FaceTrackED</span>
                    </h1>
                    <p class="text-sm text-gray-600 font-medium">Admin Dashboard</p>
                </div>
            </div>

            <?php
            include_once 'dbConnection.php';
            $email = $_SESSION['email'];
            if (!(isset($_SESSION['email']))) {
                header("location:index.php");
            } else {
                $name = $_SESSION['name'];
                echo '<div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-3">
                        <div class="h-10 w-10 bg-gradient-to-r from-primary to-accent rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg">
                            '.strtoupper(substr($name, 0, 1)).'
                        </div>
                        <div class="hidden md:block">
                            <span class="text-gray-700 font-medium">Welcome back,</span>
                            <div class="text-primary font-bold">'.htmlspecialchars($name).'</div>
                        </div>
                    </div>
                    <a href="logout.php?q=account.php" class="btn-secondary flex items-center space-x-2 text-sm py-2 px-4">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </div>';
            }
            ?>
        </div>
    </header>

    <!-- Navigation Menu -->
    <nav class="fixed top-24 w-full glass-effect z-40 transition-all duration-300">
        <div class="container mx-auto px-6">
            <div class="flex flex-wrap justify-center lg:justify-between items-center gap-2 py-3">
                <a class="nav-item <?php if(@$_GET['q']==0) echo 'active'; ?>" href="dash.php?q=0">
                    <i class="fas fa-home mr-2"></i> Dashboard
                </a>
                <a class="nav-item <?php if(@$_GET['q']==1) echo 'active'; ?>" href="dash.php?q=1">
                    <i class="fas fa-users mr-2"></i> Users
                </a>
                <a class="nav-item <?php if(@$_GET['q']==2) echo 'active'; ?>" href="dash.php?q=2">
                    <i class="fas fa-trophy mr-2"></i> Rankings
                </a>
                <a class="nav-item <?php if(@$_GET['q']==3) echo 'active'; ?>" href="dash.php?q=3">
                    <i class="fas fa-comments mr-2"></i> Feedback
                </a>
                <a class="nav-item <?php if(@$_GET['q']==4) echo 'active'; ?>" href="dash.php?q=4">
                    <i class="fas fa-plus-circle mr-2"></i> Add Exams
                </a>
                <a class="nav-item <?php if(@$_GET['q']==5) echo 'active'; ?>" href="dash.php?q=5">
                    <i class="fas fa-exclamation-triangle mr-2"></i> Warnings
                </a>
                <a class="nav-item <?php if(@$_GET['q']==7) echo 'active'; ?>" href="dash.php?q=7">
                    <i class="fas fa-shield-alt mr-2"></i> Tab Violations
                </a>
                <a class="nav-item <?php if(@$_GET['q']==6) echo 'active'; ?>" href="dash.php?q=6">
                    <i class="fas fa-chart-line mr-2"></i> Analytics
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow pt-48 pb-16">
        <div class="container mx-auto px-6 py-8">
            <!-- Breadcrumb -->
            <div class="flex items-center text-sm mb-8 card-glass px-6 py-4">
                <a href="dash.php?q=0" class="text-primary hover:text-primary-dark transition-colors font-medium">Dashboard</a>
                <i class="fas fa-chevron-right text-gray-400 mx-3"></i>
                <span class="text-gray-600 font-medium">
                    <?php 
                        if(@$_GET['q']==0) echo "Exams Management";
                        else if(@$_GET['q']==1) echo "User Management";
                        else if(@$_GET['q']==2) echo "Rankings";
                        else if(@$_GET['q']==3) echo "Feedback";
                        else if(@$_GET['q']==4) echo "Add Exam";
                        else if(@$_GET['q']==5) echo "Warnings";
                        else if(@$_GET['q']==7) echo "Tab Violations Monitor";
                        else if(@$_GET['q']==6) echo "Analytics Dashboard";
                        else echo "Dashboard";
                    ?>
                </span>
            </div>

            <div class="max-w-7xl mx-auto card-glass p-8 md:p-12 mb-10">
                <div class="bg-gradient-to-br from-white to-blue-50 rounded-2xl p-8">
                    
                    <?php if(@$_GET['q']==0) { ?>
                        <h2 class="panel-title">Exam Management</h2>
                        <p class="text-gray-600 mb-6">Manage all exams in the system. You can add, edit, or remove exams from here.</p>
                        
                        <?php
                        $result = mysqli_query($con,"SELECT * FROM quiz ORDER BY date DESC") or die('Error');
                        if(mysqli_num_rows($result) > 0) {
                            echo '<div class="table-glass overflow-hidden">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="bg-gradient-to-r from-primary to-accent text-white">
                                        <th class="px-6 py-4 text-left font-semibold">S.N.</th>
                                        <th class="px-6 py-4 text-left font-semibold">Topic</th>
                                        <th class="px-6 py-4 text-center font-semibold">Questions</th>
                                        <th class="px-6 py-4 text-center font-semibold">Marks</th>
                                        <th class="px-6 py-4 text-center font-semibold">Time</th>
                                        <th class="px-6 py-4 text-center font-semibold">Restart</th>
                                        <th class="px-6 py-4 text-center font-semibold">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">';
                            $c=1;
                            while($row = mysqli_fetch_array($result)) {
                                $title = $row['title'];
                                $total = $row['total'];
                                $sahi = $row['sahi'];
                                $time = $row['time'];
                                $eid = $row['eid'];
                                $allow_restart = $row['allow_restart'];
                                
                                echo '<tr class="border-b border-gray-100 hover:bg-gradient-to-r hover:from-blue-50 hover:to-green-50 transition-all duration-300">
                                    <td class="px-6 py-4 font-semibold text-gray-700">'.$c++.'</td>
                                    <td class="px-6 py-4 font-medium text-gray-800">'.$title.'</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">'.$total.'</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">'.$sahi*$total.'</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="bg-amber-100 text-amber-800 px-3 py-1 rounded-full text-sm font-semibold">'.$time.' min</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">';
                                
                                if($allow_restart == 1) {
                                    echo '<div class="flex items-center justify-center space-x-2">
                                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-800 text-xs font-semibold">Enabled</span>
                                        <a href="update.php?action=toggle_restart&eid='.$eid.'&val=0" class="text-sm text-red-500 hover:text-red-700 font-medium transition-colors">Disable</a>
                                    </div>';
                                } else {
                                    echo '<div class="flex items-center justify-center space-x-2">
                                        <span class="px-3 py-1 rounded-full bg-red-100 text-red-800 text-xs font-semibold">Disabled</span>
                                        <a href="update.php?action=toggle_restart&eid='.$eid.'&val=1" class="text-sm text-green-500 hover:text-green-700 font-medium transition-colors">Enable</a>
                                    </div>';
                                }
                                
                                echo '</td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-center space-x-2">
                                            <a href="dash.php?q=0&edit='.$eid.'" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white p-3 rounded-xl transition-all duration-300 transform hover:scale-110 shadow-lg hover:shadow-xl" title="Edit Exam">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="dash.php?q=4&step=1&eid='.$eid.'&n='.$total.'" class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white p-3 rounded-xl transition-all duration-300 transform hover:scale-110 shadow-lg hover:shadow-xl" title="Manage Questions">
                                                <i class="fas fa-question-circle"></i>
                                            </a>
                                            <a href="update.php?q=rmquiz&eid='.$eid.'" class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white p-3 rounded-xl transition-all duration-300 transform hover:scale-110 shadow-lg hover:shadow-xl" onclick="return confirm(\'Are you sure you want to remove this exam?\');" title="Remove Exam">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>';
                            }
                            echo '</tbody></table></div>';
                        } else {
                            echo '<div class="text-center p-12 card-glass">
                                <div class="max-w-md mx-auto">
                                    <div class="mb-6">
                                        <i class="fas fa-clipboard-list text-6xl text-gray-300"></i>
                                    </div>
                                    <h3 class="text-xl font-semibold text-gray-700 mb-3">No Exams Available</h3>
                                    <p class="text-gray-500 mb-6">Get started by creating your first exam.</p>
                                    <a href="dash.php?q=4" class="btn-primary inline-flex items-center justify-center space-x-2">
                                        <i class="fas fa-plus"></i>
                                        <span>Create New Exam</span>
                                    </a>
                                </div>
                            </div>';
                        }
                        ?>
                    <?php } ?>

                    <?php if(@$_GET['q']==0 && @$_GET['edit']) { ?>
    <?php
    $edit_eid = @$_GET['edit'];
    $edit_result = mysqli_query($con, "SELECT * FROM quiz WHERE eid='$edit_eid'") or die('Error fetching exam data');
    
    if(mysqli_num_rows($edit_result) > 0) {
        $exam_data = mysqli_fetch_array($edit_result);
        ?>
        <div class="mb-6">
            <a href="dash.php?q=0" class="btn-outline inline-flex items-center space-x-2">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Exam List</span>
            </a>
        </div>
        
        <h2 class="panel-title">Edit Exam</h2>
        <p class="text-gray-600 mb-6">Update exam details below.</p>
        
        <div class="bg-gray-50 p-6 rounded-xl shadow-md">
            <form class="form-horizontal" name="editform" action="update.php?q=editquiz&eid=<?php echo $edit_eid; ?>" method="POST">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="mb-4">
                        <label for="edit_name" class="form-label">Exam Title</label>
                        <input id="edit_name" name="name" value="<?php echo htmlspecialchars($exam_data['title']); ?>" class="form-input" type="text" required>
                    </div>
                    <div class="mb-4">
                        <label for="edit_total" class="form-label">Total Questions</label>
                        <input id="edit_total" name="total" value="<?php echo $exam_data['total']; ?>" class="form-input" type="number" required>
                        <p class="text-gray-500 text-sm mt-1">Note: Changing this won't affect existing questions</p>
                    </div>
                    <div class="mb-4">
                        <label for="edit_right" class="form-label">Marks for Correct Answer</label>
                        <input id="edit_right" name="right" value="<?php echo $exam_data['sahi']; ?>" class="form-input" min="0" type="number" required>
                    </div>
                    <div class="mb-4">
                        <label for="edit_wrong" class="form-label">Negative Marks</label>
                        <input id="edit_wrong" name="wrong" value="<?php echo $exam_data['wrong']; ?>" class="form-input" min="0" type="number" required>
                    </div>
                    <div class="mb-4">
                        <label for="edit_time" class="form-label">Time Limit (minutes)</label>
                        <input id="edit_time" name="time" value="<?php echo $exam_data['time']; ?>" class="form-input" min="1" type="number" required>
                    </div>
                    <div class="mb-4">
                        <label for="edit_tag" class="form-label">Tag</label>
                        <input id="edit_tag" name="tag" value="<?php echo htmlspecialchars($exam_data['tag']); ?>" class="form-input" type="text">
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Who can take this exam?</label>
                        <?php
                        $current_sections = !empty($exam_data['allowed_sections']) ? json_decode($exam_data['allowed_sections'], true) : [];
                        $is_all_access = empty($current_sections);
                        ?>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <input type="radio" id="edit_access_all" name="access_type" value="all" class="mr-2" <?php echo $is_all_access ? 'checked' : ''; ?> onchange="toggleEditSectionSelect()">
                                <label for="edit_access_all">All Students</label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" id="edit_access_specific" name="access_type" value="specific" class="mr-2" <?php echo !$is_all_access ? 'checked' : ''; ?> onchange="toggleEditSectionSelect()">
                                <label for="edit_access_specific">Specific Year and Section</label>
                            </div>
                        </div>
                        <div id="editSectionSelectContainer" class="mt-3 <?php echo $is_all_access ? 'hidden' : ''; ?>">
                            <label for="edit_allowed_sections" class="form-label">Select Allowed Sections (hold Ctrl to select multiple)</label>
                            <select id="edit_allowed_sections" name="allowed_sections[]" class="form-input" multiple size="6">
                                <?php
                                $sections = [
                                    "Entrance Exam", "Grade 7 - Section A", "Grade 7 - Section B",
                                    "Grade 8 - Section A", "Grade 8 - Section B", "Grade 9 - Section A",
                                    "Grade 9 - Section B", "Grade 10 - Section A", "Grade 10 - Section B",
                                    "Grade 11 - Section A", "Grade 11 - Section B", "Grade 12 - Section A",
                                    "Grade 12 - Section B"
                                ];
                                foreach($sections as $section) {
                                    $selected = in_array($section, $current_sections) ? 'selected' : '';
                                    echo "<option value=\"$section\" $selected>$section</option>";
                                }
                                ?>
                            </select>
                            <p class="text-gray-500 text-sm mt-1">Hold Ctrl (or Cmd on Mac) and click to select multiple sections</p>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Allow Exam Restart</label>
                        <div class="flex items-center space-x-2">
                            <div class="flex items-center">
                                <input type="radio" id="edit_restart_yes" name="allow_restart" value="1" class="mr-2" <?php echo ($exam_data['allow_restart'] == 1) ? 'checked' : ''; ?>>
                                <label for="edit_restart_yes">Yes</label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" id="edit_restart_no" name="allow_restart" value="0" class="mr-2" <?php echo ($exam_data['allow_restart'] == 0) ? 'checked' : ''; ?>>
                                <label for="edit_restart_no">No</label>
                            </div>
                        </div>
                        <p class="text-gray-500 text-sm mt-1">If enabled, users can take this exam multiple times</p>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="edit_desc" class="form-label">Description</label>
                    <textarea rows="4" name="desc" id="edit_desc" class="form-input" required><?php echo htmlspecialchars($exam_data['intro']); ?></textarea>
                </div>
                <div class="mt-8 flex justify-end space-x-3">
                    <a href="dash.php?q=0" class="btn-outline inline-flex items-center justify-center space-x-2 text-lg">
                        <i class="fas fa-times"></i>
                        <span>Cancel</span>
                    </a>
                    <button type="submit" class="btn-primary inline-flex items-center justify-center space-x-2 text-lg">
                        <i class="fas fa-save"></i>
                        <span>Update Exam</span>
                    </button>
                </div>
            </form>
        </div>
        
        <script>
        function toggleEditSectionSelect() {
            const accessType = document.querySelector('input[name="access_type"]:checked').value;
            const sectionContainer = document.getElementById('editSectionSelectContainer');
            const sectionSelect = document.getElementById('edit_allowed_sections');
            
            if (accessType === 'specific') {
                sectionContainer.classList.remove('hidden');
                sectionSelect.required = true;
            } else {
                sectionContainer.classList.add('hidden');
                sectionSelect.required = false;
                // Clear selections
                for (let option of sectionSelect.options) {
                    option.selected = false;
                }
            }
        }
        
        // Initialize the form on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Set initial state based on current selection
            toggleEditSectionSelect();
        });
        </script>
        
        <!-- Question Management Section -->
        <div class="mt-8 bg-white p-6 rounded-xl shadow-lg">
            <h3 class="text-xl font-semibold mb-4 text-primary">Question Management</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg text-center">
                    <i class="fas fa-question-circle text-3xl text-blue-500 mb-2"></i>
                    <h4 class="font-semibold mb-2">Current Questions</h4>
                    <p class="text-2xl font-bold text-blue-600"><?php echo mysqli_num_rows(mysqli_query($con, "SELECT * FROM questions WHERE eid='$edit_eid'")); ?></p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg text-center">
                    <i class="fas fa-target text-3xl text-green-500 mb-2"></i>
                    <h4 class="font-semibold mb-2">Target Questions</h4>
                    <p class="text-2xl font-bold text-green-600"><?php echo $exam_data['total']; ?></p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg text-center">
                    <i class="fas fa-users text-3xl text-purple-500 mb-2"></i>
                    <h4 class="font-semibold mb-2">Attempts</h4>
                    <p class="text-2xl font-bold text-purple-600"><?php echo mysqli_num_rows(mysqli_query($con, "SELECT * FROM history WHERE eid='$edit_eid'")); ?></p>
                </div>
            </div>
            
            <div class="mt-6 flex justify-center space-x-3">
                <a href="dash.php?q=4&step=1&eid=<?php echo $edit_eid; ?>&n=<?php echo $exam_data['total']; ?>" class="btn-primary inline-flex items-center justify-center space-x-2">
                    <i class="fas fa-plus-circle"></i>
                    <span>Add/Edit Questions</span>
                </a>
                <a href="dash.php?q=2&eid=<?php echo $edit_eid; ?>" class="bg-purple-500 hover:bg-purple-600 text-white font-medium py-2 px-4 rounded-lg transition-colors inline-flex items-center justify-center space-x-2">
                    <i class="fas fa-chart-bar"></i>
                    <span>View Rankings</span>
                </a>
            </div>
        </div>
        <?php
    } else {
        echo '<div class="text-center p-8 bg-red-50 rounded-lg border border-red-200">
            <i class="fas fa-exclamation-triangle text-4xl text-red-500 mb-3"></i>
            <p class="text-red-700 font-medium">Exam not found!</p>
            <a href="dash.php?q=0" class="mt-3 btn-primary inline-flex items-center space-x-2">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Exam List</span>
            </a>
        </div>';
    }
    ?>
<?php } ?>

                    <?php if(@$_GET['q'] == 2) { ?>
    <h2 class="panel-title">Exam Rankings</h2>
    <p class="text-gray-600 mb-6">View user performance rankings for specific exams.</p>
    
    <?php
    // Get all exams for the filter dropdown
    $exams_result = mysqli_query($con, "SELECT * FROM quiz ORDER BY title ASC") or die('Error fetching exams');
    
    // Check if a specific exam is selected for filtering
    $selected_eid = isset($_GET['eid']) ? $_GET['eid'] : '';
    
    // Only show rankings if an exam is selected
    if (!empty($selected_eid)) {
        // Get rankings from history table for the specific exam
        $q = mysqli_query($con, "SELECT h.email, h.score, u.name, u.college FROM history h 
                                JOIN user u ON h.email = u.email 
                                WHERE h.eid='$selected_eid' 
                                ORDER BY h.score DESC") or die('Error fetching exam rankings');
        
        // Get exam details
        $examQuery = mysqli_query($con, "SELECT title, total, sahi FROM quiz WHERE eid='$selected_eid'") or die('Error fetching exam details');
        $examData = mysqli_fetch_array($examQuery);
        $exam_title = $examData['title'];
        $totalPossible = $examData['total'] * $examData['sahi'];
        
        $ranking_title = "Rankings for: " . $exam_title;
    } else {
        $q = false; // No exam selected
        $ranking_title = "Select an exam to view rankings";
    }
    
    // Calculate statistics only if exam is selected
    if ($q && mysqli_num_rows($q) > 0) {
        $total_users = mysqli_num_rows($q);
        $scores_array = [];
        $temp_result = $q;
        while($row = mysqli_fetch_array($temp_result)) {
            $scores_array[] = $row['score'];
        }
        mysqli_data_seek($q, 0); // Reset result pointer
        
        $avg_score = $total_users > 0 ? array_sum($scores_array) / $total_users : 0;
        $highest_score = $total_users > 0 ? max($scores_array) : 0;
        $lowest_score = $total_users > 0 ? min($scores_array) : 0;
        
        echo '<div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-6">
            <!-- Statistics Cards -->
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-4 rounded-xl shadow-md">
                <div class="flex items-center">
                    <i class="fas fa-users text-2xl text-blue-600 mr-3"></i>
                    <div>
                        <p class="text-sm text-blue-700 font-medium">Total Participants</p>
                        <p class="text-2xl font-bold text-blue-800">'.$total_users.'</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-green-50 to-green-100 p-4 rounded-xl shadow-md">
                <div class="flex items-center">
                    <i class="fas fa-chart-line text-2xl text-green-600 mr-3"></i>
                    <div>
                        <p class="text-sm text-green-700 font-medium">Average Score</p>
                        <p class="text-2xl font-bold text-green-800">'.number_format($avg_score, 1).'</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 p-4 rounded-xl shadow-md">
                <div class="flex items-center">
                    <i class="fas fa-trophy text-2xl text-yellow-600 mr-3"></i>
                    <div>
                        <p class="text-sm text-yellow-700 font-medium">Highest Score</p>
                        <p class="text-2xl font-bold text-yellow-800">'.$highest_score.' / '.$totalPossible.'</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-purple-50 to-purple-100 p-4 rounded-xl shadow-md">
                <div class="flex items-center">
                    <i class="fas fa-chart-bar text-2xl text-purple-600 mr-3"></i>
                    <div>
                        <p class="text-sm text-purple-700 font-medium">Score Range</p>
                        <p class="text-2xl font-bold text-purple-800">'.$lowest_score.'-'.$highest_score.'</p>
                    </div>
                </div>
            </div>
        </div>';
    }
    
    echo '<div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
        <div class="bg-gray-50 px-4 py-2 rounded-lg shadow-sm">
            <span class="text-gray-600">' . $ranking_title . '</span>';
            if($q) {
                echo ': <span class="font-bold text-primary">'.mysqli_num_rows($q).' participants</span>';
            }
            echo '</span>
        </div>
        
        <div class="flex flex-col lg:flex-row gap-3 items-start lg:items-center">
            <!-- Exam Selection Dropdown -->
            <form method="get" action="dash.php" class="flex items-center space-x-2">
                <input type="hidden" name="q" value="2">
                <select name="eid" class="form-input py-2 px-3" onchange="this.form.submit()" required>
                    <option value="">Select an exam...</option>';
                    
                    // Reset the result pointer for exams dropdown
                    mysqli_data_seek($exams_result, 0);
                    while($exam = mysqli_fetch_array($exams_result)) {
                        $selected = ($selected_eid == $exam['eid']) ? 'selected' : '';
                        echo '<option value="'.$exam['eid'].'" '.$selected.'>'.$exam['title'].'</option>';
                    }
                    
    echo '      </select>
            </form>';
    
    // Only show additional controls if an exam is selected
    if (!empty($selected_eid) && $q && mysqli_num_rows($q) > 0) {
        echo '<!-- Search Input -->
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Search rankings..." class="form-input py-2 pl-10 pr-4" onkeyup="searchTable()">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex space-x-2">
                <button onclick="exportToCSV()" class="btn-primary inline-flex items-center justify-center space-x-2 py-2 px-4">
                    <i class="fas fa-download"></i>
                    <span>Export CSV</span>
                </button>
                
                <button onclick="printRankings()" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                    <i class="fas fa-print mr-2"></i>Print
                </button>
            </div>';
    }
    
    echo '</div>
    </div>';
    
    // Display rankings table only if exam is selected
    if (!empty($selected_eid) && $q && mysqli_num_rows($q) > 0) {
        echo '<div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Performance Distribution Chart -->
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold mb-4">Performance Distribution for: '.$exam_title.'</h3>
            <div class="relative h-64">
                <canvas id="performanceChart"></canvas>
            </div>
        </div>
        
        <div class="overflow-x-auto">
        <table id="rankingsTable" class="min-w-full bg-white">
        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
            <tr>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <div class="flex items-center space-x-1">
                        <span>Rank</span>
                        <i class="fas fa-sort text-gray-400"></i>
                    </div>
                </th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <div class="flex items-center space-x-1">
                        <span>Student</span>
                        <i class="fas fa-sort text-gray-400"></i>
                    </div>
                </th>
                <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <div class="flex items-center justify-center space-x-1">
                        <span>Score</span>
                        <i class="fas fa-sort text-gray-400"></i>
                    </div>
                </th>
                <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Performance</th>
                <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">';
        
        $c = 0;
        $scores_for_chart = [];
        
        while ($row = mysqli_fetch_array($q)) {
            $name = $row['name'];
            $s = $row['score'];
            $college = isset($row['college']) ? $row['college'] : 'N/A';
            $c++;
            
            // Calculate percentage for performance bar
            $percentage = ($s / $totalPossible) * 100;
            $scores_for_chart[] = $percentage;
            
            echo '<tr class="hover:bg-gray-50 transition-colors duration-200">
                <td class="px-6 py-4 whitespace-nowrap">';
                
                // Special styling for top 3 positions
                if($c == 1) {
                    echo '<div class="flex items-center">
                        <div class="relative">
                            <i class="fas fa-crown text-yellow-500 text-xl mr-2"></i>
                            <span class="absolute -top-1 -right-1 bg-yellow-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold">'.$c.'</span>
                        </div>
                    </div>';
                } else if($c == 2) {
                    echo '<div class="flex items-center">
                        <div class="relative">
                            <i class="fas fa-medal text-gray-400 text-xl mr-2"></i>
                            <span class="absolute -top-1 -right-1 bg-gray-400 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold">'.$c.'</span>
                        </div>
                    </div>';
                } else if($c == 3) {
                    echo '<div class="flex items-center">
                        <div class="relative">
                            <i class="fas fa-award text-yellow-700 text-xl mr-2"></i>
                            <span class="absolute -top-1 -right-1 bg-yellow-700 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold">'.$c.'</span>
                        </div>
                    </div>';
                } else {
                    echo '<div class="flex items-center">
                        <span class="bg-gray-100 text-gray-600 rounded-full h-8 w-8 flex items-center justify-center font-semibold">'.$c.'</span>
                    </div>';
                }
                
                echo '</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="h-10 w-10 bg-gradient-to-r from-primary to-primary-dark text-white rounded-full flex items-center justify-center mr-4 text-sm font-bold shadow-md">
                            '.strtoupper(substr($name, 0, 1)).'
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-900">'.$name.'</div>
                            <div class="text-sm text-gray-500">'.$college.'</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <div class="text-lg font-bold text-primary">'.$s.' / '.$totalPossible.'</div>
                    <div class="text-xs text-gray-500">'.number_format($percentage, 1).'%</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <div class="w-full bg-gray-200 rounded-full h-3 mb-2">
                        <div class="bg-gradient-to-r from-primary to-primary-dark h-3 rounded-full transition-all duration-1000 shadow-sm" style="width: '.$percentage.'%"></div>
                    </div>
                    <span class="text-sm font-medium text-gray-700">'.number_format($percentage, 1).'%</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">';
                
                // Add grade badges based on score
                if ($percentage >= 97) {
                    echo '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800 border border-purple-200">
                        <i class="fas fa-star mr-1"></i>A+
                    </span>';
                } else if ($percentage >= 93) {
                    echo '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                        <i class="fas fa-thumbs-up mr-1"></i>A
                    </span>';
                } else if ($percentage >= 90) {
                    echo '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                        <i class="fas fa-check mr-1"></i>A-
                    </span>';
                } else if ($percentage >= 87) {
                    echo '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">B+</span>';
                } else if ($percentage >= 83) {
                    echo '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200">B</span>';
                } else if ($percentage >= 80) {
                    echo '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200">B-</span>';
                } else if ($percentage >= 77) {
                    echo '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800 border border-orange-200">C+</span>';
                } else if ($percentage >= 73) {
                    echo '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800 border border-orange-200">C</span>';
                } else if ($percentage >= 70) {
                    echo '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800 border border-orange-200">C-</span>';
                } else if ($percentage >= 65) {
                    echo '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200">D</span>';
                } else {
                    echo '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200">
                        <i class="fas fa-times mr-1"></i>F
                    </span>';
                }
                
                echo '</td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <div class="flex justify-center space-x-2">
                        <button onclick="viewDetails(\''.htmlspecialchars($name).'\', '.$s.', '.$percentage.')" class="bg-blue-100 hover:bg-blue-200 text-blue-600 p-2 rounded-lg transition-colors">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="sendCertificate(\''.htmlspecialchars($name).'\')" class="bg-green-100 hover:bg-green-200 text-green-600 p-2 rounded-lg transition-colors">
                            <i class="fas fa-certificate"></i>
                        </button>
                    </div>
                </td>
            </tr>';
        }
        echo '</tbody></table></div></div>';
        
        // Performance Analytics
        echo '<div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-xl shadow-lg">
                <h4 class="text-lg font-semibold mb-4 text-gray-800">Grade Distribution</h4>
                <div class="space-y-3">';
                
                // Calculate grade distribution
                $grade_counts = ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'F' => 0];
                foreach($scores_for_chart as $score) {
                    if($score >= 90) $grade_counts['A']++;
                    else if($score >= 80) $grade_counts['B']++;
                    else if($score >= 70) $grade_counts['C']++;
                    else if($score >= 60) $grade_counts['D']++;
                    else $grade_counts['F']++;
                }
                
                foreach($grade_counts as $grade => $count) {
                    $grade_percentage = $total_users > 0 ? ($count / $total_users) * 100 : 0;
                    $color = '';
                    switch($grade) {
                        case 'A': $color = 'bg-blue-500'; break;
                        case 'B': $color = 'bg-green-500'; break;
                        case 'C': $color = 'bg-yellow-500'; break;
                        case 'D': $color = 'bg-orange-500'; break;
                        case 'F': $color = 'bg-red-500'; break;
                    }
                    echo '<div class="flex items-center justify-between">
                        <span class="text-sm font-medium">Grade '.$grade.'</span>
                        <div class="flex items-center space-x-2">
                            <div class="w-16 bg-gray-200 rounded-full h-2">
                                <div class="'.$color.' h-2 rounded-full" style="width: '.$grade_percentage.'%"></div>
                            </div>
                            <span class="text-sm text-gray-600">'.$count.'</span>
                        </div>
                    </div>';
                }
                
        echo '</div>
            </div>
            
            <div class="bg-white p-6 rounded-xl shadow-lg">
                <h4 class="text-lg font-semibold mb-4 text-gray-800">Quick Stats</h4>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Pass Rate</span>
                        <span class="font-bold text-green-600">'.number_format((($grade_counts['A'] + $grade_counts['B'] + $grade_counts['C']) / max($total_users, 1)) * 100, 1).'%</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Excellence Rate</span>
                        <span class="font-bold text-blue-600">'.number_format(($grade_counts['A'] / max($total_users, 1)) * 100, 1).'%</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Median Score</span>
                        <span class="font-bold text-primary">'.(!empty($scores_array) ? $scores_array[floor(count($scores_array)/2)] : 0).'</span>
                    </div>
                </div>
            </div>
        </div>';
        
    } else if (!empty($selected_eid)) {
        echo '<div class="text-center p-12 bg-gray-50 rounded-xl">
            <div class="max-w-sm mx-auto">
                <i class="fas fa-chart-bar text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No Rankings Available</h3>
                <p class="text-gray-500 mb-6">No one has taken this exam yet.</p>
            </div>
        </div>';
    } else {
        echo '<div class="text-center p-12 bg-gray-50 rounded-xl">
            <div class="max-w-sm mx-auto">
                <i class="fas fa-clipboard-list text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Select an Exam</h3>
                <p class="text-gray-500 mb-6">Please select an exam from the dropdown above to view its rankings.</p>
            </div>
        </div>';
    }
    ?>
    
    <!-- Include Chart.js for performance chart -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        // Performance Distribution Chart
        <?php if(!empty($selected_eid) && $q && mysqli_num_rows($q) > 0): ?>
        const performanceCtx = document.getElementById('performanceChart').getContext('2d');
        const performanceChart = new Chart(performanceCtx, {
            type: 'bar',
            data: {
                labels: ['90-100%', '80-89%', '70-79%', '60-69%', 'Below 60%'],
                datasets: [{
                    label: 'Number of Students',
                    data: [
                        <?php echo $grade_counts['A']; ?>,
                        <?php echo $grade_counts['B']; ?>,
                        <?php echo $grade_counts['C']; ?>,
                        <?php echo $grade_counts['D']; ?>,
                        <?php echo $grade_counts['F']; ?>
                    ],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(249, 115, 22, 0.8)',
                        'rgba(239, 68, 68, 0.8)'
                    ],
                    borderColor: [
                        'rgb(59, 130, 246)',
                        'rgb(16, 185, 129)',
                        'rgb(245, 158, 11)',
                        'rgb(249, 115, 22)',
                        'rgb(239, 68, 68)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
        <?php endif; ?>
        
        // Search functionality
        function searchTable() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toUpperCase();
            const table = document.getElementById('rankingsTable');
            const tr = table.getElementsByTagName('tr');
            
            for (let i = 1; i < tr.length; i++) {
                const td = tr[i].getElementsByTagName('td')[1]; // Name column
                if (td) {
                    const txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = '';
                    } else {
                        tr[i].style.display = 'none';
                    }
                }
            }
        }
        
        // Export to CSV
        function exportToCSV() {
            const table = document.getElementById('rankingsTable');
            let csv = [];
            const rows = table.querySelectorAll('tr');
            
            for (let i = 0; i < rows.length; i++) {
                const row = [], cols = rows[i].querySelectorAll('td, th');
                for (let j = 0; j < cols.length - 1; j++) { // Exclude actions column
                    row.push(cols[j].innerText);
                }
                csv.push(row.join(','));
            }
            
            const csvFile = new Blob([csv.join('\\n')], { type: 'text/csv' });
            const downloadLink = document.createElement('a');
            downloadLink.download = 'exam_rankings.csv';
            downloadLink.href = window.URL.createObjectURL(csvFile);
            downloadLink.style.display = 'none';
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }
        
        // Print rankings
        function printRankings() {
            window.print();
        }
        
        // View student details
        function viewDetails(name, score, percentage) {
            alert(`Student Details:\\n\\nName: ${name}\\nScore: ${score}\\nPercentage: ${percentage.toFixed(1)}%`);
        }
        
        // Send certificate
        function sendCertificate(name) {
            if(confirm(`Send certificate to ${name}?`)) {
                alert(`Certificate sent to ${name} successfully!`);
            }
        }
    </script>
<?php } ?>

                    <?php if(@$_GET['q']==1) { ?>
                        <h2 class="panel-title">User Management</h2>
                        <p class="text-gray-600 mb-6">Manage all registered users in the system.</p>
                        
                        <?php
                        // Get all users - with filtering if applied
                        $selected_college = isset($_GET['college']) ? $_GET['college'] : '';
                        
                        // Build query based on filter
                        if (!empty($selected_college)) {
                            $result = mysqli_query($con, "SELECT * FROM user WHERE college='$selected_college' ORDER BY name ASC") or die('Error fetching filtered users');
                            $filter_title = "Filtered Users";
                        } else {
                            $result = mysqli_query($con, "SELECT * FROM user ORDER BY name ASC") or die('Error');
                            $filter_title = "All Users";
                        }
                        
                        echo '<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                            <div class="bg-gray-50 px-4 py-2 rounded-lg shadow-sm">
                                <span class="text-gray-600">' . $filter_title . ': <span class="font-bold text-primary">'.mysqli_num_rows($result).'</span></span>
                            </div>
                            
                            <div class="flex flex-col md:flex-row gap-3 items-start md:items-center">
                                <form method="get" action="dash.php" class="flex items-center space-x-2">
                                    <input type="hidden" name="q" value="1">
                                    <select name="college" class="form-input py-2 px-3" onchange="this.form.submit()">
                                        <option value="">All Year and Section</option>
                                        <option value="Entrance Exam"' . (($selected_college == "Entrance Exam") ? ' selected' : '') . '>Entrance Exam</option>
                                        <option value="Grade 7 - Section A"' . (($selected_college == "Grade 7 - Section A") ? ' selected' : '') . '>Grade 7 - Section A</option>
                                        <option value="Grade 7 - Section B"' . (($selected_college == "Grade 7 - Section B") ? ' selected' : '') . '>Grade 7 - Section B</option>
                                        <option value="Grade 8 - Section A"' . (($selected_college == "Grade 8 - Section A") ? ' selected' : '') . '>Grade 8 - Section A</option>
                                        <option value="Grade 8 - Section B"' . (($selected_college == "Grade 8 - Section B") ? ' selected' : '') . '>Grade 8 - Section B</option>
                                        <option value="Grade 9 - Section A"' . (($selected_college == "Grade 9 - Section A") ? ' selected' : '') . '>Grade 9 - Section A</option>
                                        <option value="Grade 9 - Section B"' . (($selected_college == "Grade 9 - Section B") ? ' selected' : '') . '>Grade 9 - Section B</option>
                                        <option value="Grade 10 - Section A"' . (($selected_college == "Grade 10 - Section A") ? ' selected' : '') . '>Grade 10 - Section A</option>
                                        <option value="Grade 10 - Section B"' . (($selected_college == "Grade 10 - Section B") ? ' selected' : '') . '>Grade 10 - Section B</option>
                                        <option value="Grade 11 - Section A"' . (($selected_college == "Grade 11 - Section A") ? ' selected' : '') . '>Grade 11 - Section A</option>
                                        <option value="Grade 11 - Section B"' . (($selected_college == "Grade 11 - Section B") ? ' selected' : '') . '>Grade 11 - Section B</option>
                                        <option value="Grade 12 - Section A"' . (($selected_college == "Grade 12 - Section A") ? ' selected' : '') . '>Grade 12 - Section A</option>
                                        <option value="Grade 12 - Section B"' . (($selected_college == "Grade 12 - Section B") ? ' selected' : '') . '>Grade 12 - Section B</option>
                                    </select>
                                </form>
                                
                                <form method="post" action="" class="flex items-center">
                                    <button type="submit" name="clear_table" class="btn-secondary inline-flex items-center justify-center space-x-2" onclick="return confirm(\'Are you sure you want to clear the table?\');">
                                        <i class="fas fa-trash-alt"></i>
                                        <span>Clear All Users</span>
                                    </button>
                                </form>
                            </div>
                        </div>';
                        
                        if(mysqli_num_rows($result) > 0) {
                            echo '<div class="overflow-x-auto">
                            <table class="min-w-full bg-white border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b-2 border-gray-200">
                                    <th class="px-4 py-3 text-left">S.N.</th>
                                    <th class="px-4 py-3 text-left">Name</th>
                                    <th class="px-4 py-3 text-left">Gender</th>
                                    <th class="px-4 py-3 text-left">Year and Section</th>
                                    <th class="px-4 py-3 text-left">Email</th>
                                    <th class="px-4 py-3 text-left">Mobile</th>
                                    <th class="px-4 py-3 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>';
                            $c=1;
                            while($row = mysqli_fetch_array($result)) {
                                $name = $row['name'];
                                $mob = $row['mob'];
                                $gender = $row['gender'];
                                $email = $row['email'];
                                $college = $row['college'];
                                echo '<tr class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="px-4 py-4">'.$c++.'</td>
                                    <td class="px-4 py-4 font-medium">'.$name.'</td>
                                    <td class="px-4 py-4">'.$gender.'</td>
                                    <td class="px-4 py-4">'.$college.'</td>
                                    <td class="px-4 py-4">'.$email.'</td>
                                    <td class="px-4 py-4">'.$mob.'</td>
                                    <td class="px-4 py-4 text-center">
                                        <a title="Delete User" href="update.php?demail='.$email.'" class="btn-secondary inline-flex items-center justify-center space-x-1 py-1 px-3" onclick="return confirm(\'Are you sure you want to delete this user?\');">
                                            <i class="fas fa-user-times"></i>
                                            <span>Delete</span>
                                        </a>
                                    </td>
                                </tr>';
                            }
                            echo '</tbody></table></div>';
                        } else {
                            if (!empty($selected_college)) {
                                echo '<div class="text-center p-8 bg-gray-50 rounded-lg">
                                    <i class="fas fa-users text-4xl text-gray-400 mb-3"></i>
                                    <p class="text-gray-500">No users found in "' . htmlspecialchars($selected_college) . '".</p>
                                    <a href="dash.php?q=1" class="mt-3 inline-flex items-center text-primary hover:underline">
                                        <i class="fas fa-arrow-left mr-1"></i>
                                        Show all users
                                    </a>
                                </div>';
                            } else {
                                echo '<div class="text-center p-8 bg-gray-50 rounded-lg">
                                    <i class="fas fa-users text-4xl text-gray-400 mb-3"></i>
                                    <p class="text-gray-500">No users registered yet.</p>
                                </div>';
                            }
                        }
                        
                        if(isset($_POST['clear_table'])) {
                            mysqli_query($con, "DELETE FROM user");
                            echo "<script>alert('All users deleted successfully!'); window.location.href='dash.php?q=1';</script>";
                        }
                        ?>
                    <?php } ?>

                    <?php if(@$_GET['q']==3 && !(@$_GET['fid'])) { ?>
                        <h2 class="panel-title">Feedback Management</h2>
                        <p class="text-gray-600 mb-6">View and manage user feedback submissions.</p>
                        
                        <?php
                        $result = mysqli_query($con,"SELECT * FROM `feedback` ORDER BY `feedback`.`date` DESC") or die('Error');
                        if(mysqli_num_rows($result) > 0) {
                            echo '<div class="overflow-x-auto">
                            <table class="min-w-full bg-white border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b-2 border-gray-200">
                                    <th class="px-4 py-3 text-left">S.N.</th>
                                    <th class="px-4 py-3 text-left">Subject</th>
                                    <th class="px-4 py-3 text-left">Email</th>
                                    <th class="px-4 py-3 text-left">Date</th>
                                    <th class="px-4 py-3 text-left">Time</th>
                                    <th class="px-4 py-3 text-left">By</th>
                                    <th class="px-4 py-3 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>';
                            $c=1;
                            while($row = mysqli_fetch_array($result)) {
                                $date = $row['date'];
                                $date= date("d-m-Y",strtotime($date));
                                $time = $row['time'];
                                $subject = $row['subject'];
                                $name = $row['name'];
                                $email = $row['email'];
                                $id = $row['id'];
                                echo '<tr class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="px-4 py-4">'.$c++.'</td>
                                    <td class="px-4 py-4 font-medium"><a title="Click to open feedback" href="dash.php?q=3&fid='.$id.'" class="hover:text-primary transition-colors">'.$subject.'</a></td>
                                    <td class="px-4 py-4">'.$email.'</td>
                                    <td class="px-4 py-4">'.$date.'</td>
                                    <td class="px-4 py-4">'.$time.'</td>
                                    <td class="px-4 py-4">'.$name.'</td>
                                    <td class="px-4 py-4 text-center">
                                        <div class="flex justify-center space-x-2">
                                            <a title="View Feedback" href="dash.php?q=3&fid='.$id.'" class="bg-primary text-white p-2 rounded-lg hover:bg-primary-dark transition-colors">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a title="Delete Feedback" href="update.php?fdid='.$id.'" class="bg-secondary text-white p-2 rounded-lg hover:bg-secondary-dark transition-colors" onclick="return confirm(\'Are you sure you want to delete this feedback?\');">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>';
                            }
                            echo '</tbody></table></div>';
                        } else {
                            echo '<div class="text-center p-8 bg-gray-50 rounded-lg">
                                <i class="fas fa-comments text-4xl text-gray-400 mb-3"></i>
                                <p class="text-gray-500">No feedback submissions yet.</p>
                            </div>';
                        }
                        ?>
                    <?php } ?>

                    <?php if(@$_GET['fid']) {
                        $id=@$_GET['fid'];
                        $result = mysqli_query($con,"SELECT * FROM feedback WHERE id='$id' ") or die('Error');
                        
                        echo '<div class="mb-6">
                            <a href="dash.php?q=3" class="btn-outline inline-flex items-center space-x-2">
                                <i class="fas fa-arrow-left"></i>
                                <span>Back to Feedback List</span>
                            </a>
                        </div>';
                        
                        while($row = mysqli_fetch_array($result)) {
                            $name = $row['name'];
                            $subject = $row['subject'];
                            $date = $row['date'];
                            $date= date("d-m-Y",strtotime($date));
                            $time = $row['time'];
                            $feedback = $row['feedback'];
                            
                            echo '<div class="bg-white rounded-xl shadow-lg p-6 mb-4 border-l-4 border-primary">
                                <h2 class="text-2xl font-bold mb-4 text-primary">'.$subject.'</h2>
                                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 text-sm">
                                        <div class="bg-white p-3 rounded-lg shadow-sm">
                                            <span class="block text-xs text-gray-500 mb-1">Date</span>
                                            <span class="font-medium text-gray-800">'.$date.'</span>
                                        </div>
                                        <div class="bg-white p-3 rounded-lg shadow-sm">
                                            <span class="block text-xs text-gray-500 mb-1">Time</span>
                                            <span class="font-medium text-gray-800">'.$time.'</span>
                                        </div>
                                        <div class="bg-white p-3 rounded-lg shadow-sm">
                                            <span class="block text-xs text-gray-500 mb-1">From</span>
                                            <span class="font-medium text-gray-800">'.$name.'</span>
                                        </div>
                                    </div>
                                    <div class="mt-6 p-5 bg-white rounded-lg border border-gray-200 shadow-sm">
                                        <p class="whitespace-pre-wrap text-gray-700">'.$feedback.'</p>
                                    </div>
                                </div>
                                <div class="flex justify-end">
                                    <a href="update.php?fdid='.$id.'" class="btn-secondary inline-flex items-center space-x-2" onclick="return confirm(\'Are you sure you want to delete this feedback?\');">
                                        <i class="fas fa-trash-alt"></i>
                                        <span>Delete Feedback</span>
                                    </a>
                                </div>
                            </div>';
                        }
                    } ?>

                    <?php if(@$_GET['q']==4 && !(@$_GET['step'])) { ?>
                        <h2 class="panel-title">Add New Exam</h2>
                        <p class="text-gray-600 mb-6">Create a new exam by filling out the form below.</p>
                        
                        <div class="bg-gray-50 p-6 rounded-xl shadow-md">
                            <form class="form-horizontal" name="form" action="update.php?q=addquiz" method="POST">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="mb-4">
                                        <label for="name" class="form-label">Exam Title</label>
                                        <input id="name" name="name" placeholder="Enter exam title" class="form-input" type="text" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="total" class="form-label">Total Questions</label>
                                        <input id="total" name="total" placeholder="Enter total number of questions" class="form-input" type="number" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="right" class="form-label">Marks for Correct Answer</label>
                                        <input id="right" name="right" placeholder="Enter marks for right answer" class="form-input" min="0" type="number" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="wrong" class="form-label">Negative Marks</label>
                                        <input id="wrong" name="wrong" placeholder="Enter negative marks (without sign)" class="form-input" min="0" type="number" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="time" class="form-label">Time Limit (minutes)</label>
                                        <input id="time" name="time" placeholder="Enter time limit in minutes" class="form-input" min="1" type="number" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="tag" class="form-label">Tag</label>
                                        <input id="tag" name="tag" placeholder="Enter #tag for searching" class="form-input" type="text">
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label">Who can take this exam?</label>
                                        <div class="space-y-2">
                                            <div class="flex items-center">
                                                <input type="radio" id="access_all" name="access_type" value="all" class="mr-2" checked onchange="toggleSectionSelect()">
                                                <label for="access_all">All Students</label>
                                            </div>
                                            <div class="flex items-center">
                                                <input type="radio" id="access_specific" name="access_type" value="specific" class="mr-2" onchange="toggleSectionSelect()">
                                                <label for="access_specific">Specific Year and Section</label>
                                            </div>
                                        </div>
                                        <div id="sectionSelectContainer" class="mt-3 hidden">
                                            <label for="allowed_sections" class="form-label">Select Allowed Sections (hold Ctrl to select multiple)</label>
                                            <select id="allowed_sections" name="allowed_sections[]" class="form-input" multiple size="6">
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
                                            <p class="text-gray-500 text-sm mt-1">Hold Ctrl (or Cmd on Mac) and click to select multiple sections</p>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label">Allow Exam Restart</label>
                                        <div class="flex items-center space-x-2">
                                            <div class="flex items-center">
                                                <input type="radio" id="restart_yes" name="allow_restart" value="1" class="mr-2">
                                                <label for="restart_yes">Yes</label>
                                            </div>
                                            <div class="flex items-center">
                                                <input type="radio" id="restart_no" name="allow_restart" value="0" class="mr-2" checked>
                                                <label for="restart_no">No</label>
                                            </div>
                                        </div>
                                        <p class="text-gray-500 text-sm mt-1">If enabled, users can take this exam multiple times</p>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="desc" class="form-label">Description</label>
                                    <textarea rows="4" name="desc" id="desc" class="form-input" placeholder="Write exam description here..." required></textarea>
                                </div>
                                <div class="mt-8 flex justify-end">
                                    <button type="submit" class="btn-primary inline-flex items-center justify-center space-x-2 text-lg">
                                        <i class="fas fa-save"></i>
                                        <span>Create Exam</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <script>
                        function toggleSectionSelect() {
                            const accessType = document.querySelector('input[name="access_type"]:checked').value;
                            const sectionContainer = document.getElementById('sectionSelectContainer');
                            const sectionSelect = document.getElementById('allowed_sections');
                            
                            if (accessType === 'specific') {
                                sectionContainer.classList.remove('hidden');
                                sectionSelect.required = true;
                            } else {
                                sectionContainer.classList.add('hidden');
                                sectionSelect.required = false;
                                // Clear selections
                                for (let option of sectionSelect.options) {
                                    option.selected = false;
                                }
                            }
                        }
                        </script>
                    <?php } ?>

                    <?php if(@$_GET['q']==4 && @$_GET['step']==1) { ?>
    <?php
    $eid = @$_GET['eid'];
    $n = @$_GET['n'];
    
    // Check if we're in edit mode (existing questions)
    $existing_questions = mysqli_query($con, "SELECT * FROM questions WHERE eid='$eid' ORDER BY qid ASC");
    $question_count = mysqli_num_rows($existing_questions);
    $is_editing = $question_count > 0;
    ?>
    
    <div class="mb-6">
        <a href="dash.php?q=0" class="btn-outline inline-flex items-center space-x-2">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Exam List</span>
        </a>
    </div>
    
    <h2 class="panel-title"><?php echo $is_editing ? 'Edit Questions' : 'Add Questions'; ?></h2>
    <p class="text-gray-600 mb-6"><?php echo $is_editing ? 'Edit existing questions or add new ones for your exam.' : 'Create questions for your exam.'; ?></p>
    
    <?php if($is_editing): ?>
    <!-- Show existing questions first -->
    <div class="mb-8 bg-white p-6 rounded-xl shadow-lg">
        <h3 class="text-xl font-semibold mb-4 text-primary flex items-center">
            <i class="fas fa-list mr-3"></i>
            Existing Questions (<?php echo $question_count; ?>)
        </h3>
        
        <div class="space-y-4">
            <?php
            $q_num = 1;
            mysqli_data_seek($existing_questions, 0); // Reset pointer
            while($question = mysqli_fetch_assoc($existing_questions)): 
                $qid = $question['qid'];
                
                // Check if choices are stored directly in the questions table
                if(isset($question['choice1']) && isset($question['choice2']) && isset($question['choice3']) && isset($question['choice4'])) {
                    // Choices are in the questions table
                    $choice1 = $question['choice1'];
                    $choice2 = $question['choice2'];
                    $choice3 = $question['choice3'];
                    $choice4 = $question['choice4'];
                    $correct_answer = isset($question['ans']) ? $question['ans'] : '';
                } else {
                    // Choices might be in separate options table
                    $options_query = mysqli_query($con, "SELECT * FROM options WHERE qid='$qid' ORDER BY optionid ASC");
                    $options = [];
                    while($option = mysqli_fetch_assoc($options_query)) {
                        $options[] = $option['option'];
                    }
                    
                    // Pad with empty strings if less than 4 options
                    while(count($options) < 4) {
                        $options[] = '';
                    }
                    
                    $choice1 = $options[0];
                    $choice2 = $options[1];
                    $choice3 = $options[2];
                    $choice4 = $options[3];
                    
                    // Get correct answer
                    $answer_query = mysqli_query($con, "SELECT * FROM answer WHERE qid='$qid'");
                    $correct_answer = '';
                    if($answer_result = mysqli_fetch_assoc($answer_query)) {
                        $correct_ansid = $answer_result['ansid'];
                        // Find which option is correct (a, b, c, d)
                        $option_check = mysqli_query($con, "SELECT optionid FROM options WHERE qid='$qid' ORDER BY optionid ASC");
                        $option_index = 0;
                        while($opt = mysqli_fetch_assoc($option_check)) {
                            if($opt['optionid'] == $correct_ansid) {
                                $correct_answer = chr(97 + $option_index); // Convert to a, b, c, d
                                break;
                            }
                            $option_index++;
                        }
                    }
                }
            ?>
            <div class="border border-gray-200 rounded-lg p-3 bg-gray-50"> <!-- Reduced padding from p-4 to p-3 -->
                <div class="flex justify-between items-start mb-2">
                    <h4 class="font-semibold text-gray-800 text-sm">Question <?php echo $q_num; ?></h4> <!-- Reduced font size -->
                    <div class="flex space-x-1"> <!-- Reduced space from space-x-2 to space-x-1 -->
                        <a href="edit_question.php?qid=<?php echo $qid; ?>&eid=<?php echo $eid; ?>" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs transition-colors" <!-- Reduced padding and font size -->
                            <i class="fas fa-edit mr-1"></i>Edit
                        </a>
                        <button onclick="deleteQuestion('<?php echo $qid; ?>')" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs transition-colors" <!-- Reduced padding and font size -->
                            <i class="fas fa-trash mr-1"></i>Delete
                        </button>
                    </div>
                </div>
                <p class="text-gray-700 mb-2 text-sm"><strong>Q:</strong> <?php echo htmlspecialchars($question['qns']); ?></p> <!-- Reduced margin and font size -->
                <div class="grid grid-cols-2 gap-1 text-xs"> <!-- Reduced gap and font size -->
                    <?php 
                    $choices = [$choice1, $choice2, $choice3, $choice4];
                    $option_labels = ['A', 'B', 'C', 'D'];
                    for($i = 0; $i < 4; $i++): 
                        $option_letter = chr(97 + $i); // a, b, c, d
                        $is_correct = ($correct_answer == $option_letter);
                        $bg_class = $is_correct ? 'bg-green-100 text-green-800 font-medium' : 'bg-gray-100 text-gray-700';
                    ?>
                    <div class="<?php echo $bg_class; ?> p-1 rounded text-xs"> <!-- Reduced padding -->
                        <strong><?php echo $option_labels[$i]; ?>:</strong> <?php echo htmlspecialchars($choices[$i]); ?>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
            <?php 
            $q_num++;
            endwhile; ?>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Tabs for question entry methods -->
    <div class="mb-6">
        <div class="flex border-b border-gray-200">
            <button id="individualTab" class="px-4 py-2 font-medium text-primary border-b-2 border-primary" onclick="switchTab('individual')">
                <?php echo $is_editing ? 'Add More Questions' : 'Individual Questions'; ?>
            </button>
            <button id="bulkTab" class="px-4 py-2 font-medium text-gray-500 hover:text-primary" onclick="switchTab('bulk')">Bulk Import</button>
        </div>
    </div>
    
    <!-- Individual Questions Form -->
    <div id="individualForm" class="bg-gray-50 p-6 rounded-xl shadow-md">
        <div class="mb-4">
            <label for="questionsToAdd" class="form-label">Number of questions to add:</label>
            <select id="questionsToAdd" class="form-input w-auto" onchange="generateQuestionForms()">
                <?php for($i = 1; $i <= 10; $i++): ?>
                <option value="<?php echo $i; ?>" <?php echo $i == 1 ? 'selected' : ''; ?>><?php echo $i; ?> question<?php echo $i > 1 ? 's' : ''; ?></option>
                <?php endfor; ?>
            </select>
        </div>
        
        <form class="form-horizontal" name="form" action="update.php?q=addqns&eid=<?php echo $eid; ?>&ch=4&n=" method="POST" onsubmit="setQuestionCount()">
            <div id="questionsContainer">
                <!-- Questions will be generated here by JavaScript -->
            </div>
            <div class="mt-8 flex justify-end">
                <button type="submit" class="btn-primary inline-flex items-center justify-center space-x-2 text-lg">
                    <i class="fas fa-save"></i>
                    <span>Save Questions</span>
                </button>
            </div>
        </form>
    </div>
    
    <!-- Bulk Import Form -->
    <div id="bulkForm" class="bg-gray-50 p-6 rounded-xl shadow-md hidden">
        <form class="form-horizontal" name="bulkform" action="update.php?q=addbulkqns&eid=<?php echo $eid; ?>&ch=4" method="POST">
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-2 text-primary">Bulk Questions Import</h3>
                <p class="text-gray-600 mb-4">Paste your questions in the following format (one question per line):</p>
                <div class="bg-gray-100 p-4 rounded-lg mb-4 text-sm font-mono">
                    <p>Question text | Option A | Option B | Option C | Option D | Correct Answer (a,b,c,d)</p>
                    <p>Example: What is 2+2? | 3 | 4 | 5 | 6 | b</p>
                </div>
                <textarea rows="15" name="bulk_questions" class="form-input font-mono text-sm" placeholder="Paste your questions here..."></textarea>
            </div>
            
            <div class="mt-8 flex justify-end">
                <button type="submit" class="btn-primary inline-flex items-center justify-center space-x-2 text-lg">
                    <i class="fas fa-file-import"></i>
                    <span>Import Questions</span>
                </button>
            </div>
        </form>
    </div>
      <!-- Edit Question Modal -->
    <div id="editQuestionModal" class="modal">
        <div class="modal-content">
            <div class="bg-primary text-white px-4 py-3 rounded-t-xl flex justify-between items-center"> <!-- Reduced padding -->
                <h3 class="text-lg font-bold flex items-center"> <!-- Reduced font size -->
                    <i class="fas fa-edit mr-2"></i>
                    Edit Question
                </h3>
                <button type="button" class="closeModal text-white text-xl hover:text-green-200 transition-colors leading-none">&times;</button> <!-- Reduced font size -->
            </div>
            <div class="p-4"> <!-- Reduced padding from p-6 to p-4 -->
                <form id="editQuestionForm" action="update.php?q=editquestion" method="POST">
                    <input type="hidden" id="edit_qid" name="qid">
                    <input type="hidden" name="eid" value="<?php echo $eid; ?>">
                    
                    <div class="mb-4"> <!-- Reduced margin -->
                        <label for="edit_question" class="form-label text-sm">Question Text</label> <!-- Reduced font size -->
                        <textarea id="edit_question" name="question" rows="2" class="form-input text-sm" required placeholder="Enter the question text here..."></textarea> <!-- Reduced rows and font size -->
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4"> <!-- Reduced gap and margin -->
                    <div>
                        <label for="edit_choice1" class="form-label text-sm">Option A</label>
                        <input id="edit_choice1" name="choice1" class="form-input text-sm" type="text" required placeholder="Enter option A">
                    </div>
                    <div>
                        <label for="edit_choice2" class="form-label text-sm">Option B</label>
                        <input id="edit_choice2" name="choice2" class="form-input text-sm" type="text" required placeholder="Enter option B">
                    </div>
                    <div>
                        <label for="edit_choice3" class="form-label text-sm">Option C</label>
                        <input id="edit_choice3" name="choice3" class="form-input text-sm" type="text" required placeholder="Enter option C">
                    </div>
                    <div>
                        <label for="edit_choice4" class="form-label text-sm">Option D</label>
                        <input id="edit_choice4" name="choice4" class="form-input text-sm" type="text" required placeholder="Enter option D">
                    </div>
                </div>
                
                <div class="mb-4"> <!-- Reduced margin -->
                    <label for="edit_answer" class="form-label text-sm">Correct Answer</label>
                    <select id="edit_answer" name="answer" class="form-input text-sm" required>
                        <option value="">Select correct answer</option>
                        <option value="a">Option A</option>
                        <option value="b">Option B</option>
                        <option value="c">Option C</option>
                        <option value="d">Option D</option>
                    </select>
                </div>
                
                <div class="flex justify-end space-x-2"> <!-- Reduced space -->
                    <button type="button" class="btn-outline closeModal text-sm px-3 py-1"> <!-- Reduced size -->
                        <i class="fas fa-times mr-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn-primary text-sm px-3 py-1"> <!-- Reduced size -->
                        <i class="fas fa-save mr-1"></i>Update
                    </button>
                </div>
            </form>
        </div>
    </div>
      <script>
        // Enhanced modal functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Close modal when clicking close button
            document.querySelectorAll('.closeModal').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    closeModal();
                });
            });
            
            // Close modal when clicking outside
            document.querySelectorAll('.modal').forEach(modal => {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        closeModal();
                    }
                });
            });
            
            // Close modal on Escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    closeModal();
                }
            });
        });        function closeModal() {
            console.log('Closing modal...'); // Debug log
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                modal.classList.remove('active');
                modal.style.display = 'none';
            });
            document.body.style.overflow = '';
            console.log('Modal closed'); // Debug log
        }
        
        // Set question count for form submission
        function setQuestionCount() {
            const numQuestions = document.getElementById('questionsToAdd').value;
            const form = document.querySelector('form[name="form"]');
            const action = form.action;
            form.action = action + numQuestions;
        }
        
        // Generate question forms based on selected number
        function generateQuestionForms() {
            const numQuestions = document.getElementById('questionsToAdd').value;
            const container = document.getElementById('questionsContainer');
            container.innerHTML = '';
            
            for(let i = 1; i <= numQuestions; i++) {
                const questionDiv = document.createElement('div');
                questionDiv.className = 'mb-8 p-5 border border-gray-200 rounded-xl bg-gray-50';
                questionDiv.innerHTML = `
                    <h3 class="text-xl font-semibold mb-4 text-primary flex items-center">
                        <span class="h-8 w-8 bg-primary text-white rounded-full flex items-center justify-center mr-3">${i}</span>
                        Question ${i}
                    </h3>
                    <div class="mb-5">
                        <label for="qns${i}" class="form-label">Question Text</label>
                        <textarea rows="3" name="qns${i}" id="qns${i}" class="form-input" placeholder="Write question ${i} here..." required></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-3">
                            <label for="${i}1" class="form-label">Option A</label>
                            <input id="${i}1" name="${i}1" placeholder="Enter option A" class="form-input" type="text" required>
                        </div>
                        <div class="mb-3">
                            <label for="${i}2" class="form-label">Option B</label>
                            <input id="${i}2" name="${i}2" placeholder="Enter option B" class="form-input" type="text" required>
                        </div>
                        <div class="mb-3">
                            <label for="${i}3" class="form-label">Option C</label>
                            <input id="${i}3" name="${i}3" placeholder="Enter option C" class="form-input" type="text" required>
                        </div>
                        <div class="mb-3">
                            <label for="${i}4" class="form-label">Option D</label>
                            <input id="${i}4" name="${i}4" placeholder="Enter option D" class="form-input" type="text" required>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label for="ans${i}" class="form-label">Correct Answer</label>
                        <select id="ans${i}" name="ans${i}" class="form-input" required>
                            <option value="">Select correct answer</option>
                            <option value="a">Option A</option>
                            <option value="b">Option B</option>
                            <option value="c">Option C</option>
                            <option value="d">Option D</option>
                        </select>
                    </div>
                `;
                container.appendChild(questionDiv);
            }
        }
        
        // Initialize with 1 question
        generateQuestionForms();
        
        function switchTab(tab) {
            // Hide all forms
            document.getElementById('individualForm').classList.add('hidden');
            document.getElementById('bulkForm').classList.add('hidden');
            
            // Remove active class from all tabs
            document.getElementById('individualTab').classList.remove('border-primary', 'text-primary');
            document.getElementById('individualTab').classList.add('text-gray-500');
            document.getElementById('bulkTab').classList.remove('border-primary', 'text-primary');
            document.getElementById('bulkTab').classList.add('text-gray-500');
            
            // Show selected form and activate tab
            if (tab === 'individual') {
                document.getElementById('individualForm').classList.remove('hidden');
                document.getElementById('individualTab').classList.add('border-b-2', 'border-primary', 'text-primary');
                document.getElementById('individualTab').classList.remove('text-gray-500');
            } else {
                document.getElementById('bulkForm').classList.remove('hidden');
                document.getElementById('bulkTab').classList.add('border-b-2', 'border-primary', 'text-primary');
                document.getElementById('bulkTab').classList.remove('text-gray-500');
            }
        }        // Enhanced edit question function with simpler approach
        function editQuestion(qid) {
            console.log('Editing question:', qid); // Debug log
            
            // Find and show modal
            const modal = document.getElementById('editQuestionModal');
            if (!modal) {
                console.error('Modal not found!');
                alert('Modal element not found. Please check the page structure.');
                return;
            }
            
            console.log('Modal element found, attempting to show...'); // Debug log
            
            // Remove any existing classes and force show
            modal.className = 'modal active';
            modal.style.cssText = `
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                right: 0 !important;
                bottom: 0 !important;
                z-index: 99999 !important;
                background-color: rgba(0, 0, 0, 0.8) !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                padding: 1rem !important;
            `;
            
            // Also ensure modal content is visible
            const modalContent = modal.querySelector('.modal-content');
            if (modalContent) {
                modalContent.style.cssText = `
                    background-color: white !important;
                    border-radius: 0.75rem !important;
                    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5) !important;
                    width: 100% !important;
                    max-width: 28rem !important; /* Reduced from 42rem to make it smaller */
                    max-height: 85vh !important; /* Reduced from 90vh */
                    overflow-y: auto !important;
                    transform: scale(1) !important;
                    position: relative !important;
                `;
            }
            
            // Prevent body scrolling
            document.body.style.overflow = 'hidden';
            document.body.classList.add('modal-open');
            
            // Scroll to top
            window.scrollTo(0, 0);
            
            console.log('Modal should be visible now!'); // Debug log
            
            // Try to load data if the modal is showing
            if (modal.style.display === 'flex') {
                loadQuestionData(qid, modal);
            } else {
                console.error('Modal failed to show');
                // Fallback: show a simple alert
                const questionData = prompt(`Editing question ${qid}. This is a fallback since the modal isn't working. Press OK to continue or Cancel to abort.`);
                if (questionData !== null) {
                    // Could redirect to a separate edit page as fallback
                    window.location.href = `edit_question.php?qid=${qid}`;
                }
            }
        }
        
        // Separate function to load question data
        function loadQuestionData(qid, modal) {
            const formContainer = modal.querySelector('.p-6');
            if (!formContainer) {
                console.error('Form container not found');
                return;
            }
            
            const originalContent = formContainer.innerHTML;
            
            // Show loading state
            formContainer.innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-spinner fa-spin text-4xl text-primary mb-4"></i>
                    <p class="text-gray-600">Loading question data...</p>
                </div>
            `;
            
            // Fetch question data via AJAX
            fetch(`get_question.php?qid=${qid}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Question data received:', data); // Debug log
                    
                    // Restore original content
                    formContainer.innerHTML = originalContent;
                    
                    if(data.success) {
                        // Fill form fields
                        const fields = {
                            'edit_qid': qid,
                            'edit_question': data.question.qns || '',
                            'edit_choice1': data.options[0] || '',
                            'edit_choice2': data.options[1] || '',
                            'edit_choice3': data.options[2] || '',
                            'edit_choice4': data.options[3] || '',
                            'edit_answer': data.correct_answer || ''
                        };
                        
                        Object.entries(fields).forEach(([id, value]) => {
                            const element = document.getElementById(id);
                            if (element) {
                                element.value = value;
                            } else {
                                console.warn(`Element with ID ${id} not found`);
                            }
                        });
                        
                        // Re-attach event listeners
                        modal.querySelectorAll('.closeModal').forEach(btn => {
                            btn.addEventListener('click', (e) => {
                                e.preventDefault();
                                e.stopPropagation();
                                closeModal();
                            });
                        });
                    } else {
                        formContainer.innerHTML = `
                            <div class="text-center py-8">
                                <i class="fas fa-exclamation-triangle text-4xl text-red-500 mb-4"></i>
                                <p class="text-red-600 mb-4">Error: ${data.message || 'Unknown error occurred'}</p>
                                <button onclick="closeModal()" class="btn-primary">Close</button>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    formContainer.innerHTML = `
                        <div class="text-center py-8">
                            <i class="fas fa-exclamation-triangle text-4xl text-red-500 mb-4"></i>
                            <p class="text-red-600 mb-4">Error loading question data. Please try again.</p>
                            <button onclick="closeModal()" class="btn-primary">Close</button>
                        </div>
                    `;
                });
        }
        
        // Delete question function
        function deleteQuestion(qid) {
            if(confirm('Are you sure you want to delete this question? This action cannot be undone.')) {
                window.location.href = `update.php?q=deletequestion&qid=${qid}&eid=<?php echo $eid; ?>`;
            }
        }
    </script>
<?php } ?>

                    <?php if(@$_GET['q']==5) { ?>
                        <h2 class="panel-title">System Warnings</h2>
                        <p class="text-gray-600 mb-6">Monitor system warnings and potential issues.</p>
                        
                        <?php
                        // Check for various system warnings
                        $warnings = array();
                        
                        // 1. Check for quizzes with no questions
                        $empty_quiz_result = mysqli_query($con, "SELECT q.eid, q.title 
                                           FROM quiz q 
                                           LEFT JOIN questions qn ON q.eid = qn.eid 
                                           WHERE qn.eid IS NULL") or die('Error');
                        while($row = mysqli_fetch_array($empty_quiz_result)) {
                            $warnings[] = array(
                                'type' => 'empty_quiz',
                                'severity' => 'high',
                                'message' => 'Quiz "'.$row['title'].'" has no questions',
                                'eid' => $row['eid']
                            );
                        }
                        
                        // 2. Check for users who haven't taken any quiz
                        $inactive_users_result = mysqli_query($con, "SELECT u.email, u.name 
                                               FROM user u 
                                               LEFT JOIN history h ON u.email = h.email 
                                               WHERE h.email IS NULL") or die('Error');
                        while($row = mysqli_fetch_array($inactive_users_result)) {
                            $warnings[] = array(
                                'type' => 'inactive_user',
                                'severity' => 'medium',
                                'message' => 'User "'.$row['name'].'" has not taken any quiz yet',
                                'email' => $row['email']
                            );
                        }
                        
                        // 3. Check for quizzes with low performance (high failure rate)
                        $quiz_performance_result = mysqli_query($con, "SELECT q.eid, q.title, q.total, q.sahi,
                                                COUNT(h.email) as attempts,
                                                AVG(h.score) as avg_score
                                                FROM quiz q
                                                JOIN history h ON q.eid = h.eid
                                                GROUP BY q.eid
                                                HAVING COUNT(h.email) > 3") or die('Error');
                        while($row = mysqli_fetch_array($quiz_performance_result)) {
                            $max_score = $row['total'] * $row['sahi'];
                            $avg_percent = ($row['avg_score'] / $max_score) * 100;
                            
                            if($avg_percent < 60) {
                                $warnings[] = array(
                                    'type' => 'low_performance',
                                    'severity' => 'medium',
                                    'message' => 'Quiz "'.$row['title'].'" has low average performance ('.round($avg_percent, 1).'%)',
                                    'eid' => $row['eid']
                                );
                            }
                        }
                        
                        // 4. Check for disabled users
                        $disabled_users_result = mysqli_query($con, "SELECT email, name, status FROM user WHERE status=0") or die('Error checking disabled users');
                        while($row = mysqli_fetch_array($disabled_users_result)) {
                            $warnings[] = array(
                                'type' => 'disabled_user',
                                'severity' => 'high',
                                'message' => 'User "'.$row['name'].'" has been disabled',
                                'email' => $row['email']
                            );
                        }
                        
                        // Display warnings
                        if(count($warnings) > 0) {
                            echo '<div class="overflow-x-auto">
                            <table class="min-w-full bg-white border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b-2 border-gray-200">
                                    <th class="px-4 py-3 text-left">Warning</th>
                                    <th class="px-4 py-3 text-left">Severity</th>
                                    <th class="px-4 py-3 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>';
                            
                            foreach($warnings as $warning) {
                                echo '<tr class="border-b border-gray-200 hover:bg-gray-50">';
                                
                                // Warning message
                                echo '<td class="px-4 py-4">';
                                
                                // Icon based on warning type
                                if($warning['type'] == 'empty_quiz') {
                                    echo '<div class="flex items-center"><i class="fas fa-exclamation-circle text-yellow-500 mr-2"></i> ';
                                } else if($warning['type'] == 'inactive_user') {
                                    echo '<div class="flex items-center"><i class="fas fa-user-clock text-blue-500 mr-2"></i> ';
                                } else if($warning['type'] == 'low_performance') {
                                    echo '<div class="flex items-center"><i class="fas fa-chart-line text-red-500 mr-2"></i> ';
                                } else if($warning['type'] == 'disabled_user') {
                                    echo '<div class="flex items-center"><i class="fas fa-user-slash text-red-500 mr-2"></i> ';
                                }
                                
                                echo $warning['message'].'</div></td>';
                                
                                // Severity
                                echo '<td class="px-4 py-4">';
                                if($warning['severity'] == 'high') {
                                    echo '<span class="px-2 py-1 rounded-full bg-red-100 text-red-800 text-xs font-semibold">High</span>';
                                } else if($warning['severity'] == 'medium') {
                                    echo '<span class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-800 text-xs font-semibold">Medium</span>';
                                } else {
                                    echo '<span class="px-2 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-semibold">Low</span>';
                                }
                                echo '</td>';
                                
                                // Actions
                                echo '<td class="px-4 py-4 text-center">';
                                if($warning['type'] == 'empty_quiz') {
                                    echo '<a href="dash.php?q=4&step=1&eid='.$warning['eid'].'" class="btn-primary inline-flex items-center justify-center space-x-1 py-1 px-3">
                                        <i class="fas fa-plus"></i>
                                        <span>Add Questions</span>
                                    </a>';
                                } else if($warning['type'] == 'inactive_user') {
                                    echo '<a href="mailto:'.$warning['email'].'" class="btn-primary inline-flex items-center justify-center space-x-1 py-1 px-3">
                                        <i class="fas fa-envelope"></i>
                                        <span>Contact User</span>
                                    </a>';
                                } else if($warning['type'] == 'low_performance') {
                                    echo '<a href="dash.php?q=0&review='.$warning['eid'].'" class="btn-primary inline-flex items-center justify-center space-x-1 py-1 px-3">
                                        <i class="fas fa-search"></i>
                                        <span>Review Quiz</span>
                                    </a>';
                                } else if($warning['type'] == 'disabled_user') {
                                    echo '<a href="update.php?action=enable_user&email='.$warning['email'].'" class="btn-primary inline-flex items-center justify-center space-x-1 py-1 px-3">
                                        <i class="fas fa-user-check"></i>
                                        <span>Enable User</span>
                                    </a>';
                                }
                                echo '</td></tr>';
                            }
                            
                            echo '</tbody></table></div>';
                        } else {
                            echo '<div class="text-center p-8 bg-gray-50 rounded-lg">
                                <i class="fas fa-check-circle text-5xl text-green-500 mb-3"></i>
                                <p class="text-gray-700 font-medium mb-2">No warnings found</p>
                                <p class="text-gray-500">The system is running smoothly with no detected issues.</p>
                            </div>';
                        }
                        ?>
                    <?php } ?>

                    <?php if(@$_GET['q']==7) { ?>
                        <h2 class="panel-title">Tab Violations Monitor</h2>
                        <p class="text-gray-600 mb-6">Monitor and manage tab switching violations during exams.</p>
                        
                        <?php
                        // Get violation statistics
                        $total_violations_query = mysqli_query($con, "SELECT COUNT(*) as count FROM tab_violations") or die('Error fetching violations');
                        $total_violations = mysqli_fetch_assoc($total_violations_query)['count'];
                        
                        $today_violations_query = mysqli_query($con, "SELECT COUNT(*) as count FROM tab_violations WHERE DATE(timestamp) = CURDATE()") or die('Error fetching today violations');
                        $today_violations = mysqli_fetch_assoc($today_violations_query)['count'];
                        
                        $terminated_exams_query = mysqli_query($con, "SELECT COUNT(*) as count FROM exam_terminations") or die('Error fetching terminated exams');
                        $terminated_exams = mysqli_fetch_assoc($terminated_exams_query)['count'];
                        
                        $unique_violators_query = mysqli_query($con, "SELECT COUNT(DISTINCT email) as count FROM tab_violations") or die('Error fetching unique violators');
                        $unique_violators = mysqli_fetch_assoc($unique_violators_query)['count'];
                        ?>
                        
                        <!-- Statistics Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                            <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-red-500">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-600">Total Violations</p>
                                        <p class="text-3xl font-bold text-red-600"><?php echo $total_violations; ?></p>
                                    </div>
                                    <div class="p-3 bg-red-100 rounded-full">
                                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-orange-500">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-600">Today's Violations</p>
                                        <p class="text-3xl font-bold text-orange-600"><?php echo $today_violations; ?></p>
                                    </div>
                                    <div class="p-3 bg-orange-100 rounded-full">
                                        <i class="fas fa-calendar-day text-orange-600 text-xl"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-purple-500">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-600">Terminated Exams</p>
                                        <p class="text-3xl font-bold text-purple-600"><?php echo $terminated_exams; ?></p>
                                    </div>
                                    <div class="p-3 bg-purple-100 rounded-full">
                                        <i class="fas fa-ban text-purple-600 text-xl"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-blue-500">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-600">Unique Violators</p>
                                        <p class="text-3xl font-bold text-blue-600"><?php echo $unique_violators; ?></p>
                                    </div>
                                    <div class="p-3 bg-blue-100 rounded-full">
                                        <i class="fas fa-users text-blue-600 text-xl"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php
                        // Get filtering options
                        $selected_violation_type = isset($_GET['violation_type']) ? $_GET['violation_type'] : '';
                        $selected_email = isset($_GET['email_filter']) ? $_GET['email_filter'] : '';
                        $selected_exam = isset($_GET['exam_filter']) ? $_GET['exam_filter'] : '';
                        
                        // Build query with filters
                        $where_conditions = [];
                        if (!empty($selected_violation_type)) {
                            $where_conditions[] = "tv.violation_type = '" . mysqli_real_escape_string($con, $selected_violation_type) . "'";
                        }
                        if (!empty($selected_email)) {
                            $where_conditions[] = "tv.email = '" . mysqli_real_escape_string($con, $selected_email) . "'";
                        }
                        if (!empty($selected_exam)) {
                            $where_conditions[] = "tv.exam_id = '" . mysqli_real_escape_string($con, $selected_exam) . "'";
                        }
                        
                        $where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';
                        
                        // Get violations with user and exam info
                        $violations_query = "SELECT tv.*, u.name as user_name, q.title as exam_title 
                                           FROM tab_violations tv 
                                           LEFT JOIN user u ON tv.email = u.email 
                                           LEFT JOIN quiz q ON tv.exam_id = q.eid 
                                           $where_clause 
                                           ORDER BY tv.timestamp DESC 
                                           LIMIT 50";
                        $violations_result = mysqli_query($con, $violations_query) or die('Error fetching violations');
                        ?>
                        
                        <!-- Filters -->
                        <div class="bg-white p-6 rounded-xl shadow-lg mb-6">
                            <h3 class="text-lg font-semibold mb-4">Filters</h3>
                            <form method="get" action="dash.php" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <input type="hidden" name="q" value="7">
                                
                                <div>
                                    <label class="form-label">Violation Type</label>
                                    <select name="violation_type" class="form-input">
                                        <option value="">All Types</option>
                                        <option value="Tab switched or window minimized" <?php echo $selected_violation_type == 'Tab switched or window minimized' ? 'selected' : ''; ?>>Tab Switching</option>
                                        <option value="Developer tools attempt (F12)" <?php echo $selected_violation_type == 'Developer tools attempt (F12)' ? 'selected' : ''; ?>>Developer Tools</option>
                                        <option value="Context menu attempted" <?php echo $selected_violation_type == 'Context menu attempted' ? 'selected' : ''; ?>>Context Menu</option>
                                        <option value="Window lost focus" <?php echo $selected_violation_type == 'Window lost focus' ? 'selected' : ''; ?>>Lost Focus</option>
                                        <option value="Exited fullscreen mode" <?php echo $selected_violation_type == 'Exited fullscreen mode' ? 'selected' : ''; ?>>Exited Fullscreen</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="form-label">User Email</label>
                                    <input type="text" name="email_filter" value="<?php echo htmlspecialchars($selected_email); ?>" 
                                           placeholder="Enter email..." class="form-input">
                                </div>
                                
                                <div>
                                    <label class="form-label">Exam ID</label>
                                    <input type="text" name="exam_filter" value="<?php echo htmlspecialchars($selected_exam); ?>" 
                                           placeholder="Enter exam ID..." class="form-input">
                                </div>
                                
                                <div class="flex items-end space-x-2">
                                    <button type="submit" class="btn-primary">Apply Filters</button>
                                    <a href="dash.php?q=7" class="btn-outline">Clear</a>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Violations Table -->
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white">
                                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Violation Type</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Exam</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Question #</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Count</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timestamp</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php
                                        if (mysqli_num_rows($violations_result) > 0) {
                                            while ($violation = mysqli_fetch_array($violations_result)) {
                                                $severity_class = '';
                                                $severity_text = '';
                                                
                                                switch($violation['violation_count']) {
                                                    case 1:
                                                        $severity_class = 'bg-yellow-100 text-yellow-800';
                                                        $severity_text = 'Warning';
                                                        break;
                                                    case 2:
                                                        $severity_class = 'bg-orange-100 text-orange-800';
                                                        $severity_text = 'Alert';
                                                        break;
                                                    case 3:
                                                    default:
                                                        $severity_class = 'bg-red-100 text-red-800';
                                                        $severity_text = 'Critical';
                                                        break;
                                                }
                                                
                                                echo '<tr class="hover:bg-gray-50 transition-colors duration-200">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="text-sm font-medium text-gray-900">' . htmlspecialchars($violation['user_name'] ?? 'Unknown') . '</div>
                                                            <div class="text-sm text-gray-500">' . htmlspecialchars($violation['email']) . '</div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">' . htmlspecialchars($violation['violation_type']) . '</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">' . htmlspecialchars($violation['exam_title'] ?? $violation['exam_id'] ?? 'N/A') . '</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">' . ($violation['question_number'] ?? 'N/A') . '</td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $severity_class . '">
                                                            ' . $violation['violation_count'] . ' - ' . $severity_text . '
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">' . date('M d, Y H:i', strtotime($violation['timestamp'])) . '</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        <button onclick="viewViolationDetails(' . $violation['id'] . ')" class="text-blue-600 hover:text-blue-900 mr-3">View</button>
                                                        <button onclick="dismissViolation(' . $violation['id'] . ')" class="text-red-600 hover:text-red-900">Dismiss</button>
                                                    </td>
                                                </tr>';
                                            }
                                        } else {
                                            echo '<tr><td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                                <i class="fas fa-shield-alt text-4xl mb-4 text-gray-400"></i>
                                                <p>No violations found.</p>
                                            </td></tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Violation Type Distribution Chart -->
                        <div class="mt-8 bg-white p-6 rounded-xl shadow-lg">
                            <h3 class="text-lg font-semibold mb-4">Violation Type Distribution</h3>
                            <div class="relative h-64">
                                <canvas id="violationChart"></canvas>
                            </div>
                        </div>
                        
                        <script>
                        // Chart for violation types
                        <?php
                        $chart_query = mysqli_query($con, "SELECT violation_type, COUNT(*) as count FROM tab_violations GROUP BY violation_type ORDER BY count DESC");
                        $chart_labels = [];
                        $chart_data = [];
                        while ($row = mysqli_fetch_array($chart_query)) {
                            $chart_labels[] = addslashes($row['violation_type']);
                            $chart_data[] = $row['count'];
                        }
                        ?>
                        
                        const ctx = document.getElementById('violationChart').getContext('2d');
                        const violationChart = new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                labels: [<?php echo "'" . implode("','", $chart_labels) . "'"; ?>],
                                datasets: [{
                                    data: [<?php echo implode(',', $chart_data); ?>],
                                    backgroundColor: [
                                        '#ef4444', '#f97316', '#eab308', '#22c55e', '#3b82f6', '#8b5cf6', '#ec4899'
                                    ],
                                    borderWidth: 2,
                                    borderColor: '#ffffff'
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: {
                                            padding: 20,
                                            usePointStyle: true
                                        }
                                    }
                                }
                            }
                        });
                        
                        function viewViolationDetails(violationId) {
                            // Implement violation details modal
                            Swal.fire({
                                title: 'Violation Details',
                                text: 'Violation ID: ' + violationId,
                                icon: 'info'
                            });
                        }
                        
                        function dismissViolation(violationId) {
                            Swal.fire({
                                title: 'Dismiss Violation?',
                                text: 'This will mark the violation as dismissed.',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Yes, dismiss it'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Implement dismissal logic
                                    Swal.fire('Dismissed!', 'The violation has been dismissed.', 'success');
                                }
                            });
                        }
                        </script>
                    <?php } ?>

                    <?php if(@$_GET['q']==6) { ?>
                        <h2 class="panel-title">Analytics Dashboard</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                            <!-- Summary Cards -->
                            <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-blue-500">
                                <div class="flex items-center">
                                    <i class="fas fa-users text-3xl text-blue-500 mr-4"></i>
                                    <div>
                                        <h3 class="text-lg font-semibold">Total Users</h3>
                                        <p class="text-2xl font-bold text-blue-800"><?php echo mysqli_num_rows(mysqli_query($con, "SELECT * FROM user")); ?></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-green-500">
                                <div class="flex items-center">
                                    <i class="fas fa-clipboard-list text-3xl text-green-500 mr-4"></i>
                                    <div>
                                        <h3 class="text-lg font-semibold">Total Exams</h3>
                                        <p class="text-2xl font-bold text-green-600"><?php echo mysqli_num_rows(mysqli_query($con, "SELECT * FROM quiz")); ?></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-yellow-500">
                                <div class="flex items-center">
                                    <i class="fas fa-chart-line text-3xl text-yellow-500 mr-4"></i>
                                    <div>
                                        <h3 class="text-lg font-semibold">Total Attempts</h3>
                                        <p class="text-2xl font-bold text-yellow-600"><?php echo mysqli_num_rows(mysqli_query($con, "SELECT * FROM history")); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Performance Charts -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                            <!-- Exam Performance by Grade -->
                            <div class="bg-white p-6 rounded-xl shadow-lg">
                                <h4 class="text-lg font-semibold mb-6 text-center">Exam Performance by Grade</h4>
                                <div class="relative h-80">
                                    <canvas id="gradePerformanceChart"></canvas>
                                </div>
                                
                                <!-- Legend -->
                                <div class="mt-4 flex flex-wrap justify-center gap-4 text-sm">
                                    <div class="flex items-center">
                                        <div class="w-4 h-4 bg-blue-500 rounded mr-2"></div>
                                        <span>Grade 7</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
                                        <span>Grade 8</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-4 h-4 bg-yellow-500 rounded mr-2"></div>
                                        <span>Grade 9</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-4 h-4 bg-red-500 rounded mr-2"></div>
                                        <span>Grade 10</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-4 h-4 bg-purple-500 rounded mr-2"></div>
                                        <span>Grade 11</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-4 h-4 bg-pink-500 rounded mr-2"></div>
                                        <span>Grade 12</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Monthly Exam Activity -->
                            <div class="bg-white p-6 rounded-xl shadow-lg">
                                <h4 class="text-lg font-semibold mb-6 text-center">Monthly Exam Activity</h4>
                                <div class="relative h-80">
                                    <canvas id="monthlyActivityChart"></canvas>
                                </div>
                                
                                <!-- Stats Summary -->
                                <div class="mt-4 grid grid-cols-2 gap-4 text-center text-sm">
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <div class="font-semibold text-primary">Peak Month</div>
                                        <div class="text-gray-600">March</div>
                                    </div>
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <div class="font-semibold text-primary">Total Exams</div>
                                        <div class="text-gray-600">156</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Analytics -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Top Performing Students -->
                            <div class="bg-white p-6 rounded-xl shadow-lg">
                                <h4 class="text-lg font-semibold mb-4">Top Performing Students</h4>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-yellow-500 text-white rounded-full flex items-center justify-center mr-3 font-bold">1</div>
                                            <span class="font-medium">Maria Santos</span>
                                        </div>
                                        <span class="text-primary font-bold">95.5%</span>
                                    </div>
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-gray-400 text-white rounded-full flex items-center justify-center mr-3 font-bold">2</div>
                                            <span class="font-medium">Juan Cruz</span>
                                        </div>
                                        <span class="text-primary font-bold">92.8%</span>
                                    </div>
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-yellow-700 text-white rounded-full flex items-center justify-center mr-3 font-bold">3</div>
                                            <span class="font-medium">Ana Garcia</span>
                                        </div>
                                        <span class="text-primary font-bold">89.2%</span>
                                    </div>
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-gray-300 text-gray-700 rounded-full flex items-center justify-center mr-3 font-bold">4</div>
                                            <span class="font-medium">Pedro Lopez</span>
                                        </div>
                                        <span class="text-primary font-bold">87.1%</span>
                                    </div>
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-gray-300 text-gray-700 rounded-full flex items-center justify-center mr-3 font-bold">5</div>
                                            <span class="font-medium">Lisa Reyes</span>
                                        </div>
                                        <span class="text-primary font-bold">85.6%</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Recent Activity -->
                            <div class="bg-white p-6 rounded-xl shadow-lg">
                                <h4 class="text-lg font-semibold mb-4">Recent Activity</h4>
                                <div class="space-y-3">
                                    <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                                        <i class="fas fa-user-check text-green-500 mt-1"></i>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium">New user registered</p>
                                            <p class="text-xs text-gray-500">Carlos Mendoza - 2 hours ago</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                                        <i class="fas fa-clipboard-check text-blue-500 mt-1"></i>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium">Exam completed</p>
                                            <p class="text-xs text-gray-500">Mathematics Quiz - 4 hours ago</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                                        <i class="fas fa-plus-circle text-primary mt-1"></i>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium">New exam created</p>
                                            <p class="text-xs text-gray-500">Science Quiz - 6 hours ago</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                                        <i class="fas fa-trophy text-yellow-500 mt-1"></i>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium">High score achieved</p>
                                            <p class="text-xs text-gray-500">Maria Santos - 98%
                                        <div class="flex-1">
                                            <p class="text-sm font-medium">New feedback received</p>
                                            <p class="text-xs text-gray-500">System suggestion - 12 hours ago</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Include Chart.js -->
                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                        <script>
                            // Exam Performance by Grade Chart
                            const gradeCtx = document.getElementById('gradePerformanceChart').getContext('2d');
                            const gradeChart = new Chart(gradeCtx, {
                                type: 'doughnut',
                                data: {
                                    labels: ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12'],
                                    datasets: [{
                                        data: [15, 22, 18, 25, 12, 8],
                                        backgroundColor: [
                                            '#3B82F6', // Blue
                                            '#10B981', // Green
                                            '#F59E0B', // Yellow
                                            '#EF4444', // Red
                                            '#8B5CF6', // Purple
                                            '#EC4899'  // Pink
                                        ],
                                        borderWidth: 2,
                                        borderColor: '#FFFFFF'
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {
                                            display: false
                                        },
                                        tooltip: {
                                            callbacks: {
                                                label: function(context) {
                                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                                    const percentage = ((context.parsed / total) * 100).toFixed(1);
                                                    return context.label + ': ' + context.parsed + ' students (' + percentage + '%)';
                                                }
                                            }
                                        }
                                    },
                                    cutout: '70%', // Donut hole size
                                }
                            });

                            // Monthly Exam Activity Chart
                            const monthlyCtx = document.getElementById('monthlyActivityChart').getContext('2d');
                            const monthlyChart = new Chart(monthlyCtx, {
                                type: 'line',
                                data: {
                                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                                    datasets: [{
                                        label: 'Exam Attempts',
                                        data: [8, 12, 25, 18, 15, 22, 16, 20, 14, 10, 8, 6],
                                        borderColor: '#28a745',
                                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                                        borderWidth: 3,
                                        fill: true,
                                        tension: 0.4,
                                        pointBackgroundColor: '#28a745',
                                        pointBorderColor: '#FFFFFF',
                                        pointBorderWidth: 2,
                                        pointRadius: 6
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {
                                            display: false
                                        }
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            grid: {
                                                color: 'rgba(0, 0, 0, 0.1)'
                                            },
                                            ticks: {
                                                stepSize: 5
                                            }
                                        },
                                        x: {
                                            grid: {
                                                color: 'rgba(0, 0, 0, 0.1)'
                                            }
                                        }
                                    },
                                    interaction: {
                                        intersect: false,
                                        mode: 'index'
                                    }
                                }
                            });
                        </script>
                    <?php } ?>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-primary text-white py-6 mt-auto">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-center">
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
                <div class="flex flex-col md:flex-row items-center md:space-x-4 space-y-4 md:space-y0">
                    <div class="flex-shrink-0">
                        <img src="image/CAM00121.jpg" class="h-24 w-24 object-cover rounded-full shadow-lg border-2 border-primary transition-transform duration-300 hover:scale-105" alt="Developer">
                    </div>
                    <div class="text-center md:text-left">
                        <h4 class="text-xl font-bold text-primary mb-1">Kian A. Rodrigez</h4>
                        <p class="flex items-center justify-center md:justify-start text-gray-600 mb-1">
                            <i class="fas fa-phone-alt mr-2 text-primary"></i>
                            +9366717240
                        </p>
                        <p class="flex items-center justify-center md:justify-start text-gray-600">
                            <i class="fas fa-envelope mr-2 text-primary"></i>
                            kianr664@gmail.com
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Handle modal functionality
    document.addEventListener('DOMContentLoaded', function() {
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
    });
    </script>
</body>
</html>
