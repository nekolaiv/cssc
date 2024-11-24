-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 24, 2024 at 06:12 AM
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
-- Table structure for table `Admin_Accounts`
--

CREATE TABLE `Admin_Accounts` (
  `admin_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(10) NOT NULL DEFAULT 'admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Admin_Accounts`
--

INSERT INTO `Admin_Accounts` (`admin_id`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin1@wmsu.edu.ph', '$2y$10$PryNK6ZXjpzbPEC4/VRojudk68PV5QgtJ0/ZoTa/Az4XTLNsgAyYG', 'admin', '2024-10-18 05:57:37', '2024-10-18 05:57:37');

-- --------------------------------------------------------

--
-- Table structure for table `Advisers`
--

CREATE TABLE `Advisers` (
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
-- Dumping data for table `Advisers`
--

INSERT INTO `Advisers` (`id`, `first_name`, `last_name`, `middle_name`, `email`, `course`, `year_level`, `created_at`, `updated_at`) VALUES
(1, 'Salimar', 'Tahil', '', 'salimar_tahil@wmsu.edu.ph', 'Computer Science', 1, '2024-11-15 23:41:27', '2024-11-15 23:41:27');

-- --------------------------------------------------------

--
-- Table structure for table `Current_Academic_Term`
--

CREATE TABLE `Current_Academic_Term` (
  `id` int(11) NOT NULL,
  `school_year` varchar(9) NOT NULL,
  `semester` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Current_Academic_Term`
--

INSERT INTO `Current_Academic_Term` (`id`, `school_year`, `semester`) VALUES
(1, '2023-2024', 'First');

-- --------------------------------------------------------

--
-- Table structure for table `Registered_Students`
--

CREATE TABLE `Registered_Students` (
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
-- Dumping data for table `Registered_Students`
--

INSERT INTO `Registered_Students` (`user_id`, `student_id`, `email`, `password`, `first_name`, `last_name`, `middle_name`, `course`, `year_level`, `section`, `role`, `adviser_name`, `status`, `created_at`, `updated_at`) VALUES
(1, '202300001', 'hz202300001@wmsu.edu.ph', '$2y$10$9q2ry8UU3Kamlb701RQc/eE1cX1TXrvAl1TkTy3Q8O31TFlg9ak.C', 'John', 'Doe', 'Michael', 'Computer Science', 1, 'A', 'student', 'Salimar Tahil', 'Not Submitted', '2024-11-15 23:50:57', '2024-11-19 00:36:47'),
(2, '202300002', 'hz202300002@wmsu.edu.ph', '$2y$10$Y3.xh.E1fqoYtBwJEih0BeWCTXmpnUCCvP/xBm.h.6ZB0gw4B3Ep6', 'Jane', 'Smith', 'Elizabeth', 'Computer Science', 2, 'B', 'student', ' ', 'Not Submitted', '2024-11-21 02:08:50', '2024-11-21 02:08:50');

-- --------------------------------------------------------

--
-- Table structure for table `Staff_Accounts`
--

CREATE TABLE `Staff_Accounts` (
  `staff_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(10) NOT NULL DEFAULT 'staff',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Students_Unverified_Entries`
--

CREATE TABLE `Students_Unverified_Entries` (
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

--
-- Dumping data for table `Students_Unverified_Entries`
--

INSERT INTO `Students_Unverified_Entries` (`id`, `student_id`, `email`, `fullname`, `course`, `year_level`, `section`, `adviser_name`, `gwa`, `image_proof`, `created_at`, `updated_at`) VALUES
(1, '202300001', 'hz202300001@wmsu.edu.ph', 'Doe, John Michael', 'Computer Science', 1, 'A', 'Salimar Tahil', 2.00, NULL, '2024-11-16 14:19:55', '2024-11-22 05:11:12');

-- --------------------------------------------------------

--
-- Table structure for table `Students_Verified_Entries`
--

CREATE TABLE `Students_Verified_Entries` (
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

--
-- Dumping data for table `Students_Verified_Entries`
--

INSERT INTO `Students_Verified_Entries` (`id`, `student_id`, `email`, `fullname`, `course`, `year_level`, `section`, `adviser_name`, `gwa`, `image_proof`, `created_at`, `updated_at`) VALUES
(1, '202300001', 'student1@wmsu.edu.ph', 'Doe, John Michael', 'Computer Science', 1, 'BSCS1A', 'Tahil, Salimar', 1.20, NULL, '2024-11-21 06:43:56', '2024-11-21 06:43:56'),
(2, '202300002', 'student2@wmsu.edu.ph', 'Doe, Jane Michelle', 'Computer Science', 1, 'BSCS1A', 'Tahil, Salimar', 1.00, NULL, '2024-11-21 06:44:55', '2024-11-21 06:44:55');

-- --------------------------------------------------------

--
-- Table structure for table `Unregistered_Students`
--

CREATE TABLE `Unregistered_Students` (
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
-- Dumping data for table `Unregistered_Students`
--

INSERT INTO `Unregistered_Students` (`id`, `student_id`, `email`, `first_name`, `last_name`, `middle_name`, `course`, `year_level`, `section`, `created_at`, `updated_at`) VALUES
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
-- Indexes for table `Admin_Accounts`
--
ALTER TABLE `Admin_Accounts`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `Advisers`
--
ALTER TABLE `Advisers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `Current_Academic_Term`
--
ALTER TABLE `Current_Academic_Term`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Registered_Students`
--
ALTER TABLE `Registered_Students`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `student_id` (`student_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `Staff_Accounts`
--
ALTER TABLE `Staff_Accounts`
  ADD PRIMARY KEY (`staff_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `Students_Unverified_Entries`
--
ALTER TABLE `Students_Unverified_Entries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Students_Verified_Entries`
--
ALTER TABLE `Students_Verified_Entries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Unregistered_Students`
--
ALTER TABLE `Unregistered_Students`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Admin_Accounts`
--
ALTER TABLE `Admin_Accounts`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Advisers`
--
ALTER TABLE `Advisers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Current_Academic_Term`
--
ALTER TABLE `Current_Academic_Term`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Registered_Students`
--
ALTER TABLE `Registered_Students`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `Staff_Accounts`
--
ALTER TABLE `Staff_Accounts`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Students_Unverified_Entries`
--
ALTER TABLE `Students_Unverified_Entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Students_Verified_Entries`
--
ALTER TABLE `Students_Verified_Entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `Unregistered_Students`
--
ALTER TABLE `Unregistered_Students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
