-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 26, 2024 at 06:28 AM
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
-- Database: `ccs_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'FK to user table',
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL COMMENT 'FK to role table',
  `status` varchar(50) DEFAULT 'active' COMMENT 'Account status',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`id`, `user_id`, `username`, `password`, `role_id`, `status`, `created_at`) VALUES
(1, 1, 'john_doe', '$2y$10$.EgyXCBNPIMlf5gSVPmBNefBq3Bqgorl2jgcWba9MbX3doKXAF8/O', 1, 'active', '2024-12-15 05:59:37'),
(2, 2, 'jane_smith', 'student2123123123', 1, 'active', '2024-12-15 05:59:37'),
(3, 3, 'alex_johnson', 'student3123123123', 1, 'active', '2024-12-15 05:59:37'),
(4, 4, 'emily_brown', 'student4123123123', 1, 'active', '2024-12-15 05:59:37'),
(5, 5, 'chris_davis', 'student5123123123', 1, 'active', '2024-12-15 05:59:37'),
(6, 6, 'olivia_miller', 'student6123123123', 1, 'active', '2024-12-15 05:59:37'),
(7, 7, 'liam_wilson', 'student7123123123', 1, 'active', '2024-12-15 05:59:37'),
(8, 8, 'sophia_moore', 'student8123123123', 1, 'active', '2024-12-15 05:59:37'),
(9, 9, 'noah_taylor', 'student9123123123', 1, 'active', '2024-12-15 05:59:37'),
(10, 10, 'ava_anderson', 'student10123123123', 1, 'active', '2024-12-15 05:59:37'),
(11, 11, 'ethan_martinez', 'student11123123123', 1, 'active', '2024-12-15 05:59:37'),
(12, 12, 'mia_hernandez', 'student12123123123', 1, 'active', '2024-12-15 05:59:37'),
(13, 13, 'jacob_lee', 'student13123123123', 1, 'active', '2024-12-15 05:59:37'),
(14, 14, 'zoe_clark', 'student14123123123', 1, 'active', '2024-12-15 05:59:37'),
(15, 15, 'luke_lewis', 'student15123123123', 1, 'active', '2024-12-15 05:59:37'),
(16, 16, 'ella_walker', 'student16123123123', 1, 'active', '2024-12-15 05:59:37'),
(17, 17, 'james_hall', 'student17123123123', 1, 'active', '2024-12-15 05:59:37'),
(18, 18, 'luna_allen', 'student18123123123', 1, 'active', '2024-12-15 05:59:37'),
(19, 19, 'logan_king', 'student19123123123', 1, 'active', '2024-12-15 05:59:37'),
(20, 20, 'riley_wright', 'student20123123123', 1, 'active', '2024-12-15 05:59:37'),
(21, 21, 'carter_hill', 'student21123123123', 1, 'active', '2024-12-15 05:59:37'),
(22, 22, 'aria_scott', 'student22123123123', 1, 'active', '2024-12-15 05:59:37'),
(23, 23, 'levi_green', 'student23123123123', 1, 'active', '2024-12-15 05:59:37'),
(24, 24, 'nora_adams', 'student24123123123', 1, 'active', '2024-12-15 05:59:37'),
(25, 25, 'daniel_baker', 'student25123123123', 1, 'active', '2024-12-15 05:59:37'),
(26, 26, 'abigail_gonzalez', 'student26123123123', 1, 'active', '2024-12-15 05:59:37'),
(27, 27, 'jackson_nelson', 'student27123123123', 1, 'active', '2024-12-15 05:59:37'),
(28, 28, 'charlotte_carter', 'student28123123123', 1, 'active', '2024-12-15 05:59:37'),
(29, 29, 'elijah_mitchell', 'student29123123123', 1, 'active', '2024-12-15 05:59:37'),
(30, 30, 'scarlett_perez', 'student30123123123', 1, 'active', '2024-12-15 05:59:37'),
(31, 31, 'michael_carter', 'staff1123123123', 2, 'active', '2024-12-21 05:59:37'),
(32, 32, 'sarah_evans', 'staff2123123123', 2, 'active', '2024-12-21 05:59:37'),
(33, 33, 'david_martin', 'staff3123123123', 2, 'active', '2024-12-21 05:59:37'),
(34, 34, 'jessica_garcia', 'staff4123123123', 2, 'active', '2024-12-21 05:59:37'),
(35, 35, 'james_roberts', 'staff5123123123', 2, 'active', '2024-12-21 05:59:37'),
(36, 36, 'emma_moore', 'staff6123123123', 2, 'active', '2024-12-21 05:59:37'),
(37, 37, 'oliver_clark', 'staff7123123123', 2, 'active', '2024-12-21 05:59:37'),
(38, 38, 'sophia_lewis', 'staff8123123123', 2, 'active', '2024-12-21 05:59:37'),
(39, 39, 'benjamin_walker', 'staff9123123123', 2, 'active', '2024-12-21 05:59:37'),
(40, 40, 'lucas_adams', '$2y$10$qUGZDXAJsmgJX7mcsxlLFuajWUKz6tCFjSx9PEWs.KBz/xUWUFdhK', 3, 'active', '2024-12-21 05:59:37'),
(41, 41, 'mia_perez', 'admin2123123123', 3, 'active', '2024-12-21 05:59:37'),
(42, 42, 'ethan_hill', 'admin3123123123', 3, 'active', '2024-12-21 05:59:37');

-- --------------------------------------------------------

--
-- Table structure for table `adviser`
--

CREATE TABLE `adviser` (
  `adviser_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'FK to user table',
  `firstname` varchar(100) NOT NULL,
  `middlename` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) NOT NULL,
  `department_id` int(11) NOT NULL COMMENT 'FK to department table'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adviser`
--

INSERT INTO `adviser` (`adviser_id`, `user_id`, `firstname`, `middlename`, `lastname`, `department_id`) VALUES
(1, 1, 'Alice', 'B.', 'Smith', 1),
(2, 2, 'Bob', 'C.', 'Johnson', 1),
(3, 3, 'Charlie', 'D.', 'Williams', 2),
(4, 4, 'David', 'E.', 'Brown', 2),
(5, 5, 'Ella', 'F.', 'Davis', 1),
(6, 6, 'Frank', 'G.', 'Miller', 2),
(7, 7, 'Grace', 'H.', 'Wilson', 1),
(8, 8, 'Hannah', 'I.', 'Moore', 2),
(9, 9, 'Isaac', 'J.', 'Taylor', 1),
(10, 10, 'Julia', 'K.', 'Anderson', 2);

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'FK to user table',
  `role_id` int(11) NOT NULL COMMENT 'FK to role table',
  `action_type` varchar(50) NOT NULL COMMENT 'Action type (e.g., CREATE, UPDATE, DELETE)',
  `action_details` text NOT NULL COMMENT 'Description of action performed',
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `role_id`, `action_type`, `action_details`, `timestamp`) VALUES
(1, 1, 2, 'CREATE', 'Created student application ID 1', '2024-12-15 13:59:37'),
(2, 2, 2, 'UPDATE', 'Updated student application ID 2 to Approved', '2024-12-15 13:59:37'),
(3, 3, 3, 'REJECT', 'Rejected student application ID 3', '2024-12-15 13:59:37'),
(4, 4, 1, 'CREATE', 'Created student application ID 4', '2024-12-15 13:59:37'),
(5, 5, 3, 'APPROVE', 'Approved student application ID 5', '2024-12-15 13:59:37'),
(6, 6, 2, 'CREATE', 'Created student application ID 6', '2024-12-15 13:59:37'),
(7, 7, 3, 'REJECT', 'Rejected student application ID 7', '2024-12-15 13:59:37'),
(8, 8, 2, 'APPROVE', 'Approved student application ID 8', '2024-12-15 13:59:37'),
(9, 9, 2, 'UPDATE', 'Updated student application ID 9', '2024-12-15 13:59:37'),
(10, 10, 3, 'REJECT', 'Rejected student application ID 10', '2024-12-15 13:59:37');

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `id` int(11) NOT NULL,
  `course_name` varchar(100) NOT NULL COMMENT 'Name of the course'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`id`, `course_name`) VALUES
(3, 'Associate in Computer Technology (ACT)'),
(1, 'Bachelor of Computer Science (BSCS)'),
(2, 'Bachelor of Information Technology (BSIT)');

-- --------------------------------------------------------

--
-- Table structure for table `curriculum`
--

CREATE TABLE `curriculum` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL COMMENT 'FK to course table',
  `version` varchar(50) NOT NULL COMMENT 'Version identifier (e.g., 2024, Version 1)',
  `effective_year` varchar(9) NOT NULL COMMENT 'Academic year when the curriculum starts (e.g., 2024-2025)',
  `remarks` text DEFAULT NULL COMMENT 'Optional description or notes for the curriculum',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `curriculum`
--

INSERT INTO `curriculum` (`id`, `course_id`, `version`, `effective_year`, `remarks`, `created_at`) VALUES
(1, 1, 'v2016', '2016-2022', 'BSCS Curriculum Version 2016', '2024-12-15 13:59:37'),
(2, 2, 'v2016', '2016-2022', 'BSIT Curriculum Version 2016', '2024-12-15 13:59:37'),
(3, 3, 'v2016', '2016-2022', 'ACT Curriculum Version 2016', '2024-12-15 13:59:37'),
(4, 1, 'v2023', '2023-2040', 'BSCS Curriculum Version 2023', '2024-12-15 13:59:37'),
(5, 2, 'v2023', '2023-2040', 'BSIT Curriculum Version 2023', '2024-12-15 13:59:37'),
(6, 3, 'v2023', '2023-2040', 'ACT Curriculum Version 2023', '2024-12-15 13:59:37');

-- --------------------------------------------------------

--
-- Table structure for table `dean_lister_application_periods`
--

CREATE TABLE `dean_lister_application_periods` (
  `id` int(11) NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `status` enum('open','closed') DEFAULT 'closed',
  `year` varchar(9) NOT NULL COMMENT 'Academic year, e.g., 2024-2025',
  `semester` varchar(20) NOT NULL COMMENT 'Semester, e.g., 1st, 2nd, Summer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dean_lister_application_periods`
