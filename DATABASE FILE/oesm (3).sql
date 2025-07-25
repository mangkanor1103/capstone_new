-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 06, 2025 at 06:08 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `oesm`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `email` varchar(50) NOT NULL,
  `password` varchar(500) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `college` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`email`, `password`, `gender`, `college`) VALUES
('sunnygkp10@gmail.com', '123456', '', ''),
('d@d.com', 'asd', 'a', 'asd');

-- --------------------------------------------------------

--
-- Table structure for table `answer`
--

CREATE TABLE `answer` (
  `qid` text NOT NULL,
  `ansid` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `answer`
--

INSERT INTO `answer` (`qid`, `ansid`) VALUES
('55892169bf6a7', '55892169d2efc'),
('5589216a3646e', '5589216a48722'),
('558922117fcef', '5589221195248'),
('55892211e44d5', '55892211f1fa7'),
('558922894c453', '558922895ea0a'),
('558922899ccaa', '55892289aa7cf'),
('558923538f48d', '558923539a46c'),
('55892353f05c4', '55892354051be'),
('607336aa8c987', '607336aa961b9'),
('607336aacedd1', '607336aadc68e'),
('607336ab244aa', '607336ab31664'),
('683707626953b', '6837076269b59'),
('683707626d99f', '683707626f441'),
('6837076272986', '6837076273c6e'),
('6837076276e60', '683707627af08'),
('683707627e094', '683707627f269'),
('6837076281ce6', '6837076283265'),
('6837076285517', '68370762862ba'),
('6837076289386', '683707628a88b'),
('683707628bc29', '683707628d423'),
('683707628f420', '683707629035f'),
('6837076292572', '68370762931d3'),
('683707629585d', '68370762969d0'),
('683707629769f', '6837076297f20'),
('683707629a388', '683707629a90a'),
('683707629ccae', '683707629ea15'),
('68370762a14eb', '68370762a2274'),
('68370762a3e1d', '68370762abcf9'),
('68370762af199', '68370762b05c2'),
('68370762b2374', '68370762b3046'),
('68370762b3de7', '68370762b4648'),
('68411daa11b67', '68411daa143a8');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` text NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `subject` varchar(500) NOT NULL,
  `feedback` varchar(500) NOT NULL,
  `date` date NOT NULL,
  `time` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `name`, `email`, `subject`, `feedback`, `date`, `time`) VALUES
('60730932a3d1b', 'Demo Test', 'test@feedback.com', 'Testing Feedbacks', 'This is a demo text for testing purpose', '2021-04-11', '04:35:30pm'),
('607309ab640d8', 'Chris', 'chris@gmail.com', 'Regard System', 'this is a demo text!', '2021-04-11', '04:37:31pm'),
('60730a627e21f', 'Oliver', 'oliver@gmail.com', 'Bug', 'demo demo', '2021-04-11', '04:40:34pm');

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `email` varchar(50) NOT NULL,
  `eid` text NOT NULL,
  `score` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `sahi` int(11) NOT NULL,
  `wrong` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `history`
--

INSERT INTO `history` (`email`, `eid`, `score`, `level`, `sahi`, `wrong`, `date`) VALUES
('sunnygkp10@gmail.com', '558921841f1ec', 4, 2, 2, 0, '2015-06-23 01:31:26'),
('sunnygkp10@gmail.com', '558920ff906b8', 4, 2, 2, 0, '2015-06-23 05:32:09'),
('avantika420@gmail.com', '558921841f1ec', 4, 2, 2, 0, '2015-06-23 06:33:04'),
('avantika420@gmail.com', '5589222f16b93', 4, 2, 2, 0, '2015-06-23 06:49:39'),
('mi5@hollywood.com', '5589222f16b93', 4, 2, 2, 0, '2015-06-23 07:12:56'),
('nik1@gmail.com', '558921841f1ec', 1, 2, 1, 1, '2015-06-23 08:11:50'),
('clancy@gmail.com', '5589222f16b93', 4, 2, 2, 0, '2021-04-11 05:24:37'),
('sunnygkp10@gmail.com', '5589222f16b93', 4, 2, 2, 0, '2021-04-11 08:27:21'),
('doe@gmail.com', '558921841f1ec', 4, 2, 2, 0, '2021-04-11 09:20:17'),
('james@gmail.com', '558921841f1ec', 4, 2, 2, 0, '2021-04-11 09:26:12'),
('james@gmail.com', '5589222f16b93', 4, 2, 2, 0, '2021-04-11 09:26:54'),
('steeve@gmail.com', '558921841f1ec', 4, 2, 2, 0, '2021-04-11 09:44:46'),
('steeve@gmail.com', '5589222f16b93', 4, 2, 2, 0, '2021-04-11 09:45:20'),
('steeve@gmail.com', '6073360884420', 6, 3, 3, 0, '2021-04-11 09:50:15'),
('kianr6644@gmail.com', '558922ec03021', 1, 2, 1, 1, '2025-05-14 08:25:42'),
('kianr6644@gmail.com', '6073360884420', 2, 3, 2, 1, '2025-05-14 09:10:01'),
('rheamelchorQ@gmail.com', '6073360884420', 1, 3, 1, 2, '2025-05-28 13:11:40'),
('1rheamelchorq@gmail.com', '6073360884420', 0, 3, 0, 3, '2025-06-05 02:05:56'),
('kianr664@gmail.com', '6837075900ce6', 6, 20, 6, 14, '2025-06-05 02:29:01'),
('kianr664@gmail.com', '6073360884420', 0, 3, 0, 3, '2025-06-05 03:08:26'),
('kianr664@gmail.com', '558922ec03021', 0, 2, 0, 2, '2025-06-05 06:15:44');

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE `options` (
  `qid` varchar(50) NOT NULL,
  `option` varchar(5000) NOT NULL,
  `optionid` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`qid`, `option`, `optionid`) VALUES
('55892169bf6a7', 'usermod', '55892169d2efc'),
('55892169bf6a7', 'useradd', '55892169d2f05'),
('55892169bf6a7', 'useralter', '55892169d2f09'),
('55892169bf6a7', 'groupmod', '55892169d2f0c'),
('5589216a3646e', '751', '5589216a48713'),
('5589216a3646e', '752', '5589216a4871a'),
('5589216a3646e', '754', '5589216a4871f'),
('5589216a3646e', '755', '5589216a48722'),
('558922117fcef', 'echo', '5589221195248'),
('558922117fcef', 'print', '558922119525a'),
('558922117fcef', 'printf', '5589221195265'),
('558922117fcef', 'cout', '5589221195270'),
('55892211e44d5', 'int a', '55892211f1f97'),
('55892211e44d5', '$a', '55892211f1fa7'),
('55892211e44d5', 'long int a', '55892211f1fb4'),
('55892211e44d5', 'int a$', '55892211f1fbd'),
('558922894c453', 'cin>>a;', '558922895ea0a'),
('558922894c453', 'cin<<a;', '558922895ea26'),
('558922894c453', 'cout>>a;', '558922895ea34'),
('558922894c453', 'cout<a;', '558922895ea41'),
('558922899ccaa', 'cout', '55892289aa7cf'),
('558922899ccaa', 'cin', '55892289aa7df'),
('558922899ccaa', 'print', '55892289aa7eb'),
('558922899ccaa', 'printf', '55892289aa7f5'),
('558923538f48d', '255.0.0.0', '558923539a46c'),
('558923538f48d', '255.255.255.0', '558923539a480'),
('558923538f48d', '255.255.0.0', '558923539a48b'),
('558923538f48d', 'none of these', '558923539a495'),
('55892353f05c4', '192.168.1.100', '5589235405192'),
('55892353f05c4', '172.168.16.2', '55892354051a3'),
('55892353f05c4', '10.0.0.0.1', '55892354051b4'),
('55892353f05c4', '11.11.11.11', '55892354051be'),
('607336aa8c987', 'module.expose', '607336aa961a7'),
('607336aa8c987', 'module', '607336aa961b1'),
('607336aa8c987', 'module.exports', '607336aa961b5'),
('607336aa8c987', 'all', '607336aa961b9'),
('607336aacedd1', 'nodejs codeastro.js', '607336aadc686'),
('607336aacedd1', 'node codeastro.js', '607336aadc68e'),
('607336aacedd1', 'codeastro.js', '607336aadc691'),
('607336aacedd1', 'none', '607336aadc694'),
('607336ab244aa', 'npm --version', '607336ab31664'),
('607336ab244aa', 'npm --ver', '607336ab31670'),
('607336ab244aa', 'npm help', '607336ab31672'),
('607336ab244aa', 'none', '607336ab31673'),
('683707626953b', 'Central Processing Unit', '6837076269b59'),
('683707626953b', 'Central Programming Unit', '683707626a0cc'),
('683707626953b', 'Central Performance Unit', '683707626a64f'),
('683707626953b', 'Computer Processing Unit', '683707626c34b'),
('683707626d99f', 'Keyboard', '683707626e39a'),
('683707626d99f', 'Mouse', '683707626ec67'),
('683707626d99f', 'Monitor', '683707626f441'),
('683707626d99f', 'Scanner', '683707627068c'),
('6837076272986', 'Compiles programs', '68370762731c3'),
('6837076272986', 'Manages hardware and software', '6837076273c6e'),
('6837076272986', 'Edits documents', '6837076274e16'),
('6837076272986', 'Sends emails', '6837076275779'),
('6837076276e60', 'HighText Machine Language', '6837076279970'),
('6837076276e60', 'HyperText and Links Markup Language', '683707627a439'),
('6837076276e60', 'HyperText Markup Language', '683707627af08'),
('6837076276e60', 'None of the above', '683707627b879'),
('683707627e094', 'HTTP', '683707627ea45'),
('683707627e094', 'Python', '683707627f269'),
('683707627e094', 'USB', '68370762804ea'),
('683707627e094', 'HTML', '6837076280f5f'),
('6837076281ce6', 'Google', '6837076282477'),
('6837076281ce6', 'Apple', '6837076282a54'),
('6837076281ce6', 'Microsoft', '6837076283265'),
('6837076281ce6', 'IBM', '68370762838f7'),
('6837076285517', 'Uniform Resource Locator', '68370762862ba'),
('6837076285517', 'Uniform Response Locator', '6837076286ad0'),
('6837076285517', 'Unified Resource Link', '6837076287422'),
('6837076285517', 'User Resource Link', '6837076287a16'),
('6837076289386', 'Microsoft Word', '6837076289a9a'),
('6837076289386', 'Adobe Photoshop', '683707628a18b'),
('6837076289386', 'Linux', '683707628a88b'),
('6837076289386', 'macOS', '683707628aefa'),
('683707628bc29', 'RAM', '683707628cc72'),
('683707628bc29', 'ROM', '683707628d423'),
('683707628bc29', 'Cache', '683707628ddca'),
('683707628bc29', 'Virtual Memory', '683707628e4d9'),
('683707628f420', 'USB Drive', '683707628faa8'),
('683707628f420', 'Router', '683707629035f'),
('683707628f420', 'Monitor', '68370762911a0'),
('683707628f420', 'Printer', '68370762918c4'),
('6837076292572', 'Internet Protocol', '68370762931d3'),
('6837076292572', 'Internal Processing', '6837076293c87'),
('6837076292572', 'Integrated Platform', '6837076294183'),
('6837076292572', 'Interface Protocol', '6837076294aa9'),
('683707629585d', 'Chrome', '6837076295d5c'),
('683707629585d', 'Firefox', '68370762961f2'),
('683707629585d', 'Ubuntu', '68370762969d0'),
('683707629585d', 'Safari', '6837076296e6a'),
('683707629769f', 'Optical', '6837076297b04'),
('683707629769f', 'Magnetic', '6837076297f20'),
('683707629769f', 'Electrical', '6837076299179'),
('683707629769f', 'Laser', '68370762996e2'),
('683707629a388', 'Local Area Network', '683707629a90a'),
('683707629a388', 'Logical Area Network', '683707629aebb'),
('683707629a388', 'Large Area Network', '683707629b43c'),
('683707629a388', 'Light Access Network', '683707629b977'),
('683707629ccae', 'Object-Oriented', '683707629d49e'),
('683707629ccae', 'Functional', '683707629dac0'),
('683707629ccae', 'Procedural', '683707629e40d'),
('683707629ccae', 'Mechanical', '683707629ea15'),
('68370762a14eb', 'Windows', '68370762a1c1e'),
('68370762a14eb', 'MySQL', '68370762a2274'),
('68370762a14eb', 'Excel', '68370762a28ad'),
('68370762a14eb', 'Notepad', '68370762a311c'),
('68370762a3e1d', 'Basic Input Output System', '68370762abcf9'),
('68370762a3e1d', 'Binary Input Output Software', '68370762ac569'),
('68370762a3e1d', 'Basic Internal Operating System', '68370762ad154'),
('68370762a3e1d', 'Basic Integrated Output System', '68370762ade10'),
('68370762af199', 'C++', '68370762af925'),
('68370762af199', 'HTML', '68370762b05c2'),
('68370762af199', 'Python', '68370762b1216'),
('68370762af199', 'Java', '68370762b190a'),
('68370762b2374', 'A programming language', '68370762b27d3'),
('68370762b2374', 'Sending spam emails', '68370762b2c0b'),
('68370762b2374', 'Attempt to steal personal information via fake sites', '68370762b3046'),
('68370762b2374', 'A virus removal tool', '68370762b34c5'),
('68370762b3de7', 'System software', '68370762b4224'),
('68370762b3de7', 'Utility software', '68370762b4648'),
('68370762b3de7', 'Application software', '68370762b4abd'),
('68370762b3de7', 'Programming software', '68370762b575d'),
('68411daa11b67', 'xegx', '68411daa143a8'),
('68411daa11b67', 'xgxxegx', '68411daa143ab'),
('68411daa11b67', 'exgx', '68411daa143ac'),
('68411daa11b67', 'xge', '68411daa143ad');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `eid` text NOT NULL,
  `qid` text NOT NULL,
  `qns` text NOT NULL,
  `choice` int(10) NOT NULL,
  `sn` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`eid`, `qid`, `qns`, `choice`, `sn`) VALUES
('558920ff906b8', '55892169bf6a7', 'what is command for changing user information??', 4, 1),
('558920ff906b8', '5589216a3646e', 'what is permission for view only for other??', 4, 2),
('558921841f1ec', '558922117fcef', 'what is command for print in php??', 4, 1),
('558921841f1ec', '55892211e44d5', 'which is a variable of php??', 4, 2),
('5589222f16b93', '558922894c453', 'what is correct statement in c++??', 4, 1),
('5589222f16b93', '558922899ccaa', 'which command is use for print the output in c++?', 4, 2),
('558922ec03021', '558923538f48d', 'what is correct mask for A class IP???', 4, 1),
('558922ec03021', '55892353f05c4', 'which is not a private IP??', 4, 2),
('6073360884420', '607336aa8c987', 'The node.js modules can be exposed using', 4, 1),
('6073360884420', '607336aacedd1', 'Which statement executes the code of codeastro.js file?', 4, 2),
('6073360884420', '607336ab244aa', 'How can we check the current version of NPM?', 4, 3),
('558920ff906b8', 'q1', 'What does CPU stand for?', 4, 1),
('558920ff906b8', 'q2', 'Which of the following is NOT an input device?', 4, 2),
('558920ff906b8', 'q3', 'What is the function of an operating system?', 4, 3),
('558920ff906b8', 'q4', 'What does HTML stand for?', 4, 4),
('558920ff906b8', 'q5', 'Which of these is a programming language?', 4, 5),
('558920ff906b8', 'q6', 'Which company developed the Windows operating system?', 4, 6),
('558920ff906b8', 'q7', 'What is a URL?', 4, 7),
('558920ff906b8', 'q8', 'Which of the following is an example of open-source software?', 4, 8),
('558920ff906b8', 'q9', 'What type of memory is used to store data permanently?', 4, 9),
('558920ff906b8', 'q10', 'Which device connects a computer to a network?', 4, 10),
('558920ff906b8', 'q11', 'What does IP stand for in networking?', 4, 11),
('558920ff906b8', 'q12', 'Which of these is not a web browser?', 4, 12),
('558920ff906b8', 'q13', 'Which technology is used in hard drives to store data?', 4, 13),
('558920ff906b8', 'q14', 'What is the full form of LAN?', 4, 14),
('558920ff906b8', 'q15', 'Which of the following is NOT a programming paradigm?', 4, 15),
('558920ff906b8', 'q16', 'Which of the following is a database management system?', 4, 16),
('558920ff906b8', 'q17', 'What does BIOS stand for?', 4, 17),
('558920ff906b8', 'q18', 'Which of these is used to design web pages?', 4, 18),
('558920ff906b8', 'q19', 'What is phishing?', 4, 19),
('558920ff906b8', 'q20', 'What kind of software is an antivirus?', 4, 20),
('6837075900ce6', '683707626953b', 'What does UPC stand for?', 4, 1),
('6837075900ce6', '683707626d99f', 'Which of the following is NOT an input device?', 4, 2),
('6837075900ce6', '6837076272986', 'What is the function of an operating system?', 4, 3),
('6837075900ce6', '6837076276e60', 'What does HTML stand for?', 4, 4),
('6837075900ce6', '683707627e094', 'Which of these is a programming language?', 4, 5),
('6837075900ce6', '6837076281ce6', 'Which company developed the Windows operating system?', 4, 6),
('6837075900ce6', '6837076285517', 'What is a URL?', 4, 7),
('6837075900ce6', '6837076289386', 'Which of the following is an example of open-source software?', 4, 8),
('6837075900ce6', '683707628bc29', 'What type of memory is used to store data permanently?', 4, 9),
('6837075900ce6', '683707628f420', 'Which device connects a computer to a network?', 4, 10),
('6837075900ce6', '6837076292572', 'What does IP stand for in networking?', 4, 11),
('6837075900ce6', '683707629585d', 'Which of these is not a web browser?', 4, 12),
('6837075900ce6', '683707629769f', 'Which technology is used in hard drives to store data?', 4, 13),
('6837075900ce6', '683707629a388', 'What is the full form of LAN?', 4, 14),
('6837075900ce6', '683707629ccae', 'Which of the following is NOT a programming paradigm?', 4, 15),
('6837075900ce6', '68370762a14eb', 'Which of the following is a database management system?', 4, 16),
('6837075900ce6', '68370762a3e1d', 'What does BIOS stand for?', 4, 17),
('6837075900ce6', '68370762af199', 'Which of these is used to design web pages?', 4, 18),
('6837075900ce6', '68370762b2374', 'What is phishing?', 4, 19),
('6837075900ce6', '68370762b3de7', 'What kind of software is an antivirus?', 4, 20),
('6073360884420', '68411daa11b67', 'segs', 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `quiz`
--

CREATE TABLE `quiz` (
  `eid` text NOT NULL,
  `title` varchar(100) NOT NULL,
  `sahi` int(11) NOT NULL,
  `wrong` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `time` bigint(20) NOT NULL,
  `intro` text NOT NULL,
  `tag` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `allow_restart` tinyint(1) NOT NULL DEFAULT 0,
  `allowed_sections` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `quiz`
--

INSERT INTO `quiz` (`eid`, `title`, `sahi`, `wrong`, `total`, `time`, `intro`, `tag`, `date`, `allow_restart`, `allowed_sections`) VALUES
('558920ff906b8', 'Linux : File Managment', 2, 1, 2, 5, '', 'linux', '2015-06-23 01:03:59', 0, NULL),
('558921841f1ec', 'Php Coding', 2, 1, 2, 5, '', 'PHP', '2015-06-23 01:06:12', 0, NULL),
('5589222f16b93', 'C++ Coding', 2, 1, 2, 5, '', 'c++', '2015-06-23 01:09:03', 0, NULL),
('558922ec03021', 'Networking', 2, 1, 2, 5, '', 'networking', '2015-06-23 01:12:12', 0, NULL),
('6073360884420', 'Nodejs Term', 2, 2, 3, 2, 'Basic test for nodejs terms', 'nodejs', '2025-06-05 06:12:52', 0, '[\"Grade 7 - Section A\",\"Grade 7 - Section B\",\"Grade 8 - Section A\",\"Grade 8 - Section B\"]'),
('6837075900ce6', 'It', 1, 1, 20, 60, 'for it', 'ewdcew', '2025-06-05 06:12:24', 0, ''),
('684155c7c4bfd', 'Entrance Exam', 1, 1, 5, 60, 'sasasa', 'IT', '2025-06-05 08:31:03', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `rank`
--

CREATE TABLE `rank` (
  `email` varchar(50) NOT NULL,
  `score` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `rank`
--

INSERT INTO `rank` (`email`, `score`, `time`) VALUES
('maria.santos@email.com', 495, '2025-06-05 03:18:24'),
('juan.cruz@email.com', 478, '2025-06-05 03:18:24'),
('ana.garcia@email.com', 465, '2025-06-05 03:18:24'),
('pedro.lopez@email.com', 452, '2025-06-05 03:18:25'),
('lisa.reyes@email.com', 441, '2025-06-05 03:18:25'),
('carlos.mendoza@email.com', 435, '2025-06-05 03:18:25'),
('sofia.martinez@email.com', 428, '2025-06-05 03:18:25'),
('miguel.torres@email.com', 415, '2025-06-05 03:18:25'),
('elena.rivera@email.com', 402, '2025-06-05 03:18:25'),
('jose.hernandez@email.com', 398, '2025-06-05 03:18:25'),
('lucia.flores@email.com', 385, '2025-06-05 03:18:25'),
('diego.morales@email.com', 372, '2025-06-05 03:18:25'),
('kianr664@gmail.com', 0, '2025-06-05 06:15:44');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `name` varchar(50) NOT NULL,
  `gender` varchar(5) NOT NULL,
  `college` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `mob` bigint(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`name`, `gender`, `college`, `email`, `mob`, `password`, `photo`, `status`) VALUES
('Kian A. Rodriguez', 'M', '3F1', 'kianr6644@gmail.com', 9234567654, '$2y$10$hL7pA1pnRbjjGb.SNI1FE.wdDFboGFSEUiWP1kd9z0Vjp7p.9MR3K', 'uploads/user_682480786960b.png', 1),
('Rhea M. Melchor', 'F', '3F1', 'rheamelchorq@gmail.com', 9234567654, '$2y$10$M1TAAfoyshPULjjoduy1e.l2YRwfpNHblGAa.F6sSgSnOccYLTVpu', 'uploads/user_683706d10580f.png', 1),
('Rhea M. Melchor', 'F', '3F1', '1rheamelchorq@gmail.com', 9234567654, '$2y$10$Cwbfr9b5ubbBTGVXed0q0uXV6syuuGia8h2.UA/xz/rkGGe.uWjo2', 'uploads/user_6840faee95bef.png', 1),
('Kian A. Rodriguez', 'M', 'Grade 11 - Section A', 'kianr664@gmail.com', 9234567654, '$2y$10$gy6rsosW3v/AR3nBm7xJfOMGjyT4nh3nYG..QuCY9OjUENTlyZSzG', 'uploads/user_6841008dae71a.png', 1),
('Kyrelle Milanes', 'F', 'Grade 11 - Section B', 'ky@gmail.com', 325252, '$2y$10$OU5XI/9KaHWx037QpPeL.O0bhyQrewuZdvrJgZmM.eQBHROHEwPzW', 'uploads/user_68414634a6f71.png', 1),
('Kyrelle Milanes', 'F', 'Grade 7 - Section A', 'ky1@gmail.com', 325252, '$2y$10$yly3IaJb7iXUqP8tO3u.ZekB89C..Zm.YLPcmaFecn5i8utMewsEG', 'uploads/user_684153dc24105.png', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_exam_settings`
--

CREATE TABLE `user_exam_settings` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `eid` varchar(50) NOT NULL,
  `allow_restart` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `warning`
--

CREATE TABLE `warning` (
  `id` int(11) NOT NULL,
  `timestamp` datetime NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `warning`
--

INSERT INTO `warning` (`id`, `timestamp`, `email`) VALUES
(1, '2025-05-14 15:10:45', 'kianr6644@gmail.com'),
(2, '2025-05-14 17:16:00', 'kianr6644@gmail.com'),
(3, '2025-05-14 17:16:18', 'kianr6644@gmail.com'),
(4, '2025-05-14 17:18:13', 'kianr6644@gmail.com'),
(5, '2025-05-14 17:18:15', 'kianr6644@gmail.com'),
(6, '2025-05-14 17:19:27', 'kianr6644@gmail.com'),
(7, '2025-05-14 17:19:40', 'kianr6644@gmail.com'),
(8, '2025-05-14 17:21:23', 'kianr6644@gmail.com'),
(9, '2025-05-14 17:21:24', 'kianr6644@gmail.com'),
(10, '2025-05-14 17:21:26', 'kianr6644@gmail.com'),
(11, '2025-05-14 17:21:27', 'kianr6644@gmail.com'),
(12, '2025-05-14 17:27:05', 'kianr6644@gmail.com'),
(13, '2025-05-14 17:27:30', 'kianr6644@gmail.com'),
(14, '2025-05-14 17:27:38', 'kianr6644@gmail.com'),
(15, '2025-05-14 17:27:50', 'kianr6644@gmail.com'),
(16, '2025-05-14 17:34:30', 'kianr6644@gmail.com'),
(17, '2025-05-14 17:54:29', 'kianr6644@gmail.com'),
(18, '2025-05-14 17:55:21', 'kianr6644@gmail.com'),
(19, '2025-05-14 17:55:22', 'kianr6644@gmail.com'),
(20, '2025-05-14 18:00:43', 'kianr6644@gmail.com'),
(21, '2025-05-14 18:00:44', 'kianr6644@gmail.com'),
(22, '2025-05-14 18:02:49', 'kianr6644@gmail.com'),
(23, '2025-05-14 18:02:51', 'kianr6644@gmail.com'),
(24, '2025-05-14 18:03:13', 'kianr6644@gmail.com'),
(25, '2025-05-14 18:05:06', 'kianr6644@gmail.com'),
(26, '2025-05-14 18:05:08', 'kianr6644@gmail.com'),
(27, '2025-05-14 18:06:48', 'kianr6644@gmail.com'),
(28, '2025-05-14 18:07:01', 'kianr6644@gmail.com'),
(29, '2025-05-14 18:09:14', 'kianr6644@gmail.com'),
(30, '2025-05-14 18:09:26', 'kianr6644@gmail.com'),
(31, '2025-05-14 18:09:55', 'kianr6644@gmail.com'),
(32, '2025-05-14 18:19:02', 'kianr6644@gmail.com'),
(33, '2025-05-14 18:19:05', 'kianr6644@gmail.com'),
(34, '2025-05-14 18:20:40', 'kianr6644@gmail.com'),
(35, '2025-05-14 18:20:43', 'kianr6644@gmail.com'),
(36, '2025-05-14 18:20:47', 'kianr6644@gmail.com'),
(37, '2025-05-14 18:20:49', 'kianr6644@gmail.com'),
(38, '2025-05-14 18:20:59', 'kianr6644@gmail.com'),
(39, '2025-05-14 18:21:10', 'kianr6644@gmail.com'),
(40, '2025-05-14 18:21:22', 'kianr6644@gmail.com'),
(41, '2025-05-14 18:23:17', 'kianr6644@gmail.com'),
(42, '2025-05-14 18:55:38', 'kianr6644@gmail.com'),
(43, '2025-05-14 18:55:48', 'kianr6644@gmail.com'),
(44, '2025-05-14 18:56:03', 'kianr6644@gmail.com'),
(45, '2025-05-14 18:56:06', 'kianr6644@gmail.com'),
(46, '2025-05-14 19:01:24', 'kianr6644@gmail.com'),
(47, '2025-05-14 19:01:51', 'kianr6644@gmail.com'),
(48, '2025-05-14 19:03:14', 'kianr6644@gmail.com'),
(49, '2025-05-14 19:03:15', 'kianr6644@gmail.com'),
(50, '2025-05-14 19:03:23', 'kianr6644@gmail.com'),
(51, '2025-05-14 19:03:24', 'kianr6644@gmail.com'),
(52, '2025-05-14 19:09:48', 'kianr6644@gmail.com'),
(0, '2025-05-28 14:54:32', 'rheamelchorq@gmail.com'),
(0, '2025-05-28 14:54:42', 'rheamelchorq@gmail.com'),
(0, '2025-05-28 14:54:50', 'rheamelchorq@gmail.com'),
(0, '2025-05-28 14:54:57', 'rheamelchorq@gmail.com'),
(0, '2025-05-28 14:55:05', 'rheamelchorq@gmail.com'),
(0, '2025-05-28 14:55:15', 'rheamelchorq@gmail.com'),
(0, '2025-05-28 14:55:24', 'rheamelchorq@gmail.com'),
(0, '2025-05-28 14:55:30', 'rheamelchorq@gmail.com'),
(0, '2025-05-28 14:55:37', 'rheamelchorq@gmail.com'),
(0, '2025-06-05 04:05:36', '1rheamelchorq@gmail.com'),
(0, '2025-06-05 10:23:42', 'ky1@gmail.com'),
(0, '2025-06-05 10:24:29', 'ky1@gmail.com'),
(0, '2025-06-05 10:24:52', 'ky1@gmail.com');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
