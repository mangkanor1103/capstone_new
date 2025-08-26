-- SQL script to create tab_violations table
-- This table stores records of tab switching and other security violations during exams

CREATE TABLE IF NOT EXISTS `tab_violations` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create indexes for better performance
CREATE INDEX `email_timestamp_idx` ON `tab_violations` (`email`, `timestamp`);
CREATE INDEX `exam_violations_idx` ON `tab_violations` (`exam_id`, `email`);

-- Insert some sample violation types for reference
INSERT INTO `tab_violations` (`email`, `name`, `violation_type`, `violation_count`, `timestamp`, `exam_id`, `question_number`) VALUES
('admin@example.com', 'Sample Admin', 'Tab switched or window minimized', 1, NOW(), 'sample_exam_01', 1),
('admin@example.com', 'Sample Admin', 'Context menu attempted', 2, NOW(), 'sample_exam_01', 1),
('admin@example.com', 'Sample Admin', 'Developer tools attempt (F12)', 3, NOW(), 'sample_exam_01', 2)
ON DUPLICATE KEY UPDATE id=id;
