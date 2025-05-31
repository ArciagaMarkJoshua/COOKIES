-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 31, 2025 at 11:42 AM
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
-- Database: `studentprofiledb`
--

-- --------------------------------------------------------

--
-- Table structure for table `academicyears`
--

CREATE TABLE `academicyears` (
  `Code` varchar(50) NOT NULL,
  `AcademicYear` varchar(50) NOT NULL,
  `StartDate` date NOT NULL,
  `EndDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `academicyears`
--

INSERT INTO `academicyears` (`Code`, `AcademicYear`, `StartDate`, `EndDate`) VALUES
('AY2023', '2023-2024', '2023-06-01', '2024-03-31'),
('AY2025', '2024-2025', '2025-01-01', '2025-05-31');

-- --------------------------------------------------------

--
-- Table structure for table `accounttypes`
--

CREATE TABLE `accounttypes` (
  `Code` varchar(50) NOT NULL,
  `AccountName` enum('Student','Staff') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounttypes`
--

INSERT INTO `accounttypes` (`Code`, `AccountName`) VALUES
('ADMIN', 'Staff'),
('STAFF', 'Staff'),
('STU', 'Student');

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `user_type` enum('Student','Staff') NOT NULL,
  `action` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clearance`
--

CREATE TABLE `clearance` (
  `clearance_id` int(11) NOT NULL,
  `studentNo` varchar(20) NOT NULL,
  `status` enum('Pending','Completed') DEFAULT 'Pending',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clearance`
--

INSERT INTO `clearance` (`clearance_id`, `studentNo`, `status`, `updated_at`, `created_at`) VALUES
(9, '2022-02243', 'Pending', '2025-05-03 12:51:22', '2025-05-03 12:51:22'),
(10, '2022-02249', 'Pending', '2025-04-30 05:54:49', '2025-04-30 05:54:49'),
(11, '2022-02248', 'Pending', '2025-04-30 05:55:56', '2025-04-30 05:55:56'),
(12, '2022-02251', 'Pending', '2025-04-30 05:56:05', '2025-04-30 05:56:05'),
(13, '2022-02250', 'Pending', '2025-04-30 05:56:16', '2025-04-30 05:56:16'),
(14, '2022-02253', 'Pending', '2025-04-30 05:56:24', '2025-04-30 05:56:24'),
(15, '2022-02252', 'Pending', '2025-04-30 05:56:47', '2025-04-30 05:56:47'),
(16, '2022-02247', 'Pending', '2025-04-30 05:56:56', '2025-04-30 05:56:56'),
(17, '1111', 'Pending', '2025-05-31 09:38:48', '2025-04-30 05:57:07'),
(18, '2022-02244', 'Completed', '2025-04-30 05:57:23', '2025-04-30 05:57:23'),
(19, '2022-02246', 'Pending', '2025-04-30 05:57:27', '2025-04-30 05:57:27'),
(20, '2022-02254', 'Pending', '2025-04-30 05:57:36', '2025-04-30 05:57:36'),
(21, '222222', 'Pending', '2025-05-03 12:52:25', '2025-05-03 12:52:25'),
(22, '0123', 'Pending', '2025-05-31 09:17:57', '2025-05-31 09:17:57');

-- --------------------------------------------------------

--
-- Table structure for table `clearance_requirements`
--

CREATE TABLE `clearance_requirements` (
  `requirement_id` int(11) NOT NULL,
  `requirement_name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `is_required` tinyint(1) DEFAULT 1,
  `department_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clearance_requirements`
--

INSERT INTO `clearance_requirements` (`requirement_id`, `requirement_name`, `description`, `is_required`, `department_id`) VALUES
(1, 'Library Clearance', 'Screenshot of the library page', 1, 1),
(2, 'Guidance Interview', 'photo id', 1, 2),
(3, 'Dean\'s Approval', 'Obtain clearance signature from the Dean', 1, 3),
(4, 'Financial Clearance', 'Settle all financial obligations', 1, 4),
(5, 'Registrar Clearance', 'Submit all required documents to registrar', 1, 5),
(6, 'Property Clearance', 'Return all school property', 1, 6),
(7, 'Student Council Clearance', 'Complete student organization obligations', 1, 7);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `DepartmentID` int(11) NOT NULL,
  `DepartmentName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`DepartmentID`, `DepartmentName`) VALUES
(1, 'College Library'),
(2, 'Guidance Office'),
(3, 'Office of the Dean'),
(4, 'Office of the Finance Director'),
(5, 'Office of the Registrar'),
(6, 'Property Custodian'),
(7, 'Student Council');

-- --------------------------------------------------------

--
-- Table structure for table `levels`
--

CREATE TABLE `levels` (
  `LevelID` int(11) NOT NULL,
  `LevelName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `levels`
--

INSERT INTO `levels` (`LevelID`, `LevelName`) VALUES
(1, '1st Year'),
(2, '2nd Year'),
(3, '3rd Year'),
(4, '4th Year'),
(5, '5th Year');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `user_type` enum('Student','Staff') NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `ProgramCode` varchar(50) NOT NULL,
  `ProgramTitle` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`ProgramCode`, `ProgramTitle`) VALUES
('BSCS', 'Bachelor of Science in Computer Science'),
('BSIS', 'Bachelor of Science in Information Systems'),
('BSIT', 'Bachelor of Science in Information Technology');

-- --------------------------------------------------------

--
-- Table structure for table `registration_requests`
--

CREATE TABLE `registration_requests` (
  `request_id` int(11) NOT NULL,
  `studentNo` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `middleName` varchar(50) DEFAULT NULL,
  `programCode` varchar(10) NOT NULL,
  `level` int(11) NOT NULL,
  `sectionCode` varchar(10) NOT NULL,
  `academicYear` varchar(20) NOT NULL,
  `semester` varchar(20) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `status` enum('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  `request_date` datetime NOT NULL,
  `processed_date` datetime DEFAULT NULL,
  `processed_by` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registration_requests`
--

INSERT INTO `registration_requests` (`request_id`, `studentNo`, `email`, `lastName`, `firstName`, `middleName`, `programCode`, `level`, `sectionCode`, `academicYear`, `semester`, `password_hash`, `status`, `request_date`, `processed_date`, `processed_by`, `notes`) VALUES
(1, '000', 'mark3@dyci.edu.ph', 'as', 'as', 'as', 'BSCS', 2, '1a', '2023-2024', 'Second Semester', '$2y$10$ttW4DYdrlzKO.UMyDOcOEOKCfbibjrbLy.PlliP79dVXeyWLzRGxO', 'Approved', '2025-05-31 17:11:20', '2025-05-31 17:11:27', 1, ''),
(2, '0123', 'request@dyci.edu.ph', 'alll', 'allll', 'asallll', 'BSCS', 1, '1b', '2023-2024', 'First Semester', '$2y$10$OGH0nlSb1vda5RR7FeWpmO/6L/oTrFjMQq7VFUSvYyH8wJcffPyOG', 'Approved', '2025-05-31 17:17:09', '2025-05-31 17:17:28', 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `SectionCode` varchar(50) NOT NULL,
  `SectionTitle` varchar(255) NOT NULL,
  `YearLevel` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`SectionCode`, `SectionTitle`, `YearLevel`) VALUES
('1a', '1A', 1),
('1b', '1B', 1),
('1c', '1C', 1),
('1d', '1D', 1),
('2a', '2A', 2),
('2b', '2B', 2),
('2c', '2C', 2),
('2d', '2D', 2),
('3a', '3A', 3),
('3b', '3B', 3),
('3c', '3C', 3),
('3d', '3D', 3),
('4a', '4A', 4),
('4b', '4B', 4),
('4c', '4C', 4),
('4d', '4D', 4),
('5a', '5A', 5),
('5b', '5B', 5),
('5c', '5C', 5),
('5d', '5D', 5);

-- --------------------------------------------------------

--
-- Table structure for table `semesters`
--

CREATE TABLE `semesters` (
  `Code` varchar(50) NOT NULL,
  `Semester` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `semesters`
--

INSERT INTO `semesters` (`Code`, `Semester`) VALUES
('S1', 'First Semester'),
('S2', 'Second Semester'),
('SU', 'Summer Semester');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `CtrlNo` int(11) NOT NULL,
  `StaffID` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `PasswordHash` varchar(255) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `Mname` varchar(50) DEFAULT NULL,
  `Department` varchar(100) NOT NULL,
  `AccountType` enum('Admin','Staff') NOT NULL,
  `LastLogin` timestamp NULL DEFAULT NULL,
  `IsActive` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`CtrlNo`, `StaffID`, `Username`, `Email`, `PasswordHash`, `LastName`, `FirstName`, `Mname`, `Department`, `AccountType`, `LastLogin`, `IsActive`) VALUES
(4, 1, 'admin', 'admin@dyci.edu.ph', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'ADMIN', 'Admin', 'Guidance Office', 'Admin', NULL, 1),
(5, 123, 'Mark', 'arciagamarkjoshua@gmail.com', '$2y$10$5VJsUzIrFPJJ4oPEmtqGVemYEqOqkOkd7GjbywkRN9jSWORsNKH4.', 'Arciaga', 'Mark Joshua', 'Perico', 'Office of the Dean', 'Staff', NULL, 1),
(6, 2, 'reanne', 'reannematias0909@gmail.com', '$2y$10$qkssuNUpR3GsYhLOfHO0KOwt985fS3pzNzesJAL5f7iSMgAGPJ4xq', 'Matias', 'Reeanne', 'Sta.rosa', 'Office of the Finance Director', 'Staff', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `CtrlNo` int(11) NOT NULL,
  `studentNo` varchar(20) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `PasswordHash` varchar(255) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `Mname` varchar(50) DEFAULT NULL,
  `ProgramCode` varchar(10) NOT NULL,
  `Level` int(11) NOT NULL,
  `SectionCode` varchar(10) NOT NULL,
  `AcademicYear` varchar(20) DEFAULT NULL,
  `Semester` varchar(20) DEFAULT NULL,
  `AccountType` varchar(20) NOT NULL DEFAULT 'Student',
  `LastLogin` timestamp NULL DEFAULT NULL,
  `IsActive` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`CtrlNo`, `studentNo`, `Username`, `Email`, `PasswordHash`, `LastName`, `FirstName`, `Mname`, `ProgramCode`, `Level`, `SectionCode`, `AcademicYear`, `Semester`, `AccountType`, `LastLogin`, `IsActive`) VALUES
(1, '2022-02243', 'Mark', 'arciagamarkjoshua@gmail.com', '$2y$10$pr5iEB45vyetpVyfEKnMD.78hzkX2tcwglyEhpSm4yN.VpdXvwBki', 'Arciaga', 'Mark Joshua', 'Perico', 'BSCS', 3, '2b', '2024-2025', 'Second Semester', 'STU', NULL, 1),
(2, '2022-02244', 'Reanne', 'reannematias@gmail.com', '$2y$10$pr5iEB45vyetpVyfEKnMD.78hzkX2tcwglyEhpSm4yN...', 'Matias', 'Reanne Ashley', 'Perico', 'BSCS', 3, '2b', '2024-2025', 'Second Semester', 'STU', NULL, 1),
(3, '1111', 'Andrei', 'andreimartinez@gmail.com', '$2y$10$6Q2HJoD0CbU4mTBcQM.7T.pcY8LgtGdX0uWrbqsKeGnUd.FKToNhm', 'Martinez', 'Andrei', 'Perico', 'BSCS', 3, '3c', '2024-2025', 'Second Semester', 'STU', NULL, 1),
(4, '2022-02246', 'Richmond', 'richmondpascual@gmail.com', '$2y$10$pr5iEB45vyetpVyfEKnMD.78hzkX2tcwglyEhpSm4yN...', 'Pascual', 'Richmond', 'Perico', 'BSCS', 3, '2b', '2024-2025', 'Second Semester', 'STU', NULL, 1),
(5, '2022-02247', 'Thirdy', 'thirdydoldol@gmail.com', '$2y$10$pr5iEB45vyetpVyfEKnMD.78hzkX2tcwglyEhpSm4yN...', 'DolDol', 'Thirdy', 'Perico', 'BSCS', 3, '2b', '2024-2025', 'Second Semester', 'STU', NULL, 1),
(6, '2022-02248', 'Patrick', 'patrickbaltaraz@gmail.com', '$2y$10$pr5iEB45vyetpVyfEKnMD.78hzkX2tcwglyEhpSm4yN...', 'Baltaraz', 'Patrick', 'Perico', 'BSCS', 3, '2b', '2024-2025', 'Second Semester', 'STU', NULL, 1),
(7, '2022-02249', 'Caroll', 'carollvillarreal@gmail.com', '$2y$10$pr5iEB45vyetpVyfEKnMD.78hzkX2tcwglyEhpSm4yN...', 'Villarreal', 'Caroll', 'Perico', 'BSCS', 3, '2b', '2024-2025', 'Second Semester', 'STU', NULL, 1),
(8, '2022-02250', 'Erica', 'ericacruz@gmail.com', '$2y$10$pr5iEB45vyetpVyfEKnMD.78hzkX2tcwglyEhpSm4yN...', 'Cruz', 'Erica', 'Perico', 'BSCS', 3, '2b', '2024-2025', 'Second Semester', 'STU', NULL, 1),
(9, '2022-02251', 'RC', 'rcchua@gmail.com', '$2y$10$pr5iEB45vyetpVyfEKnMD.78hzkX2tcwglyEhpSm4yN...', 'Chua', 'RC', 'Perico', 'BSCS', 3, '2b', '2024-2025', 'Second Semester', 'STU', NULL, 1),
(10, '2022-02252', 'Charles', 'charlesdelosantos@gmail.com', '$2y$10$pr5iEB45vyetpVyfEKnMD.78hzkX2tcwglyEhpSm4yN...', 'Delo Santos', 'Charles', 'Perico', 'BSCS', 3, '2b', '2024-2025', 'Second Semester', 'STU', NULL, 1),
(11, '2022-02253', 'Ram', 'ramdejesus@gmail.com', '$2y$10$pr5iEB45vyetpVyfEKnMD.78hzkX2tcwglyEhpSm4yN...', 'De Jesus', 'Ram Michael', 'Perico', 'BSCS', 3, '2b', '2024-2025', 'Second Semester', 'STU', NULL, 1),
(12, '2022-02254', 'Robinx', 'robinxsanpedro@gmail.com', '$2y$10$pr5iEB45vyetpVyfEKnMD.78hzkX2tcwglyEhpSm4yN...', 'San Pedro', 'Robinx', 'Perico', 'BSCS', 3, '2b', '2024-2025', 'Second Semester', 'STU', NULL, 1),
(13, '222222', 'Mark', 'dad@gmail.com', '$2y$10$IvTYXNHQC6CQu6V.0LcrZuVga/KHR1muS0YCAtiwI8TLXmoJrPN82', 'Arciaga', 'Mark Joshua', 'Perico', 'BSCS', 1, '3b', '2023-2024', 'Second Semester', 'STU', NULL, 1),
(15, '000', '000', 'mark3@dyci.edu.ph', '$2y$10$NEy26fMGV3P.LHMv5WGJd.FmVpsiXDwRsg1OWKGQr4U7JanMNSVIu', 'as', 'as', 'as', 'BSCS', 2, '1a', '2025', '1', 'Student', NULL, 1),
(17, '0123', '0123', 'request@dyci.edu.ph', '$2y$10$OGH0nlSb1vda5RR7FeWpmO/6L/oTrFjMQq7VFUSvYyH8wJcffPyOG', 'alll', 'allll', 'asallll', 'BSCS', 1, '1b', '2023-2024', 'First Semester', 'STU', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `student_clearance_status`
--

CREATE TABLE `student_clearance_status` (
  `id` int(11) NOT NULL,
  `studentNo` varchar(20) NOT NULL,
  `requirement_id` int(11) NOT NULL,
  `StaffID` int(11) NOT NULL,
  `status` enum('Pending','Approved') DEFAULT 'Pending',
  `approved_by` varchar(100) DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `date_approved` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_clearance_status`
--

INSERT INTO `student_clearance_status` (`id`, `studentNo`, `requirement_id`, `StaffID`, `status`, `approved_by`, `comments`, `date_approved`, `created_at`, `updated_at`) VALUES
(1, '1111', 1, 1, 'Pending', 'ADMIN Admin', NULL, NULL, '2025-05-07 07:14:27', '2025-05-31 09:38:48'),
(2, '1111', 2, 1, 'Pending', 'ADMIN Admin', NULL, NULL, '2025-05-07 07:14:30', '2025-05-31 09:38:54'),
(3, '1111', 3, 123, 'Approved', 'ADMIN Admin', NULL, NULL, '2025-05-07 07:14:31', '2025-05-31 09:39:46'),
(4, '1111', 4, 1, 'Pending', 'System Administrator', NULL, NULL, '2025-05-07 07:14:32', '2025-05-31 09:38:56'),
(5, '1111', 5, 1, 'Pending', 'ADMIN Admin', NULL, NULL, '2025-05-07 07:14:33', '2025-05-31 09:38:57'),
(6, '1111', 6, 1, 'Pending', 'ADMIN Admin', NULL, NULL, '2025-05-21 15:28:56', '2025-05-31 09:38:58'),
(7, '1111', 7, 1, 'Pending', 'System Administrator', NULL, NULL, '2025-04-30 06:09:59', '2025-05-31 09:38:59'),
(8, '2022-02243', 1, 123, 'Approved', 'Mark Joshua Arciaga', NULL, NULL, '2025-05-03 12:51:58', '2025-05-31 09:31:06'),
(9, '2022-02243', 2, 1, 'Approved', 'ADMIN Admin', NULL, NULL, '2025-05-07 04:00:39', '2025-05-31 09:31:06'),
(10, '2022-02243', 3, 1, 'Approved', 'ADMIN Admin', NULL, NULL, '2025-05-21 15:48:13', '2025-05-31 09:31:06'),
(11, '2022-02243', 4, 1, 'Pending', NULL, NULL, NULL, '2025-05-03 12:51:24', '2025-05-03 12:51:24'),
(12, '2022-02243', 5, 1, 'Pending', NULL, NULL, NULL, '2025-05-03 12:51:25', '2025-05-03 12:51:25'),
(13, '2022-02243', 6, 1, 'Pending', NULL, NULL, NULL, '2025-05-03 12:51:26', '2025-05-03 12:51:26'),
(14, '2022-02243', 7, 1, 'Pending', NULL, NULL, NULL, '2025-05-03 12:51:26', '2025-05-03 12:51:26'),
(15, '2022-02244', 1, 1, 'Approved', 'ADMIN Admin', NULL, NULL, '2025-04-30 05:57:23', '2025-05-31 09:31:06'),
(16, '2022-02244', 2, 1, 'Approved', 'ADMIN Admin', NULL, NULL, '2025-04-30 05:57:19', '2025-05-31 09:31:06'),
(17, '2022-02244', 3, 1, 'Approved', 'ADMIN Admin', NULL, NULL, '2025-04-30 05:57:20', '2025-05-31 09:31:06'),
(18, '2022-02244', 4, 1, 'Approved', 'ADMIN Admin', NULL, NULL, '2025-04-30 05:57:20', '2025-05-31 09:31:06'),
(19, '2022-02244', 5, 1, 'Approved', 'ADMIN Admin', NULL, NULL, '2025-04-30 05:57:22', '2025-05-31 09:31:06'),
(20, '2022-02244', 6, 1, 'Approved', 'ADMIN Admin', NULL, NULL, '2025-04-30 05:57:21', '2025-05-31 09:31:06'),
(21, '2022-02244', 7, 1, 'Approved', 'ADMIN Admin', NULL, NULL, '2025-04-30 05:57:22', '2025-05-31 09:31:06'),
(22, '0123', 1, 1, 'Pending', 'ADMIN Admin', NULL, NULL, '2025-05-31 09:17:57', '2025-05-31 09:31:28'),
(23, '0123', 2, 1, 'Pending', 'ADMIN Admin', NULL, NULL, '2025-05-31 09:17:59', '2025-05-31 09:31:29'),
(24, '0123', 3, 1, 'Pending', NULL, NULL, NULL, '2025-05-31 09:18:01', '2025-05-31 09:31:30'),
(25, '0123', 4, 1, 'Pending', NULL, NULL, NULL, '2025-05-31 09:18:02', '2025-05-31 09:18:02'),
(26, '0123', 5, 1, 'Pending', NULL, NULL, NULL, '2025-05-31 09:18:04', '2025-05-31 09:31:32'),
(27, '0123', 6, 1, 'Pending', NULL, NULL, NULL, '2025-05-31 09:18:06', '2025-05-31 09:31:34'),
(28, '0123', 7, 1, 'Pending', NULL, NULL, NULL, '2025-05-31 09:18:07', '2025-05-31 09:31:35');

--
-- Triggers `student_clearance_status`
--
DELIMITER $$
CREATE TRIGGER `after_clearance_status_update` AFTER UPDATE ON `student_clearance_status` FOR EACH ROW BEGIN
    DECLARE total_requirements INT;
    DECLARE approved_requirements INT;
    
    -- Count total requirements
    SELECT COUNT(*) INTO total_requirements FROM clearance_requirements;
    
    -- Count approved requirements for this student
    SELECT COUNT(*) INTO approved_requirements 
    FROM student_clearance_status 
    WHERE studentNo = NEW.studentNo AND status = 'Approved';
    
    -- Update overall clearance status
    IF approved_requirements = total_requirements THEN
        UPDATE clearance SET status = 'Completed' WHERE studentNo = NEW.studentNo;
    ELSE
        UPDATE clearance SET status = 'Pending' WHERE studentNo = NEW.studentNo;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `student_requirement_descriptions`
--

CREATE TABLE `student_requirement_descriptions` (
  `id` int(11) NOT NULL,
  `studentNo` varchar(20) NOT NULL,
  `requirement_id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_requirement_descriptions`
--

INSERT INTO `student_requirement_descriptions` (`id`, `studentNo`, `requirement_id`, `description`, `created_at`, `updated_at`) VALUES
(11, '0123', 1, 'sss', '2025-05-31 09:18:19', '2025-05-31 09:23:24'),
(12, '1111', 1, 'ss', '2025-05-31 09:32:29', '2025-05-31 09:32:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academicyears`
--
ALTER TABLE `academicyears`
  ADD PRIMARY KEY (`Code`);

--
-- Indexes for table `accounttypes`
--
ALTER TABLE `accounttypes`
  ADD PRIMARY KEY (`Code`);

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `user_type` (`user_type`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `clearance`
--
ALTER TABLE `clearance`
  ADD PRIMARY KEY (`clearance_id`),
  ADD UNIQUE KEY `studentNo` (`studentNo`);

--
-- Indexes for table `clearance_requirements`
--
ALTER TABLE `clearance_requirements`
  ADD PRIMARY KEY (`requirement_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`DepartmentID`),
  ADD UNIQUE KEY `DepartmentName` (`DepartmentName`);

--
-- Indexes for table `levels`
--
ALTER TABLE `levels`
  ADD PRIMARY KEY (`LevelID`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `user_type` (`user_type`),
  ADD KEY `is_read` (`is_read`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`ProgramCode`);

--
-- Indexes for table `registration_requests`
--
ALTER TABLE `registration_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD UNIQUE KEY `studentNo` (`studentNo`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `processed_by` (`processed_by`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`SectionCode`),
  ADD KEY `YearLevel` (`YearLevel`);

--
-- Indexes for table `semesters`
--
ALTER TABLE `semesters`
  ADD PRIMARY KEY (`Code`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`CtrlNo`),
  ADD UNIQUE KEY `StaffID` (`StaffID`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD KEY `Department` (`Department`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`CtrlNo`),
  ADD UNIQUE KEY `studentNo` (`studentNo`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD KEY `ProgramCode` (`ProgramCode`),
  ADD KEY `Level` (`Level`),
  ADD KEY `SectionCode` (`SectionCode`);

--
-- Indexes for table `student_clearance_status`
--
ALTER TABLE `student_clearance_status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_requirement` (`studentNo`,`requirement_id`),
  ADD KEY `requirement_id` (`requirement_id`),
  ADD KEY `StaffID` (`StaffID`),
  ADD KEY `idx_approved_by` (`approved_by`);

--
-- Indexes for table `student_requirement_descriptions`
--
ALTER TABLE `student_requirement_descriptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_requirement` (`studentNo`,`requirement_id`),
  ADD KEY `requirement_id` (`requirement_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clearance`
--
ALTER TABLE `clearance`
  MODIFY `clearance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `clearance_requirements`
--
ALTER TABLE `clearance_requirements`
  MODIFY `requirement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `DepartmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `levels`
--
ALTER TABLE `levels`
  MODIFY `LevelID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `registration_requests`
--
ALTER TABLE `registration_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `CtrlNo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `CtrlNo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `student_clearance_status`
--
ALTER TABLE `student_clearance_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `student_requirement_descriptions`
--
ALTER TABLE `student_requirement_descriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `clearance`
--
ALTER TABLE `clearance`
  ADD CONSTRAINT `clearance_ibfk_1` FOREIGN KEY (`studentNo`) REFERENCES `students` (`studentNo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `clearance_requirements`
--
ALTER TABLE `clearance_requirements`
  ADD CONSTRAINT `clearance_requirements_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`DepartmentID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `registration_requests`
--
ALTER TABLE `registration_requests`
  ADD CONSTRAINT `registration_requests_ibfk_1` FOREIGN KEY (`processed_by`) REFERENCES `staff` (`StaffID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `sections`
--
ALTER TABLE `sections`
  ADD CONSTRAINT `sections_ibfk_1` FOREIGN KEY (`YearLevel`) REFERENCES `levels` (`LevelID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`ProgramCode`) REFERENCES `programs` (`ProgramCode`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `students_ibfk_2` FOREIGN KEY (`Level`) REFERENCES `levels` (`LevelID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `students_ibfk_3` FOREIGN KEY (`SectionCode`) REFERENCES `sections` (`SectionCode`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student_clearance_status`
--
ALTER TABLE `student_clearance_status`
  ADD CONSTRAINT `student_clearance_status_ibfk_1` FOREIGN KEY (`studentNo`) REFERENCES `students` (`studentNo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_clearance_status_ibfk_2` FOREIGN KEY (`requirement_id`) REFERENCES `clearance_requirements` (`requirement_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_clearance_status_ibfk_3` FOREIGN KEY (`StaffID`) REFERENCES `staff` (`StaffID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student_requirement_descriptions`
--
ALTER TABLE `student_requirement_descriptions`
  ADD CONSTRAINT `student_requirement_descriptions_ibfk_1` FOREIGN KEY (`studentNo`) REFERENCES `students` (`studentNo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_requirement_descriptions_ibfk_2` FOREIGN KEY (`requirement_id`) REFERENCES `clearance_requirements` (`requirement_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
