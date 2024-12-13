-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 01, 2024 at 02:41 AM
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
-- Database: `cssc`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_accounts`
--

CREATE TABLE `admin_accounts` (
  `admin_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(10) NOT NULL DEFAULT 'admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_accounts`
--

INSERT INTO `admin_accounts` (`admin_id`, `email`, `password`, `role`, `created_at`, `updated_at`, `first_name`, `last_name`, `middle_name`) VALUES
(1, 'admin1@wmsu.edu.ph', '$2y$10$..H1Ak/R7PtbvOtl19mecOCmGdOPaMFSD3q0IahJlsNySRMWtmsT2', 'admin', '2024-12-01 01:17:07', '2024-12-01 01:17:07', 'Emman Nicholas Blabe', 'Idulsa', 'Bautista'),
(2, 'admin2@wmsu.edu.ph', '$2y$10$UIe/wwQbsK83g95e9LmR8O4a8Up52021NiasOpEr5afVy9ojmLvym', 'admin', '2024-12-01 01:25:39', '2024-12-01 01:25:39', 'Ahmad', 'Yahiya', 'Feyaz');

-- --------------------------------------------------------

--
-- Table structure for table `advisers`
--

CREATE TABLE `advisers` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `course` varchar(100) NOT NULL,
  `year_level` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `advisers`
--

INSERT INTO `advisers` (`id`, `first_name`, `last_name`, `middle_name`, `email`, `course`, `year_level`, `created_at`, `updated_at`) VALUES
(1, 'Salimar', 'Tahil', 'Bendanillo', 'salimar_tahil@wmsu.edu.ph', 'Computer Science', 1, '2024-11-15 23:41:27', '2024-12-01 01:32:15'),
(2, 'Rhamirl', 'Jaafar', 'Balang', 'rhamirl_jaafar@wmsu.edu.ph', 'Information Technology', 3, '2024-12-01 01:34:05', '2024-12-01 01:35:29'),
(3, 'Jaydee', 'Ballaho', '', 'jaydee_ballaho@wmsu.edu.ph', 'Associate in Computer Technology', 2, '2024-12-01 01:35:12', '2024-12-01 01:35:12');

-- --------------------------------------------------------

--
-- Table structure for table `current_academic_term`
--

