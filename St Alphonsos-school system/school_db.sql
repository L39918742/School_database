-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 01, 2026 at 10:32 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `school_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `class_id` int(11) NOT NULL,
  `class_name` varchar(50) NOT NULL,
  `capacity` int(11) NOT NULL,
  `teacher_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`class_id`, `class_name`, `capacity`, `teacher_id`) VALUES
(4, 'Class 1', 20, 2),
(7, 'Class 3', 23, 9),
(8, 'Class 2', 10, 8);

-- --------------------------------------------------------

--
-- Table structure for table `parent_guardian`
--

CREATE TABLE `parent_guardian` (
  `parent_id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `address` varchar(150) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parent_guardian`
--

INSERT INTO `parent_guardian` (`parent_id`, `first_name`, `last_name`, `address`, `phone`, `email`) VALUES
(2, 'Rex', 'Belllen', NULL, '0347894565', 'rxy@gmail.com'),
(3, 'Unis', 'Joe', NULL, '0000000000', 'developer@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `pupil`
--

CREATE TABLE `pupil` (
  `pupil_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `date_of_birth` date NOT NULL,
  `address` varchar(150) DEFAULT NULL,
  `medical_info` text DEFAULT NULL,
  `class_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pupil`
--

INSERT INTO `pupil` (`pupil_id`, `first_name`, `last_name`, `date_of_birth`, `address`, `medical_info`, `class_id`) VALUES
(4, 'Tom', 'Cruise', '2026-02-24', NULL, NULL, 4),
(6, 'Doltan', 'Wayne', '2026-02-08', NULL, NULL, 8);

-- --------------------------------------------------------

--
-- Table structure for table `pupil_parent`
--

CREATE TABLE `pupil_parent` (
  `pupil_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `relationship_type` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pupil_parent`
--

INSERT INTO `pupil_parent` (`pupil_id`, `parent_id`, `relationship_type`) VALUES
(4, 2, 'Father');

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

CREATE TABLE `teacher` (
  `teacher_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `address` varchar(150) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `annual_salary` decimal(10,2) DEFAULT NULL,
  `background_check_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`teacher_id`, `first_name`, `last_name`, `address`, `phone`, `email`, `annual_salary`, `background_check_date`) VALUES
(2, 'Jhon ', 'Doe', NULL, NULL, NULL, 1500.00, NULL),
(8, 'Helyn ', 'Way', NULL, NULL, NULL, 1200.00, NULL),
(9, 'Well', 'Bruce', NULL, NULL, NULL, 1000.00, NULL),
(10, 'Steve ', 'Jobs', NULL, NULL, NULL, 2200.00, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`class_id`),
  ADD UNIQUE KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `parent_guardian`
--
ALTER TABLE `parent_guardian`
  ADD PRIMARY KEY (`parent_id`);

--
-- Indexes for table `pupil`
--
ALTER TABLE `pupil`
  ADD PRIMARY KEY (`pupil_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `pupil_parent`
--
ALTER TABLE `pupil_parent`
  ADD PRIMARY KEY (`pupil_id`,`parent_id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`teacher_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `class`
--
ALTER TABLE `class`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `parent_guardian`
--
ALTER TABLE `parent_guardian`
  MODIFY `parent_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pupil`
--
ALTER TABLE `pupil`
  MODIFY `pupil_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `teacher`
--
ALTER TABLE `teacher`
  MODIFY `teacher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `class`
--
ALTER TABLE `class`
  ADD CONSTRAINT `class_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`teacher_id`);

--
-- Constraints for table `pupil`
--
ALTER TABLE `pupil`
  ADD CONSTRAINT `pupil_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`);

--
-- Constraints for table `pupil_parent`
--
ALTER TABLE `pupil_parent`
  ADD CONSTRAINT `pupil_parent_ibfk_1` FOREIGN KEY (`pupil_id`) REFERENCES `pupil` (`pupil_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pupil_parent_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `parent_guardian` (`parent_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
