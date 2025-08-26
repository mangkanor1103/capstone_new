<?php
// diagnostic.php - Check database and tab detection setup
require_once 'dbConnection.php';

echo "<h2>FaceTrackED - Tab Detection Diagnostic</h2>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .success { color: green; background: #f0f8f0; padding: 10px; border-left: 4px solid green; margin: 5px 0; }
    .error { color: red; background: #f8f0f0; padding: 10px; border-left: 4px solid red; margin: 5px 0; }
    .info { color: blue; background: #f0f0f8; padding: 10px; border-left: 4px solid blue; margin: 5px 0; }
    table { border-collapse: collapse; width: 100%; margin: 10px 0; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th { background-color: #f2f2f2; }
</style>";

// 1. Check if tab_violations table exists
echo "<h3>1. Database Tables Check</h3>";
$tables_check = mysqli_query($con, "SHOW TABLES LIKE 'tab_violations'");
if (mysqli_num_rows($tables_check) > 0) {
    echo "<div class='success'>✓ tab_violations table exists</div>";
    
    // Show table structure
    $structure = mysqli_query($con, "DESCRIBE tab_violations");
    echo "<h4>tab_violations table structure:</h4>";
    echo "<table><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    while ($row = mysqli_fetch_array($structure)) {
        echo "<tr><td>{$row['Field']}</td><td>{$row['Type']}</td><td>{$row['Null']}</td><td>{$row['Key']}</td><td>{$row['Default']}</td></tr>";
    }
    echo "</table>";
} else {
    echo "<div class='error'>✗ tab_violations table does not exist</div>";
}

// 2. Check history table for termination columns
echo "<h3>2. History Table Check</h3>";
$history_check = mysqli_query($con, "SHOW TABLES LIKE 'history'");
if (mysqli_num_rows($history_check) > 0) {
    echo "<div class='success'>✓ history table exists</div>";
    
    // Check for termination columns
    $terminated_check = mysqli_query($con, "SHOW COLUMNS FROM history LIKE 'terminated'");
    $reason_check = mysqli_query($con, "SHOW COLUMNS FROM history LIKE 'termination_reason'");
    
    if (mysqli_num_rows($terminated_check) > 0) {
        echo "<div class='success'>✓ 'terminated' column exists in history table</div>";
    } else {
        echo "<div class='error'>✗ 'terminated' column missing from history table</div>";
    }
    
    if (mysqli_num_rows($reason_check) > 0) {
        echo "<div class='success'>✓ 'termination_reason' column exists in history table</div>";
    } else {
        echo "<div class='error'>✗ 'termination_reason' column missing from history table</div>";
    }
    
    // Show relevant columns
    $history_structure = mysqli_query($con, "DESCRIBE history");
    echo "<h4>history table structure:</h4>";
    echo "<table><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    while ($row = mysqli_fetch_array($history_structure)) {
        echo "<tr><td>{$row['Field']}</td><td>{$row['Type']}</td><td>{$row['Null']}</td><td>{$row['Key']}</td><td>{$row['Default']}</td></tr>";
    }
    echo "</table>";
} else {
    echo "<div class='error'>✗ history table does not exist</div>";
}

// 3. Check for exam_terminations table
echo "<h3>3. Exam Terminations Table Check</h3>";
$terminations_check = mysqli_query($con, "SHOW TABLES LIKE 'exam_terminations'");
if (mysqli_num_rows($terminations_check) > 0) {
    echo "<div class='success'>✓ exam_terminations table exists</div>";
} else {
    echo "<div class='error'>✗ exam_terminations table does not exist</div>";
}

// 4. Check for any existing violations
echo "<h3>4. Existing Violations Check</h3>";
if (mysqli_num_rows($tables_check) > 0) {
    $violations_count = mysqli_query($con, "SELECT COUNT(*) as count FROM tab_violations");
    if ($violations_count) {
        $count_data = mysqli_fetch_assoc($violations_count);
        echo "<div class='info'>Found {$count_data['count']} existing violations in database</div>";
        
        if ($count_data['count'] > 0) {
            // Show recent violations
            $recent_violations = mysqli_query($con, "SELECT * FROM tab_violations ORDER BY timestamp DESC LIMIT 5");
            echo "<h4>Recent violations:</h4>";
            echo "<table><tr><th>Email</th><th>Violation Type</th><th>Count</th><th>Timestamp</th></tr>";
            while ($violation = mysqli_fetch_array($recent_violations)) {
                echo "<tr><td>{$violation['email']}</td><td>{$violation['violation_type']}</td><td>{$violation['violation_count']}</td><td>{$violation['timestamp']}</td></tr>";
            }
            echo "</table>";
        }
    }
}

// 5. Test save_tab_violation.php accessibility
echo "<h3>5. Files Check</h3>";
if (file_exists('save_tab_violation.php')) {
    echo "<div class='success'>✓ save_tab_violation.php exists</div>";
} else {
    echo "<div class='error'>✗ save_tab_violation.php not found</div>";
}

if (file_exists('account.php')) {
    echo "<div class='success'>✓ account.php exists</div>";
} else {
    echo "<div class='error'>✗ account.php not found</div>";
}

// 6. Session check
echo "<h3>6. Session Information</h3>";
session_start();
echo "<div class='info'>Session ID: " . session_id() . "</div>";
if (isset($_SESSION['email'])) {
    echo "<div class='info'>Logged in user: " . $_SESSION['email'] . "</div>";
    echo "<div class='info'>Exam active: " . ($_SESSION['exam_active'] ? 'Yes' : 'No') . "</div>";
} else {
    echo "<div class='info'>No user logged in</div>";
}

// 7. Recommendations
echo "<h3>7. Recommendations</h3>";
echo "<div class='info'>
<ol>
<li>If tables are missing, run: <a href='install_tab_monitoring.php'>install_tab_monitoring.php</a></li>
<li>Test the detection system: <a href='test_tab_detection.html'>test_tab_detection.html</a></li>
<li>Login and start an exam to test in real environment</li>
<li>Check browser console for JavaScript errors</li>
<li>Make sure you're using Chrome/Firefox/Edge (modern browser)</li>
</ol>
</div>";

mysqli_close($con);
?>
