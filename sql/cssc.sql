-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 10, 2024 at 03:33 AM
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
-- Table structure for table `Awards`
--

CREATE TABLE `Awards` (
  `award_id` int(11) NOT NULL,
  `award_name` varchar(100) NOT NULL,
  `min_gpa` decimal(5,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Courses`
--

CREATE TABLE `Courses` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `credits` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Grades`
--

CREATE TABLE `Grades` (
  `grade_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `grade` decimal(3,2) NOT NULL,
  `verification_status` enum('PENDING','VERIFIED','REJECTED') DEFAULT 'PENDING',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('ADMIN','INSTRUCTOR','STUDENT') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `User_Awards`
--

CREATE TABLE `User_Awards` (
  `user_award_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `award_id` int(11) NOT NULL,
  `awarded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `User_Shifting`
--

CREATE TABLE `User_Shifting` (
  `recommendation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `recommended_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Awards`
--
ALTER TABLE `Awards`
  ADD PRIMARY KEY (`award_id`);

--
-- Indexes for table `Courses`
--
ALTER TABLE `Courses`
  ADD PRIMARY KEY (`course_id`);

--
-- Indexes for table `Grades`
--
ALTER TABLE `Grades`
  ADD PRIMARY KEY (`grade_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `User_Awards`
--
ALTER TABLE `User_Awards`
  ADD PRIMARY KEY (`user_award_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `award_id` (`award_id`);

--
-- Indexes for table `User_Shifting`
--
ALTER TABLE `User_Shifting`
  ADD PRIMARY KEY (`recommendation_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Awards`
--
ALTER TABLE `Awards`
  MODIFY `award_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Courses`
--
ALTER TABLE `Courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Grades`
--
ALTER TABLE `Grades`
  MODIFY `grade_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `User_Awards`
--
ALTER TABLE `User_Awards`
  MODIFY `user_award_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `User_Shifting`
--
ALTER TABLE `User_Shifting`
  MODIFY `recommendation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Grades`
--
ALTER TABLE `Grades`
  ADD CONSTRAINT `Grades_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `Grades_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `Courses` (`course_id`);

--
-- Constraints for table `User_Awards`
--
ALTER TABLE `User_Awards`
  ADD CONSTRAINT `User_Awards_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `User_Awards_ibfk_2` FOREIGN KEY (`award_id`) REFERENCES `Awards` (`award_id`);

--
-- Constraints for table `User_Shifting`
--
ALTER TABLE `User_Shifting`
  ADD CONSTRAINT `User_Shifting_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