--

INSERT INTO `dean_lister_application_periods` (`id`, `start_date`, `end_date`, `status`, `year`, `semester`) VALUES
(1, '2024-06-01 00:00:00', '2024-06-30 00:00:00', 'open', '2024-2025', '1st'),
(2, '2024-12-01 00:00:00', '2024-12-31 00:00:00', 'closed', '2024-2025', '2nd'),
(3, '2023-06-01 00:00:00', '2023-06-30 00:00:00', 'closed', '2023-2024', '1st'),
(4, '2023-12-01 00:00:00', '2023-12-31 00:00:00', 'closed', '2023-2024', '2nd'),
(5, '2022-06-01 00:00:00', '2022-06-30 00:00:00', 'closed', '2022-2023', '1st'),
(6, '2022-12-01 00:00:00', '2022-12-31 00:00:00', 'closed', '2022-2023', '2nd'),
(7, '2021-06-01 00:00:00', '2021-06-30 00:00:00', 'closed', '2021-2022', '1st'),
(8, '2021-12-01 00:00:00', '2021-12-31 00:00:00', 'closed', '2021-2022', '2nd'),
(9, '2020-06-01 00:00:00', '2020-06-30 00:00:00', 'closed', '2020-2021', '1st'),
(10, '2020-12-01 00:00:00', '2020-12-31 00:00:00', 'closed', '2020-2021', '2nd');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `id` int(11) NOT NULL,
  `department_name` varchar(100) NOT NULL COMMENT 'Name of the department'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`id`, `department_name`) VALUES
(1, 'Department of Computer Science'),
(2, 'Department of Information Technology'),
(3, 'Department of Associate in Computer Technology');

-- --------------------------------------------------------

--
-- Table structure for table `prospectus`
--

