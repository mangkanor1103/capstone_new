<?php
include_once 'dbConnection.php';
session_start();
$email=$_SESSION['email'];
//delete feedback
if(isset($_SESSION['key'])){
if(@$_GET['fdid'] && $_SESSION['key']=='sunny7785068889') {
$id=@$_GET['fdid'];
$result = mysqli_query($con,"DELETE FROM feedback WHERE id='$id' ") or die('Error');
header("location:dash.php?q=3");
}
}

//delete user
if(isset($_SESSION['key'])){
if(@$_GET['demail'] && $_SESSION['key']=='sunny7785068889') {
$demail=@$_GET['demail'];
$r1 = mysqli_query($con,"DELETE FROM rank WHERE email='$demail' ") or die('Error');
$r2 = mysqli_query($con,"DELETE FROM history WHERE email='$demail' ") or die('Error');
$result = mysqli_query($con,"DELETE FROM user WHERE email='$demail' ") or die('Error');
header("location:dash.php?q=1");
}
}
//remove quiz
if(isset($_SESSION['key'])){
if(@$_GET['q']== 'rmquiz' && $_SESSION['key']=='sunny7785068889') {
$eid=@$_GET['eid'];
$result = mysqli_query($con,"SELECT * FROM questions WHERE eid='$eid' ") or die('Error');
while($row = mysqli_fetch_array($result)) {
	$qid = $row['qid'];
$r1 = mysqli_query($con,"DELETE FROM options WHERE qid='$qid'") or die('Error');
$r2 = mysqli_query($con,"DELETE FROM answer WHERE qid='$qid' ") or die('Error');
}
$r3 = mysqli_query($con,"DELETE FROM questions WHERE eid='$eid' ") or die('Error');
$r4 = mysqli_query($con,"DELETE FROM quiz WHERE eid='$eid' ") or die('Error');
$r4 = mysqli_query($con,"DELETE FROM history WHERE eid='$eid' ") or die('Error');

header("location:dash.php?q=0");
}
}

