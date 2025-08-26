-- SQL script to add termination tracking to existing tables
-- Add columns to history table to track exam terminations

ALTER TABLE `history` 
ADD COLUMN `terminated` TINYINT(1) DEFAULT 0 AFTER `date`,
ADD COLUMN `termination_reason` VARCHAR(255) DEFAULT NULL AFTER `terminated`;

-- Create exam_terminations table to log all termination events
CREATE TABLE IF NOT EXISTS `exam_terminations` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create a view to easily see terminated exams
CREATE OR REPLACE VIEW `terminated_exams_view` AS
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
ORDER BY h.date DESC;

-- Add index for better performance on terminated exams
CREATE INDEX `terminated_exams_idx` ON `history` (`terminated`, `email`, `date`);
