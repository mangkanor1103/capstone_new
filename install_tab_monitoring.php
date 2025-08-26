<?php
// install_tab_monitoring.php - Installation script for tab monitoring functionality
require_once 'dbConnection.php';

echo "<h2>FaceTrackED - Tab Monitoring Installation</h2>";
echo "<p>Installing tab switching prevention and monitoring features...</p>";

$errors = [];
$success = [];

// 1. Create tab_violations table
$sql_tab_violations = "CREATE TABLE IF NOT EXISTS `tab_violations` (
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

if (mysqli_query($con, $sql_tab_violations)) {
    $success[] = "✓ Created tab_violations table";
} else {
    $errors[] = "✗ Error creating tab_violations table: " . mysqli_error($con);
}

// 2. Add termination columns to history table
// First check if columns already exist
$check_terminated = "SHOW COLUMNS FROM history LIKE 'terminated'";
$result_terminated = mysqli_query($con, $check_terminated);

$check_termination_reason = "SHOW COLUMNS FROM history LIKE 'termination_reason'";
$result_termination_reason = mysqli_query($con, $check_termination_reason);

if (mysqli_num_rows($result_terminated) == 0) {
    // Add terminated column
    $sql_add_terminated = "ALTER TABLE `history` ADD COLUMN `terminated` TINYINT(1) DEFAULT 0";
    if (mysqli_query($con, $sql_add_terminated)) {
        $success[] = "✓ Added 'terminated' column to history table";
    } else {
        $errors[] = "✗ Error adding 'terminated' column: " . mysqli_error($con);
    }
} else {
    $success[] = "✓ 'terminated' column already exists in history table";
}

if (mysqli_num_rows($result_termination_reason) == 0) {
    // Add termination_reason column
    $sql_add_reason = "ALTER TABLE `history` ADD COLUMN `termination_reason` VARCHAR(255) DEFAULT NULL";
    if (mysqli_query($con, $sql_add_reason)) {
        $success[] = "✓ Added 'termination_reason' column to history table";
    } else {
        $errors[] = "✗ Error adding 'termination_reason' column: " . mysqli_error($con);
    }
} else {
    $success[] = "✓ 'termination_reason' column already exists in history table";
}

// 3. Create exam_terminations table
$sql_terminations = "CREATE TABLE IF NOT EXISTS `exam_terminations` (
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

if (mysqli_query($con, $sql_terminations)) {
    $success[] = "✓ Created exam_terminations table";
} else {
    $errors[] = "✗ Error creating exam_terminations table: " . mysqli_error($con);
}

// 4. Create indexes for better performance
$indexes = [
    "CREATE INDEX IF NOT EXISTS `email_timestamp_idx` ON `tab_violations` (`email`, `timestamp`)" => "Email-timestamp index on tab_violations",
    "CREATE INDEX IF NOT EXISTS `exam_violations_idx` ON `tab_violations` (`exam_id`, `email`)" => "Exam-violations index on tab_violations",
    "CREATE INDEX IF NOT EXISTS `terminated_exams_idx` ON `history` (`terminated`, `email`, `date`)" => "Terminated exams index on history"
];

foreach ($indexes as $index_sql => $description) {
    if (mysqli_query($con, $index_sql)) {
        $success[] = "✓ Created/verified $description";
    } else {
        // Most index errors are because they already exist, which is fine
        if (strpos(mysqli_error($con), 'Duplicate key name') !== false) {
            $success[] = "✓ $description already exists";
        } else {
            $errors[] = "✗ Error creating $description: " . mysqli_error($con);
        }
    }
}

// 5. Create terminated exams view
$sql_view = "CREATE OR REPLACE VIEW `terminated_exams_view` AS
SELECT 
    h.email,
    h.eid as exam_id,
    q.title as exam_title,
    h.score,
    h.level as questions_completed,
    h.termination_reason,
    h.date as termination_date,
    et.violation_count
FROM history h
LEFT JOIN quiz q ON h.eid = q.eid
LEFT JOIN exam_terminations et ON h.email = et.email AND h.eid = et.exam_id
WHERE h.terminated = 1
ORDER BY h.date DESC";

if (mysqli_query($con, $sql_view)) {
    $success[] = "✓ Created terminated exams view";
} else {
    $errors[] = "✗ Error creating terminated exams view: " . mysqli_error($con);
}

// Display results
echo "<div style='margin: 20px 0;'>";
echo "<h3 style='color: green;'>Success:</h3>";
foreach ($success as $msg) {
    echo "<p style='color: green;'>$msg</p>";
}

if (!empty($errors)) {
    echo "<h3 style='color: red;'>Errors:</h3>";
    foreach ($errors as $msg) {
        echo "<p style='color: red;'>$msg</p>";
    }
}
echo "</div>";

// Test the installation
echo "<h3>Testing Installation:</h3>";
$test_queries = [
    "SELECT COUNT(*) as count FROM tab_violations" => "Tab violations table",
    "SELECT COUNT(*) as count FROM exam_terminations" => "Exam terminations table",
    "SHOW COLUMNS FROM history LIKE 'terminated'" => "History table columns"
];

foreach ($test_queries as $query => $description) {
    $result = mysqli_query($con, $query);
    if ($result) {
        echo "<p style='color: green;'>✓ $description is working</p>";
    } else {
        echo "<p style='color: red;'>✗ $description test failed: " . mysqli_error($con) . "</p>";
    }
}

echo "<h3>Installation Complete!</h3>";
echo "<p>The tab switching prevention system has been installed. Features include:</p>";
echo "<ul>";
echo "<li>Detection of tab switching and window focus changes</li>";
echo "<li>Prevention of developer tools access</li>";
echo "<li>Fullscreen mode enforcement during exams</li>";
echo "<li>Automatic exam termination after 3 violations</li>";
echo "<li>Comprehensive violation logging</li>";
echo "<li>Admin dashboard integration for monitoring</li>";
echo "</ul>";

echo "<p><strong>Next Steps:</strong></p>";
echo "<ul>";
echo "<li>Test the system by starting an exam</li>";
echo "<li>Check admin dashboard for violation monitoring</li>";
echo "<li>Configure violation thresholds if needed</li>";
echo "</ul>";

echo "<p><a href='account.php?q=1'>Go to Student Dashboard</a> | <a href='dash.php?q=0'>Go to Admin Dashboard</a></p>";

mysqli_close($con);
?>