CREATE TABLE `prospectus` (
  `id` int(11) NOT NULL,
  `curriculum_id` int(11) NOT NULL COMMENT 'FK to curriculum table',
  `subject_code` varchar(20) NOT NULL,
  `descriptive_title` varchar(255) NOT NULL,
  `prerequisite` varchar(255) DEFAULT NULL,
  `lec_units` decimal(3,1) DEFAULT NULL,
  `lab_units` decimal(3,1) DEFAULT NULL,
  `total_units` decimal(3,1) GENERATED ALWAYS AS (`lec_units` + `lab_units`) STORED,
  `year_level` int(11) NOT NULL COMMENT 'Year when the subject is taken',
  `semester` varchar(20) NOT NULL COMMENT 'Semester when the subject is taken'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prospectus`
--

INSERT INTO `prospectus` (`id`, `curriculum_id`, `subject_code`, `descriptive_title`, `prerequisite`, `lec_units`, `lab_units`, `year_level`, `semester`) VALUES
(1, 4, 'CC100', 'Introduction to Computing', NULL, 3.0, 0.0, 1, '1st'),
(2, 4, 'CC101', 'Computer Programming 1', NULL, 3.0, 1.0, 1, '1st'),
(3, 4, 'DS111', 'Discrete Structures 1', NULL, 3.0, 0.0, 1, '1st'),
(4, 4, 'CAS101', 'Purposive Communication', NULL, 3.0, 0.0, 1, '1st'),
(5, 4, 'MATH100', 'Mathematics in the Modern World', NULL, 3.0, 0.0, 1, '1st'),
(6, 4, 'HIST100', 'Life and Works of Rizal', NULL, 3.0, 0.0, 1, '1st'),
(7, 4, 'US101', 'Understanding the Self', NULL, 3.0, 0.0, 1, '1st'),
(8, 4, 'PATHFIT1', 'Movement Competency Training', NULL, 2.0, 0.0, 1, '1st'),
(9, 4, 'CC102', 'Computer Programming 2', 'CC101', 3.0, 1.0, 1, '2nd'),
(10, 4, 'OOP112', 'Object-Oriented Programming', 'CC101', 3.0, 0.0, 1, '2nd'),
(11, 4, 'WD114', 'Web Development 1', 'CC101', 3.0, 0.0, 1, '2nd'),
(12, 4, 'HCI116', 'Human Computer Interaction', 'CC101', 3.0, 0.0, 1, '2nd'),
(13, 4, 'DS118', 'Discrete Structures 2', 'DS111', 3.0, 0.0, 1, '2nd'),
(14, 4, 'HIST101', 'Readings in Philippine History', NULL, 3.0, 0.0, 1, '2nd'),
(15, 4, 'STS100', 'Science, Technology and Society', NULL, 3.0, 0.0, 1, '2nd'),
(16, 4, 'PATHFIT2', 'Exercise-Based Fitness Activity', 'PATHFIT1', 2.0, 0.0, 1, '2nd'),
(17, 4, 'CC103', 'Data Structures and Algorithms', 'CC102', 3.0, 0.0, 2, '1st'),
(18, 4, 'CC104', 'Information Management', 'CC102,OOP112', 3.0, 0.0, 2, '1st'),
(19, 4, 'MAD121', 'Mobile Application Development', 'CC102,OOP112', 3.0, 0.0, 2, '1st'),
(20, 4, 'WD123', 'Web Development 2', 'WD114', 3.0, 0.0, 2, '1st'),
(21, 4, 'SIPP125', 'Social Issues and Professional Practices', 'CC102', 3.0, 0.0, 2, '1st'),
(22, 4, 'NC127', 'Networks and Communications', 'CC102', 3.0, 0.0, 2, '1st'),
(23, 4, 'PATHFIT3', 'Martial Arts 1', 'PATHFIT2', 2.0, 0.0, 2, '1st'),
(24, 4, 'CC105', 'Application Development and Emerging Technologies', 'CC104', 3.0, 0.0, 2, '2nd'),
(25, 4, 'ACTINT122', 'ACT Internship', 'WD123,SIPP125', 6.0, 0.0, 2, '2nd'),
(26, 4, 'AO124', 'Architecture and Organization', 'DS111,CC103', 3.0, 0.0, 2, '2nd'),
(27, 4, 'PHILCON', 'Philippine Constitution', NULL, 3.0, 0.0, 2, '2nd'),
(28, 4, 'CW101', 'The Contemporary World', NULL, 3.0, 0.0, 2, '2nd'),
(29, 4, 'PATHFIT4', 'Martial Arts 2', 'PATHFIT3', 2.0, 0.0, 2, '2nd'),
(30, 4, 'SE131', 'Software Engineering 1', 'CC105,WD123', 3.0, 0.0, 3, '1st'),
(31, 4, 'ADS133', 'Advanced Database Systems', 'CC104', 3.0, 0.0, 3, '1st'),
(32, 4, 'ATFL135', 'Automata Theory and Formal Languages', 'AC1218,PL129', 3.0, 0.0, 3, '1st'),
(33, 4, 'OS137', 'Operating Systems', 'AO124', 3.0, 0.0, 3, '1st'),
(34, 4, 'CALC139', 'Calculus for Computer Science', 'MATH100', 3.0, 0.0, 3, '1st'),
(35, 4, 'CSE1', 'CS Elective 1', NULL, 3.0, 0.0, 3, '1st'),
(36, 4, 'CSE2', 'CS Elective 2', NULL, 3.0, 0.0, 3, '1st'),
(37, 4, 'ES130', 'Embedded Systems', 'OS137', 3.0, 0.0, 3, '2nd'),
(38, 4, 'SE132', 'Software Engineering 2', 'SE131', 3.0, 0.0, 3, '2nd'),
(39, 4, 'IAS134', 'Information Assurance and Security', 'CC104,OS137', 2.0, 0.0, 3, '2nd'),
(40, 4, 'TW136', 'Technical Writing for Computer Science', 'SE131,ADS133', 1.0, 0.0, 3, '2nd'),
(41, 4, 'CSE3', 'CS Elective 3', NULL, 3.0, 0.0, 3, '2nd'),
(42, 4, 'GEE1', 'GE Elective 1', NULL, 3.0, 0.0, 3, '2nd'),
(43, 4, 'GEE2', 'GE Elective 2', NULL, 3.0, 0.0, 3, '2nd'),
(44, 4, 'GEE3', 'GE Elective 3', NULL, 3.0, 0.0, 3, '2nd'),
(47, 4, 'CSPRAC142', 'Practicum Industry Immersion', 'THESIS141', 3.0, 0.0, 4, '2nd'),
(48, 4, 'ETHICS101', 'Ethics', NULL, 3.0, 0.0, 4, '2nd'),
(49, 1, 'NC127', 'Networks and Communications', NULL, 3.0, 0.0, 1, '1st'),
(50, 1, 'CC101', 'Computer Programming 1', NULL, 3.0, 1.0, 1, '1st'),
(51, 1, 'CAS101', 'Purposive Communication', NULL, 3.0, 0.0, 1, '1st'),
(52, 1, 'MATH100', 'Mathematics in the Modern World', NULL, 3.0, 0.0, 1, '1st'),
(53, 1, 'CC102', 'Computer Programming 2', 'CC101', 3.0, 1.0, 1, '2nd'),
(54, 1, 'WD114', 'Web Development 1', NULL, 3.0, 0.0, 1, '2nd'),
(55, 1, 'PATHFIT2', 'Exercise-Based Fitness Activity', NULL, 2.0, 0.0, 1, '2nd'),
(56, 1, 'CW101', 'The Contemporary World', NULL, 3.0, 0.0, 1, '2nd'),
(57, 1, 'CC103', 'Data Structures and Algorithms', 'CC102', 3.0, 0.0, 2, '1st'),
(58, 1, 'MAD121', 'Mobile Application Development', NULL, 3.0, 0.0, 2, '1st'),
(59, 1, 'SIPP125', 'Social Issues and Professional Practices', NULL, 3.0, 0.0, 2, '1st'),
(60, 1, 'HIST100', 'Life and Works of Rizal', NULL, 3.0, 0.0, 2, '1st'),
(61, 1, 'CC105', 'Application Development and Emerging Technologies', 'CC103', 3.0, 0.0, 2, '2nd'),
(62, 1, 'PHILCON', 'Philippine Constitution', NULL, 3.0, 0.0, 2, '2nd'),
(63, 1, 'AO124', 'Architecture and Organization', NULL, 3.0, 0.0, 2, '2nd'),
(64, 1, 'DS111', 'Discrete Structures 1', NULL, 3.0, 0.0, 2, '2nd'),
(65, 1, 'SE131', 'Software Engineering 1', 'CC103', 3.0, 0.0, 3, '1st'),
(66, 1, 'ADS133', 'Advanced Database Systems', 'CC104', 3.0, 0.0, 3, '1st'),
(67, 1, 'CALC139', 'Calculus for Computer Science', NULL, 3.0, 0.0, 3, '1st'),
(68, 1, 'CSE1', 'CS Elective 1', NULL, 3.0, 0.0, 3, '1st'),
(69, 1, 'ES130', 'Embedded Systems', 'CC105', 3.0, 0.0, 3, '2nd'),
(70, 1, 'SE132', 'Software Engineering 2', 'SE131', 3.0, 0.0, 3, '2nd'),
(71, 1, 'IAS134', 'Information Assurance and Security', NULL, 2.0, 0.0, 3, '2nd'),
(72, 1, 'TW136', 'Technical Writing for Computer Science', NULL, 1.0, 0.0, 3, '2nd'),
(73, 1, 'THESIS139', 'CS Thesis 1', NULL, 3.0, 0.0, 3, 'Summer'),
(74, 1, 'THESIS141', 'CS Thesis 2', 'THESIS139', 3.0, 0.0, 4, '1st'),
(75, 1, 'A&H100', 'Art Appreciation', NULL, 3.0, 0.0, 4, '1st'),
(76, 1, 'CSPRAC142', 'Practicum Industry Immersion', NULL, 3.0, 0.0, 4, '2nd'),
(77, 1, 'ETHICS101', 'Ethics', NULL, 3.0, 0.0, 4, '2nd'),
(78, 5, 'IT101', 'Fundamentals of IT', NULL, 3.0, 0.0, 1, '1st'),
(79, 5, 'CC101', 'Computer Programming 1', NULL, 3.0, 1.0, 1, '1st'),
(80, 5, 'CAS101', 'Purposive Communication', NULL, 3.0, 0.0, 1, '1st'),
(81, 5, 'MATH100', 'Mathematics in the Modern World', NULL, 3.0, 0.0, 1, '1st'),
(82, 5, 'HIST100', 'Life and Works of Rizal', NULL, 3.0, 0.0, 1, '1st'),
(83, 5, 'CC102', 'Computer Programming 2', 'CC101', 3.0, 1.0, 1, '2nd'),
(84, 5, 'WD114', 'Web Development 1', NULL, 3.0, 0.0, 1, '2nd'),
(85, 5, 'DS111', 'Discrete Structures 1', NULL, 3.0, 0.0, 1, '2nd'),
(86, 5, 'STS100', 'Science, Technology and Society', NULL, 3.0, 0.0, 1, '2nd'),
(87, 5, 'PATHFIT1', 'Movement Competency Training', NULL, 2.0, 0.0, 1, '2nd'),
(88, 5, 'CC103', 'Data Structures and Algorithms', 'CC102', 3.0, 0.0, 2, '1st'),
(89, 5, 'CC104', 'Information Management', NULL, 3.0, 0.0, 2, '1st'),
(90, 5, 'MAD121', 'Mobile Application Development', NULL, 3.0, 0.0, 2, '1st'),
(91, 5, 'NC127', 'Networks and Communications', NULL, 3.0, 0.0, 2, '1st'),
(92, 5, 'SIPP125', 'Social Issues and Professional Practices', NULL, 3.0, 0.0, 2, '1st'),
(93, 5, 'CC105', 'Application Development and Emerging Technologies', 'CC103', 3.0, 0.0, 2, '2nd'),
(94, 5, 'AO124', 'Architecture and Organization', NULL, 3.0, 0.0, 2, '2nd'),
(95, 5, 'PHILCON', 'Philippine Constitution', NULL, 3.0, 0.0, 2, '2nd'),
(96, 5, 'CW101', 'The Contemporary World', NULL, 3.0, 0.0, 2, '2nd'),
(97, 5, 'DS118', 'Discrete Structures 2', 'DS111', 3.0, 0.0, 2, '2nd'),
(98, 2, 'IT101', 'Fundamentals of IT', NULL, 3.0, 0.0, 1, '1st'),
(99, 2, 'CC101', 'Computer Programming 1', NULL, 3.0, 1.0, 1, '1st'),
(100, 2, 'CAS101', 'Purposive Communication', NULL, 3.0, 0.0, 1, '1st'),
(101, 2, 'MATH100', 'Mathematics in the Modern World', NULL, 3.0, 0.0, 1, '1st'),
(102, 2, 'HIST100', 'Life and Works of Rizal', NULL, 3.0, 0.0, 1, '1st'),
(103, 2, 'WD114', 'Web Development 1', NULL, 3.0, 0.0, 1, '2nd'),
(104, 2, 'CC102', 'Computer Programming 2', 'CC101', 3.0, 1.0, 1, '2nd'),
(105, 2, 'STS100', 'Science, Technology and Society', NULL, 3.0, 0.0, 1, '2nd'),
(106, 2, 'DS111', 'Discrete Structures 1', NULL, 3.0, 0.0, 1, '2nd'),
(107, 2, 'PATHFIT1', 'Movement Competency Training', NULL, 2.0, 0.0, 1, '2nd'),
(108, 2, 'CC104', 'Information Management', NULL, 3.0, 0.0, 2, '1st'),
(109, 2, 'MAD121', 'Mobile Application Development', NULL, 3.0, 0.0, 2, '1st'),
(110, 2, 'CC103', 'Data Structures and Algorithms', 'CC102', 3.0, 0.0, 2, '1st'),
(111, 2, 'NC127', 'Networks and Communications', NULL, 3.0, 0.0, 2, '1st'),
(112, 2, 'SIPP125', 'Social Issues and Professional Practices', NULL, 3.0, 0.0, 2, '1st'),
(113, 2, 'CC105', 'Application Development and Emerging Technologies', 'CC103', 3.0, 0.0, 2, '2nd'),
(114, 2, 'AO124', 'Architecture and Organization', NULL, 3.0, 0.0, 2, '2nd'),
(115, 2, 'PHILCON', 'Philippine Constitution', NULL, 3.0, 0.0, 2, '2nd'),
(116, 2, 'CW101', 'The Contemporary World', NULL, 3.0, 0.0, 2, '2nd'),
(117, 2, 'DS118', 'Discrete Structures 2', 'DS111', 3.0, 0.0, 2, '2nd'),
(118, 2, 'CC106', 'Advanced Web Development', 'WD114', 3.0, 1.0, 3, '1st'),
(119, 2, 'CSE1', 'CS Elective 1', NULL, 3.0, 0.0, 3, '1st'),
(120, 2, 'SE131', 'Software Engineering 1', 'CC103', 3.0, 0.0, 3, '1st'),
(121, 2, 'ADS133', 'Advanced Database Systems', 'CC104', 3.0, 0.0, 3, '1st'),
(122, 2, 'CALC139', 'Calculus for Computer Science', NULL, 3.0, 0.0, 3, '1st'),
(123, 2, 'SE132', 'Software Engineering 2', 'SE131', 3.0, 0.0, 3, '2nd'),
(124, 2, 'IAS134', 'Information Assurance and Security', NULL, 2.0, 0.0, 3, '2nd'),
(125, 2, 'TW136', 'Technical Writing for Computer Science', NULL, 1.0, 0.0, 3, '2nd'),
(126, 2, 'CS139', 'Introduction to Artificial Intelligence', NULL, 3.0, 0.0, 3, '2nd'),
(127, 2, 'ES130', 'Embedded Systems', NULL, 3.0, 0.0, 3, '2nd'),
(128, 6, 'CC100', 'Introduction to Computing', NULL, 3.0, 0.0, 1, '1st'),
(129, 6, 'CC101', 'Computer Programming 1', NULL, 3.0, 1.0, 1, '1st'),
(130, 6, 'DS111', 'Discrete Structures 1', NULL, 3.0, 0.0, 1, '1st'),
(131, 6, 'CAS101', 'Purposive Communication', NULL, 3.0, 0.0, 1, '1st'),
(132, 6, 'MATH100', 'Mathematics in the Modern World', NULL, 3.0, 0.0, 1, '1st'),
(133, 6, 'HIST100', 'Life and Works of Rizal', NULL, 3.0, 0.0, 1, '1st'),
(134, 6, 'US101', 'Understanding the Self', NULL, 3.0, 0.0, 1, '1st'),
(135, 6, 'PATHFIT1', 'Movement Competency Training', NULL, 2.0, 0.0, 1, '1st'),
(136, 6, 'CC102', 'Computer Programming 2', 'CC101', 3.0, 1.0, 1, '2nd'),
(137, 6, 'OOP112', 'Object-Oriented Programming', NULL, 3.0, 0.0, 1, '2nd'),
(138, 6, 'WD114', 'Web Development 1', NULL, 3.0, 0.0, 1, '2nd'),
(139, 6, 'HCI116', 'Human Computer Interaction', NULL, 3.0, 0.0, 1, '2nd'),
(140, 6, 'DS118', 'Discrete Structures 2', 'DS111', 3.0, 0.0, 1, '2nd'),
(141, 6, 'HIST101', 'Readings in Philippine History', NULL, 3.0, 0.0, 1, '2nd'),
(142, 6, 'STS100', 'Science, Technology and Society', NULL, 3.0, 0.0, 1, '2nd'),
(143, 6, 'PATHFIT2', 'Exercise-Based Fitness Activity', NULL, 2.0, 0.0, 1, '2nd'),
(144, 6, 'CC103', 'Data Structures and Algorithms', 'CC102', 3.0, 0.0, 2, '1st'),
(145, 6, 'CC104', 'Information Management', NULL, 3.0, 0.0, 2, '1st'),
(146, 6, 'MAD121', 'Mobile Application Development', NULL, 3.0, 0.0, 2, '1st'),
(147, 6, 'WD123', 'Web Development 2', NULL, 3.0, 0.0, 2, '1st'),
(148, 6, 'SIPP125', 'Social Issues and Professional Practices', NULL, 3.0, 0.0, 2, '1st'),
(149, 6, 'NC127', 'Networks and Communications', NULL, 3.0, 0.0, 2, '1st'),
(150, 6, 'PATHFIT3', 'Martial Arts 1', NULL, 2.0, 0.0, 2, '1st'),
(151, 6, 'CC105', 'Application Development and Emerging Technologies', 'CC103', 3.0, 0.0, 2, '2nd'),
(152, 6, 'ACTINT122', 'ACT Internship', NULL, 6.0, 0.0, 2, '2nd'),
(153, 6, 'AO124', 'Architecture and Organization', NULL, 3.0, 0.0, 2, '2nd'),
(154, 6, 'PHILCON', 'Philippine Constitution', NULL, 3.0, 0.0, 2, '2nd'),
(155, 6, 'CW101', 'The Contemporary World', NULL, 3.0, 0.0, 2, '2nd'),
(156, 6, 'PATHFIT4', 'Martial Arts 2', NULL, 2.0, 0.0, 2, '2nd'),
(157, 3, 'CC100', 'Introduction to Computing', NULL, 3.0, 0.0, 1, '1st'),
(158, 3, 'MATH100', 'Mathematics in the Modern World', NULL, 3.0, 0.0, 1, '1st'),
(159, 3, 'PATHFIT1', 'Movement Competency Training', NULL, 2.0, 0.0, 1, '1st'),
(160, 3, 'CAS101', 'Purposive Communication', NULL, 3.0, 0.0, 1, '1st'),
(161, 3, 'HIST100', 'Life and Works of Rizal', NULL, 3.0, 0.0, 1, '1st'),
(162, 3, 'DS111', 'Discrete Structures 1', NULL, 3.0, 0.0, 1, '2nd'),
(163, 3, 'OOP112', 'Object-Oriented Programming', NULL, 3.0, 0.0, 1, '2nd'),
(164, 3, 'WD114', 'Web Development 1', NULL, 3.0, 0.0, 1, '2nd'),
(165, 3, 'HCI116', 'Human Computer Interaction', NULL, 3.0, 0.0, 1, '2nd'),
(166, 3, 'DS118', 'Discrete Structures 2', 'DS111', 3.0, 0.0, 1, '2nd'),
(167, 3, 'CC103', 'Data Structures and Algorithms', 'CC102', 3.0, 0.0, 2, '1st'),
(168, 3, 'MAD121', 'Mobile Application Development', NULL, 3.0, 0.0, 2, '1st'),
(169, 3, 'SIPP125', 'Social Issues and Professional Practices', NULL, 3.0, 0.0, 2, '1st'),
(170, 3, 'CC104', 'Information Management', NULL, 3.0, 0.0, 2, '1st'),
(171, 3, 'NC127', 'Networks and Communications', NULL, 3.0, 0.0, 2, '1st'),
(172, 3, 'CC105', 'Application Development and Emerging Technologies', 'CC103', 3.0, 0.0, 2, '2nd'),
(173, 3, 'PATHFIT3', 'Martial Arts 1', NULL, 2.0, 0.0, 2, '2nd'),
(174, 3, 'ACTINT122', 'ACT Internship', NULL, 6.0, 0.0, 2, '2nd'),
(175, 3, 'PHILCON', 'Philippine Constitution', NULL, 3.0, 0.0, 2, '2nd'),
(176, 3, 'CW101', 'The Contemporary World', NULL, 3.0, 0.0, 2, '2nd');

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

CREATE TABLE `rating` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'FK to user table',
  `subject_id` int(11) NOT NULL COMMENT 'FK to prospectus table',
  `application_id` int(11) DEFAULT NULL COMMENT 'FK to student_applications table',
  `rating` decimal(4,2) DEFAULT NULL CHECK (`rating` between 0.00 and 5.00),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL COMMENT 'Role name (Admin, Staff, Student)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `name`) VALUES
(1, 'user'),
(2, 'staff'),
(3, 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `student_applications`
--

CREATE TABLE `student_applications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'FK to user table',
  `adviser_id` int(11) DEFAULT NULL COMMENT 'FK to adviser table',
  `school_year` varchar(20) NOT NULL COMMENT 'Academic year',
  `semester` varchar(20) NOT NULL COMMENT 'Semester applied for',
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `rejection_reason` text DEFAULT NULL,
  `total_rating` decimal(4,2) DEFAULT NULL COMMENT 'Calculated GPA or equivalent',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `dean_lister_period_id` int(11) DEFAULT NULL COMMENT 'FK to dean_lister_application_periods',
  `image_proof` longblob DEFAULT NULL COMMENT 'Path to the uploaded proof document'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_applications`
--

INSERT INTO `student_applications` (`id`, `user_id`, `adviser_id`, `school_year`, `semester`, `status`, `rejection_reason`, `total_rating`, `created_at`, `updated_at`, `dean_lister_period_id`, `image_proof`) VALUES
(2, 2, 1, '2024-2025', '2nd', 'Approved', NULL, 1.60, '2024-12-14 21:59:37', '2024-12-25 01:49:29', 2, NULL),
(3, 3, 1, '2023-2024', '1st', 'Approved', NULL, 1.70, '2023-12-14 21:59:37', '2024-12-25 01:49:29', 3, NULL),
(4, 7, 1, '2023-2024', '2nd', 'Approved', NULL, 1.80, '2023-12-14 21:59:37', '2024-12-25 01:49:29', 4, NULL),
(5, 8, 1, '2024-2025', '1st', 'Approved', NULL, 1.90, '2024-12-14 21:59:37', '2024-12-25 01:49:29', 1, NULL),
(6, 9, 1, '2024-2025', '2nd', 'Approved', NULL, 1.85, '2024-12-14 21:59:37', '2024-12-25 01:49:29', 2, NULL),
(7, 10, 1, '2023-2024', '1st', 'Approved', NULL, 1.95, '2023-12-14 21:59:37', '2024-12-25 01:49:29', 3, NULL),
(8, 11, 1, '2023-2024', '2nd', 'Approved', NULL, 1.75, '2023-12-14 21:59:37', '2024-12-25 01:49:29', 4, NULL),
(9, 12, 1, '2023-2024', '1st', 'Approved', NULL, 1.85, '2023-12-14 21:59:37', '2024-12-25 01:49:29', 3, NULL),
(10, 13, 2, '2024-2025', '2nd', 'Approved', NULL, 1.60, '2024-12-14 21:59:37', '2024-12-25 01:49:29', 2, NULL),
(11, 14, 2, '2023-2024', '1st', 'Approved', NULL, 1.70, '2023-12-14 21:59:37', '2024-12-25 01:49:29', 3, NULL),
(12, 15, 2, '2023-2024', '2nd', 'Approved', NULL, 1.80, '2023-12-14 21:59:37', '2024-12-25 01:49:29', 4, NULL),
(13, 16, 2, '2024-2025', '1st', 'Approved', NULL, 1.90, '2024-12-14 21:59:37', '2024-12-25 01:49:29', 1, NULL),
(14, 17, 2, '2024-2025', '2nd', 'Approved', NULL, 1.85, '2024-12-14 21:59:37', '2024-12-25 01:49:29', 2, NULL),
(15, 18, 2, '2023-2024', '1st', 'Approved', NULL, 1.75, '2023-12-14 21:59:37', '2024-12-25 01:49:29', 3, NULL),
(16, 19, 2, '2023-2024', '2nd', 'Approved', NULL, 1.80, '2023-12-14 21:59:37', '2024-12-25 01:49:29', 4, NULL),
(17, 20, 2, '2023-2024', '1st', 'Approved', NULL, 1.90, '2023-12-14 21:59:37', '2024-12-25 01:49:29', 1, NULL),
(18, 21, 2, '2023-2024', '2nd', 'Approved', NULL, 1.85, '2023-12-14 21:59:37', '2024-12-25 01:49:29', 2, NULL),
(19, 22, 2, '2023-2024', '1st', 'Approved', NULL, 1.95, '2023-12-14 21:59:37', '2024-12-25 01:49:29', 3, NULL),
(20, 23, 2, '2023-2024', '2nd', 'Approved', NULL, 1.85, '2023-12-14 21:59:37', '2024-12-25 01:49:29', 4, NULL),
(21, 24, 2, '2023-2024', '1st', 'Approved', NULL, 1.75, '2023-12-14 21:59:37', '2024-12-25 01:49:29', 1, NULL),
(22, 25, 3, '2024-2025', '2nd', 'Approved', NULL, 1.60, '2024-12-14 21:59:37', '2024-12-25 01:49:29', 2, NULL),
(23, 26, 3, '2023-2024', '1st', 'Approved', NULL, 1.70, '2023-12-14 21:59:37', '2024-12-25 01:49:29', 3, NULL),
(24, 27, 3, '2023-2024', '2nd', 'Approved', NULL, 1.80, '2023-12-14 21:59:37', '2024-12-25 01:49:29', 4, NULL),
(25, 28, 3, '2023-2024', '1st', 'Approved', NULL, 1.85, '2024-12-14 21:59:37', '2024-12-25 01:49:29', 3, NULL),
(26, 29, 3, '2024-2025', '2nd', 'Approved', NULL, 1.75, '2024-12-14 21:59:37', '2024-12-25 01:49:29', 2, NULL),
(27, 30, 3, '2023-2024', '2nd', 'Approved', NULL, 1.95, '2023-12-14 21:59:37', '2024-12-25 01:49:29', 4, NULL),
(31, 4, 1, '2024-2025', '1st', 'Approved', NULL, 1.65, '2024-12-14 21:59:37', '2024-12-25 01:49:29', 1, NULL),
(32, 5, 1, '2024-2025', '2nd', 'Approved', NULL, 1.75, '2024-12-14 21:59:37', '2024-12-25 01:49:29', 2, NULL),
(33, 6, 1, '2023-2024', '1st', 'Approved', NULL, 1.80, '2023-12-14 21:59:37', '2024-12-25 01:49:29', 3, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `identifier` varchar(20) NOT NULL COMMENT 'Unique identifier for user (e.g., Student Number)',
  `firstname` varchar(100) NOT NULL,
  `middlename` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `curriculum_id` int(11) DEFAULT NULL COMMENT 'FK to curriculum table for students',
  `department_id` int(11) DEFAULT NULL COMMENT 'FK to department table for staff/advisers',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `identifier`, `firstname`, `middlename`, `lastname`, `email`, `curriculum_id`, `department_id`, `created_at`) VALUES
(1, '202400001', 'John', 'A.', 'Doe', 'hz202400001@wmsu.edu.ph', 4, 1, '2024-12-15 05:59:37'),
(2, '202400002', 'Jane', 'B.', 'Smith', 'hz202400002@wmsu.edu.ph', 4, 1, '2024-12-15 05:59:37'),
(3, '202400003', 'Alex', 'C.', 'Johnson', 'hz202400003@wmsu.edu.ph', 4, 1, '2024-12-15 05:59:37'),
(4, '202300004', 'Emily', 'D.', 'Brown', 'hz202300004@wmsu.edu.ph', 4, 1, '2024-12-15 05:59:37'),
(5, '202300005', 'Chris', 'E.', 'Davis', 'hz202300005@wmsu.edu.ph', 4, 1, '2024-12-15 05:59:37'),
(6, '202300006', 'Olivia', 'F.', 'Miller', 'hz202300006@wmsu.edu.ph', 4, 1, '2024-12-15 05:59:37'),
(7, '202200007', 'Liam', 'G.', 'Wilson', 'hz202200007@wmsu.edu.ph', 1, 1, '2024-12-15 05:59:37'),
(8, '202200008', 'Sophia', 'H.', 'Moore', 'hz202200008@wmsu.edu.ph', 1, 1, '2024-12-15 05:59:37'),
(9, '202200009', 'Noah', 'I.', 'Taylor', 'hz202200009@wmsu.edu.ph', 1, 1, '2024-12-15 05:59:37'),
(10, '202100010', 'Ava', 'J.', 'Anderson', 'hz202100010@wmsu.edu.ph', 1, 1, '2024-12-15 05:59:37'),
(11, '202100011', 'Ethan', 'K.', 'Martinez', 'hz202100011@wmsu.edu.ph', 1, 1, '2024-12-15 05:59:37'),
(12, '202100012', 'Mia', 'L.', 'Hernandez', 'hz202100012@wmsu.edu.ph', 1, 1, '2024-12-15 05:59:37'),
(13, '202400013', 'Jacob', 'M.', 'Lee', 'hz202400013@wmsu.edu.ph', 5, 2, '2024-12-15 05:59:37'),
(14, '202400014', 'Zoe', 'N.', 'Clark', 'hz202400014@wmsu.edu.ph', 5, 2, '2024-12-15 05:59:37'),
(15, '202400015', 'Luke', 'O.', 'Lewis', 'hz202400015@wmsu.edu.ph', 5, 2, '2024-12-15 05:59:37'),
(16, '202300016', 'Ella', 'P.', 'Walker', 'hz202300016@wmsu.edu.ph', 5, 2, '2024-12-15 05:59:37'),
(17, '202300017', 'James', 'Q.', 'Hall', 'hz202300017@wmsu.edu.ph', 5, 2, '2024-12-15 05:59:37'),
(18, '202300018', 'Luna', 'R.', 'Allen', 'hz202300018@wmsu.edu.ph', 5, 2, '2024-12-15 05:59:37'),
(19, '202200019', 'Logan', 'S.', 'King', 'hz202200019@wmsu.edu.ph', 2, 2, '2024-12-15 05:59:37'),
(20, '202200020', 'Riley', 'T.', 'Wright', 'hz202200020@wmsu.edu.ph', 2, 2, '2024-12-15 05:59:37'),
(21, '202200021', 'Carter', 'U.', 'Hill', 'hz202200021@wmsu.edu.ph', 2, 2, '2024-12-15 05:59:37'),
(22, '202100022', 'Aria', 'V.', 'Scott', 'hz202100022@wmsu.edu.ph', 2, 2, '2024-12-15 05:59:37'),
(23, '202100023', 'Levi', 'W.', 'Green', 'hz202100023@wmsu.edu.ph', 2, 2, '2024-12-15 05:59:37'),
(24, '202100024', 'Nora', 'X.', 'Adams', 'hz202100024@wmsu.edu.ph', 2, 2, '2024-12-15 05:59:37'),
(25, '202400025', 'Daniel', 'Y.', 'Baker', 'hz202400025@wmsu.edu.ph', 6, 3, '2024-12-15 05:59:37'),
(26, '202400026', 'Abigail', 'Z.', 'Gonzalez', 'hz202400026@wmsu.edu.ph', 6, 3, '2024-12-15 05:59:37'),
(27, '202400027', 'Jackson', 'AA.', 'Nelson', 'hz202400027@wmsu.edu.ph', 6, 3, '2024-12-15 05:59:37'),
(28, '202300028', 'Charlotte', 'BB.', 'Carter', 'hz202300028@wmsu.edu.ph', 6, 3, '2024-12-15 05:59:37'),
(29, '202300029', 'Elijah', 'CC.', 'Mitchell', 'hz202300029@wmsu.edu.ph', 6, 3, '2024-12-15 05:59:37'),
(30, '202300030', 'Scarlett', 'DD.', 'Perez', 'hz202300030@wmsu.edu.ph', 6, 3, '2024-12-15 05:59:37'),
(31, '000000001', 'Michael', 'A.', 'Carter', 'staff1@wmsu.edu.ph', NULL, 1, '2024-12-21 05:59:37'),
(32, '000000002', 'Sarah', 'B.', 'Evans', 'staff2@wmsu.edu.ph', NULL, 1, '2024-12-21 05:59:37'),
(33, '000000003', 'David', 'C.', 'Martin', 'staff3@wmsu.edu.ph', NULL, 1, '2024-12-21 05:59:37'),
(34, '000000004', 'Jessica', 'D.', 'Garcia', 'staff4@wmsu.edu.ph', NULL, 2, '2024-12-21 05:59:37'),
(35, '000000005', 'James', 'E.', 'Roberts', 'staff5@wmsu.edu.ph', NULL, 2, '2024-12-21 05:59:37'),
(36, '000000006', 'Emma', 'F.', 'Moore', 'staff6@wmsu.edu.ph', NULL, 2, '2024-12-21 05:59:37'),
(37, '000000007', 'Oliver', 'G.', 'Clark', 'staff7@wmsu.edu.ph', NULL, 3, '2024-12-21 05:59:37'),
(38, '000000008', 'Sophia', 'H.', 'Lewis', 'staff8@wmsu.edu.ph', NULL, 3, '2024-12-21 05:59:37'),
(39, '000000009', 'Benjamin', 'I.', 'Walker', 'staff9@wmsu.edu.ph', NULL, 3, '2024-12-21 05:59:37'),
(40, '000000010', 'Lucas', 'J.', 'Adams', 'admin1@wmsu.edu.ph', NULL, 1, '2024-12-21 05:59:37'),
(41, '000000011', 'Mia', 'K.', 'Perez', 'admin2@wmsu.edu.ph', NULL, 2, '2024-12-21 05:59:37'),
(42, '000000012', 'Ethan', 'L.', 'Hill', 'admin3@wmsu.edu.ph', NULL, 3, '2024-12-21 05:59:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `adviser`
--
ALTER TABLE `adviser`
  ADD PRIMARY KEY (`adviser_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `course_name` (`course_name`);

--
-- Indexes for table `curriculum`
--
ALTER TABLE `curriculum`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `course_id` (`course_id`,`version`);

--
-- Indexes for table `dean_lister_application_periods`
--
ALTER TABLE `dean_lister_application_periods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `year` (`year`,`semester`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prospectus`
--
ALTER TABLE `prospectus`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `curriculum_id` (`curriculum_id`,`subject_code`,`year_level`,`semester`);

--
-- Indexes for table `rating`
--
ALTER TABLE `rating`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_applications`
--
ALTER TABLE `student_applications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`school_year`,`semester`),
  ADD KEY `adviser_id` (`adviser_id`),
  ADD KEY `dean_lister_period_id` (`dean_lister_period_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `curriculum_id` (`curriculum_id`),
  ADD KEY `department_id` (`department_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `adviser`
--
ALTER TABLE `adviser`
  MODIFY `adviser_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `curriculum`
--
ALTER TABLE `curriculum`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `dean_lister_application_periods`
--
ALTER TABLE `dean_lister_application_periods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `prospectus`
--
ALTER TABLE `prospectus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=177;

--
-- AUTO_INCREMENT for table `rating`
--
ALTER TABLE `rating`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `student_applications`
--
ALTER TABLE `student_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account`
--
ALTER TABLE `account`
  ADD CONSTRAINT `account_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `adviser`
--
ALTER TABLE `adviser`
  ADD CONSTRAINT `adviser_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `adviser_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `department` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `audit_logs_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `curriculum`
--
ALTER TABLE `curriculum`
  ADD CONSTRAINT `curriculum_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `prospectus`
--
ALTER TABLE `prospectus`
  ADD CONSTRAINT `prospectus_ibfk_1` FOREIGN KEY (`curriculum_id`) REFERENCES `curriculum` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rating`
--
ALTER TABLE `rating`
  ADD CONSTRAINT `rating_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rating_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `prospectus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rating_ibfk_3` FOREIGN KEY (`application_id`) REFERENCES `student_applications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_applications`
--
ALTER TABLE `student_applications`
  ADD CONSTRAINT `student_applications_ibfk_1` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_applications_ibfk_2` FOREIGN KEY (`dean_lister_period_id`) REFERENCES `dean_lister_application_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`curriculum_id`) REFERENCES `curriculum` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `user_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `department` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
