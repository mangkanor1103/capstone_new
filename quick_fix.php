<?php
// quick_fix.php - Quick fix for tab monitoring setup
require_once 'dbConnection.php';

echo "<h2>Quick Fix for Tab Monitoring</h2>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .success { color: green; background: #f0f8f0; padding: 10px; border-left: 4px solid green; margin: 5px 0; }
    .error { color: red; background: #f8f0f0; padding: 10px; border-left: 4px solid red; margin: 5px 0; }
</style>";

$fixes_applied = [];
$errors = [];

// 1. Create tab_violations table if it doesn't exist
$check_tab_violations = mysqli_query($con, "SHOW TABLES LIKE 'tab_violations'");
if (mysqli_num_rows($check_tab_violations) == 0) {
    $sql = "CREATE TABLE `tab_violations` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `email` varchar(255) NOT NULL,
        `name` varchar(255) NOT NULL,
        `violation_type` varchar(255) NOT NULL,
        `violation_count` int(11) DEFAULT 1,
        `timestamp` datetime NOT NULL,
        `exam_id` varchar(50) DEFAULT NULL,
        `question_number` int(11) DEFAULT NULL,
        `ip_address` varchar(45) DEFAULT NULL,
        `user_agent` text DEFAULT NULL,
        `status` enum('active','resolved','dismissed') DEFAULT 'active',
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `email_index` (`email`),
        KEY `timestamp_index` (`timestamp`),
        KEY `exam_id_index` (`exam_id`),
        KEY `violation_type_index` (`violation_type`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    if (mysqli_query($con, $sql)) {
        $fixes_applied[] = "✓ Created tab_violations table";
    } else {
        $errors[] = "✗ Failed to create tab_violations table: " . mysqli_error($con);
    }
} else {
    $fixes_applied[] = "✓ tab_violations table already exists";
}

// 2. Add columns to history table if they don't exist
$check_terminated = mysqli_query($con, "SHOW COLUMNS FROM history LIKE 'terminated'");
if (mysqli_num_rows($check_terminated) == 0) {
    $sql = "ALTER TABLE `history` ADD COLUMN `terminated` TINYINT(1) DEFAULT 0";
    if (mysqli_query($con, $sql)) {
        $fixes_applied[] = "✓ Added 'terminated' column to history table";
    } else {
        $errors[] = "✗ Failed to add 'terminated' column: " . mysqli_error($con);
    }
} else {
    $fixes_applied[] = "✓ 'terminated' column already exists";
}

$check_reason = mysqli_query($con, "SHOW COLUMNS FROM history LIKE 'termination_reason'");
if (mysqli_num_rows($check_reason) == 0) {
    $sql = "ALTER TABLE `history` ADD COLUMN `termination_reason` VARCHAR(255) DEFAULT NULL";
    if (mysqli_query($con, $sql)) {
        $fixes_applied[] = "✓ Added 'termination_reason' column to history table";
    } else {
        $errors[] = "✗ Failed to add 'termination_reason' column: " . mysqli_error($con);
    }
} else {
    $fixes_applied[] = "✓ 'termination_reason' column already exists";
}

// 3. Create exam_terminations table if it doesn't exist
$check_terminations = mysqli_query($con, "SHOW TABLES LIKE 'exam_terminations'");
if (mysqli_num_rows($check_terminations) == 0) {
    $sql = "CREATE TABLE `exam_terminations` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `email` varchar(255) NOT NULL,
        `exam_id` varchar(50) NOT NULL,
        `termination_reason` varchar(255) NOT NULL,
        `violation_count` int(11) DEFAULT 0,
        `timestamp` datetime NOT NULL,
        `ip_address` varchar(45) DEFAULT NULL,
        `user_agent` text DEFAULT NULL,
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `email_index` (`email`),
        KEY `exam_id_index` (`exam_id`),
        KEY `timestamp_index` (`timestamp`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    if (mysqli_query($con, $sql)) {
        $fixes_applied[] = "✓ Created exam_terminations table";
    } else {
        $errors[] = "✗ Failed to create exam_terminations table: " . mysqli_error($con);
    }
} else {
    $fixes_applied[] = "✓ exam_terminations table already exists";
}

// Display results
echo "<h3>Fixes Applied:</h3>";
foreach ($fixes_applied as $fix) {
    echo "<div class='success'>$fix</div>";
}

if (!empty($errors)) {
    echo "<h3>Errors:</h3>";
    foreach ($errors as $error) {
        echo "<div class='error'>$error</div>";
    }
}

// Test the setup
echo "<h3>Testing Setup:</h3>";
$test_insert = "INSERT INTO tab_violations (email, name, violation_type, violation_count, timestamp) 
                VALUES ('test@example.com', 'Test User', 'Test violation', 1, NOW())";
if (mysqli_query($con, $test_insert)) {
    $test_id = mysqli_insert_id($con);
    echo "<div class='success'>✓ Test violation record created (ID: $test_id)</div>";
    
    // Clean up test record
    mysqli_query($con, "DELETE FROM tab_violations WHERE id = $test_id");
    echo "<div class='success'>✓ Test record cleaned up</div>";
} else {
    echo "<div class='error'>✗ Failed to create test record: " . mysqli_error($con) . "</div>";
}

echo "<h3>Next Steps:</h3>";
echo "<div style='background: #e7f3ff; padding: 15px; border-left: 4px solid #2196F3;'>
<ol>
<li><strong>Test the detection:</strong> Go to <a href='test_tab_detection.html'>test_tab_detection.html</a></li>
<li><strong>Check diagnostic:</strong> Go to <a href='diagnostic.php'>diagnostic.php</a></li>
<li><strong>Test with real exam:</strong> Login and start an exam in <a href='account.php'>account.php</a></li>
<li><strong>Monitor violations:</strong> Check admin dashboard at <a href='dash.php?q=7'>dash.php?q=7</a></li>
</ol>
</div>";

mysqli_close($con);
?>
