<?php
include_once 'dbConnection.php';

if(isset($_GET['qid'])) {
    $qid = $_GET['qid'];
    
    // First, let's check what tables actually exist and get question details
    $question_result = mysqli_query($con, "SELECT * FROM questions WHERE qid='$qid'") or die('Error fetching question: ' . mysqli_error($con));
    
    if(mysqli_num_rows($question_result) > 0) {
        $question = mysqli_fetch_array($question_result);
        
        // Based on your existing code structure, it seems like your questions table might have choice columns
        // Let's try to get the data from the questions table itself first
        if(isset($question['choice1']) && isset($question['choice2']) && isset($question['choice3']) && isset($question['choice4'])) {
            // If choices are stored in the questions table
            $options = [
                $question['choice1'],
                $question['choice2'], 
                $question['choice3'],
                $question['choice4']
            ];
            $correct_answer = isset($question['ans']) ? $question['ans'] : '';
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'question' => $question,
                'options' => $options,
                'correct_answer' => $correct_answer
            ]);
        } else {
            // If choices are in separate tables, try the options table
            $options_result = mysqli_query($con, "SELECT * FROM options WHERE qid='$qid' ORDER BY optionid ASC");
            
            if($options_result && mysqli_num_rows($options_result) > 0) {
                $options = [];
                $option_ids = [];
                
                while($option = mysqli_fetch_array($options_result)) {
                    $options[] = $option['option'];
                    $option_ids[] = $option['optionid'];
                }
                
                // Get correct answer
                $answer_result = mysqli_query($con, "SELECT * FROM answer WHERE qid='$qid'");
                $correct_answer = '';
                
                if($answer_result && mysqli_num_rows($answer_result) > 0) {
                    $answer = mysqli_fetch_array($answer_result);
                    $correct_ansid = $answer['ansid'];
                    
                    // Find which option is correct
                    for($i = 0; $i < count($option_ids); $i++) {
                        if($option_ids[$i] == $correct_ansid) {
                            $correct_answer = chr(97 + $i); // Convert to a, b, c, d
                            break;
                        }
                    }
                }
                
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'question' => $question,
                    'options' => $options,
                    'correct_answer' => $correct_answer
                ]);
            } else {
                // No options found in separate table either
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'No options found for this question',
                    'debug' => 'Options query: ' . mysqli_error($con)
                ]);
            }
        }
    } else {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Question not found',
            'debug' => 'Question query: ' . mysqli_error($con)
        ]);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'No question ID provided'
    ]);
}
?>