CREATE TABLE `current_academic_term` (
  `id` int(11) NOT NULL,
  `school_year` varchar(9) NOT NULL,
  `semester` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `current_academic_term`
--

INSERT INTO `current_academic_term` (`id`, `school_year`, `semester`) VALUES
(1, '2023-2024', 'First');

-- --------------------------------------------------------

--
-- Table structure for table `student_accounts`
--

CREATE TABLE `student_accounts` (
  `user_id` int(11) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `course` varchar(100) NOT NULL,
  `year_level` int(11) NOT NULL,
  `section` varchar(10) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'student',
  `adviser_name` varchar(255) DEFAULT NULL,
  `status` enum('Not Submitted','Pending','Verified','Need Revision') NOT NULL DEFAULT 'Not Submitted',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_accounts`
--

INSERT INTO `student_accounts` (`user_id`, `student_id`, `email`, `password`, `first_name`, `last_name`, `middle_name`, `course`, `year_level`, `section`, `role`, `adviser_name`, `status`, `created_at`, `updated_at`) VALUES
(1, '202300001', 'hz202300001@wmsu.edu.ph', '$2y$10$cYsXuMVdmyw21xY0hzALM.6pY0V5Z4LRefXvd9ZwBP3Av332gG4j.', 'John', 'Doe', 'Michael', 'Computer Science', 1, 'A', 'student', 'Tahil, Salimar Bendanillo', 'Not Submitted', '2024-12-01 01:37:59', '2024-12-01 01:37:59'),
(2, '202300013', 'hz202300013@wmsu.edu.ph', '$2y$10$Auk9ddjtFfMa3KWna6IK.OJPNOeu8sEtGmNIo8EFDYLCwkb8JPbZ2', 'Robert', 'Johnson', 'William', 'Information Technology', 3, 'C', 'student', 'Jaafar, Rhamirl Balang', 'Not Submitted', '2024-12-01 01:38:48', '2024-12-01 01:38:48'),
(3, '202300022', 'hz202300022@wmsu.edu.ph', '$2y$10$n52fyKth8WXJZ79dYeEMee4FdlLHIi9kHeTRhdatKYDzElYymk2bi', 'Jane', 'Smith', 'Elizabeth', 'Associate in Computer Technology', 2, 'B', 'student', 'Ballaho, Jaydee ', 'Not Submitted', '2024-12-01 01:39:07', '2024-12-01 01:39:07');

-- --------------------------------------------------------

--
-- Table structure for table `staff_accounts`
--

CREATE TABLE `staff_accounts` (
  `staff_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(10) NOT NULL DEFAULT 'staff',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff_accounts`
--

INSERT INTO `staff_accounts` (`staff_id`, `email`, `password`, `role`, `created_at`, `updated_at`, `first_name`, `last_name`, `middle_name`) VALUES
(1, 'staff1@wmsu.edu.ph', '$2y$10$tR0ZU0Z9ap//gqbggxRAA.kVQji4u.qqc.9oCee8AvrH8CWEonAku', 'staff', '2024-12-01 01:29:50', '2024-12-01 01:29:50', 'Emman Nicholas Blabe', 'Idulsa', 'Bautista'),
(2, 'staff2@wmsu.edu.ph', '$2y$10$YrF2JCr9ifhvDWH.8VOzleHu9168AK1kiUZoN2kY0GMfWYyCw8ISq', 'staff', '2024-12-01 01:30:25', '2024-12-01 01:30:25', 'Ahmad', 'Yahiya', 'Feyaz');

-- --------------------------------------------------------

--
-- Table structure for table `students_unverified_entries`
--

CREATE TABLE `students_unverified_entries` (
  `id` int(11) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `course` varchar(100) NOT NULL,
  `year_level` int(11) NOT NULL,
  `section` varchar(50) NOT NULL,
  `adviser_name` varchar(255) NOT NULL,
  `gwa` decimal(4,2) NOT NULL,
  `image_proof` blob DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students_verified_entries`
--

CREATE TABLE `students_verified_entries` (
  `id` int(11) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `course` varchar(100) NOT NULL,
  `year_level` int(11) NOT NULL,
  `section` varchar(50) NOT NULL,
  `adviser_name` varchar(255) NOT NULL,
  `gwa` decimal(4,2) NOT NULL,
  `image_proof` blob DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `unregistered_students`
--

CREATE TABLE `unregistered_students` (
  `id` int(11) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `course` varchar(100) NOT NULL,
  `year_level` int(11) NOT NULL,
  `section` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `unregistered_students`
--

INSERT INTO `unregistered_students` (`id`, `student_id`, `email`, `first_name`, `last_name`, `middle_name`, `course`, `year_level`, `section`, `created_at`, `updated_at`) VALUES
(1, '202300001', 'hz202300001@wmsu.edu.ph', 'John', 'Doe', 'Michael', 'Computer Science', 1, 'A', '2024-11-15 08:16:04', '2024-11-16 02:59:33'),
(2, '202300002', 'hz202300002@wmsu.edu.ph', 'Jane', 'Smith', 'Elizabeth', 'Computer Science', 2, 'B', '2024-11-15 08:16:04', '2024-11-16 02:59:37'),
(3, '202300003', 'hz202300003@wmsu.edu.ph', 'Robert', 'Johnson', 'William', 'Computer Science', 3, 'C', '2024-11-15 08:16:04', '2024-11-16 02:59:41'),
(4, '202300004', 'hz202300004@wmsu.edu.ph', 'Emily', 'Williams', 'Grace', 'Computer Science', 1, 'A', '2024-11-15 08:16:04', '2024-11-16 02:59:45'),
(5, '202300005', 'hz202300005@wmsu.edu.ph', 'Michael', 'Brown', 'James', 'Computer Science', 2, 'B', '2024-11-15 08:16:04', '2024-11-16 02:59:48'),
(6, '202300006', 'hz202300006@wmsu.edu.ph', 'Sarah', 'Jones', 'Anne', 'Computer Science', 3, 'C', '2024-11-15 08:16:04', '2024-11-16 02:59:51'),
(7, '202300007', 'hz202300007@wmsu.edu.ph', 'David', 'Garcia', 'Luis', 'Computer Science', 1, 'A', '2024-11-15 08:16:04', '2024-11-16 02:59:55'),
(8, '202300008', 'hz202300008@wmsu.edu.ph', 'Linda', 'Martinez', 'Sofia', 'Computer Science', 2, 'B', '2024-11-15 08:16:04', '2024-11-16 02:59:58'),
(9, '202300009', 'hz202300009@wmsu.edu.ph', 'James', 'Hernandez', 'Carlos', 'Computer Science', 3, 'C', '2024-11-15 08:16:04', '2024-11-16 03:00:01'),
(10, '202300010', 'hz202300010@wmsu.edu.ph', 'Laura', 'Lopez', 'Maria', 'Computer Science', 1, 'A', '2024-11-15 08:16:04', '2024-11-16 03:00:04'),
(11, '202300021', 'hz202300021@wmsu.edu.ph', 'John', 'Doe', 'Michael', 'Associate in Computer Technology', 1, 'A', '2024-11-15 00:16:30', '2024-11-15 18:58:52'),
(12, '202300022', 'hz202300022@wmsu.edu.ph', 'Jane', 'Smith', 'Elizabeth', 'Associate in Computer Technology', 2, 'B', '2024-11-15 00:16:30', '2024-11-15 18:58:59'),
(13, '202300023', 'hz202300023@wmsu.edu.ph', 'Robert', 'Johnson', 'William', 'Associate in Computer Technology', 3, 'C', '2024-11-15 00:16:30', '2024-11-15 18:59:06'),
(14, '202300024', 'hz202300024@wmsu.edu.ph', 'Emily', 'Williams', 'Grace', 'Associate in Computer Technology', 1, 'A', '2024-11-15 00:16:30', '2024-11-15 18:59:09'),
(15, 'hz202300025', 'hz202300025@wmsu.edu.ph', 'Michael', 'Brown', 'James', 'Associate in Computer Technology', 2, 'B', '2024-11-15 00:16:30', '2024-11-15 00:16:30'),
(16, '202300026', 'hz202300026@wmsu.edu.ph', 'Sarah', 'Jones', 'Anne', 'Associate in Computer Technology', 3, 'C', '2024-11-15 00:16:30', '2024-11-15 18:59:14'),
(17, '202300027', 'hz202300027@wmsu.edu.ph', 'David', 'Garcia', 'Luis', 'Associate in Computer Technology', 1, 'A', '2024-11-15 00:16:30', '2024-11-15 18:59:17'),
(18, '202300028', 'hz202300028@wmsu.edu.ph', 'Linda', 'Martinez', 'Sofia', 'Associate in Computer Technology', 2, 'B', '2024-11-15 00:16:30', '2024-11-15 18:59:20'),
(19, '202300029', 'hz202300029@wmsu.edu.ph', 'James', 'Hernandez', 'Carlos', 'Associate in Computer Technology', 3, 'C', '2024-11-15 00:16:30', '2024-11-15 18:59:23'),
(20, '202300030', 'hz202300030@wmsu.edu.ph', 'Laura', 'Lopez', 'Maria', 'Associate in Computer Technology', 1, 'A', '2024-11-15 00:16:30', '2024-11-15 18:59:28'),
(21, '202300011', 'hz202300011@wmsu.edu.ph', 'John', 'Doe', 'Michael', 'Information Technology', 1, 'A', '2024-11-15 00:16:20', '2024-11-15 19:00:10'),
(22, '202300012', 'hz202300012@wmsu.edu.ph', 'Jane', 'Smith', 'Elizabeth', 'Information Technology', 2, 'B', '2024-11-15 00:16:20', '2024-11-15 19:00:13'),
(23, '202300013', 'hz202300013@wmsu.edu.ph', 'Robert', 'Johnson', 'William', 'Information Technology', 3, 'C', '2024-11-15 00:16:20', '2024-11-15 19:00:16'),
(24, '202300014', 'hz202300014@wmsu.edu.ph', 'Emily', 'Williams', 'Grace', 'Information Technology', 1, 'A', '2024-11-15 00:16:20', '2024-11-15 19:00:19'),
(25, '202300015', 'hz202300015@wmsu.edu.ph', 'Michael', 'Brown', 'James', 'Information Technology', 2, 'B', '2024-11-15 00:16:20', '2024-11-15 19:00:22'),
(26, '202300016', 'hz202300016@wmsu.edu.ph', 'Sarah', 'Jones', 'Anne', 'Information Technology', 3, 'C', '2024-11-15 00:16:20', '2024-11-15 19:00:25'),
(27, '202300017', 'hz202300017@wmsu.edu.ph', 'David', 'Garcia', 'Luis', 'Information Technology', 1, 'A', '2024-11-15 00:16:20', '2024-11-15 19:00:27'),
(28, '202300018', 'hz202300018@wmsu.edu.ph', 'Linda', 'Martinez', 'Sofia', 'Information Technology', 2, 'B', '2024-11-15 00:16:20', '2024-11-15 19:00:31'),
(29, '202300019', 'hz202300019@wmsu.edu.ph', 'James', 'Hernandez', 'Carlos', 'Information Technology', 3, 'C', '2024-11-15 00:16:20', '2024-11-15 19:00:34'),
(30, '202300020', 'hz202300020@wmsu.edu.ph', 'Laura', 'Lopez', 'Maria', 'Information Technology', 1, 'A', '2024-11-15 00:16:20', '2024-11-15 19:00:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_accounts`
--
ALTER TABLE `admin_accounts`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `advisers`
--
ALTER TABLE `advisers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `current_academic_term`
--
ALTER TABLE `current_academic_term`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_accounts`
--
ALTER TABLE `student_accounts`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `student_id` (`student_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `staff_accounts`
--
ALTER TABLE `staff_accounts`
  ADD PRIMARY KEY (`staff_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `students_unverified_entries`
--
ALTER TABLE `students_unverified_entries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students_verified_entries`
--
ALTER TABLE `students_verified_entries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `unregistered_students`
--
ALTER TABLE `unregistered_students`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_accounts`
--
ALTER TABLE `admin_accounts`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `advisers`
--
ALTER TABLE `advisers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `current_academic_term`
--
ALTER TABLE `current_academic_term`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `student_accounts`
--
ALTER TABLE `student_accounts`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `staff_accounts`
--
ALTER TABLE `staff_accounts`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `students_unverified_entries`
--
ALTER TABLE `students_unverified_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students_verified_entries`
--
ALTER TABLE `students_verified_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `unregistered_students`
--
ALTER TABLE `unregistered_students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