//add quiz
if(isset($_SESSION['key'])){
if(@$_GET['q']== 'addquiz' && $_SESSION['key']=='sunny7785068889') {
$name = $_POST['name'];
$name= ucwords(strtolower($name));
$total = $_POST['total'];
$sahi = $_POST['right'];
$wrong = $_POST['wrong'];
$time = $_POST['time'];
$tag = $_POST['tag'];
$desc = $_POST['desc'];
$allow_restart = isset($_POST['allow_restart']) ? $_POST['allow_restart'] : 0;
$id=uniqid();
$allowed_sections = null;
if(isset($_POST['access_type']) && $_POST['access_type'] == 'specific' && isset($_POST['allowed_sections']) && !empty($_POST['allowed_sections'])) {
$allowed_sections = json_encode($_POST['allowed_sections']);
}

$q3=mysqli_query($con,"INSERT INTO quiz (eid, title, sahi, wrong, total, time, intro, tag, date, allow_restart, allowed_sections) 
                      VALUES ('$id','$name','$sahi','$wrong','$total','$time','$desc','$tag', NOW(), '$allow_restart', '$allowed_sections')");

header("location:dash.php?q=4&step=1&eid=$id&n=$total");
}
}

//toggle restart setting
if(isset($_SESSION['key'])){
    if(@$_GET['action'] == 'toggle_restart' && isset($_GET['eid']) && isset($_GET['val']) && $_SESSION['key']=='sunny7785068889') {
        $eid = $_GET['eid'];
        $val = $_GET['val'];
        $result = mysqli_query($con,"UPDATE quiz SET allow_restart='$val' WHERE eid='$eid'") or die('Error toggling restart setting');
        header("location:dash.php?q=0");
    }
}

//add question
if(isset($_SESSION['key'])){
if(@$_GET['q']== 'addqns' && $_SESSION['key']=='sunny7785068889') {
$n=@$_GET['n'];
$eid=@$_GET['eid'];
$ch=@$_GET['ch'];

for($i=1;$i<=$n;$i++)
 {
 $qid=uniqid();
 $qns=$_POST['qns'.$i];
$q3=mysqli_query($con,"INSERT INTO questions VALUES  ('$eid','$qid','$qns' , '$ch' , '$i')");
  $oaid=uniqid();
  $obid=uniqid();
$ocid=uniqid();
$odid=uniqid();
$a=$_POST[$i.'1'];
$b=$_POST[$i.'2'];
$c=$_POST[$i.'3'];
$d=$_POST[$i.'4'];
$qa=mysqli_query($con,"INSERT INTO options VALUES  ('$qid','$a','$oaid')") or die('Error61');
$qb=mysqli_query($con,"INSERT INTO options VALUES  ('$qid','$b','$obid')") or die('Error62');
$qc=mysqli_query($con,"INSERT INTO options VALUES  ('$qid','$c','$ocid')") or die('Error63');
$qd=mysqli_query($con,"INSERT INTO options VALUES  ('$qid','$d','$odid')") or die('Error64');
$e=$_POST['ans'.$i];
switch($e)
{
case 'a':
$ansid=$oaid;
break;
case 'b':
$ansid=$obid;
break;
case 'c':
$ansid=$ocid;
break;
case 'd':
$ansid=$odid;
break;
default:
$ansid=$oaid;
}


$qans=mysqli_query($con,"INSERT INTO answer VALUES  ('$qid','$ansid')");

 }
header("location:dash.php?q=0");
}
}

//add bulk questions
if(isset($_SESSION['key'])){
if(@$_GET['q']== 'addbulkqns' && $_SESSION['key']=='sunny7785068889') {
$eid=@$_GET['eid'];
$ch=@$_GET['ch'];
$total = isset($_POST['n']) ? (int)$_POST['n'] : 0;
$bulk_questions = isset($_POST['bulk_questions']) ? trim($_POST['bulk_questions']) : '';

// Parse bulk questions
$lines = explode("\n", $bulk_questions);
$lines = array_filter(array_map('trim', $lines)); // Remove empty lines
$success_count = 0;

if(count($lines) > 0) {
    foreach($lines as $i => $line) {
        // Skip if we've reached the total number of questions
        if($success_count >= $total) {
            break;
        }
        
        // Split the line by the pipe character
        $parts = array_map('trim', explode('|', $line));
        
        // Check if we have all parts (question, 4 options, and answer)
        if(count($parts) >= 6) {
            $qid = uniqid();
            $qns = $parts[0];
            $success = true;
            
            // Insert question
            $q3 = mysqli_query($con, "INSERT INTO questions VALUES ('$eid','$qid','$qns','$ch','" . ($success_count + 1) . "')");
            if(!$q3) {
                $success = false;
            }
            
            // Insert options
            $option_ids = [];
            for($j = 1; $j <= 4; $j++) {
                $option_text = isset($parts[$j]) ? $parts[$j] : '';
                $option_id = uniqid();
                $option_ids[$j] = $option_id;
                
                $q_option = mysqli_query($con, "INSERT INTO options VALUES ('$qid','$option_text','$option_id')");
                if(!$q_option) {
                    $success = false;
                }
            }
            
            // Insert answer based on the specified letter
            $ans_letter = strtolower(isset($parts[5]) ? trim($parts[5]) : 'a');
            switch($ans_letter) {
                case 'a': $ansid = $option_ids[1]; break;
                case 'b': $ansid = $option_ids[2]; break;
                case 'c': $ansid = $option_ids[3]; break;
                case 'd': $ansid = $option_ids[4]; break;
                default: $ansid = $option_ids[1]; // Default to first option
            }
            
            $q_ans = mysqli_query($con, "INSERT INTO answer VALUES ('$qid','$ansid')");
            if(!$q_ans) {
                $success = false;
            }
            
            if($success) {
                $success_count++;
            }
        }
    }
    
    if($success_count > 0) {
        echo "<script>alert('Successfully imported $success_count questions.'); window.location.href='dash.php?q=0';</script>";
    } else {
        echo "<script>alert('No questions were imported. Please check your format and try again.'); window.location.href='dash.php?q=4&step=1&eid=$eid&n=$total';</script>";
    }
} else {
    echo "<script>alert('No questions found to import.'); window.location.href='dash.php?q=4&step=1&eid=$eid&n=$total';</script>";
}

}
}

//quiz start
if(@$_GET['q']== 'quiz' && @$_GET['step']== 2) {
$eid=@$_GET['eid'];
$sn=@$_GET['n'];
$total=@$_GET['t'];
$ans=$_POST['ans'];
$qid=@$_GET['qid'];
$q=mysqli_query($con,"SELECT * FROM answer WHERE qid='$qid' " );
while($row=mysqli_fetch_array($q) )
{
$ansid=$row['ansid'];
}
if($ans == $ansid)
{
$q=mysqli_query($con,"SELECT * FROM quiz WHERE eid='$eid' " );
while($row=mysqli_fetch_array($q) )
{
$sahi=$row['sahi'];
}
if($sn == 1)
{
$q=mysqli_query($con,"INSERT INTO history VALUES('$email','$eid' ,'0','0','0','0',NOW())")or die('Error');
}
$q=mysqli_query($con,"SELECT * FROM history WHERE eid='$eid' AND email='$email' ")or die('Error115');

while($row=mysqli_fetch_array($q) )
{
$s=$row['score'];
$r=$row['sahi'];
}
$r++;
$s=$s+$sahi;
$q=mysqli_query($con,"UPDATE `history` SET `score`=$s,`level`=$sn,`sahi`=$r, date= NOW()  WHERE  email = '$email' AND eid = '$eid'")or die('Error124');

} 
else
{
$q=mysqli_query($con,"SELECT * FROM quiz WHERE eid='$eid' " )or die('Error129');

while($row=mysqli_fetch_array($q) )
{
$wrong=$row['wrong'];
}
if($sn == 1)
{
$q=mysqli_query($con,"INSERT INTO history VALUES('$email','$eid' ,'0','0','0','0',NOW() )")or die('Error137');
}
$q=mysqli_query($con,"SELECT * FROM history WHERE eid='$eid' AND email='$email' " )or die('Error139');
while($row=mysqli_fetch_array($q) )
{
$s=$row['score'];
$w=$row['wrong'];
}
$w++;
$s=$s-$wrong;
// Ensure score doesn't go below zero
if($s < 0) $s = 0;
$q=mysqli_query($con,"UPDATE `history` SET `score`=$s,`level`=$sn,`wrong`=$w, date=NOW() WHERE  email = '$email' AND eid = '$eid'")or die('Error147');
}
if($sn != $total)
{
$sn++;
header("location:account.php?q=quiz&step=2&eid=$eid&n=$sn&t=$total")or die('Error152');
}
else if( $_SESSION['key']!='sunny7785068889')
{
$q=mysqli_query($con,"SELECT score FROM history WHERE eid='$eid' AND email='$email'" )or die('Error156');
while($row=mysqli_fetch_array($q) )
{
$s=$row['score'];
}
$q=mysqli_query($con,"SELECT * FROM rank WHERE email='$email'" )or die('Error161');
$rowcount=mysqli_num_rows($q);
if($rowcount == 0)
{
$q2=mysqli_query($con,"INSERT INTO rank VALUES('$email','$s',NOW())")or die('Error165');
}
else
{
while($row=mysqli_fetch_array($q) )
{
$sun=$row['score'];
}
$sun=$s+$sun;
$q=mysqli_query($con,"UPDATE `rank` SET `score`=$sun ,time=NOW() WHERE email= '$email'")or die('Error174');
}
header("location:account.php?q=result&eid=$eid");
}
else
{
header("location:account.php?q=result&eid=$eid");
}
}

//restart quiz
if(@$_GET['q']== 'quizre' && @$_GET['step']== 25 ) {
$eid=@$_GET['eid'];
$n=@$_GET['n'];
$t=@$_GET['t'];
$q=mysqli_query($con,"SELECT score FROM history WHERE eid='$eid' AND email='$email'" )or die('Error156');
while($row=mysqli_fetch_array($q) )
{
$s=$row['score'];
}
$q=mysqli_query($con,"DELETE FROM `history` WHERE eid='$eid' AND email='$email' " )or die('Error184');
$q=mysqli_query($con,"SELECT * FROM rank WHERE email='$email'" )or die('Error161');
while($row=mysqli_fetch_array($q) )
{
$sun=$row['score'];
}
$sun=$sun-$s;
$q=mysqli_query($con,"UPDATE `rank` SET `score`=$sun ,time=NOW() WHERE email= '$email'")or die('Error174');
header("location:account.php?q=quiz&step=2&eid=$eid&n=1&t=$t");
}

// Check if the delete action is requested
if (isset($_GET['demail'])) {
    $emailToDelete = $_GET['demail'];

    // Prepare and execute the delete query
    $deleteQuery = "DELETE FROM user WHERE email='$emailToDelete'";
    if (mysqli_query($con, $deleteQuery)) {
        echo "<script>alert('User deleted successfully!'); window.location.href='dash.php?q=1';</script>";
    } else {
        echo "<script>alert('Error deleting user.'); window.location.href='dash.php?q=1';</script>";
    }
}

//enable user
if(isset($_SESSION['key'])){
    if(@$_GET['action'] == 'enable_user' && isset($_GET['email']) && $_SESSION['key']=='sunny7785068889') {
        $email = $_GET['email'];
        $result = mysqli_query($con,"UPDATE user SET status=1 WHERE email='$email'") or die('Error enabling user');
        header("location:dash.php?q=5");
    }
}

// Handle edit quiz
if(@$_GET['q']=='editquiz') {
    $eid = $_GET['eid'];
    $name = $_POST['name'];
    $total = $_POST['total'];
    $right = $_POST['right'];
    $wrong = $_POST['wrong'];
    $time = $_POST['time'];
    $tag = $_POST['tag'];
    $desc = $_POST['desc'];
    $allow_restart = $_POST['allow_restart'];
    
    $allowed_sections = null;
    if(isset($_POST['access_type']) && $_POST['access_type'] == 'specific' && isset($_POST['allowed_sections']) && !empty($_POST['allowed_sections'])) {
        $allowed_sections = json_encode($_POST['allowed_sections']);
    }
    
    $query = "UPDATE quiz SET 
              title='$name', 
              total='$total', 
              sahi='$right', 
              wrong='$wrong', 
              time='$time', 
              tag='$tag', 
              intro='$desc', 
              allow_restart='$allow_restart', 
              allowed_sections='$allowed_sections' 
              WHERE eid='$eid'";
    
    if(mysqli_query($con, $query)) {
        echo "<script>alert('Exam updated successfully!'); window.location.href='dash.php?q=0';</script>";
    } else {
        echo "<script>alert('Error updating exam!'); window.location.href='dash.php?q=0&edit=$eid';</script>";
    }
}

// Handle bulk add questions
if(@$_GET['q']=='addbulkqns') {
    $eid = $_GET['eid'];
    $ch = $_GET['ch'];
    
    $bulk_questions = $_POST['bulk_questions'];
    $lines = explode("\n", trim($bulk_questions));
    
    $questions_added = 0;
    $errors = array();
    
    foreach($lines as $line_num => $line) {
        $line = trim($line);
        if(empty($line)) continue;
        
        $parts = explode('|', $line);
        if(count($parts) !== 6) {
            $errors[] = "Line " . ($line_num + 1) . ": Invalid format (expected 6 parts separated by |)";
            continue;
        }
        
        $qns = trim($parts[0]);
        $choice1 = trim($parts[1]);
        $choice2 = trim($parts[2]);
        $choice3 = trim($parts[3]);
        $choice4 = trim($parts[4]);
        $ans = strtolower(trim($parts[5]));
        
        // Validate answer
        if(!in_array($ans, ['a', 'b', 'c', 'd'])) {
            $errors[] = "Line " . ($line_num + 1) . ": Invalid answer '$ans' (must be a, b, c, or d)";
            continue;
        }
        
        // Insert question
        $query = "INSERT INTO questions (eid, qns, choice1, choice2, choice3, choice4, ans) 
                  VALUES ('$eid', '$qns', '$choice1', '$choice2', '$choice3', '$choice4', '$ans')";
        
        if(mysqli_query($con, $query)) {
            $questions_added++;
        } else {
            $errors[] = "Line " . ($line_num + 1) . ": Database error - " . mysqli_error($con);
        }
    }
    
    $message = "$questions_added question(s) imported successfully!";
    if(!empty($errors)) {
        $message .= "\\n\\nErrors:\\n" . implode("\\n", $errors);
    }
    
    echo "<script>alert('$message'); window.location.href='dash.php?q=4&step=1&eid=$eid';</script>";
}

// Handle edit question
if(@$_GET['q']=='editquestion') {
    $qid = $_POST['qid'];
    $eid = $_POST['eid'];
    $question = $_POST['question'];
    $choices = [$_POST['choice1'], $_POST['choice2'], $_POST['choice3'], $_POST['choice4']];
    $answer = $_POST['answer'];
    
    // Update question
    $query = "UPDATE questions SET qns='$question' WHERE qid='$qid'";
    mysqli_query($con, $query);
    
    // Get existing options
    $options_result = mysqli_query($con, "SELECT * FROM options WHERE qid='$qid' ORDER BY optionid ASC");
    $option_ids = [];
    while($option = mysqli_fetch_array($options_result)) {
        $option_ids[] = $option['optionid'];
    }
    
    // Update options
    for($i = 0; $i < 4; $i++) {
        if(isset($option_ids[$i])) {
            mysqli_query($con, "UPDATE options SET option='{$choices[$i]}' WHERE optionid='{$option_ids[$i]}'");
        }
    }
    
    // Update answer
    $answer_index = ord($answer) - 97; // Convert a,b,c,d to 0,1,2,3
    if(isset($option_ids[$answer_index])) {
        mysqli_query($con, "UPDATE answer SET ansid='{$option_ids[$answer_index]}' WHERE qid='$qid'");
    }
    
    echo "<script>alert('Question updated successfully!'); window.location.href='dash.php?q=4&step=1&eid=$eid';</script>";
}

// Handle delete question
if(@$_GET['q']=='deletequestion') {
    $qid = $_GET['qid'];
    $eid = $_GET['eid'];
    
    // Delete from all related tables
    mysqli_query($con, "DELETE FROM answer WHERE qid='$qid'");
    mysqli_query($con, "DELETE FROM options WHERE qid='$qid'");
    mysqli_query($con, "DELETE FROM questions WHERE qid='$qid'");
    
    echo "<script>alert('Question deleted successfully!'); window.location.href='dash.php?q=4&step=1&eid=$eid';</script>";
}
?>



