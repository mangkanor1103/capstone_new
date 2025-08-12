<?php
// Simple script to create the warning_images table
require_once 'dbConnection.php';

$sql = "CREATE TABLE IF NOT EXISTS `warning_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `warning_id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image_data` longblob NOT NULL,
  `image_name` varchar(255) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `violation_count` int(11) NOT NULL DEFAULT 0,
  `violation_level` varchar(50) NOT NULL DEFAULT 'MINOR',
  PRIMARY KEY (`id`),
  KEY `warning_id` (`warning_id`),
  KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

if ($con->query($sql) === TRUE) {
    echo "Table 'warning_images' created successfully!";
} else {
    echo "Error creating table: " . $con->error;
}

$con->close();
?>
