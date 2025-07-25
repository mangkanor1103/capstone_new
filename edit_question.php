<?php
// Start session at the very beginning of the file
include_once 'dbConnection.php';
session_start();

// Check if user is logged in
if (!(isset($_SESSION['email']))) {
    header("location:index.php");
    exit();
}

$qid = isset($_GET['qid']) ? $_GET['qid'] : '';
$eid = isset($_GET['eid']) ? $_GET['eid'] : '';

if (empty($qid) || empty($eid)) {
    header("location:dash.php?q=0");
    exit();
}

// Fetch question data
$question_query = mysqli_query($con, "SELECT * FROM questions WHERE qid='$qid' AND eid='$eid'") or die('Error fetching question');
if (mysqli_num_rows($question_query) == 0) {
    header("location:dash.php?q=0");
    exit();
}

$question_data = mysqli_fetch_assoc($question_query);

// Check if choices are stored directly in the questions table
if(isset($question_data['choice1']) && isset($question_data['choice2']) && isset($question_data['choice3']) && isset($question_data['choice4'])) {
    // Choices are in the questions table
    $choice1 = $question_data['choice1'];
    $choice2 = $question_data['choice2'];
    $choice3 = $question_data['choice3'];
    $choice4 = $question_data['choice4'];
    $correct_answer = isset($question_data['ans']) ? $question_data['ans'] : '';
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

// Get exam title for breadcrumb
$exam_query = mysqli_query($con, "SELECT title FROM quiz WHERE eid='$eid'");
$exam_data = mysqli_fetch_assoc($exam_query);
$exam_title = $exam_data['title'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Question - RS Online Exam System</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#28a745',
                        'primary-dark': '#218838',
                        'primary-light': '#9be3b0',
                        secondary: '#dc3545',
                        'secondary-dark': '#bd2130',
                    },
                }
            }
        }
    </script>
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
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
            text-align: left;
        }
        
        .btn-primary {
            @apply bg-primary hover:bg-primary-dark text-white font-medium py-2 px-6 rounded-lg transform transition-all duration-300 hover:scale-105 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-primary focus:ring-opacity-50;
        }
        
        .btn-outline {
            @apply border border-gray-400 text-gray-700 font-medium py-2 px-6 rounded-lg transform transition-all duration-300 hover:scale-105 hover:shadow-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50;
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-green-50 to-blue-50">
    <!-- Header -->
    <header class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <img src="image/rslogo.jpg" alt="RS Logo" class="h-12 w-12 rounded-full shadow-md">
                <h1 class="text-xl md:text-2xl font-bold text-primary">RS Online Exam - Edit Question</h1>
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-gray-700 font-medium">Hello, <span class="text-primary font-semibold"><?php echo htmlspecialchars($_SESSION['name']); ?></span></span>
                <a href="logout.php?q=account.php" class="bg-secondary hover:bg-secondary-dark text-white font-medium py-2 px-4 rounded-lg transition-colors flex items-center space-x-2 text-sm">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        <div class="flex items-center text-sm mb-6 bg-white rounded-lg px-4 py-2 shadow-md">
            <a href="dash.php?q=0" class="text-primary hover:text-primary-dark transition-colors">Dashboard</a>
            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
            <a href="dash.php?q=4&step=1&eid=<?php echo $eid; ?>" class="text-primary hover:text-primary-dark transition-colors">Questions</a>
            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
            <span class="text-gray-600">Edit Question</span>
        </div>

        <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-primary text-white px-6 py-4">
                <h2 class="text-2xl font-bold flex items-center">
                    <i class="fas fa-edit mr-3"></i>
                    Edit Question
                </h2>
                <p class="text-green-100 mt-1">Exam: <?php echo htmlspecialchars($exam_title); ?></p>
            </div>
            
            <div class="p-6">
                <form action="update.php?q=editquestion" method="POST" class="space-y-6">
                    <input type="hidden" name="qid" value="<?php echo $qid; ?>">
                    <input type="hidden" name="eid" value="<?php echo $eid; ?>">
                    
                    <!-- Question Text -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <label for="question" class="form-label text-base font-semibold">Question Text</label>
                        <textarea id="question" name="question" rows="4" class="form-input" required placeholder="Enter the question text here..."><?php echo htmlspecialchars($question_data['qns']); ?></textarea>
                    </div>
                    
                    <!-- Options -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">Answer Options</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="choice1" class="form-label">Option A</label>
                                <input id="choice1" name="choice1" class="form-input" type="text" required placeholder="Enter option A" value="<?php echo htmlspecialchars($choice1); ?>">
                            </div>
                            <div>
                                <label for="choice2" class="form-label">Option B</label>
                                <input id="choice2" name="choice2" class="form-input" type="text" required placeholder="Enter option B" value="<?php echo htmlspecialchars($choice2); ?>">
                            </div>
                            <div>
                                <label for="choice3" class="form-label">Option C</label>
                                <input id="choice3" name="choice3" class="form-input" type="text" required placeholder="Enter option C" value="<?php echo htmlspecialchars($choice3); ?>">
                            </div>
                            <div>
                                <label for="choice4" class="form-label">Option D</label>
                                <input id="choice4" name="choice4" class="form-input" type="text" required placeholder="Enter option D" value="<?php echo htmlspecialchars($choice4); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Correct Answer -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <label for="answer" class="form-label text-base font-semibold">Correct Answer</label>
                        <select id="answer" name="answer" class="form-input" required>
                            <option value="">Select correct answer</option>
                            <option value="a" <?php echo ($correct_answer == 'a') ? 'selected' : ''; ?>>Option A</option>
                            <option value="b" <?php echo ($correct_answer == 'b') ? 'selected' : ''; ?>>Option B</option>
                            <option value="c" <?php echo ($correct_answer == 'c') ? 'selected' : ''; ?>>Option C</option>
                            <option value="d" <?php echo ($correct_answer == 'd') ? 'selected' : ''; ?>>Option D</option>
                        </select>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                        <a href="dash.php?q=4&step=1&eid=<?php echo $eid; ?>" class="btn-outline inline-flex items-center space-x-2">
                            <i class="fas fa-arrow-left"></i>
                            <span>Back to Questions</span>
                        </a>
                        
                        <div class="flex space-x-3">
                            <button type="button" onclick="resetForm()" class="btn-outline">
                                <i class="fas fa-undo mr-2"></i>Reset
                            </button>
                            <button type="submit" class="btn-primary inline-flex items-center space-x-2">
                                <i class="fas fa-save"></i>
                                <span>Update Question</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Preview Section -->
        <div class="max-w-4xl mx-auto mt-6 bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-blue-500 text-white px-6 py-4">
                <h3 class="text-xl font-bold flex items-center">
                    <i class="fas fa-eye mr-3"></i>
                    Question Preview
                </h3>
            </div>
            
            <div class="p-6">
                <div id="questionPreview" class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-800 mb-3">Question:</h4>
                    <p id="previewQuestion" class="text-gray-700 mb-4"><?php echo htmlspecialchars($question_data['qns']); ?></p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <div id="previewA" class="bg-white p-2 rounded border <?php echo ($correct_answer == 'a') ? 'border-green-500 bg-green-50' : 'border-gray-200'; ?>">
                            <strong>A:</strong> <span id="previewChoice1"><?php echo htmlspecialchars($choice1); ?></span>
                        </div>
                        <div id="previewB" class="bg-white p-2 rounded border <?php echo ($correct_answer == 'b') ? 'border-green-500 bg-green-50' : 'border-gray-200'; ?>">
                            <strong>B:</strong> <span id="previewChoice2"><?php echo htmlspecialchars($choice2); ?></span>
                        </div>
                        <div id="previewC" class="bg-white p-2 rounded border <?php echo ($correct_answer == 'c') ? 'border-green-500 bg-green-50' : 'border-gray-200'; ?>">
                            <strong>C:</strong> <span id="previewChoice3"><?php echo htmlspecialchars($choice3); ?></span>
                        </div>
                        <div id="previewD" class="bg-white p-2 rounded border <?php echo ($correct_answer == 'd') ? 'border-green-500 bg-green-50' : 'border-gray-200'; ?>">
                            <strong>D:</strong> <span id="previewChoice4"><?php echo htmlspecialchars($choice4); ?></span>
                        </div>
                    </div>
                    
                    <div class="mt-3 text-sm text-gray-600">
                        <strong>Correct Answer:</strong> 
                        <span id="previewCorrect" class="text-green-600 font-medium">
                            <?php 
                                if($correct_answer) {
                                    echo 'Option ' . strtoupper($correct_answer);
                                } else {
                                    echo 'Not selected';
                                }
                            ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Real-time preview updates
        document.addEventListener('DOMContentLoaded', function() {
            const questionInput = document.getElementById('question');
            const choice1Input = document.getElementById('choice1');
            const choice2Input = document.getElementById('choice2');
            const choice3Input = document.getElementById('choice3');
            const choice4Input = document.getElementById('choice4');
            const answerSelect = document.getElementById('answer');
            
            function updatePreview() {
                // Update question text
                document.getElementById('previewQuestion').textContent = questionInput.value || 'Question text will appear here...';
                
                // Update choices
                document.getElementById('previewChoice1').textContent = choice1Input.value || 'Option A text...';
                document.getElementById('previewChoice2').textContent = choice2Input.value || 'Option B text...';
                document.getElementById('previewChoice3').textContent = choice3Input.value || 'Option C text...';
                document.getElementById('previewChoice4').textContent = choice4Input.value || 'Option D text...';
                
                // Update correct answer highlighting
                const correctAnswer = answerSelect.value;
                const options = ['A', 'B', 'C', 'D'];
                const previewOptions = ['previewA', 'previewB', 'previewC', 'previewD'];
                
                previewOptions.forEach((optionId, index) => {
                    const element = document.getElementById(optionId);
                    const letter = String.fromCharCode(97 + index); // a, b, c, d
                    
                    if (letter === correctAnswer) {
                        element.className = 'bg-green-50 p-2 rounded border border-green-500';
                    } else {
                        element.className = 'bg-white p-2 rounded border border-gray-200';
                    }
                });
                
                // Update correct answer text
                const correctText = correctAnswer ? `Option ${correctAnswer.toUpperCase()}` : 'Not selected';
                document.getElementById('previewCorrect').textContent = correctText;
            }
            
            // Add event listeners
            questionInput.addEventListener('input', updatePreview);
            choice1Input.addEventListener('input', updatePreview);
            choice2Input.addEventListener('input', updatePreview);
            choice3Input.addEventListener('input', updatePreview);
            choice4Input.addEventListener('input', updatePreview);
            answerSelect.addEventListener('change', updatePreview);
        });
        
        // Reset form function
        function resetForm() {
            if (confirm('Are you sure you want to reset all changes? This will restore the original question data.')) {
                location.reload();
            }
        }
        
        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const question = document.getElementById('question').value.trim();
            const choice1 = document.getElementById('choice1').value.trim();
            const choice2 = document.getElementById('choice2').value.trim();
            const choice3 = document.getElementById('choice3').value.trim();
            const choice4 = document.getElementById('choice4').value.trim();
            const answer = document.getElementById('answer').value;
            
            if (!question) {
                alert('Please enter the question text.');
                e.preventDefault();
                return;
            }
            
            if (!choice1 || !choice2 || !choice3 || !choice4) {
                alert('Please fill in all answer options.');
                e.preventDefault();
                return;
            }
            
            if (!answer) {
                alert('Please select the correct answer.');
                e.preventDefault();
                return;
            }
            
            // Check for duplicate options
            const choices = [choice1, choice2, choice3, choice4];
            const uniqueChoices = [...new Set(choices)];
            if (uniqueChoices.length !== choices.length) {
                alert('Please ensure all answer options are different.');
                e.preventDefault();
                return;
            }
        });
    </script>
</body>
</html>