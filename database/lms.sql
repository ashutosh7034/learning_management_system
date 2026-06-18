-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 01, 2026 at 11:19 AM
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
-- Database: `lms`
--

-- --------------------------------------------------------

--
-- Table structure for table `lms_audit_log`
--

CREATE TABLE `lms_audit_log` (
  `audit_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action_type` varchar(100) NOT NULL,
  `affected_table` varchar(100) DEFAULT NULL,
  `affected_record` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `username` varchar(200) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `browser_user_agent` text DEFAULT NULL,
  `session_duration_seconds` int(11) DEFAULT NULL,
  `logout_at` timestamp NULL DEFAULT NULL,
  `performed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lms_audit_log`
--

INSERT INTO `lms_audit_log` (`audit_id`, `user_id`, `action_type`, `affected_table`, `affected_record`, `description`, `username`, `ip_address`, `browser_user_agent`, `session_duration_seconds`, `logout_at`, `performed_at`) VALUES
(34, 1, 'LOGIN_SUCCESS', 'lms_login', 1, 'User \'superadmin@tcetmumbai.in\' logged in successfully with role  from IP ::1. Browser: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'superadmin@tcetmumbai.in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-30 08:45:32'),
(35, 1, 'LOGIN_SUCCESS', 'lms_login', 1, 'User \'superadmin@tcetmumbai.in\' logged in successfully with role 1 from IP ::1. Browser: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'superadmin@tcetmumbai.in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-30 08:48:59'),
(36, 1, 'LOGIN_SUCCESS', 'lms_login', 1, 'User \'superadmin@tcetmumbai.in\' logged in successfully with role  from IP ::1. Browser: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'superadmin@tcetmumbai.in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-30 09:14:55'),
(37, 1, 'LOGIN_SUCCESS', 'lms_login', 1, 'User \'superadmin@tcetmumbai.in\' logged in successfully with role  from IP ::1. Browser: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'superadmin@tcetmumbai.in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-30 09:16:19'),
(38, 1, 'LOGIN_SUCCESS', 'lms_login', 1, 'User \'superadmin@tcetmumbai.in\' logged in successfully with role  from IP ::1. Browser: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'superadmin@tcetmumbai.in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-30 09:16:23'),
(39, 1, 'LOGIN_SUCCESS', 'lms_login', 1, 'User \'superadmin@tcetmumbai.in\' logged in successfully with role 1 from IP ::1. Browser: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'superadmin@tcetmumbai.in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-30 09:25:12'),
(40, 1, 'LOGIN_SUCCESS', 'lms_login', 1, 'User \'superadmin@tcetmumbai.in\' logged in successfully with role 1 from IP ::1. Browser: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'superadmin@tcetmumbai.in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-30 09:25:14'),
(41, 1, 'LOGIN_SUCCESS', 'lms_login', 1, 'User \'superadmin@tcetmumbai.in\' logged in successfully with role 1 from IP ::1. Browser: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'superadmin@tcetmumbai.in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-30 09:26:45'),
(42, 1, 'LOGIN_SUCCESS', 'lms_login', 1, 'User \'superadmin@tcetmumbai.in\' logged in successfully with role 1 from IP ::1. Browser: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'superadmin@tcetmumbai.in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-30 09:28:49'),
(43, 1, 'LOGIN_SUCCESS', 'lms_login', 1, 'User \'superadmin@tcetmumbai.in\' logged in successfully with role 1 from IP ::1. Browser: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'superadmin@tcetmumbai.in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-30 09:28:52'),
(44, 2, 'LOGIN_SUCCESS', 'lms_login', 2, 'User \'admin@tcetmumbai.in\' logged in successfully with role 2 from IP ::1. Browser: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'admin@tcetmumbai.in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-30 09:29:00'),
(45, 1, 'LOGIN_SUCCESS', 'lms_login', 1, 'User \'superadmin@tcetmumbai.in\' logged in successfully with role 1 from IP ::1. Browser: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'superadmin@tcetmumbai.in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-30 09:30:27'),
(46, 1, 'LOGIN_SUCCESS', 'lms_login', 1, 'User \'superadmin@tcetmumbai.in\' logged in successfully with role 1 from IP ::1. Browser: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'superadmin@tcetmumbai.in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-30 09:30:30'),
(47, 1, 'LOGIN_SUCCESS', 'lms_login', 1, 'User \'superadmin@tcetmumbai.in\' logged in successfully with role 1 from IP ::1. Browser: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'superadmin@tcetmumbai.in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-30 09:30:48'),
(48, 1, 'LOGIN_SUCCESS', 'lms_login', 1, 'User \'superadmin@tcetmumbai.in\' logged in successfully with role 1 from IP ::1. Browser: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'superadmin@tcetmumbai.in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-30 09:31:33'),
(49, 1, 'LOGIN_SUCCESS', 'lms_login', 1, 'User \'superadmin@tcetmumbai.in\' logged in successfully with role 1 from IP ::1. Browser: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'superadmin@tcetmumbai.in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-30 09:31:36'),
(50, 1, 'LOGIN_SUCCESS', 'lms_login', 1, 'User \'superadmin@tcetmumbai.in\' logged in successfully with role 1 from IP ::1. Browser: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'superadmin@tcetmumbai.in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-30 09:34:14'),
(51, 1, 'LOGIN_SUCCESS', 'lms_login', 1, 'User \'superadmin@tcetmumbai.in\' logged in successfully with role 1 from IP ::1. Browser: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'superadmin@tcetmumbai.in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-30 09:34:17'),
(52, 1, 'LOGIN_SUCCESS', 'lms_login', 1, 'User \'superadmin@tcetmumbai.in\' logged in successfully with role 1 from IP ::1. Browser: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'superadmin@tcetmumbai.in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-30 09:34:22'),
(53, 1, 'LOGIN_SUCCESS', 'lms_login', 1, 'User \'superadmin@tcetmumbai.in\' logged in successfully with role 1 from IP ::1. Browser: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'superadmin@tcetmumbai.in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-30 09:34:53'),
(54, 1, 'LOGIN_SUCCESS', 'lms_login', 1, 'User \'superadmin@tcetmumbai.in\' logged in successfully with role 1 from IP ::1. Browser: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'superadmin@tcetmumbai.in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-30 09:39:42'),
(55, 1, 'LOGIN_SUCCESS', 'lms_login', 1, 'User \'superadmin@tcetmumbai.in\' logged in successfully with role 1 from IP ::1. Browser: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'superadmin@tcetmumbai.in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-30 09:40:29'),
(56, 1, 'LOGIN_SUCCESS', 'lms_login', 1, 'User \'superadmin@tcetmumbai.in\' logged in successfully with role 1 from IP ::1. Browser: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'superadmin@tcetmumbai.in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-30 09:42:05'),
(57, 1, 'LOGIN_SUCCESS', 'lms_login', 1, 'User \'superadmin@tcetmumbai.in\' logged in successfully with role  from IP ::1. Browser: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'superadmin@tcetmumbai.in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-30 09:42:28'),
(58, 1, 'LOGIN_SUCCESS', 'lms_login', 1, 'User \'superadmin@tcetmumbai.in\' logged in successfully with role 1 from IP ::1. Browser: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\nLogout: User \'superadmin@tcetmumbai.in\' logged out from IP ::1. Browser: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'superadmin@tcetmumbai.in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 176, '2026-04-30 10:09:08', '2026-04-30 10:06:12'),
(59, 1, 'LOGIN_SUCCESS', 'lms_login', 1, 'User \'superadmin@tcetmumbai.in\' logged in successfully with role 1 from IP ::1. Browser: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\nLogout: User \'superadmin@tcetmumbai.in\' logged out from IP ::1. Browser: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'superadmin@tcetmumbai.in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 669, '2026-04-30 10:20:22', '2026-04-30 10:09:13'),
(60, 3, 'LOGIN_SUCCESS', 'lms_login', 3, 'User \'coordinator@tcetmumbai.in\' logged in successfully with role 2 from IP ::1. Browser: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\nLogout: User \'coordinator@tcetmumbai.in\' logged out from IP ::1. Browser: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'coordinator@tcetmumbai.in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 998, '2026-04-30 10:37:14', '2026-04-30 10:20:36'),
(61, 0, 'LOGIN_SUCCESS', 'lms_login', 0, 'User \'ashutosh3276s16@tcetmumbai.in\' logged in successfully with role 5 from IP ::1. Browser: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'ashutosh3276s16@tcetmumbai.in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-30 10:39:34'),
(63, 1, 'LOGIN_SUCCESS', 'lms_login', 1, 'User \'superadmin@tcetmumbai.in\' logged in successfully with role 1 from IP ::1. Browser: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36\nLogout: User \'superadmin@tcetmumbai.in\' logged out from IP ::1. Browser: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'superadmin@tcetmumbai.in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 96, '2026-04-30 10:44:35', '2026-04-30 10:42:59');

-- --------------------------------------------------------

--
-- Table structure for table `lms_batch_master`
--

CREATE TABLE `lms_batch_master` (
  `batch_id` int(11) NOT NULL,
  `batch_name` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lms_batch_master`
--

INSERT INTO `lms_batch_master` (`batch_id`, `batch_name`, `date`) VALUES
(1, '2027', '2026-04-16 09:07:59'),
(2, '2028', '2026-04-16 09:08:15'),
(3, '2029', '2026-04-16 09:08:15');

-- --------------------------------------------------------

--
-- Table structure for table `lms_cgpa_master`
--

CREATE TABLE `lms_cgpa_master` (
  `cgpa_id` int(11) NOT NULL,
  `cgpa_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lms_class_master`
--

CREATE TABLE `lms_class_master` (
  `class_id` int(11) NOT NULL,
  `class_name` varchar(50) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lms_class_master`
--

INSERT INTO `lms_class_master` (`class_id`, `class_name`, `date`) VALUES
(1, 'FY', '2026-04-16 08:46:58'),
(2, 'SY', '2026-04-16 08:46:58'),
(3, 'TY', '2026-04-16 08:46:58'),
(4, 'FE', '2026-04-16 08:46:58'),
(5, 'SE', '2026-04-16 08:46:58'),
(6, 'TE', '2026-04-16 08:46:58'),
(7, 'BE', '2026-04-16 08:46:58');

-- --------------------------------------------------------

--
-- Table structure for table `lms_coordinator`
--

CREATE TABLE `lms_coordinator` (
  `coordinator_id` int(11) NOT NULL,
  `login_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lms_coordinator`
--

INSERT INTO `lms_coordinator` (`coordinator_id`, `login_id`, `created_at`) VALUES
(0, 7, '2026-04-30 08:40:19');

-- --------------------------------------------------------

--
-- Table structure for table `lms_coordinator_mentor`
--

CREATE TABLE `lms_coordinator_mentor` (
  `id` int(11) NOT NULL,
  `coordinator_id` int(11) NOT NULL,
  `mentor_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lms_credit_ledger`
--

CREATE TABLE `lms_credit_ledger` (
  `credit_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `specialization_id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `credits_earned` decimal(4,2) NOT NULL DEFAULT 0.00,
  `recorded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lms_department_master`
--

CREATE TABLE `lms_department_master` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(150) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lms_department_master`
--

INSERT INTO `lms_department_master` (`department_id`, `department_name`, `date`) VALUES
(1, 'MCA', '2026-04-22 10:19:07'),
(2, 'AIML', '2026-04-16 09:31:31'),
(3, 'CE', '2026-04-22 10:16:32'),
(4, 'IT', '2026-04-22 10:16:38'),
(5, 'EXTC', '2026-04-22 10:16:45'),
(6, 'ECS', '2026-04-22 10:18:15'),
(7, 'MECH', '2026-04-22 10:18:15'),
(8, 'CIVIL', '2026-04-22 10:18:15'),
(9, 'CSE-CS', '2026-04-22 10:18:15'),
(10, 'MME', '2026-04-22 10:18:15'),
(11, 'BCA', '2026-04-22 10:19:55'),
(12, 'AIDS', '2026-04-22 10:19:55'),
(13, 'IOT', '2026-04-22 10:19:55');

-- --------------------------------------------------------

--
-- Table structure for table `lms_division_master`
--

CREATE TABLE `lms_division_master` (
  `division_id` int(11) NOT NULL,
  `division_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lms_division_master`
--

INSERT INTO `lms_division_master` (`division_id`, `division_name`) VALUES
(1, 'A'),
(2, 'B'),
(3, 'C'),
(4, 'D'),
(5, 'E'),
(6, 'F');

-- --------------------------------------------------------

--
-- Table structure for table `lms_eligibility_log`
--

CREATE TABLE `lms_eligibility_log` (
  `eligibility_log_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `specialization_id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `cgpa_at_check` decimal(4,2) DEFAULT NULL,
  `kt_count_at_check` int(11) NOT NULL DEFAULT 0,
  `outcome` enum('Eligible','Ineligible') NOT NULL,
  `checked_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lms_enrollment`
--

CREATE TABLE `lms_enrollment` (
  `enrollment_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `specialization_id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `status` enum('Active','Suspended','Completed','Dropped') NOT NULL DEFAULT 'Active',
  `enrolled_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lms_login`
--

CREATE TABLE `lms_login` (
  `login_id` int(11) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lms_login`
--

INSERT INTO `lms_login` (`login_id`, `username`, `password`, `user_id`, `created_at`) VALUES
(1, 'superadmin@tcetmumbai.in', 'Amit@1234', 1, '0000-00-00 00:00:00'),
(2, 'admin@tcetmumbai.in', 'Amit@1234', 2, '0000-00-00 00:00:00'),
(3, 'coordinator@tcetmumbai.in', 'Amit@1234', 3, '0000-00-00 00:00:00'),
(4, 'mentor@tcetmumbai.in', 'Amit@1234', 4, '0000-00-00 00:00:00'),
(5, 'student@tcetmumbai.in', 'Amit@1234', 5, '0000-00-00 00:00:00'),
(6, 'aakash@tcetmumbai.in', 'Amit@1234', 6, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `lms_mentor_student_mapping`
--

CREATE TABLE `lms_mentor_student_mapping` (
  `mapping_id` int(11) NOT NULL,
  `mentor_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lms_menu_allocation_master`
--

CREATE TABLE `lms_menu_allocation_master` (
  `menu_allocation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `sub_menu_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lms_menu_allocation_master`
--

INSERT INTO `lms_menu_allocation_master` (`menu_allocation_id`, `user_id`, `role_id`, `menu_id`, `sub_menu_id`) VALUES
(1, 0, 1, 1, NULL),
(2, 0, 1, 1, 1),
(3, 0, 1, 1, 2),
(6, 0, 1, 1, 5),
(7, 0, 1, 2, NULL),
(22, 0, 4, 1, NULL),
(23, 0, 4, 1, 2),
(24, 0, 4, 1, 5),
(25, 0, 1, 3, NULL),
(28, 0, 4, 3, NULL),
(32, 0, 1, 3, 7),
(35, 0, 4, 3, 7),
(36, 0, 1, 3, 8),
(39, 0, 4, 3, 8),
(47, 0, 1, 4, NULL),
(50, 0, 4, 4, NULL),
(54, 0, 1, 4, 9),
(57, 0, 4, 4, 9),
(58, 0, 1, 4, 10),
(61, 0, 4, 4, 10),
(69, 0, 1, 5, NULL),
(72, 0, 4, 5, NULL),
(76, 0, 1, 5, 11),
(79, 0, 4, 5, 11),
(80, 0, 1, 5, 12),
(83, 0, 4, 5, 12),
(91, 0, 4, 2, NULL),
(93, 0, 1, 2, 13),
(96, 0, 4, 2, 13),
(147, 0, 1, 2, 20),
(148, 0, 1, 2, 21),
(149, 0, 1, 2, 22),
(150, 0, 1, 2, 23),
(151, 0, 1, 2, 24),
(152, 0, 1, 2, 25),
(153, 0, 1, 5, 13),
(154, 0, 1, 5, 20),
(155, 0, 1, 5, 21),
(156, 0, 1, 5, 23),
(157, 0, 1, 5, 24),
(158, 0, 1, 5, 25),
(159, 0, 1, 5, 46),
(240, 0, 1, 5, 47),
(241, 0, 1, 4, 48),
(247, 0, 5, 1, 1),
(248, 0, 5, 1, NULL),
(249, 0, 5, 5, 20),
(250, 0, 5, 5, NULL),
(251, 0, 5, 5, 21),
(252, 0, 2, 1, 1),
(253, 0, 2, 1, NULL),
(254, 0, 2, 1, 2),
(255, 0, 2, 2, 7),
(256, 0, 2, 2, NULL),
(257, 0, 2, 2, 8),
(258, 0, 2, 3, 9),
(259, 0, 2, 3, NULL),
(260, 0, 2, 3, 10),
(261, 0, 2, 4, 11),
(262, 0, 2, 4, NULL),
(263, 0, 2, 4, 12),
(264, 0, 2, 4, 48),
(265, 0, 2, 5, 13),
(266, 0, 2, 5, NULL),
(267, 0, 2, 5, 20),
(268, 0, 2, 5, 21),
(269, 0, 2, 5, 47),
(270, 0, 3, 1, 1),
(271, 0, 3, 1, NULL),
(272, 0, 3, 1, 2),
(273, 0, 3, 1, 5),
(274, 0, 3, 2, 7),
(275, 0, 3, 2, NULL),
(276, 0, 3, 2, 8),
(277, 0, 3, 3, 9),
(278, 0, 3, 3, NULL),
(279, 0, 3, 3, 10),
(280, 0, 3, 4, 11),
(281, 0, 3, 4, NULL),
(282, 0, 3, 4, 12),
(283, 0, 3, 5, 13),
(284, 0, 3, 5, NULL),
(285, 0, 3, 5, 20),
(286, 0, 3, 5, 21),
(287, 0, 3, 5, 47),
(288, 0, 4, 1, 1),
(289, 0, 1, 5, 49),
(290, 0, 1, 3, 50);

-- --------------------------------------------------------

--
-- Table structure for table `lms_menu_master`
--

CREATE TABLE `lms_menu_master` (
  `menu_id` int(11) NOT NULL,
  `menu_name` varchar(100) NOT NULL,
  `menu_icon` varchar(100) NOT NULL DEFAULT 'fa fa-folder'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lms_menu_master`
--

INSERT INTO `lms_menu_master` (`menu_id`, `menu_name`, `menu_icon`) VALUES
(1, 'Students', 'fa fa-graduation-cap'),
(2, 'Admin', 'fa fa-cogs'),
(3, 'coordinator', 'fa fa-user-secret'),
(4, 'mentor', 'fa fa-users'),
(5, 'Settings', 'fa fa-user');

-- --------------------------------------------------------

--
-- Table structure for table `lms_minorcourse`
--

CREATE TABLE `lms_minorcourse` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `course_type` varchar(50) NOT NULL,
  `coordinator` varchar(100) DEFAULT NULL,
  `total_credits` int(11) DEFAULT 18
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lms_minorcourse`
--

INSERT INTO `lms_minorcourse` (`course_id`, `course_name`, `course_type`, `coordinator`, `total_credits`) VALUES
(1, 'Performing Arts - Music', 'Certification', NULL, 18),
(2, 'Performing Arts - Dance', 'Certification', NULL, 18),
(3, 'Performing Arts - Drama', 'Certification', NULL, 18),
(4, 'Life Sciences - Science of Energy', 'MOOCs', 'Dr. Rajni Bahuguna', 18),
(5, 'Life Sciences - Biotechnology', 'MOOCs', NULL, 18),
(6, 'Mathematical Computing', 'MOOCs', 'Dr. Vivek Bharatiya', 18),
(7, 'Finance Management', 'MOOCs', 'Mr. Sudhir Mundra', 18),
(8, 'Life Skills - Health & Nutrition', 'Institute', NULL, 18),
(9, 'Life Skills - Social & Welfare', 'MOOCs', 'Dr. Vinita Gupta', 18),
(10, 'Life Skills - Physical Education (NCC)', 'Institute', 'Commandar Vijaypratap Singh', 18);

-- --------------------------------------------------------

--
-- Table structure for table `lms_minorsubject`
--

CREATE TABLE `lms_minorsubject` (
  `subject_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `duration` varchar(20) DEFAULT '12 weeks',
  `detail` text DEFAULT NULL,
  `credits` int(11) DEFAULT 3
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lms_minorsubject`
--

INSERT INTO `lms_minorsubject` (`subject_id`, `course_id`, `semester_id`, `subject_name`, `duration`, `detail`, `credits`) VALUES
(1, 1, 3, 'Raga studies', '12 weeks', NULL, 3),
(2, 1, 4, 'Study of Tala', '12 weeks', NULL, 3),
(3, 1, 5, 'Hindustani music', '12 weeks', NULL, 3),
(4, 1, 6, 'Folk music', '12 weeks', NULL, 3),
(5, 1, 7, 'Western music', '12 weeks', NULL, 3),
(6, 2, 3, 'History of Dance', '12 weeks', NULL, 3),
(7, 2, 4, 'Indian Culture', '12 weeks', NULL, 3),
(8, 2, 5, 'Techniques of Dance', '12 weeks', NULL, 3),
(9, 2, 6, 'Performance Practice', '12 weeks', NULL, 3),
(10, 2, 7, 'Dance on Camera', '12 weeks', NULL, 3),
(11, 3, 3, 'Indian theatre', '12 weeks', NULL, 3),
(12, 3, 4, 'Basic vocal practice', '12 weeks', NULL, 3),
(13, 3, 5, 'Event management', '12 weeks', NULL, 3),
(14, 3, 6, 'Camera, light, sound', '12 weeks', NULL, 3),
(15, 3, 7, 'Projects on short films', '12 weeks', NULL, 3),
(16, 4, 3, 'Non-Conventional Energy Resources', '12 weeks', 'https://nptel.ac.in/courses/121106014', 3),
(17, 4, 4, 'Waste to Energy Conversion', '12 weeks', 'https://nptel.ac.in/courses/103107125', 3),
(18, 4, 5, 'Mass Momentum And Energy Balances In Engineering Analysis', '12 weeks', 'https://nptel.ac.in/courses/105105186', 3),
(19, 4, 6, 'Renewable Energy Engineering: Solar, Wind and Biomass Energy Systems', '12 weeks', 'https://nptel.ac.in/courses/103103206', 3),
(20, 4, 7, 'Energy Efficiency, Acoustics and Daylighting in Building', '12 weeks', 'https://nptel.ac.in/courses/105102175', 3),
(21, 4, 8, 'Energy Resources, Economics and Environment', '12 weeks', 'https://nptel.ac.in/courses/109101171', 3),
(22, 5, 3, 'Basics of Biology', '12 weeks', 'https://onlinecourses.nptel.ac.in/noc23_bt25', 3),
(23, 5, 4, 'Structural Biology', '12 weeks', 'https://onlinecourses.nptel.ac.in/noc23_bt23', 3),
(24, 5, 5, 'Aspects Of Biochemical Engineering', '12 weeks', 'https://onlinecourses.nptel.ac.in/noc23_bt08', 3),
(25, 5, 6, 'Computational Systems Biology', '12 weeks', 'https://onlinecourses.nptel.ac.in/noc23_bt14', 3),
(26, 5, 7, 'Bioinformatics: Algorithms and Applications', '12 weeks', 'https://onlinecourses.nptel.ac.in/noc23_bt12', 3),
(27, 5, 8, 'Material and Energy Balances', '12 weeks', 'https://onlinecourses.nptel.ac.in/noc23_bt16', 3),
(28, 6, 3, 'Essentials of Data Science with R Software-1: Probability & Statistical Inference', '12 weeks', 'https://archive.nptel.ac.in/courses/111/104/111104146/', 3),
(29, 6, 4, 'Essentials Of Data Science With R Software-2: Sampling Theory And Linear Regression Analysis', '12 weeks', 'https://archive.nptel.ac.in/courses/111/104/111104147/', 3),
(30, 6, 5, 'Numerical Linear Algebra', '12 weeks', 'https://archive.nptel.ac.in/courses/111/107/111107106/', 3),
(31, 6, 6, 'Convex Optimization', '12 weeks', 'https://archive.nptel.ac.in/courses/111/104/111104068/', 3),
(32, 6, 7, 'Regression Analysis', '12 weeks', 'https://archive.nptel.ac.in/courses/111/105/111105042/', 3),
(33, 6, 8, 'Applied Multivariate Statistical Modelling', '12 weeks', 'https://archive.nptel.ac.in/courses/111/105/111105091/', 3),
(34, 7, 3, 'Financial Statement Analysis and Reporting', '12 weeks', 'https://onlinecourses.nptel.ac.in/noc23_mg12', 3),
(35, 7, 4, 'Financial Accounting', '12 weeks', 'https://onlinecourses.nptel.ac.in/noc23_mg65', 3),
(36, 7, 5, 'Financial Derivatives & Risk Management', '12 weeks', 'https://onlinecourses.nptel.ac.in/noc23_mg34', 3),
(37, 7, 6, 'Financial Institutions And Markets', '12 weeks', 'https://onlinecourses.nptel.ac.in/noc23_mg46', 3),
(38, 7, 7, 'Safety and Risk Analytics', '12 weeks', 'https://onlinecourses.nptel.ac.in/noc23_mg48', 3),
(39, 7, 8, 'Artificial Intelligence (AI) for Investments', '12 weeks', 'https://onlinecourses.nptel.ac.in/noc23_mg63', 3),
(40, 8, 3, 'Basic Food Science-I', '12 weeks', 'Basic concept on Food, Nutrition and Nutrients, Carbohydrates, Lipids, Proteins', 3),
(41, 8, 4, 'Basic Food Science-II', '12 weeks', 'Dietary Fibre, Minerals & Trace Elements, Vitamins, Water, Methods of Cooking and Preventing Nutrient Losses', 3),
(42, 8, 5, 'Human Nutrition-I', '12 weeks', 'Minimum Nutritional Requirement, Energy in Human Nutrition, Growth & Development from infancy to adulthood, Growth monitoring and promotion', 3),
(43, 8, 6, 'Human Nutrition-II', '12 weeks', 'Nutrition During Pregnancy, Nutrition during Lactation, Nutrition during Infancy', 3),
(44, 8, 7, 'Therapeutic Nutrition', '12 weeks', 'Principles of nutrition care, Etiology, Food allergy and food intolerance', 3),
(45, 8, 8, 'Public Health Nutrition', '12 weeks', 'Introduction to Nutritional deficiency diseases, Causes, Social Health problems, Nutrition for Special conditions, Food Security', 3),
(46, 9, 3, 'Concept of Society and Social Issues in India - Social Justice & Regional Imbalance', '12 weeks', 'https://onlinecourses.swayam2.ac.in/cec21_hs31', 3),
(47, 9, 4, 'Woman Empowerment - Gender Justice and Workplace Security', '12 weeks', 'https://onlinecourses.nptel.ac.in/noc20_mg42', 3),
(48, 9, 5, 'Health, Hygiene and Diseases - Disaster Management', '12 weeks', 'http://ecoursesonline.iasri.res.in/course/view.php?id=187', 3),
(49, 9, 6, 'Environment Education for Sustainable Development', '12 weeks', 'https://onlinecourses.nptel.ac.in/noc22_hs61/preview', 3),
(50, 9, 7, 'Youth Empowerment Programs - Education & Awareness Activities', '12 weeks', 'https://onlinecourses.nptel.ac.in/noc23_hs74/preview | https://onlinecourses.swayam2.ac.in/aic23_ge05/preview', 3),
(51, 9, 8, 'Voluntary Organization (VOs) and Government Organization (GOs)', '12 weeks', 'https://nptel.ac.in/courses/110106141', 3),
(52, 10, 1, 'NCC Programme I', '12 weeks', 'NCC General, National Integration-1, National Integration-2', 3),
(53, 10, 2, 'NCC Programme II', '12 weeks', 'Personality Development, Leadership, Communication, Health & Hygiene, Nutrition, Social Service, Community Development', 3),
(54, 10, 3, 'NCC Programme III', '12 weeks', 'Disaster Management, Adventure, Border & Coastal Area', 3),
(55, 10, 4, 'NCC Programme IV', '12 weeks', 'Environmental Awareness and Conservation, General Awareness, Armed Forces', 3),
(56, 10, 5, 'NCC Programme V', '12 weeks', 'Obstacle Training, Defence Entrance Examination & SSB Training, Government New Initiatives, Infantry Weapons', 3),
(57, 10, 6, 'NCC Programme VI', '12 weeks', 'Communication, Emotional Intelligence, Defence Entrance Exams and SSB Training, Indian Armed Forces, Medical and Paramedical Services of Armed Forces', 3);

-- --------------------------------------------------------

--
-- Table structure for table `lms_minor_certificates`
--

CREATE TABLE `lms_minor_certificates` (
  `certificate_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `enrollment_id` int(11) NOT NULL,
  `course_name` varchar(200) NOT NULL,
  `issuing_institution` varchar(200) NOT NULL,
  `completion_date` date DEFAULT NULL,
  `file_path` varchar(500) NOT NULL,
  `verification_status` enum('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  `verified_by` int(11) DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lms_nptel_records`
--

CREATE TABLE `lms_nptel_records` (
  `nptel_id` int(11) NOT NULL,
  `enrollment_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `course_name` varchar(200) NOT NULL,
  `score` decimal(5,2) DEFAULT NULL,
  `pass_fail` enum('Pass','Fail','Pending') NOT NULL DEFAULT 'Pending',
  `offline_exam_flag` tinyint(1) NOT NULL DEFAULT 0,
  `offline_exam_score` decimal(5,2) DEFAULT NULL,
  `offline_exam_date` date DEFAULT NULL,
  `recorded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lms_offline_marks_entry`
--

CREATE TABLE `lms_offline_marks_entry` (
  `entry_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `course_name` varchar(200) NOT NULL,
  `nptel_status` enum('Pass','Fail') NOT NULL,
  `nptel_exam_score` decimal(5,2) DEFAULT NULL,
  `nptel_assignment_raw` decimal(5,2) DEFAULT NULL,
  `nptel_assignment_converted` decimal(5,2) DEFAULT NULL,
  `ise1_marks` decimal(5,2) DEFAULT NULL,
  `ise2_marks` decimal(5,2) DEFAULT NULL,
  `ese_written_marks` decimal(5,2) DEFAULT NULL,
  `college_total_score` decimal(6,2) DEFAULT NULL,
  `final_score` decimal(6,2) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lms_research_records`
--

CREATE TABLE `lms_research_records` (
  `research_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `enrollment_id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `milestone_title` varchar(200) NOT NULL,
  `milestone_status` enum('Pending','In Progress','Completed') NOT NULL DEFAULT 'Pending',
  `remarks` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lms_role_master`
--

CREATE TABLE `lms_role_master` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lms_role_master`
--

INSERT INTO `lms_role_master` (`role_id`, `role_name`) VALUES
(1, 'SUPER ADMIN'),
(2, 'ADMIN'),
(3, 'COORDINATOR / HOD'),
(4, 'MENTOR'),
(5, 'STUDENT');

-- --------------------------------------------------------

--
-- Table structure for table `lms_section_master`
--

CREATE TABLE `lms_section_master` (
  `id` int(11) NOT NULL,
  `sections` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `lms_section_master`
--

INSERT INTO `lms_section_master` (`id`, `sections`, `date`) VALUES
(1, 'A', '2022-04-27 01:03:40'),
(2, 'B', '2022-04-27 01:04:04'),
(3, 'C', '2022-04-27 01:04:18'),
(4, 'D', '2022-04-27 01:04:26'),
(5, 'E', '2022-04-27 01:04:33'),
(6, 'F', '2022-04-27 01:04:42'),
(16, 'G', '2026-04-17 12:57:33'),
(18, 'H', '2026-04-17 13:00:41');

-- --------------------------------------------------------

--
-- Table structure for table `lms_semester`
--

CREATE TABLE `lms_semester` (
  `semester_id` int(11) NOT NULL,
  `semester_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lms_semester`
--

INSERT INTO `lms_semester` (`semester_id`, `semester_name`) VALUES
(1, 'Semester I'),
(2, 'Semester II'),
(3, 'Semester III'),
(4, 'Semester IV'),
(5, 'Semester V'),
(6, 'Semester VI'),
(7, 'Semester VII'),
(8, 'Semester VIII');

-- --------------------------------------------------------

--
-- Table structure for table `lms_semester_master`
--

CREATE TABLE `lms_semester_master` (
  `semester_id` int(11) NOT NULL,
  `semester_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lms_semester_master`
--

INSERT INTO `lms_semester_master` (`semester_id`, `semester_name`) VALUES
(1, 'Semester 1'),
(2, 'Semester 2'),
(3, 'Semester 3'),
(4, 'Semester 4'),
(5, 'Semester 5'),
(6, 'Semester 6'),
(7, 'Semester 7'),
(8, 'Semester 8');

-- --------------------------------------------------------

--
-- Table structure for table `lms_session_master`
--

CREATE TABLE `lms_session_master` (
  `session_id` int(11) NOT NULL,
  `session_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lms_session_master`
--

INSERT INTO `lms_session_master` (`session_id`, `session_name`) VALUES
(1, '2025 -2026'),
(2, '2026 -2027');

-- --------------------------------------------------------

--
-- Table structure for table `lms_specialization_master`
--

CREATE TABLE `lms_specialization_master` (
  `specialization_id` int(11) NOT NULL,
  `specialization_name` varchar(150) NOT NULL,
  `min_cgpa` decimal(4,2) NOT NULL DEFAULT 0.00,
  `kt_allowed` tinyint(1) NOT NULL DEFAULT 0,
  `sem_from` int(11) DEFAULT NULL,
  `sem_to` int(11) DEFAULT NULL,
  `is_exclusive` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lms_specialization_master`
--

INSERT INTO `lms_specialization_master` (`specialization_id`, `specialization_name`, `min_cgpa`, `kt_allowed`, `sem_from`, `sem_to`, `is_exclusive`) VALUES
(1, 'Honours Degree', 7.00, 0, 4, 8, 1),
(2, 'Honours with Research', 7.50, 0, 7, 8, 1),
(3, 'Minor Degree', 7.00, 1, NULL, NULL, 0),
(4, 'Minor Multidisciplinary', 0.00, 0, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `lms_specialization_subject_master`
--

CREATE TABLE `lms_specialization_subject_master` (
  `subject_id` int(11) NOT NULL,
  `subject_name` text NOT NULL,
  `specialization_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lms_specialization_subject_master`
--

INSERT INTO `lms_specialization_subject_master` (`subject_id`, `subject_name`, `specialization_id`) VALUES
(1, 'Artificial Intelligence and Machine Learning', 1),
(2, 'Data Science', 1),
(3, 'Advance Web Development', 1),
(4, 'Advanced Cyber secur4y and Quantum Cryptography', 1),
(5, 'Cyber Security', 1),
(6, 'Finance Management', 1),
(7, 'Sector Specific Specialization in Artificial Intelligence', 1),
(8, 'Innovation, Entrepreneurial and Venture Development', 1),
(9, 'Blockchain', 1),
(10, 'Business Development, Marketing and Finance', 1),
(11, 'VLSI Design & Technology', 1),
(12, 'Sector Specific Specialization in 13', 1),
(13, '3D Printing', 1),
(14, 'Internet of Things', 1),
(15, 'Railway Technology', 1),
(16, 'Energy Engineering', 1),
(17, 'Infrastructure Engineering', 1),
(18, 'Green Technology and Sustainability', 1),
(19, 'Robotics', 1),
(20, 'Electric Vehicle Technology', 1),
(21, 'Mathematical Computing', 1),
(22, 'Sector Specific Specialization in 2', 1);

-- --------------------------------------------------------

--
-- Table structure for table `lms_students`
--

CREATE TABLE `lms_students` (
  `id` int(11) NOT NULL,
  `reg_no` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `class_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `department` varchar(100) DEFAULT NULL,
  `specialization` varchar(100) DEFAULT NULL,
  `specialization_subject` varchar(100) DEFAULT NULL,
  `cgpa` decimal(3,2) DEFAULT NULL,
  `mobile_no` varchar(15) DEFAULT NULL,
  `roll_no` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `batch_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lms_student_master`
--

CREATE TABLE `lms_student_master` (
  `student_id` int(11) NOT NULL,
  `registration_no` varchar(200) NOT NULL,
  `class_id` int(11) NOT NULL,
  `division_id` int(11) NOT NULL,
  `grad_year` int(11) DEFAULT NULL,
  `roll_no` varchar(50) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `specialization_id` int(11) DEFAULT NULL,
  `specialization_subject_id` int(11) DEFAULT NULL,
  `minor_course_id` int(11) DEFAULT NULL,
  `minor_subject_id` int(11) DEFAULT NULL,
  `cgpa` decimal(4,2) DEFAULT NULL,
  `fname` varchar(100) NOT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `mark_list` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `m_sem1` text NOT NULL,
  `m_sem2` text NOT NULL,
  `m_sem3` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `academic_year_id` int(11) DEFAULT NULL,
  `current_semester_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lms_student_master`
--

INSERT INTO `lms_student_master` (`student_id`, `registration_no`, `class_id`, `division_id`, `grad_year`, `roll_no`, `department_id`, `specialization_id`, `specialization_subject_id`, `minor_course_id`, `minor_subject_id`, `cgpa`, `fname`, `mobile`, `email`, `mark_list`, `status`, `m_sem1`, `m_sem2`, `m_sem3`, `created_at`, `academic_year_id`, `current_semester_id`) VALUES
(12, 'S1032241059', 5, 1, 0, '32', 3, 1, 1, NULL, NULL, 9.09, 'Purva Dinkar Gade', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(13, 'S1032241051', 5, 1, 0, '24', 3, 1, 1, NULL, NULL, 9.86, 'Sanjana Dhopte', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(14, 'S1032241060', 5, 1, 0, '33', 3, 1, 1, NULL, NULL, 6.75, 'Ishan Ranj4 Gadecha', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(15, 'S1032241048', 5, 1, 0, '21', 3, 1, 1, NULL, NULL, 9.31, 'Mohammad Shoeb Md Farookh Choudhary', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(16, 'S1032241068', 5, 1, 0, '41', 3, 1, 1, NULL, NULL, 9.38, 'Aman Rajkumar Gupta', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(17, 'S1032241085', 5, 1, 0, '58', 3, 1, 1, NULL, NULL, 6.90, 'Rishabh Jaiswal', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(18, 'S1032241071', 5, 1, 0, '44', 3, 1, 1, NULL, NULL, 9.73, 'Himanshu Gupta', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(19, 'S1032241089', 5, 1, 0, '62', 3, 1, 1, NULL, NULL, 9.66, 'Prince Jha', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(20, 'S1032241074', 5, 1, 0, '47', 3, 1, 1, NULL, NULL, 7.20, 'Pratham Gupta', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(21, 'S1032250200', 5, 1, 0, '66', 3, 1, 1, NULL, NULL, 9.95, 'Ananya Dhote', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(22, 'S1032241075', 5, 1, 0, '48', 3, 1, 1, NULL, NULL, 9.31, 'Sarthak Gupta', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(23, 'S1032250204', 5, 1, 0, '69', 3, 1, 1, NULL, NULL, 8.23, 'Sagar Sanjay Gupta', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(24, 'S1032241044', 5, 1, 0, '17', 3, 1, 1, NULL, NULL, 9.75, 'Sanju Chauhan', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(25, 'S1032241070', 5, 1, 0, '43', 3, 1, 1, NULL, NULL, 9.10, 'Disha Gupta', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(26, 'S1032241065', 5, 1, 0, '38', 3, 1, 1, NULL, NULL, 9.55, 'Pr4ha Goradia', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(27, 'S1032241047', 5, 1, 0, '20', 3, 1, 1, NULL, NULL, 8.30, 'Krish Choudhary', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(28, 's1032241084', 5, 1, 0, '57', 3, 1, 1, NULL, NULL, 9.34, 'Krish Jain', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(29, '1032241028', 5, 1, 0, '1', 3, 1, 1, NULL, NULL, 8.01, 'Tiya Agarwal', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(30, 'S1032241077', 5, 1, 0, '50', 3, 1, 1, NULL, NULL, 9.14, 'Sum4 Santosh Gupta', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(31, 'S1032241041', 5, 1, 0, '14', 3, 1, 1, NULL, NULL, 9.86, 'Dipendra Kumar Chaturvedi', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(32, 'S1032241090', 5, 1, 0, '63', 3, 1, 1, NULL, NULL, 9.05, 'Rishu Jha', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(33, 'S1032241037', 5, 1, 0, '10', 3, 1, 1, NULL, NULL, 8.70, 'Hredey Chaand', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(34, 'S1032241064', 5, 1, 0, '37', 3, 1, 1, NULL, NULL, 8.18, 'Jay Bipinbhai Gohil', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(35, '1032241093', 5, 2, 0, '2', 3, 1, 1, NULL, NULL, 7.08, 'Daksh Preetam Jodhavat', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(36, 'S1032241128', 5, 2, 0, '37', 3, 1, 1, NULL, NULL, 8.10, 'Sakshi Maurya', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(37, 'S1032241101', 5, 2, 0, '10', 3, 1, 1, NULL, NULL, 8.00, 'Shreya Kesharwani', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(38, 'S1032241154', 5, 2, 0, '63', 3, 1, 1, NULL, NULL, 8.82, 'Dhruva Ramesh Panmand', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(39, '1032241146', 5, 2, 0, '55', 3, 1, 1, NULL, NULL, 8.73, 'Prince Shyamdhar Pal', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(40, 'S1032241125', 5, 2, 0, '34', 3, 1, 1, NULL, NULL, 9.44, 'Anish Ajaykumar Maurya', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(41, 'S1032241122', 5, 2, 0, '31', 3, 1, 1, NULL, NULL, 9.14, 'Priya Amar Manna', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(42, 'S1032241127', 5, 2, 0, '36', 3, 1, 1, NULL, NULL, 9.12, 'Ashish Sanjay Maurya', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(43, 'S1032241124', 5, 2, 0, '33', 3, 1, 1, NULL, NULL, 8.91, 'Adarsh Vinod Maurya', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(44, 'S1032241136', 5, 2, 0, '45', 3, 1, 1, NULL, NULL, 8.50, 'Yash Mishra', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(45, 'S1032241143', 5, 2, 0, '52', 3, 1, 1, NULL, NULL, 8.00, 'Asma Qureshi', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(46, 'S1032241132', 5, 2, 0, '41', 3, 1, 1, NULL, NULL, 8.50, 'Harsh Mishra', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(47, 'S1032241150', 5, 2, 0, '59', 3, 1, 1, NULL, NULL, 10.00, 'Anshika Ashish Pandey', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(48, 'S1032241141', 5, 2, 0, '50', 3, 1, 1, NULL, NULL, 9.60, 'Areeza Mukadam', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(49, 'S1032241140', 5, 2, 0, '49', 3, 1, 1, NULL, NULL, 7.23, 'Krish Morya', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(50, 'S1032241111', 5, 2, 0, '20', 3, 1, 1, NULL, NULL, 9.84, 'Devanshu Kumawat', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(51, 'S1032241153', 5, 2, 0, '62', 3, 1, 1, NULL, NULL, 8.68, 'Esha Pandey', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(52, 'S1032250208', 5, 2, 0, '67', 3, 1, 1, NULL, NULL, 8.50, 'Rishabh Trivedi', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(53, 'S1032250222', 5, 2, 0, '69', 3, 1, 1, NULL, NULL, 9.68, 'Jogeshkumar Kantilal Mali', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(54, 'S1032241108', 5, 2, 0, '17', 3, 1, 1, NULL, NULL, 8.30, 'Yash Khatri', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(55, 'S1032250210', 5, 2, 0, '64', 3, 1, 1, NULL, NULL, 9.41, 'Parth Akshay Dave', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(56, 'S1032250211', 5, 2, 0, '66', 3, 1, 1, NULL, NULL, 9.45, 'Aryan Navghare', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(57, 'S1032241104', 5, 2, 0, '13', 3, 1, 1, NULL, NULL, 8.48, 'Inzamamul haque Hamid ali Khan', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(58, 'S1032241149', 5, 2, 0, '58', 3, 1, 1, NULL, NULL, 9.95, 'Akish Anil Pandey', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(59, 'S1032241135', 5, 2, 0, '44', 3, 1, 1, NULL, NULL, 9.69, 'Sagar Mr4unjay Mishra', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(60, 'S1032250207', 5, 2, 0, '65', 3, 1, 1, NULL, NULL, 9.36, 'SAZIA SALIM KAROL', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(61, '1032241109', 5, 2, 0, '18', 3, 1, 1, NULL, NULL, 7.50, 'Durvakshi Killedar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(62, 'S1032241142', 5, 2, 0, '51', 3, 1, 1, NULL, NULL, 8.90, 'Divya nagaraju Dumpeti', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(63, 'S1032241163', 5, 3, 0, '9', 3, 1, 1, NULL, NULL, 8.27, 'Akshat Prajapati', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(64, 'S1032241213', 5, 3, 0, '59', 3, 1, 1, NULL, NULL, 8.22, 'Ved Shukla', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(65, 'S1032241179', 5, 3, 0, '25', 3, 1, 1, NULL, NULL, 8.87, 'Saish Raut', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(66, 'S1032241217', 5, 3, 0, '63', 3, 1, 1, NULL, NULL, 9.27, 'Ad4ya Sunilkumar Singh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(67, 'S1032241214', 5, 3, 0, '60', 3, 1, 1, NULL, NULL, 9.68, 'Arsh Siddiqui', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(68, 'S1032241170', 5, 3, 0, '16', 3, 1, 1, NULL, NULL, 8.71, 'Ad4ya Raj Prasad', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(69, 'S1032250214', 5, 3, 0, '67', 3, 1, 1, NULL, NULL, 9.73, 'Shaikh Mohd Fahad', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(70, 'S1032241162', 5, 3, 0, '8', 3, 1, 1, NULL, NULL, 8.74, 'Radhe Patel', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(71, 'S1032241191', 5, 3, 0, '37', 3, 1, 1, NULL, NULL, 8.57, 'Rajvi Shah', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(72, 'S1032241171', 5, 3, 0, '17', 3, 1, 1, NULL, NULL, 9.00, 'Dhananjay Prasad', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(73, 'S1032241188', 5, 3, 0, '34', 3, 1, 1, NULL, NULL, 8.67, 'Aneesh Sawant', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(74, 'S1032241193', 5, 3, 0, '39', 3, 1, 1, NULL, NULL, 9.05, 'Mohammad Istiyaq Ahmed Shakil shaikh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(75, 'S1032241181', 5, 3, 0, '27', 3, 1, 1, NULL, NULL, 7.00, 'Priyanshu reddy', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(76, '1032241192', 5, 3, 0, '38', 3, 1, 1, NULL, NULL, 7.32, 'Shaikh mohammad adnan mohd irfan', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(77, 'S1032241195', 5, 3, 0, '41', 3, 1, 1, NULL, NULL, 8.59, 'Sohail Najir Shaikh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(78, 'S1032241182', 5, 3, 0, '28', 3, 1, 1, NULL, NULL, 8.49, 'MohammedSaad LaiqHasan Rizvi', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(79, 'S1032241166', 5, 3, 0, '12', 3, 1, 1, NULL, NULL, 9.60, 'Tanish Sandip Patil', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(80, 'S1032241161', 5, 3, 0, '7', 3, 1, 1, NULL, NULL, 8.00, 'Manas Patel', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(81, 'S1032241183', 5, 3, 0, '29', 3, 1, 1, NULL, NULL, 7.68, 'Shruti Sable', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(82, 'S1032241185', 5, 3, 0, '31', 3, 1, 1, NULL, NULL, 9.40, 'JAYAD4YA SALOI', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(83, 'S1032241187', 5, 3, 0, '33', 3, 1, 1, NULL, NULL, 9.60, 'Vivek Kumar R4lal Saw', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(84, 'S1032241176', 5, 3, 0, '22', 3, 1, 1, NULL, NULL, 7.68, 'Mayank Rai', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(85, 'S1032241186', 5, 3, 0, '32', 3, 1, 1, NULL, NULL, 9.42, 'Anish Sasmal', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(86, 'S1032250212', 5, 3, 0, '68', 3, 1, 1, NULL, NULL, 10.00, 'Am4 Vishwakarma', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(87, 'S1032250215', 5, 3, 0, '65', 3, 1, 1, NULL, NULL, 9.45, 'Md Irfanul Hamidul Haque', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(88, 'S1032241207', 5, 3, 0, '53', 3, 1, 1, NULL, NULL, 9.53, 'Harsh4a Shirsat', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(89, 'S1032241211', 5, 3, 0, '57', 3, 1, 1, NULL, NULL, 9.86, 'Shivang Shukla', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(90, 'S1032241190', 5, 3, 0, '36', 3, 1, 1, NULL, NULL, 8.71, 'Prasham Shah', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(91, 'S1032241215', 5, 3, 0, '61', 3, 1, 1, NULL, NULL, 7.77, 'Aarav Singh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(92, 'S1032241189', 5, 3, 0, '35', 3, 1, 1, NULL, NULL, 8.70, 'Devashree Sawant', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(93, '1032250213', 5, 3, 0, '64', 3, 1, 1, NULL, NULL, 9.32, 'Abhay R. Dubey', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(94, '1032241197', 5, 3, 0, '43', 3, 1, 1, NULL, NULL, 9.10, 'Mahimna Sharma', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(95, 'S1032241200', 5, 3, 0, '46', 3, 1, 1, NULL, NULL, 8.95, 'Ad4ya Mukhlal Shaw', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(96, 'S1032241175', 5, 3, 0, '21', 3, 1, 1, NULL, NULL, 9.63, 'Ayush Rajesh Rai', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(97, '1032241169', 5, 3, 0, '15', 3, 1, 1, NULL, NULL, 9.08, 'Sum4kumar Sanjay Kumar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(98, 'S1032241204', 5, 3, 0, '50', 3, 1, 1, NULL, NULL, 10.00, 'Shishir p Shetty', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(99, 'S1032241196', 5, 3, 0, '42', 3, 1, 1, NULL, NULL, 10.00, 'Atul Sharma', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(100, 'S1032241194', 5, 3, 0, '40', 3, 1, 1, NULL, NULL, 7.50, 'Saahil Khalid Shaikh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(101, '1032241234', 5, 4, 0, '17', 3, 1, 1, NULL, NULL, 7.86, 'Udaypratap Singh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(102, 'S1032241232', 5, 4, 0, '15', 3, 1, 1, NULL, NULL, 8.99, 'Rudrapratap Singh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(103, 'S1032241249', 5, 4, 0, '32', 3, 1, 1, NULL, NULL, 9.62, 'Akshay Upadhyay', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(104, 'S1032241222', 5, 4, 0, '5', 3, 1, 1, NULL, NULL, 9.50, 'Ashm4 singh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(105, 'S1032241221', 5, 4, 0, '4', 3, 1, 1, NULL, NULL, 8.82, 'Aryan Singh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(106, 'S1032250217', 5, 4, 0, '70', 3, 1, 1, NULL, NULL, 9.55, 'Swapnil Santosh Shinde', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(107, 'S1032241244', 5, 4, 0, '27', 3, 1, 1, NULL, NULL, 10.00, 'Nik4a Tiwari', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(108, 'S1032241256', 5, 4, 0, '39', 3, 1, 1, NULL, NULL, 9.18, 'Pratik Verma', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(109, '1032241255', 5, 4, 0, '38', 3, 1, 1, NULL, NULL, 0.00, 'Aryan verma', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(110, '1032241269', 5, 4, 0, '52', 3, 1, 1, NULL, NULL, 7.30, 'Aryan Worah', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(111, 'S1032250223', 5, 4, 0, '68', 3, 1, 1, NULL, NULL, 0.00, 'Sm4 Suhas Patil', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(112, 'S1032241275', 5, 4, 0, '58', 3, 1, 1, NULL, NULL, 0.00, 'Nikhil Mahendrakumar Yadav', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(113, 'S1032241267', 5, 4, 0, '50', 3, 1, 1, NULL, NULL, 0.00, 'Ved Amar Wade', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(114, 'S1032241260', 5, 4, 0, '43', 3, 1, 1, NULL, NULL, 8.57, 'Rahul Vishwakarma', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(115, 'S1032241273', 5, 4, 0, '56', 3, 1, 1, NULL, NULL, 0.00, 'Ayush Yadav', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(116, 'S1032241219', 5, 4, 0, '2', 3, 1, 1, NULL, NULL, 8.40, 'Singh Aman Kumar Ravindra Kumar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(117, 'S1032241094', 5, 2, 0, '3', 3, 1, 1, NULL, NULL, 8.54, 'Swarraj Joshi', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(118, '1032241252', 5, 4, 0, '35', 3, 1, 1, NULL, NULL, 9.68, 'Bhavika Shriram Vasule', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(119, 'S1032241277', 5, 4, 0, '60', 3, 1, 1, NULL, NULL, 9.65, 'Om Shivbalak Yadav', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(120, '1032241279', 5, 4, 0, '62', 3, 1, 1, NULL, NULL, 7.24, 'Vikas yadav', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(121, 'S1032241266', 5, 4, 0, '49', 3, 1, 1, NULL, NULL, 9.16, 'Yagna vyas', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(122, 'S1032241253', 5, 4, 0, '36', 3, 1, 1, NULL, NULL, 8.12, 'Akash Verma', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(123, 'S1032241040', 5, 1, 0, '13', 3, 1, 1, NULL, NULL, 7.40, 'Anagh Chaturvedi', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(124, 'S1032241069', 5, 1, 0, '42', 3, 1, 1, NULL, NULL, 9.82, 'Bhavna Gupta', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(125, 'S1032250205', 5, 1, 0, '68', 3, 1, 1, NULL, NULL, 9.41, 'Darshana Gupta', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(126, 'S1032241067', 5, 1, 0, '40', 3, 1, 2, NULL, NULL, 9.24, 'Adarsh Gupta', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(127, 'S1032241033', 5, 1, 0, '6', 3, 1, 2, NULL, NULL, 7.41, 'Arnav Bhanage', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(128, '1032241040', 5, 1, 0, '13', 3, 1, 2, NULL, NULL, 7.40, 'Anagh Deepak Chaturvedi', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(129, '1032241053', 5, 1, 0, '26', 3, 1, 2, NULL, NULL, 6.60, 'Chah4 doshi', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(130, '1032241052', 5, 1, 0, '25', 3, 1, 2, NULL, NULL, 6.45, 'KRISH', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(131, 'S1032241039', 5, 1, 0, '12', 3, 1, 2, NULL, NULL, 6.50, 'Ad4ya Chamlagain', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(132, '1032241050', 5, 1, 0, '23', 3, 1, 2, NULL, NULL, 8.41, 'Mishti Dhiman', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(133, 'S1032241086', 5, 1, 0, '59', 3, 1, 2, NULL, NULL, 8.62, 'Lovekush Jaiswar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(134, 'S1032250202', 5, 1, 0, '70', 3, 1, 2, NULL, NULL, 9.64, 'Aad4i Pawar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(135, '1032241049', 5, 1, 0, '22', 3, 1, 2, NULL, NULL, 8.44, 'Samiha Nadeem Dadarkar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(136, '1032241083', 5, 1, 0, '56', 3, 1, 2, NULL, NULL, 8.40, 'Jeel jain', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(137, 'S1032241066', 5, 1, 0, '39', 3, 1, 2, NULL, NULL, 7.50, 'Aarna Gupta', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(138, 'S1032241120', 5, 2, 0, '29', 3, 1, 2, NULL, NULL, 9.88, 'Raj Mahesh Mane', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(139, 'S1032241097', 5, 2, 0, '6', 3, 1, 2, NULL, NULL, 10.00, 'Ananya Kalia', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(140, 'S1032241164', 5, 3, 0, '10', 3, 1, 2, NULL, NULL, 9.75, 'Manasvi Viraj Patil', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(141, 'S1032241168', 5, 3, 0, '14', 3, 1, 2, NULL, NULL, 8.31, 'Srushti Pawar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(142, '1032241158', 5, 3, 0, '4', 3, 1, 2, NULL, NULL, 8.57, 'Veera pashine', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(143, 'S1032241203', 5, 3, 0, '49', 3, 1, 2, NULL, NULL, 8.05, 'Nandini Krishna Shetty', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(144, 'S1032241180', 5, 3, 0, '26', 3, 1, 2, NULL, NULL, 6.51, 'Soham Rawte', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(145, '1032241157', 5, 3, 0, '3', 3, 1, 2, NULL, NULL, 8.27, 'Shravani Parsekar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(146, 'S1032241173', 5, 3, 0, '19', 3, 1, 2, NULL, NULL, 7.64, 'Mohammad Faiz qadri', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(147, 'S1032241209', 5, 3, 0, '55', 3, 1, 2, NULL, NULL, 6.40, 'Ad4ya Shrivastava', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(148, 'S1032241233', 5, 4, 0, '16', 3, 1, 2, NULL, NULL, 8.59, 'Sakshi Singh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(149, 'MU0341120240224830', 5, 4, 0, '9', 3, 1, 2, NULL, NULL, 8.18, 'Mahek singh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(150, '1032241231', 5, 4, 0, '14', 3, 1, 2, NULL, NULL, 7.70, 'Rish4 Udai Pratap Singh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(151, 'S1032250220', 5, 4, 0, '67', 3, 1, 2, NULL, NULL, 8.00, 'Mohammad Shees', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(152, '1032250218', 5, 4, 0, '69', 3, 1, 2, NULL, NULL, 9.23, 'Shirin Mohammed Hussain Shaikh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(153, '1032241263', 5, 4, 0, '46', 3, 1, 2, NULL, NULL, 9.54, 'SHIVAM VISHWAKARMA', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(154, 'S1032241276', 5, 4, 0, '59', 3, 1, 2, NULL, NULL, 7.53, 'Nilesh Yadav', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(155, 'S1032241261', 5, 4, 0, '44', 3, 1, 2, NULL, NULL, 8.16, 'Sahil Vishwakarma', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(156, 'S1032241258', 5, 4, 0, '41', 3, 1, 2, NULL, NULL, 7.40, 'Alok shyam Vishwakarma', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(157, 'S1032241036', 5, 1, 0, '9', 3, 1, 3, NULL, NULL, 9.09, 'Harshaal Shankar Boyeni', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(158, '1032241114', 5, 2, 0, '23', 3, 1, 3, NULL, NULL, 9.32, 'Nainika Kunder', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(159, '1032241251', 5, 4, 0, '34', 3, 1, 3, NULL, NULL, 8.03, 'Shreyas Dipankar Vartak', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(160, 'S1032241259', 5, 4, 0, '42', 3, 1, 3, NULL, NULL, 0.00, 'Hr4hik Vishwakarma', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(161, 'S1032241042', 5, 1, 0, '15', 3, 1, 4, NULL, NULL, 9.18, 'Namrata Chaubey', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(162, 'S1032241237', 5, 4, 0, '20', 3, 1, 4, NULL, NULL, 0.00, 'Divyajyot Sinha', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(163, 'S1032241063', 5, 1, 0, '36', 3, 1, 10, NULL, NULL, 6.30, 'Pariket Girase', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(164, '1032241148', 5, 2, 0, '57', 3, 1, 10, NULL, NULL, 8.27, 'Kashish Panchal', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(165, '1032241110', 5, 2, 0, '19', 3, 1, 10, NULL, NULL, 7.73, 'H4anshu Kothari', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(166, '1032241134', 5, 2, 0, '43', 3, 1, 10, NULL, NULL, 9.27, 'Prathamesh Mishra', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(167, '1032241704', 5, 2, 0, '46', 3, 1, 10, NULL, NULL, 8.50, 'Puj4ha Pedapolu', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(168, '1032241095', 5, 2, 0, '4', 3, 1, 10, NULL, NULL, 8.00, 'Manasvi Kadambala', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(169, 'S1032241138', 5, 2, 0, '47', 3, 1, 10, NULL, NULL, 0.00, 'Jay Miyani', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(170, 'S1032241096', 5, 2, 0, '5', 3, 1, 10, NULL, NULL, 8.32, 'Jay Kakadiya', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(171, 'S1032241099', 5, 2, 0, '8', 3, 1, 10, NULL, NULL, 9.50, 'Riya Kasat', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(172, 'S1032241100', 5, 2, 0, '9', 3, 1, 10, NULL, NULL, 8.20, 'Pradnyesh kawate', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(173, 'S1032241167', 5, 3, 0, '13', 3, 1, 10, NULL, NULL, 7.86, 'Ved Manojkumar Patil', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(174, '1032241106', 5, 2, 0, '15', 3, 1, 5, NULL, NULL, 9.58, 'Ad4i Khandge', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(175, 'S1032241155', 5, 3, 0, '1', 3, 1, 5, NULL, NULL, 7.20, 'Kunal Parmar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(176, 'S1032241206', 5, 3, 0, '52', 3, 1, 5, NULL, NULL, 8.07, 'Sarvesh Shinde', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(177, '1032241224', 5, 4, 0, '7', 3, 1, 6, NULL, NULL, 0.00, 'Krish Sushil Singh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(178, 'S1032241205', 5, 3, 0, '51', 3, 1, 6, NULL, NULL, 9.92, 'Zidane Z Shikalgar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(179, 'S1032241159', 5, 3, 0, '5', 3, 1, 6, NULL, NULL, 9.68, 'Aayan Patel', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(180, 'S1032250205', 5, 1, 0, '68', 3, 1, 7, NULL, NULL, 9.41, 'Darshana Rajesh Gupta', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(181, 'S1032241235', 5, 4, 0, '18', 3, 1, 8, NULL, NULL, 9.10, 'Vickykumar Singh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(182, 'S1032291225', 5, 3, 0, '8', 3, 1, 1, NULL, NULL, 9.18, 'Kushgra Singh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(183, 'S1032240670', 5, 1, 0, '8', 4, 1, 3, NULL, NULL, 8.60, 'Pranav Bhavsar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(184, 'S1032240682', 5, 1, 0, '20', 4, 1, 3, NULL, NULL, 8.50, 'Taniya Dhawan', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(185, 'S1032240713', 5, 1, 0, '51', 4, 1, 4, NULL, NULL, 8.20, 'Ravishankar Chandrakant Kanaki', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(186, '', 5, 3, 0, '29', 4, 1, 4, NULL, NULL, 8.05, 'Pradnya Mukesh Sonawane', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(187, '', 5, 3, 0, '15', 4, 1, 4, NULL, NULL, 9.57, 'Pratha Shukla', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(188, '1032240817', 5, 3, 0, '28', 4, 1, 4, NULL, NULL, 9.54, 'Mihir Sonawane', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(189, 'S1032240663', 5, 1, 0, '1', 4, 1, 1, NULL, NULL, 9.75, 'Abidi Mohammed Saqlain', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(190, '1032250173', 5, 1, 0, '69', 4, 1, 1, NULL, NULL, 9.77, 'Falak Afraz Shaikh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(191, '1032240712', 5, 1, 0, '50', 4, 1, 1, NULL, NULL, 10.00, 'Suhani Subodh Kambli', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(192, '1032240686', 5, 1, 0, '24', 4, 1, 1, NULL, NULL, 9.23, 'Vidhidevi Rajkumar Dubey', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(193, '1032240676', 5, 1, 0, '14', 4, 1, 1, NULL, NULL, 9.55, 'MAHIKA CHAURASIYA', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(194, '1032240680', 5, 1, 0, '18', 4, 1, 1, NULL, NULL, 9.73, 'Anand Dangi', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(195, '1032240714', 5, 1, 0, '52', 4, 1, 1, NULL, NULL, 9.63, 'Ananya Harish Kanchan', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(196, '1032240706', 5, 1, 0, '44', 4, 1, 1, NULL, NULL, 9.92, 'Jainam Pankaj Jain', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(197, 'S1032240675', 5, 1, 0, '13', 4, 1, 1, NULL, NULL, 9.68, 'Diksha Chaurasiya', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(198, 'S1032240688', 5, 1, 0, '26', 4, 1, 1, NULL, NULL, 9.85, 'Aarya Shantaram Gaikwad', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(199, '1032250171', 5, 1, 0, '65', 4, 1, 1, NULL, NULL, 9.64, 'Isha Ganesh Jain', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(200, '1032240693', 5, 1, 0, '31', 4, 1, 1, NULL, NULL, 7.32, 'Bhumika Vinod Gothankar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(201, '1032240689', 5, 1, 0, '27', 4, 1, 1, NULL, NULL, 7.62, 'Sneha Gajera', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(202, 'S1032240684', 5, 1, 0, '22', 4, 1, 1, NULL, NULL, 8.88, 'Kalp Doshi', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(203, '1032240697', 5, 1, 0, '35', 4, 1, 1, NULL, NULL, 8.89, 'Gautam Gupta', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(204, 'S1032240678', 5, 1, 0, '16', 4, 1, 1, NULL, NULL, 8.10, 'Mihir Chettiar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(205, 'S1032240698', 5, 1, 0, '36', 4, 1, 1, NULL, NULL, 7.22, 'Lavi sanjaykumar Gupta', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(206, '1032240690', 5, 1, 0, '28', 4, 1, 1, NULL, NULL, 7.88, 'Mihir Govind Gaonkar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(207, 'S1032240752', 5, 2, 0, '26', 4, 1, 1, NULL, NULL, 8.86, 'Shivam Madan Oz', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(208, 'S1032240756', 5, 2, 0, '30', 4, 1, 1, NULL, NULL, 9.73, 'Anurag Pandey', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(209, 'S1032240742', 5, 2, 0, '16', 4, 1, 1, NULL, NULL, 9.95, 'Jennica Paresh Mistry', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(210, 'S1032240750', 5, 2, 0, '24', 4, 1, 1, NULL, NULL, 9.73, 'Bhagyashri Pravin Nere', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(211, '1032240781', 5, 2, 0, '55', 4, 1, 1, NULL, NULL, 7.63, 'Chhahat Samat', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(212, 'S1032240729', 5, 2, 0, '3', 4, 1, 1, NULL, NULL, 9.95, 'Shaheen Rajmohammad Madeena', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(213, 'S1032240769', 5, 2, 0, '43', 4, 1, 1, NULL, NULL, 7.69, 'Sakshi Prajapati', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(214, 'S1032240728', 5, 2, 0, '2', 4, 1, 1, NULL, NULL, 7.60, 'Swar Lokre', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(215, '1032240749', 5, 2, 0, '23', 4, 1, 1, NULL, NULL, 9.45, 'Raunak Nayak', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(216, 'S1032240770', 5, 2, 0, '44', 4, 1, 1, NULL, NULL, 8.50, 'Shabdansh Prajapati', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(217, 'S1032240779', 5, 2, 0, '53', 4, 1, 1, NULL, NULL, 8.05, 'Anannya Salvi', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(218, 'S1032240778', 5, 2, 0, '52', 4, 1, 1, NULL, NULL, 8.70, 'Khushi Sah', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(219, 'S1032240754', 5, 2, 0, '28', 4, 1, 1, NULL, NULL, 9.86, 'Aniket Pandey', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(220, 'S1032240738', 5, 2, 0, '12', 4, 1, 1, NULL, NULL, 9.59, 'Himanshu Mishra', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(221, 'S1032240777', 5, 2, 0, '51', 4, 1, 1, NULL, NULL, 9.30, 'Ad4ya Sah', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(222, '1032240780', 5, 2, 0, '54', 4, 1, 1, NULL, NULL, 7.30, 'Simran Samanta', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(223, 'S1032240776', 5, 2, 0, '50', 4, 1, 1, NULL, NULL, 8.91, 'Shashank Dhananjay Roy', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(224, 'S1032240784', 5, 2, 0, '58', 4, 1, 1, NULL, NULL, 8.73, 'Shaikh Foziyabano. F', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(225, 'S1032240727', 5, 2, 0, '1', 4, 1, 1, NULL, NULL, 9.22, 'Ameya Kulkarni', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(226, 'S1032240783', 5, 2, 0, '57', 4, 1, 1, NULL, NULL, 8.77, 'Dev Shah', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(227, 'S1032240744', 5, 2, 0, '18', 4, 1, 1, NULL, NULL, 8.80, 'Sanket More', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(228, 'S1032240745', 5, 2, 0, '19', 4, 1, 1, NULL, NULL, 7.50, 'Ayush Mahadev Mote', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(229, 'S1032240774', 5, 2, 0, '48', 4, 1, 1, NULL, NULL, 7.58, 'Riya S Rajpurkar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(230, 'S1032240788', 5, 2, 0, '62', 4, 1, 1, NULL, NULL, 9.20, 'Shivam Sharma', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(231, '1032240828', 5, 3, 0, '39', 4, 1, 1, NULL, NULL, 9.95, 'Arwa Vasaiwala', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(232, '1032240821', 5, 3, 0, '32', 4, 1, 1, NULL, NULL, 10.00, 'Khushboo Tiwari', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(233, '1032240811', 5, 3, 0, '22', 4, 1, 1, NULL, NULL, 9.75, 'Rishika Singh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(234, '1032240829', 5, 3, 0, '40', 4, 1, 1, NULL, NULL, 9.52, 'Aayush Vengurlekar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(235, 'S1032240823', 5, 3, 0, '34', 4, 1, 1, NULL, NULL, 8.68, 'Omkar Deenanath Tiwari', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(236, 'S1032250184', 5, 3, 0, '65', 4, 1, 1, NULL, NULL, 9.36, 'Fuzail Aqdas Abdul Qawi Khan', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(237, '1032240820', 5, 3, 0, '31', 4, 1, 1, NULL, NULL, 8.90, 'Devansh Tiwari', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(238, 'S1032240834', 5, 3, 0, '45', 4, 1, 1, NULL, NULL, 8.87, 'Gautam Vishwakarma', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(239, '1032240824', 5, 3, 0, '35', 4, 1, 1, NULL, NULL, 8.40, 'Prasham Tiwari', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(240, '1032240813', 5, 3, 0, '24', 4, 1, 1, NULL, NULL, 8.17, 'Saksham Rajesh Singh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(241, 'S1032240796', 5, 3, 0, '7', 4, 1, 1, NULL, NULL, 9.00, 'Janhavi Shrotriya', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(242, '1032240837', 5, 3, 0, '48', 4, 1, 1, NULL, NULL, 8.80, 'Shaili Vishwakarma', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(243, 'S1032240807', 5, 3, 0, '18', 4, 1, 1, NULL, NULL, 8.68, 'Dev singh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(244, '1032240800', 5, 3, 0, '11', 4, 1, 1, NULL, NULL, 9.59, 'Harsh Shukla', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(245, 'S1032240799', 5, 3, 0, '10', 4, 1, 1, NULL, NULL, 9.43, 'Girik Shukla', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(246, '1032250183', 5, 3, 0, '68', 4, 1, 1, NULL, NULL, 9.70, 'Aman Gupta', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(247, '1032240831', 5, 3, 0, '42', 4, 1, 1, NULL, NULL, 8.80, 'Ansh Viramgama', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(248, '1032240825', 5, 3, 0, '36', 4, 1, 1, NULL, NULL, 8.70, 'Disha Upwanshi', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(249, '1032240815', 5, 3, 0, '26', 4, 1, 1, NULL, NULL, 8.62, 'Shiva Sunil Singh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(250, 'S1032240801', 5, 3, 0, '12', 4, 1, 1, NULL, NULL, 8.40, 'Jatin Vinod Shekla', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(251, '1032240692', 5, 1, 0, '30', 4, 1, 9, NULL, NULL, 8.50, 'Sum4 Ghavri', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(252, '1032250174', 5, 1, 0, '70', 4, 1, 9, NULL, NULL, 8.50, 'Jhalak Tiwari', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(253, 'S1032240683', 5, 1, 0, '21', 4, 1, 10, NULL, NULL, 9.23, 'Tanishka Dombe', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(254, 'S1032240717', 5, 1, 0, '55', 4, 1, 10, NULL, NULL, 8.18, 'Muskan kapri', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(255, 'S1032240718', 5, 1, 0, '56', 4, 1, 10, NULL, NULL, 7.14, 'Yasoub kaunain', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(256, 'S1032240664', 5, 1, 0, '2', 4, 1, 10, NULL, NULL, 8.41, 'Harsh Agarwal', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(257, 'S1032240789', 5, 2, 0, '63', 4, 1, 10, NULL, NULL, 8.61, 'Shubh Wade', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(258, 'S1032240746', 5, 2, 0, '20', 4, 1, 10, NULL, NULL, 9.05, 'Shreyash Mahesh Mule', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(259, 'S1032240787', 5, 2, 0, '61', 4, 1, 10, NULL, NULL, 7.91, 'Pratik Sharma', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(260, '1032240771', 5, 2, 0, '45', 4, 1, 10, NULL, NULL, 8.55, 'Durgesh Rajesh Prasad', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(261, '1032240762', 5, 2, 0, '36', 4, 1, 10, NULL, NULL, 8.30, 'Rajas Patil', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(262, '1032230566', 5, 1, 0, '60', 4, 1, 5, NULL, NULL, 8.02, 'Aryan Kadam', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(263, 'S1032240701', 5, 1, 0, '39', 4, 1, 5, NULL, NULL, 9.36, 'Utsavi Rahul Gurjar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(264, '1032240671', 5, 1, 0, '9', 4, 1, 5, NULL, NULL, 9.27, 'Ayush Bind', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(265, 'S1032240763', 5, 2, 0, '37', 4, 1, 5, NULL, NULL, 9.68, 'Swapnil Patil', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(266, 'S1032240761', 5, 2, 0, '35', 4, 1, 5, NULL, NULL, 9.17, 'Manasvi Patel', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(267, '1032240782', 5, 2, 0, '56', 4, 1, 5, NULL, NULL, 9.45, 'Vedant Sawant', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(268, '1032240768', 5, 2, 0, '42', 4, 1, 5, NULL, NULL, 7.70, 'Parvesh prajapati', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(269, 'S1032240685', 5, 1, 0, '23', 4, 1, 2, NULL, NULL, 7.82, 'Arnav Doshi', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(270, 'S1032240677', 5, 1, 0, '15', 4, 1, 2, NULL, NULL, 9.76, 'Yash Chavan', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(271, '1032250175', 5, 1, 0, '68', 4, 1, 2, NULL, NULL, 8.68, 'Thakur Abhinav', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(272, '1032250170', 5, 1, 0, '67', 4, 1, 2, NULL, NULL, 9.86, 'Jidnyesh Badgujar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(273, 'S1032240772', 5, 2, 0, '46', 4, 1, 2, NULL, NULL, 8.95, 'Deepu Pushkar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(274, 'S1032240743', 5, 2, 0, '17', 4, 1, 2, NULL, NULL, 9.61, 'Riddhi More', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(275, 'S1032240736', 5, 2, 0, '10', 4, 1, 2, NULL, NULL, 9.49, 'Anushka Maurya', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(276, 'S1032240733', 5, 2, 0, '7', 4, 1, 2, NULL, NULL, 9.31, 'Sujoy Ma4y', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(277, 'S1032240775', 5, 2, 0, '49', 4, 1, 2, NULL, NULL, 9.50, 'Vishwa Rajendra Rajput', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(278, 'S1032250180', 5, 2, 0, '67', 4, 1, 2, NULL, NULL, 9.23, 'Ananya Singh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(279, 'S1032250179', 5, 2, 0, '68', 4, 1, 2, NULL, NULL, 9.18, 'Eshika Mishra', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(280, '1032250186', 5, 3, 0, '69', 4, 1, 2, NULL, NULL, 9.86, 'Urvi Shinde', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(281, 'S1032250182', 5, 3, 0, '66', 4, 1, 2, NULL, NULL, 9.82, 'Pranjal Mahendra Gawand', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(282, '1032240826', 5, 3, 0, '37', 4, 1, 2, NULL, NULL, 9.77, 'Manini Utekar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(283, '1032240797', 5, 3, 0, '8', 4, 1, 2, NULL, NULL, 9.47, 'Ad4i Shukla', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(284, 'S1032250187', 5, 3, 0, '67', 4, 1, 2, NULL, NULL, 9.50, 'Shwet Alok Singh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(285, '1032250185', 5, 3, 0, '70', 4, 1, 2, NULL, NULL, 9.91, 'Soham Dipen Ramjiyanj', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(286, 'S1032240839', 5, 3, 0, '50', 4, 1, 2, NULL, NULL, 9.05, 'Saeesha Wade', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(287, 'S1032240819', 5, 3, 0, '30', 4, 1, 2, NULL, NULL, 9.23, 'Khushi Madan Thakur', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(288, 'S1032240847', 5, 3, 0, '58', 4, 1, 2, NULL, NULL, 7.32, 'Riddhi yadav', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(289, 'S1032240816', 5, 3, 0, '27', 4, 1, 2, NULL, NULL, 9.82, 'Shvet Singh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(290, '1032240845', 5, 3, 0, '56', 4, 1, 2, NULL, NULL, 7.28, 'Arjun Yadav', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(291, '1032240838', 5, 3, 0, '49', 4, 1, 2, NULL, NULL, 8.90, 'Srishti Vishwakarma', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(292, 'S1032240850', 5, 3, 0, '61', 4, 1, 2, NULL, NULL, 8.64, 'Varsha Yadav', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(293, 'S1032240840', 5, 3, 0, '51', 4, 1, 2, NULL, NULL, 9.68, 'Shubham Yadav', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(294, 'S1032240849', 5, 3, 0, '60', 4, 1, 2, NULL, NULL, 7.45, 'Shivam yadav', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(295, '1032240711', 5, 1, 0, '49', 4, 1, 13, NULL, NULL, 7.00, 'Vin4 Kadam', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(296, '1032240710', 5, 1, 0, '48', 4, 1, 13, NULL, NULL, 7.00, 'Vedant Kadam', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(297, 'S1032240539', 5, 1, 0, '3', 5, 1, 11, NULL, NULL, 9.85, 'Dristi Dilip Agrawal', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(298, 'S1032240540', 5, 1, 0, '4', 5, 1, 2, NULL, NULL, 9.89, 'Shashank Mayur Barot', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(299, 'S1032240544', 5, 1, 0, '8', 5, 1, 1, NULL, NULL, 9.63, 'Priyanka Ramchandra Chavan', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(300, 'S1032240546', 5, 1, 0, '10', 5, 1, 1, NULL, NULL, 9.70, 'Tanmaya Deshpande', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(301, 'S1032240548', 5, 1, 0, '12', 5, 1, 12, NULL, NULL, 9.37, 'Pratham Divkar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(302, 'S1032240549', 5, 1, 0, '13', 5, 1, 2, NULL, NULL, 6.05, 'Krishna Dubey', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(303, 'S1032240553', 5, 1, 0, '17', 5, 1, 1, NULL, NULL, 9.29, 'Vaibhav Vimlendu Dwivedi', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(304, 'S1032240554', 5, 1, 0, '18', 5, 1, 1, NULL, NULL, 9.16, 'Sneha Gautam', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(305, 'S1032240556', 5, 1, 0, '20', 5, 1, 2, NULL, NULL, 7.67, 'Ad4ya Sanjay Gupta', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(306, 'S1032240557', 5, 1, 0, '21', 5, 1, 2, NULL, NULL, 9.86, 'Darshana Gupta', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(307, 'S1032240559', 5, 1, 0, '23', 5, 1, 2, NULL, NULL, 8.63, 'Prachi gupta', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(308, 'S1032240561', 5, 1, 0, '25', 5, 1, 2, NULL, NULL, 9.83, 'Shreya Rajkumar Gupta', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(309, 'S1032240562', 5, 1, 0, '26', 5, 1, 1, NULL, NULL, 8.96, 'Gupta Suraj Vimlesh Kumar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(310, 'S1032240565', 5, 1, 0, '29', 5, 1, 1, NULL, NULL, 8.60, 'Mohanish Jagushte', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(311, 'S1032240567', 5, 1, 0, '31', 5, 1, 1, NULL, NULL, 8.98, 'Krrish Jain', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(312, 'S1032240570', 5, 1, 0, '34', 5, 1, 1, NULL, NULL, 9.58, 'Akshat Jangid', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(313, 'S1032240571', 5, 1, 0, '35', 5, 1, 1, NULL, NULL, 8.76, 'Aman Prakash Jha', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(314, 'S1032240574', 5, 1, 0, '38', 5, 1, 2, NULL, NULL, 8.58, 'Vansh Joshi', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(315, 'S1032240576', 5, 1, 0, '40', 5, 1, 1, NULL, NULL, 7.00, 'mayuri janak kadam', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(316, 'S1032240580', 5, 1, 0, '44', 5, 1, 2, NULL, NULL, 7.14, 'Chetna keshari', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(317, 'S1032240581', 5, 1, 0, '45', 5, 1, 2, NULL, NULL, 9.12, 'Ank4 Sunil Kumar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(318, 'S1032240584', 5, 1, 0, '48', 5, 1, 14, NULL, NULL, 9.86, 'Tirth Ashok Kushwaha', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(319, 'S1032240585', 5, 1, 0, '49', 5, 1, 2, NULL, NULL, 9.55, 'vivek kushwaha', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(320, 'S1032240586', 5, 1, 0, '50', 5, 1, 2, NULL, NULL, 7.90, 'Ma4hili Gujar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(321, 'S1032240587', 5, 1, 0, '51', 5, 1, 2, NULL, NULL, 9.91, 'Tanmay Harish Malkapurkar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(322, 'S1032240588', 5, 1, 0, '52', 5, 1, 14, NULL, NULL, 9.60, 'Annirudha Narayana Mardi', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(323, 'S1032240590', 5, 1, 0, '54', 5, 1, 1, NULL, NULL, 7.00, 'Aayush Mehta', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(324, 'S1032240592', 5, 1, 0, '56', 5, 1, 2, NULL, NULL, 6.74, 'Aayush KrishnaKumar Mishra', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(325, 'S1032240594', 5, 1, 0, '58', 5, 1, 11, NULL, NULL, 6.70, 'Atharva mourya', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(326, 'S1032250160', 5, 1, 0, '65', 5, 1, 5, NULL, NULL, 7.45, 'Upadhyay R4esh Umesh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(327, 'S1032250158', 5, 1, 0, '66', 5, 1, 5, NULL, NULL, 6.50, 'Shaikh Mukeem Nabi', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(328, 'S1032250156', 5, 1, 0, '67', 5, 1, 5, NULL, NULL, 8.00, 'Avaneesh Gawde', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(329, 'S1032250157', 5, 1, 0, '68', 5, 1, 1, NULL, NULL, 9.32, 'Akshata Thaksen Patekar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(330, 'S1032250159', 5, 1, 0, '69', 5, 1, 1, NULL, NULL, 8.09, 'Riya Prakash Sawant', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(331, 'S1032250162', 5, 1, 0, '70', 5, 1, 5, NULL, NULL, 0.00, 'Riya Negi', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(332, 'S1032250161', 5, 1, 0, '71', 5, 1, 1, NULL, NULL, 8.64, 'Vedant  Kulkarni', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(333, 'S1032250155', 5, 1, 0, '72', 5, 1, 1, NULL, NULL, 0.00, 'Utkarsh Vivek Chaubey', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(334, 'S1032240582', 5, 1, 0, '46', 5, 1, 15, NULL, NULL, 8.43, 'Reejeeshraj Kumar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(335, 'S1032240601', 5, 2, 0, '2', 5, 1, 1, NULL, NULL, 9.80, 'Nidhi S4aram Pal', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(336, 'S1032240605', 5, 2, 0, '6', 5, 1, 11, NULL, NULL, 7.50, 'Rudram Panchal', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(337, 'S1032240608', 5, 2, 0, '9', 5, 1, 11, NULL, NULL, 9.47, 'Vaibhav', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(338, 'S1032240611', 5, 2, 0, '12', 5, 1, 11, NULL, NULL, 9.44, 'Aniket Mahesh Parte', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(339, 'S1032240613', 5, 2, 0, '14', 5, 1, 11, NULL, NULL, 9.08, 'Jay Jayesh Patel', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(340, 'S1032240615', 5, 2, 0, '16', 5, 1, 5, NULL, NULL, 9.64, 'Niha Satish Poojary', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(341, 'S1032240621', 5, 2, 0, '22', 5, 1, 11, NULL, NULL, 8.08, 'Omkar Ramavadh Prasad', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(342, 'S1032240622', 5, 2, 0, '23', 5, 1, 11, NULL, NULL, 9.57, 'Sahil Santosh Rai', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(343, 'S1032240623', 5, 2, 0, '24', 5, 1, 1, NULL, NULL, 9.32, 'Aachal Rasekar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(344, 'S1032240624', 5, 2, 0, '25', 5, 1, 12, NULL, NULL, 7.73, 'Atharv Nayan Ratate', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(345, 'S1032240628', 5, 2, 0, '29', 5, 1, 5, NULL, NULL, 8.53, 'Tiya Sattabhayya', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(346, 'S1032240630', 5, 2, 0, '31', 5, 1, 3, NULL, NULL, 8.05, 'Mohammad Hassan Shaikh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(347, 'S1032240634', 5, 2, 0, '35', 5, 1, 2, NULL, NULL, 6.00, 'Yug sharma', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(348, 'S1032240635', 5, 2, 0, '36', 5, 1, 1, NULL, NULL, 8.50, 'Shishir Shetty', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(349, 'S1032240636', 5, 2, 0, '37', 5, 1, 1, NULL, NULL, 7.76, 'Dhruv shinde', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(350, 'S1032240644', 5, 2, 0, '45', 5, 1, 1, NULL, NULL, 6.00, 'Pranjal Singh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(351, 'S1032240651', 5, 2, 0, '52', 5, 1, 2, NULL, NULL, 6.66, 'Atharva Telkar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(352, 'S1032240654', 5, 2, 0, '55', 5, 1, 1, NULL, NULL, 8.01, 'Saurabh Sushil Tiwari', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(353, 'S1032240657', 5, 2, 0, '58', 5, 1, 11, NULL, NULL, 10.00, 'Anuj Upadhyay', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(354, 'S1032240658', 5, 2, 0, '59', 5, 1, 14, NULL, NULL, 8.20, 'Kaavya Upadhyay', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(355, 'S1032250169', 5, 2, 0, '67', 5, 1, 7, NULL, NULL, 0.00, 'Om Yadav', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(356, 'S1032250166', 5, 2, 0, '68', 5, 1, 14, NULL, NULL, 0.00, 'Gautam Thakur', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(357, 'S1032250163', 5, 2, 0, '69', 5, 1, 10, NULL, NULL, 7.91, 'Shivraj Umakant Khandare', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(358, 'S1032250165', 5, 2, 0, '70', 5, 1, 5, NULL, NULL, 8.68, 'Pratidnya Mohan wakshe', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(359, 'S1032250168', 5, 2, 0, '71', 5, 1, 10, NULL, NULL, 0.00, 'Ad4ya Yadav', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(360, 'S1032250167', 5, 2, 0, '72', 5, 1, 10, NULL, NULL, 0.00, 'Yadav Abhijeet Rajesh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(361, 'S1032250164', 5, 2, 0, '73', 5, 1, 5, NULL, NULL, 7.68, 'Ganesh Lagad', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(362, 'S1032240318', 5, 0, 0, '3', 6, 1, 4, NULL, NULL, 7.60, 'Vignesh Bordikar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(363, 'S1032240329', 5, 0, 0, '14', 6, 1, 4, NULL, NULL, 9.30, 'Tanisha Giri', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(364, 'S1032240376', 5, 0, 0, '61', 6, 1, 4, NULL, NULL, 9.23, 'Abishta Veludandi', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(365, 'S1032240332', 5, 0, 0, '17', 6, 1, 11, NULL, NULL, 9.27, 'Aarya Gujar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(366, 'S1032240350', 5, 0, 0, '35', 6, 1, 11, NULL, NULL, 9.30, 'Aad4i Pawar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(367, 'S1032240357', 5, 0, 0, '42', 6, 1, 11, NULL, NULL, 9.62, 'Falakkhatoon Shaikh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(368, 'S1032240319', 5, 0, 0, '4', 6, 1, 9, NULL, NULL, 8.00, 'Ad4ya Chaudhari', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(369, 'S1032240323', 5, 0, 0, '8', 6, 1, 2, NULL, NULL, 8.63, 'Parthsarthi Choudhary', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(370, 'S1032240331', 5, 0, 0, '16', 6, 1, 2, NULL, NULL, 8.33, 'Rajshree Gouda', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(371, 'S1032240334', 5, 0, 0, '19', 6, 1, 2, NULL, NULL, 9.64, 'Tanvi Prakash Jabare', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(372, 'S1032250131', 5, 0, 0, '65', 6, 1, 2, NULL, NULL, 8.41, 'Sakshi Bari', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1);
INSERT INTO `lms_student_master` (`student_id`, `registration_no`, `class_id`, `division_id`, `grad_year`, `roll_no`, `department_id`, `specialization_id`, `specialization_subject_id`, `minor_course_id`, `minor_subject_id`, `cgpa`, `fname`, `mobile`, `email`, `mark_list`, `status`, `m_sem1`, `m_sem2`, `m_sem3`, `created_at`, `academic_year_id`, `current_semester_id`) VALUES
(373, 'S1032250134', 5, 0, 0, '66', 6, 1, 2, NULL, NULL, 8.18, 'Divyesh Dhananjay Desale', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(374, 'S1032250132', 5, 0, 0, '67', 6, 1, 2, NULL, NULL, 8.09, 'Yasir Irshad Khan', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(375, 'S1032250133', 5, 0, 0, '68', 6, 1, 2, NULL, NULL, 8.55, 'Arya Prasad Raverkar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(376, 'S1032250129', 5, 0, 0, '69', 6, 1, 2, NULL, NULL, 7.68, 'Sadhvi Ravipratap Singh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(377, '', 5, 0, 0, '70', 6, 1, 2, NULL, NULL, 0.00, 'Bhoomi Shrivastav', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(378, '', 5, 0, 0, '14', 7, 1, 1, NULL, NULL, 0.00, 'Roh4 Santosh Gupta', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(379, '', 5, 0, 0, '20', 7, 1, 1, NULL, NULL, 0.00, 'Santosh kamble', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(380, '', 5, 0, 0, '24', 7, 1, 4, NULL, NULL, 0.00, 'Anshul Malviya', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(381, '', 5, 0, 0, '40', 7, 1, 16, NULL, NULL, 0.00, 'Rozario Aaron Bonny', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(382, '', 5, 0, 0, '50', 7, 1, 1, NULL, NULL, 0.00, 'Kav4a Shukla', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(383, '', 5, 0, 0, '52', 7, 1, 1, NULL, NULL, 0.00, 'Shivansh Singh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(384, '', 5, 0, 0, '55', 7, 1, 2, NULL, NULL, 0.00, 'Vansh Timori', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(385, '', 5, 0, 0, '57', 7, 1, 1, NULL, NULL, 0.00, 'Khushi Unadkat', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(386, '', 5, 0, 0, '58', 7, 1, 16, NULL, NULL, 0.00, 'Upadhyay Aman Awadheshchandra', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(387, '', 5, 0, 0, '59', 7, 1, 1, NULL, NULL, 0.00, 'Shikha Verma', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(388, '', 5, 0, 0, '62', 7, 1, 1, NULL, NULL, 0.00, 'Ayush kamlesh yadav', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(389, '', 5, 0, 0, '63', 7, 1, 1, NULL, NULL, 0.00, 'Yadav J4endra Subhashchandra', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(390, '', 5, 0, 0, '67', 7, 1, 1, NULL, NULL, 0.00, 'Varuna Santosh Karande', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(391, '', 5, 0, 0, '6', 7, 1, 13, NULL, NULL, 0.00, 'Kunal Dubey', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(392, 'S1032240173', 5, 0, 0, '5', 8, 1, 17, NULL, NULL, 9.79, 'Saiesh Deshpande', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(393, 'S1032240189', 5, 0, 0, '21', 8, 1, 17, NULL, NULL, 8.19, 'Om Koltharkar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(394, 'S1032240190', 5, 0, 0, '22', 8, 1, 17, NULL, NULL, 9.24, 'Samihan Dipak Kulkarni', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(395, 'S1032240194', 5, 0, 0, '26', 8, 1, 18, NULL, NULL, 8.60, 'Manthan Mutha', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(396, 'S1032240196', 5, 0, 0, '28', 8, 1, 17, NULL, NULL, 7.36, 'Taran Ramakant Nevalkar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(397, 'S1032240204', 5, 0, 0, '36', 8, 1, 18, NULL, NULL, 8.35, 'Anas Qureshi', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(398, 'S1032240220', 5, 0, 0, '54', 8, 1, 17, NULL, NULL, 9.40, 'Bhavik Ramsagar Singh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(399, 'S1032240232', 5, 0, 0, '64', 8, 1, 17, NULL, NULL, 7.60, 'Spandan Yeole', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(400, 'S1032250128', 5, 0, 0, '66', 8, 1, 17, NULL, NULL, 8.55, 'Prathamesh Vasudev Bhagade', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(401, 'S1032250125', 5, 0, 0, '67', 8, 1, 17, NULL, NULL, 8.14, 'Ganesh Ishwar Birajdar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(402, 'S1032250124', 5, 0, 0, '68', 8, 1, 18, NULL, NULL, 7.73, 'Bhagyashree Jethwa', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(403, 'S1032240254', 5, 0, 0, '3', 9, 1, 1, NULL, NULL, 7.91, 'Kushagra Agarwal', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(404, 'S1032240255', 5, 0, 0, '4', 9, 1, 1, NULL, NULL, 9.82, 'Kushal Laxmikant Borse', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(405, 'S1032240259', 5, 0, 0, '8', 9, 1, 1, NULL, NULL, 8.52, 'Parth Santosh Dubey', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(406, 'S1032240260', 5, 0, 0, '9', 9, 1, 1, NULL, NULL, 8.90, 'Shivam Dubey', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(407, 'S1032240261', 5, 0, 0, '10', 9, 1, 2, NULL, NULL, 7.90, 'Rahul Gupta', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(408, 'S1032240262', 5, 0, 0, '11', 9, 1, 1, NULL, NULL, 10.00, 'Shreeya Jain', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(409, '1032240263S', 5, 0, 0, '12', 9, 1, 1, NULL, NULL, 9.93, 'Vrishti Nilesh Jain', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(410, '1032240265', 5, 0, 0, '14', 9, 1, 4, NULL, NULL, 8.73, 'Samartha Anil Jarad', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(411, 'S1032240267', 5, 0, 0, '16', 9, 1, 4, NULL, NULL, 8.20, 'Charvi Joshi', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(412, 'S1032240270', 5, 0, 0, '19', 9, 1, 1, NULL, NULL, 9.44, 'Sahil Kumbhar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(413, 'S1032240271', 5, 0, 0, '20', 9, 1, 1, NULL, NULL, 8.23, 'Tanishka Vijay Jaiswal', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(414, 'S1032240272', 5, 0, 0, '21', 9, 1, 4, NULL, NULL, 7.81, 'Anjali Mergu', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(415, 'S1032240273', 5, 0, 0, '22', 9, 1, 1, NULL, NULL, 8.09, 'Devyansh Mishra', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(416, 'S1032240276', 5, 0, 0, '25', 9, 1, 10, NULL, NULL, 7.53, 'Tushar Mohta', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(417, 'S1032240278', 5, 0, 0, '27', 9, 1, 10, NULL, NULL, 7.50, 'Shiva Maurya', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(418, 'S1032240281', 5, 0, 0, '30', 9, 1, 1, NULL, NULL, 9.45, 'Mugdha Patel', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(419, 'S1032240282', 5, 0, 0, '31', 9, 1, 10, NULL, NULL, 7.10, 'Yash Patel', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(420, '1032240283', 5, 0, 0, '32', 9, 1, 4, NULL, NULL, 8.26, 'Kishan Pathak', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(421, '1032240284', 5, 0, 0, '33', 9, 1, 5, NULL, NULL, 8.90, 'Shreya Nilesh Patil', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(422, 'S1032240285', 5, 0, 0, '34', 9, 1, 4, NULL, NULL, 8.59, 'Ananya Sandeep Pillai', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(423, 'S1032240289', 5, 0, 0, '38', 9, 1, 4, NULL, NULL, 9.77, 'Saee Sawant', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(424, '1032240290', 5, 0, 0, '39', 9, 1, 4, NULL, NULL, 9.68, 'Shruti Vilas Sawant', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(425, 'S1032240291', 5, 0, 0, '40', 9, 1, 4, NULL, NULL, 8.36, 'Ash4a J4endra Sharma', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(426, 'S1032240293', 5, 0, 0, '42', 9, 1, 1, NULL, NULL, 7.12, 'Atharva Shelar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(427, '103224095', 5, 0, 0, '44', 9, 1, 1, NULL, NULL, 8.50, 'Prastuth Suresh Shetty', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(428, '1032240297', 5, 0, 0, '46', 9, 1, 4, NULL, NULL, 8.60, 'Ananya Shirke', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(429, 'S1032240298', 5, 0, 0, '47', 9, 1, 10, NULL, NULL, 7.91, 'Aryan Virendra Shukla', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(430, 'S1032240299', 5, 0, 0, '48', 9, 1, 2, NULL, NULL, 9.85, 'Soham J4endra Shukla', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(431, 'S1032240303', 5, 0, 0, '52', 9, 1, 1, NULL, NULL, 7.50, 'Nikhil Singh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(432, 'S1032240314', 5, 0, 0, '63', 9, 1, 4, NULL, NULL, 9.91, 'Pooja Yadav', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(433, 'S1032240563', 5, 0, 0, '65', 9, 1, 1, NULL, NULL, 8.90, 'Harshil Hemani', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(434, '1032250150', 5, 0, 0, '67', 9, 1, 4, NULL, NULL, 8.86, 'Yamini Yogesh Gaikar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(435, '1032250153', 5, 0, 0, '68', 9, 1, 4, NULL, NULL, 9.36, 'Seema Kushwaha', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(436, 'S1032550152', 5, 0, 0, '69', 9, 1, 1, NULL, NULL, 8.36, 'Durgesh Mahindrakar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(437, 'S1032250151', 5, 0, 0, '72', 9, 1, 1, NULL, NULL, 8.36, 'Roh4', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(438, 'S1032250149', 5, 0, 0, '70', 9, 1, 10, NULL, NULL, 0.00, 'Arch4a Sharma', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(439, 'S1032240413', 5, 0, 0, '2', 10, 1, 1, NULL, NULL, 8.20, 'Yadnyesh Rajesh Andhari', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(440, 'S1032240414', 5, 0, 0, '3', 10, 1, 13, NULL, NULL, 7.02, 'Rayaan Ansari', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(441, 'MU0341120240218951', 5, 0, 0, '6', 10, 1, 1, NULL, NULL, 9.20, 'Vedantika Danavale', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(442, '24-MME-28', 5, 0, 0, '14', 10, 1, 1, NULL, NULL, 8.50, 'Saurabh Gupta', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(443, 'S1032240426', 5, 0, 0, '15', 10, 1, 19, NULL, NULL, 8.05, 'Arnav Hake', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(444, '1032240429', 5, 0, 0, '18', 10, 1, 20, NULL, NULL, 7.73, 'Anup Sanjay Jha', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(445, '1032240430', 5, 0, 0, '19', 10, 1, 20, NULL, NULL, 7.30, 'Parshuram Jha', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(446, '1032240437', 5, 0, 0, '26', 10, 1, 5, NULL, NULL, 7.30, 'Masoom Monil Hathi', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(447, '24-MME28-28', 5, 0, 0, '28', 10, 1, 19, NULL, NULL, 8.40, 'Yukta Mayekar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(448, '24-MME29-28', 5, 0, 0, '29', 10, 1, 1, NULL, NULL, 8.75, 'Sia Mehta', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(449, 'S1032240443', 5, 0, 0, '32', 10, 1, 1, NULL, NULL, 7.82, 'Manushree Naik', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(450, '1032240444', 5, 0, 0, '33', 10, 1, 19, NULL, NULL, 8.89, 'Jim4 oza', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(451, 'S1032240447', 5, 0, 0, '36', 10, 1, 19, NULL, NULL, 9.32, 'Harsh Parasnis', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(452, '123860042', 5, 0, 0, '38', 10, 1, 19, NULL, NULL, 8.14, 'Gautam patil', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(453, '1032240450', 5, 0, 0, '39', 10, 1, 5, NULL, NULL, 8.59, 'Rushikesh Patil', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(454, '', 5, 0, 0, '40', 10, 1, 19, NULL, NULL, 7.38, 'Vedant Pawar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(455, 'S1032240452', 5, 0, 0, '41', 10, 1, 1, NULL, NULL, 8.40, 'Suraj Prajapati', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(456, 'S1032240456', 5, 0, 0, '45', 10, 1, 4, NULL, NULL, 7.28, 'Maniya', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(457, 'S1032240471', 5, 0, 0, '60', 10, 1, 11, NULL, NULL, 9.38, 'Shubham Shivshankar Varma', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(458, '1032240472', 5, 0, 0, '61', 10, 1, 19, NULL, NULL, 7.80, 'Kisan Vishwakarma', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(459, '', 5, 1, 0, '38', 2, 1, 7, NULL, NULL, 9.73, 'Saumya Santosh Gupta', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(460, '', 5, 1, 0, '55', 2, 1, 5, NULL, NULL, 8.50, 'Ankush Saroj jha', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(461, '', 5, 1, 0, '39', 2, 1, 3, NULL, NULL, 8.84, 'Sejal Mahesh Gupta', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(462, '', 5, 1, 0, '47', 2, 1, 7, NULL, NULL, 9.82, 'Hardik Kamlesh Jain', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(463, '', 5, 1, 0, '6', 2, 1, 7, NULL, NULL, 9.82, 'Bala Sudalaimuthu', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(464, '', 5, 1, 0, '59', 2, 1, 7, NULL, NULL, 9.73, 'Siddharth Jha', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(465, '', 5, 1, 0, '50', 2, 1, 7, NULL, NULL, 9.86, 'Nirek Jaiswal', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(466, '', 5, 1, 0, '51', 2, 1, 7, NULL, NULL, 8.64, 'Piyush Shivkumar Jaiswal', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(467, '', 5, 1, 0, '36', 2, 1, 7, NULL, NULL, 9.45, 'Hemant Gupta', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(468, '', 5, 1, 0, '61', 2, 1, 7, NULL, NULL, 9.91, 'Anand Maruti Kalambe', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(469, '', 5, 1, 0, '58', 2, 1, 7, NULL, NULL, 9.14, 'Shekharkumar Jha', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(470, '', 5, 1, 0, '57', 2, 1, 7, NULL, NULL, 7.68, 'Mantu Satish Jha', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(471, '', 5, 1, 0, '21', 2, 1, 5, NULL, NULL, 7.25, 'Anurag Deb', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(472, '', 5, 1, 0, '53', 2, 1, 4, NULL, NULL, 8.77, 'Ganesh Raju Jani', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(473, '', 5, 1, 0, '3', 2, 1, 7, NULL, NULL, 9.70, 'Sahil Ambekar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(474, '', 5, 1, 0, '2', 2, 1, 5, NULL, NULL, 7.86, 'Krish Ambani', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(475, '', 5, 1, 0, '34', 2, 1, 7, NULL, NULL, 9.32, 'Bhoomi Gupta', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(476, '', 5, 1, 0, '30', 2, 1, 7, NULL, NULL, 9.09, 'Gaurav Giri', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(477, '', 5, 1, 0, '17', 2, 1, 7, NULL, NULL, 7.97, 'Ranjeet Singh Chauhan', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(478, '', 5, 1, 0, '43', 2, 1, 10, NULL, NULL, 8.34, 'Swati Gupta', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(479, '', 5, 1, 0, '40', 2, 1, 3, NULL, NULL, 8.33, 'Shweta gupta', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(480, '', 5, 2, 0, '2', 2, 1, 4, NULL, NULL, 9.57, 'Ridhi Karn', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(481, '', 5, 2, 0, '5', 2, 1, 7, NULL, NULL, 7.95, 'Khan Mohd Usaid Asad', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(482, '', 5, 2, 0, '14', 2, 1, 5, NULL, NULL, 9.14, 'Alok Sharad Mahadik', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(483, '', 5, 2, 0, '17', 2, 1, 5, NULL, NULL, 8.77, 'Anuj Maurya', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(484, '', 5, 2, 0, '20', 2, 1, 7, NULL, NULL, 8.77, 'Varun Maurya', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(485, '', 5, 2, 0, '62', 2, 1, 7, NULL, NULL, 8.49, 'M4eshkumar Deeparam Puroh4', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(486, '', 5, 2, 0, '16', 2, 1, 7, NULL, NULL, 8.35, 'Prakash Mandal', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(487, '', 5, 2, 0, '1', 2, 1, 5, NULL, NULL, 8.36, 'Yuvraj Kanojiya', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(488, '', 5, 2, 0, '68', 2, 1, 7, NULL, NULL, 9.27, 'Arya Anil Pawar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(489, '', 5, 2, 0, '6', 2, 1, 7, NULL, NULL, 8.41, 'Sarvadnya Madhav Kharde', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(490, '', 5, 2, 0, '66', 2, 1, 7, NULL, NULL, 9.18, 'Harshini Mishal', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(491, '', 5, 2, 0, '33', 2, 1, 5, NULL, NULL, 7.50, 'Prajwal Nangarepatil', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(492, '', 5, 2, 0, '42', 2, 1, 7, NULL, NULL, 8.00, 'Aparna Pandey', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(493, '', 5, 2, 0, '35', 2, 1, 5, NULL, NULL, 9.09, 'Nipun Raj', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(494, '', 5, 2, 0, '27', 2, 1, 5, NULL, NULL, 7.64, 'Suryakant Mishra', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(495, '', 5, 2, 0, '25', 2, 1, 5, NULL, NULL, 9.42, 'Satyam Mishra', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(496, '', 5, 2, 0, '44', 2, 1, 7, NULL, NULL, 9.00, 'Sum4 Panigrahi', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(497, '', 5, 2, 0, '7', 2, 1, 5, NULL, NULL, 8.82, 'Yashavi Jayprakash Kharwar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(498, '', 5, 2, 0, '4', 2, 1, 5, NULL, NULL, 8.03, 'Khan Arsalaan Mohammed Moomshad', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(499, '', 5, 2, 0, '31', 2, 1, 5, NULL, NULL, 7.60, 'Arman Rahiman Mulani', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(500, '', 5, 2, 0, '53', 2, 1, 7, NULL, NULL, 7.93, 'Dikshant Pednekar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(501, '', 5, 2, 0, '51', 2, 1, 3, NULL, NULL, 8.77, 'Soham Milind Patil', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(502, '', 5, 2, 0, '69', 2, 1, 7, NULL, NULL, 9.64, 'Abhij4h Mahesh Shetty', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(503, '', 5, 2, 0, '65', 2, 1, 7, NULL, NULL, 9.32, 'Aaryan Santosh Chavan', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(504, '', 5, 2, 0, '61', 2, 1, 7, NULL, NULL, 7.97, 'Pooja Puri', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(505, '', 5, 2, 0, '15', 2, 1, 5, NULL, NULL, 8.42, 'Karthik Santosh Mahajan', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(506, '', 5, 2, 0, '52', 2, 1, 7, NULL, NULL, 7.68, 'Tushar Patil', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(507, '', 5, 2, 0, '9', 2, 1, 10, NULL, NULL, 8.30, 'Piyush Kishnani', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(508, '', 5, 2, 0, '55', 2, 1, 5, NULL, NULL, 9.23, 'Piyush Neeraj Prajapati', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(509, '', 5, 2, 0, '36', 2, 1, 5, NULL, NULL, 8.14, 'Aakash Nishad', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(510, '', 5, 2, 0, '40', 2, 1, 7, NULL, NULL, 9.29, 'Ad4ya Vijayshankar Pandey', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(511, '', 5, 2, 0, '32', 2, 1, 5, NULL, NULL, 7.82, 'Swayam naik', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(512, '', 5, 2, 0, '45', 2, 1, 5, NULL, NULL, 8.63, 'Henil', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(513, '', 5, 2, 0, '21', 2, 1, 14, NULL, NULL, 7.50, 'Atharva Mhapsekar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(514, '', 5, 2, 0, '18', 2, 1, 14, NULL, NULL, 7.58, 'Harsh Maurya', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(515, '', 5, 2, 0, '19', 2, 1, 14, NULL, NULL, 7.50, 'N4in Maurya', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(516, '', 5, 3, 0, '63', 2, 1, 8, NULL, NULL, 8.47, 'Yash Manojkumar Yadav', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(517, '', 5, 3, 0, '34', 2, 1, 7, NULL, NULL, 8.09, 'Shivam Singh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(518, '', 5, 3, 0, '28', 2, 1, 7, NULL, NULL, 8.98, 'Ansh Singh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(519, '', 5, 3, 0, '62', 2, 1, 7, NULL, NULL, 8.80, 'Vishal Arvind Yadav', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(520, '', 5, 3, 0, '59', 2, 1, 10, NULL, NULL, 9.24, 'Riya Yadav', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(521, '', 5, 3, 0, '53', 2, 1, 10, NULL, NULL, 8.59, 'Pranav Vishwakarma', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(522, '', 5, 3, 0, '27', 2, 1, 7, NULL, NULL, 9.36, 'Amar Ajay Singh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(523, '', 5, 3, 0, '29', 2, 1, 7, NULL, NULL, 8.55, 'Bhavesh sona singh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(524, '', 5, 3, 0, '31', 2, 1, 19, NULL, NULL, 9.20, 'Priya Prashant Singh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(525, '', 5, 3, 0, '54', 2, 1, 7, NULL, NULL, 9.66, 'Sanch4a Sanjay Warkad', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(526, '', 5, 3, 0, '32', 2, 1, 7, NULL, NULL, 9.20, 'Pushkar Singh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(527, '', 5, 3, 0, '26', 2, 1, 7, NULL, NULL, 8.91, 'Ad4ya Manoj Singh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(528, '', 5, 3, 0, '58', 2, 1, 7, NULL, NULL, 9.61, 'Pulk4 Yadav', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(529, '', 5, 3, 0, '56', 2, 1, 7, NULL, NULL, 8.10, 'Yadav Ashu Subash', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(530, '', 5, 3, 0, '47', 2, 1, 7, NULL, NULL, 7.40, 'Varma saurabh santosh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(531, '', 5, 3, 0, '4', 2, 1, 7, NULL, NULL, 9.12, 'Am4 Kr. Sahu', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(532, '', 5, 3, 0, '45', 2, 1, 7, NULL, NULL, 8.20, 'Shivam Upadhyay', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(533, '', 5, 3, 0, '30', 2, 1, 7, NULL, NULL, 8.50, 'Prakash singh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(534, '', 5, 3, 0, '39', 2, 1, 8, NULL, NULL, 9.91, 'Diya Tailor', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(535, '', 5, 3, 0, '41', 2, 1, 7, NULL, NULL, 7.00, 'Atharva Tandel', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(536, '', 5, 3, 0, '43', 2, 1, 7, NULL, NULL, 9.23, 'Vedika Thorat', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(537, '', 5, 3, 0, '21', 2, 1, 8, NULL, NULL, 9.68, 'Nidhi Dilipkumar Shukla', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(538, '', 5, 3, 0, '1', 2, 1, 8, NULL, NULL, 8.95, 'Rudransh Puthan', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(539, '', 5, 3, 0, '35', 2, 1, 7, NULL, NULL, 9.73, 'Shruti Shailendra Kumar Singh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(540, '', 5, 3, 0, '42', 2, 1, 4, NULL, NULL, 8.77, 'Pratham Thakur', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(541, '', 5, 3, 0, '52', 2, 1, 7, NULL, NULL, 8.02, 'Abhishek Vishwakarma', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(542, '', 5, 3, 0, '46', 2, 1, 7, NULL, NULL, 9.59, 'Parag Valam', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(543, '', 5, 3, 0, '60', 2, 1, 7, NULL, NULL, 9.59, 'Ruchi Yadav', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(544, '', 5, 3, 0, '25', 12, 1, 3, NULL, NULL, 8.70, 'Jiya Singh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(545, '', 5, 3, 0, '32', 12, 1, 3, NULL, NULL, 9.63, 'Samiksha Singh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(546, '', 5, 3, 0, '18', 12, 1, 3, NULL, NULL, 9.47, 'Shweta Sanjay Shukla', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(547, '', 5, 3, 0, '51', 12, 1, 3, NULL, NULL, 9.39, 'Jagdish Wagh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(548, '', 5, 3, 0, '21', 12, 1, 3, NULL, NULL, 9.34, 'Arp4a Singh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(549, '', 5, 3, 0, '53', 12, 1, 4, NULL, NULL, 7.62, 'Abhishek Surendra yadav', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(550, '', 5, 1, 0, '23', 12, 1, 4, NULL, NULL, 9.21, 'Heer Dave', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(551, '', 5, 1, 0, '67', 12, 1, 4, NULL, NULL, 8.32, 'Sakshi Maurya', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(552, '', 5, 1, 0, '49', 12, 1, 4, NULL, NULL, 9.98, 'Laksh4a Hingar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(553, '', 5, 1, 0, '19', 12, 1, 4, NULL, NULL, 9.24, 'Ramkumar Chaurasiya', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(554, '', 5, 1, 0, '50', 12, 1, 4, NULL, NULL, 9.18, 'Anushka Mangesh Jadhav', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(555, '', 5, 3, 0, '11', 12, 1, 4, NULL, NULL, 9.33, 'Om Sharma', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(556, '', 5, 3, 0, '45', 12, 1, 10, NULL, NULL, 9.20, 'Ved Sunil Nalavade', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(557, '', 5, 1, 0, '8', 12, 1, 10, NULL, NULL, 8.26, 'Ashr4 Bang', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(558, '', 5, 1, 0, '44', 12, 1, 10, NULL, NULL, 8.00, 'Gaurav Rajesh Gupta', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(559, '', 5, 1, 0, '38', 12, 1, 5, NULL, NULL, 7.87, 'Gaurav Manoj Thakur', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(560, '', 5, 3, 0, '62', 12, 1, 5, NULL, NULL, 9.36, 'Sudhirkumar Maganlal Yadav', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(561, '', 5, 1, 0, '11', 12, 1, 5, NULL, NULL, 9.64, 'Sanskar Sanjay Bhoir', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(562, '', 5, 1, 0, '52', 12, 1, 5, NULL, NULL, 9.72, 'Soham Nishant Jain', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(563, '', 5, 1, 0, '55', 12, 1, 5, NULL, NULL, 8.95, 'N4in Nandlal Jaiswal', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(564, '', 5, 1, 0, '51', 12, 1, 5, NULL, NULL, 9.30, 'Bhavik Jain', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(565, '', 5, 1, 0, '48', 12, 1, 5, NULL, NULL, 7.92, 'Satyam Gupta', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(566, '', 5, 1, 0, '60', 12, 1, 5, NULL, NULL, 9.03, 'Gyaneshwar Jha', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(567, '', 5, 1, 0, '5', 12, 1, 5, NULL, NULL, 7.45, 'Omkar Auti', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(568, '', 5, 2, 0, '52', 12, 1, 5, NULL, NULL, 8.14, 'Prateek Prajapati', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(569, '', 5, 1, 0, '56', 12, 1, 5, NULL, NULL, 8.86, 'Ayush Jaju', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(570, '', 5, 3, 0, '34', 12, 1, 6, NULL, NULL, 7.63, 'Sushil singh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(571, '', 5, 1, 0, '14', 12, 1, 21, NULL, NULL, 9.80, 'Vedant Bist', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(572, '', 5, 3, 0, '63', 12, 1, 7, NULL, NULL, 8.00, 'Ujala Yadav', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(573, '', 5, 1, 0, '1', 12, 1, 7, NULL, NULL, 8.96, 'Abdul Samad', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(574, '', 5, 2, 0, '28', 12, 1, 22, NULL, NULL, 8.47, 'Adh4hya Haridas Nair', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(575, '', 5, 3, 0, '42', 12, 1, 7, NULL, NULL, 8.80, 'Shreyash Tiwari', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(576, '', 5, 3, 0, '43', 12, 1, 7, NULL, NULL, 9.00, 'Gangotrinath Tripathi', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(577, '', 5, 3, 0, '6', 12, 1, 22, NULL, NULL, 7.50, 'Anoop Sanjay Sharma', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(578, '', 5, 0, 0, '2', 13, 1, 1, NULL, NULL, 9.26, 'Aryan Prashant Bhalerao', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(579, '', 5, 0, 0, '5', 13, 1, 1, NULL, NULL, 7.20, 'Tushar Dix4', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(580, '', 5, 0, 0, '7', 13, 1, 1, NULL, NULL, 7.15, 'Kanhayya Kailas Gupta', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(581, '', 5, 0, 0, '17', 13, 1, 1, NULL, NULL, 9.37, 'Anuraag nair', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(582, '', 5, 0, 0, '18', 13, 1, 1, NULL, NULL, 8.20, 'Smr4i Pandey', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(583, '', 5, 0, 0, '28', 13, 1, 1, NULL, NULL, 8.59, 'Zabal Thakar', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(584, '', 5, 0, 0, '30', 13, 1, 1, NULL, NULL, 7.48, 'Saurabh Tripathi', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(585, '', 5, 0, 0, '34', 13, 1, 1, NULL, NULL, 9.18, 'Sheetal Bhendigeri', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(586, '', 5, 0, 0, '9', 13, 1, 2, NULL, NULL, 7.81, 'Ramkrishna jha', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(587, '', 5, 0, 0, '11', 13, 1, 2, NULL, NULL, 7.55, 'Niraj kanojiya', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(588, '', 5, 0, 0, '14', 13, 1, 2, NULL, NULL, 8.42, 'Daksh Am4 Khut', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(589, '', 5, 0, 0, '20', 13, 1, 2, NULL, NULL, 8.80, 'Rajpuroh4 Sanjaysing Lunkaran', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(590, '', 5, 0, 0, '22', 13, 1, 2, NULL, NULL, 8.38, 'Tejas Ramesh Sharma', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(591, '', 5, 0, 0, '23', 13, 1, 2, NULL, NULL, 8.36, 'Shreya Santosh Shrivastav', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(592, '', 5, 0, 0, '32', 13, 1, 2, NULL, NULL, 7.30, 'Shivam Yadav', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(593, '', 5, 0, 0, '24', 13, 1, 3, NULL, NULL, 8.07, 'Deep Singh', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(594, '', 5, 0, 0, '12', 13, 1, 10, NULL, NULL, 8.91, 'Payal Kawale', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(595, '', 5, 0, 0, '10', 13, 1, 5, NULL, NULL, 8.91, 'Anand kalirana', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(596, '', 5, 0, 0, '16', 13, 1, 5, NULL, NULL, 7.23, 'Shreya Mishra', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(597, '', 5, 0, 0, '31', 13, 1, 5, NULL, NULL, 8.04, 'Yatin varma', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(598, '', 5, 0, 0, '1', 13, 1, 19, NULL, NULL, 7.60, 'Girik Arora', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', 1, 1),
(599, 'B1032243044', 2, 5, 1, '33', 1, 3, 18, NULL, NULL, NULL, 'Why AmI', '8997232773', 'why@tcetmumbai.in', NULL, 1, '[]', '[]', '[]', '2026-04-23 09:33:22', NULL, 1),
(620, '43848388347', 3, 6, NULL, '33', 5, 4, NULL, NULL, NULL, 7.00, '33', '3333333333', 'mohan@tcetmumbai.in', NULL, 1, '[]', '[]', '[]', '2026-04-29 06:59:50', 2, 8),
(622, '323899382', 3, 3, NULL, '33', 4, 4, 16, NULL, NULL, 8.00, 'ui-0', '3333333333', 'u@tcetmumbai.in', NULL, 1, '[]', '[]', '[]', '2026-04-29 07:52:37', 2, 6),
(627, '0987612348', 6, 3, NULL, '8', 4, 2, 18, NULL, NULL, 9.00, 'uuui', '7898787878', 'u@tcetmumbai.in', NULL, 1, '[]', '[]', '[]', '2026-04-29 13:02:16', 2, 6),
(628, '0987612344', 6, 3, NULL, '8', 4, 2, 18, NULL, NULL, 9.00, 'uuui', '7898787878', 'u@tcetmumbai.in', NULL, 1, '[]', '[]', '[]', '2026-04-29 13:03:12', 2, 6),
(629, '098761237', 3, 3, NULL, '8', 4, 4, NULL, 9, 48, 8.00, 'uuui', '7898787878', 'u@tcetmumbai.in', NULL, 1, '[]', '[]', '[]', '2026-04-29 13:04:08', 2, 6),
(631, '437874387473', 1, 4, NULL, '9', 4, 3, NULL, NULL, NULL, 7.00, 'iufrfuf', '3883298933', 'ui@tcetmumbai.in', NULL, 1, '[]', '[]', '[]', '2026-04-29 13:11:01', 1, 3),
(634, '09876123450', 6, 4, 2028, '8', 3, 4, NULL, 8, 45, 9.00, 'uqwuiwq', '9210219210', 'u@tcetmumbai.in', NULL, 1, '[]', '[]', '[]', '2026-04-29 13:39:12', 1, 2),
(635, '7878787', 3, 3, 2028, 'u', 3, 1, 17, NULL, NULL, 9.00, 'jjkas', '2192902102', 'ui@tcetmumbai.in', NULL, 1, '[]', '[]', '[]', '2026-04-29 13:40:33', 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `lms_student_master_old`
--

CREATE TABLE `lms_student_master_old` (
  `student_id` int(11) NOT NULL,
  `academic_year` varchar(100) NOT NULL,
  `registration_no` varchar(200) NOT NULL,
  `joining_date` varchar(100) NOT NULL,
  `class_id` int(11) NOT NULL,
  `division_id` int(11) NOT NULL,
  `batch_id` int(11) DEFAULT NULL,
  `roll_no` varchar(50) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `specialization_id` int(11) DEFAULT NULL,
  `specialization_subject_id` int(11) DEFAULT NULL,
  `cgpa` decimal(4,2) DEFAULT NULL,
  `fname` varchar(100) NOT NULL,
  `mname` varchar(100) DEFAULT NULL,
  `lname` varchar(100) DEFAULT NULL,
  `dob` varchar(100) DEFAULT NULL,
  `gender` varchar(50) DEFAULT NULL,
  `nationality` varchar(100) DEFAULT NULL,
  `apaar_id` varchar(100) DEFAULT NULL,
  `uan` varchar(100) DEFAULT NULL,
  `pan` varchar(100) DEFAULT NULL,
  `permanent_address` text DEFAULT NULL,
  `present_address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `pincode` varchar(20) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `mark_list` varchar(255) DEFAULT NULL,
  `birth_certificate` varchar(255) DEFAULT NULL,
  `transfer_certificate` varchar(255) DEFAULT NULL,
  `caste_certificate` varchar(255) DEFAULT NULL,
  `migration_certificate` varchar(255) DEFAULT NULL,
  `affidavit` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lms_student_master_old`
--

INSERT INTO `lms_student_master_old` (`student_id`, `academic_year`, `registration_no`, `joining_date`, `class_id`, `division_id`, `batch_id`, `roll_no`, `department_id`, `specialization_id`, `specialization_subject_id`, `cgpa`, `fname`, `mname`, `lname`, `dob`, `gender`, `nationality`, `apaar_id`, `uan`, `pan`, `permanent_address`, `present_address`, `city`, `pincode`, `country`, `state`, `phone`, `mobile`, `email`, `photo`, `mark_list`, `birth_certificate`, `transfer_certificate`, `caste_certificate`, `migration_certificate`, `affidavit`, `status`, `created_at`) VALUES
(1, '2026 - 2027', '55', '2004-01-02', 2, 1, 1, '51', 1, 1, 1, 8.00, '', '', '', '', 'Male', 'INDIAN', '', '', '', '                ', '                ', '', '', 'India', 'Maharashtra', '', '', '', '', '', '', '', '', '', '', 0, '2026-04-16 13:44:07'),
(2, '2026 - 2027', '555', '2026-04-23', 2, 2, 1, '21', 1, 1, 1, 8.00, 'Ashutosh', '', 'Pandey', '', 'Male', 'INDIAN', '', '', '', 'A/102 Krishna Vihar Apt, Opp Firebigade Office, Vasai East', '                ', 'Vasai', '401209', 'India', 'Maharashtra', '9702420582', '', 'ashutosh3276s16@gmail.com', '', '', '', '', '', '', '', 1, '2026-04-17 06:27:11'),
(3, '2026 - 2027', '', '', 0, 0, 0, '', 0, 2, 0, NULL, '', '', '', '', 'Male', 'INDIAN', '', '', '', '                ', '                ', '', '', 'India', 'Maharashtra', '', '', '', '', '', '', '', '', '', '', 1, '2026-04-17 06:37:47');

-- --------------------------------------------------------

--
-- Table structure for table `lms_sub_menu_master`
--

CREATE TABLE `lms_sub_menu_master` (
  `sub_menu_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `sub_menu_name` varchar(100) NOT NULL,
  `sub_menu_icon` varchar(100) NOT NULL DEFAULT 'fa fa-angle-double-right',
  `sub_menu_route` varchar(255) NOT NULL DEFAULT '#'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lms_sub_menu_master`
--

INSERT INTO `lms_sub_menu_master` (`sub_menu_id`, `menu_id`, `sort_order`, `sub_menu_name`, `sub_menu_icon`, `sub_menu_route`) VALUES
(1, 1, 1, 'Enroll', 'fa fa-folder', 'student_admission.php'),
(2, 1, 2, 'List of Students', 'fa fa-info-circle', 'student-info.php'),
(5, 1, 3, 'Concise Details', 'fa fa-info-circle', 'student_concise_details.php'),
(7, 2, 1, 'Register Admin', 'fa fa-plus', 'admin_register.php'),
(8, 2, 2, 'Admin Info', 'fa fa-info-circle', 'admin_info.php'),
(9, 3, 1, 'Register Coordinator', 'fa fa-plus', 'coordinator_register.php'),
(10, 3, 2, 'Coordinator Info', 'fa fa-info-circle', 'coordinator_info.php'),
(11, 4, 1, 'Register Mentor', 'fa fa-plus', 'mentor_register.php'),
(12, 4, 2, 'Mentor Info', 'fa fa-info-circle', 'mentor_info.php'),
(13, 5, 1, 'Masters', 'fa fa-cog', 'class_crud_new.php#section-list'),
(20, 5, 2, 'Profile', 'fa fa-user', 'profile.php'),
(21, 5, 3, 'Update Password', 'fa fa-folder', 'change_password.php'),
(23, 5, 5, 'Menu', 'fa fa-folder', 'class_crud_new.php?tab=menu-list'),
(24, 5, 6, 'Sub Menu', 'fa fa-folder', 'class_crud_new.php?tab=sub-menu-list'),
(25, 5, 7, 'Side Menu Allocation', 'fa fa-check-square-o', 'allocation_master.php'),
(46, 5, 4, 'Manage Section', 'fa fa-list-alt', 'class_crud_new.php?tab=section-list'),
(47, 5, 8, 'Offline Marks Entry', 'fa fa-pencil-square-o', 'offline_marks_entry.php'),
(48, 4, 3, 'Mentor Allocation', 'fa fa-exchange', 'mentor_allocation.php'),
(49, 5, 9, 'Audit Log', 'fa fa-history', 'audit_log.php'),
(50, 3, 3, 'Coordinator Allocation', 'fa fa-exchange', 'coordinator_allocation.php');

-- --------------------------------------------------------

--
-- Table structure for table `lms_user_log_master`
--

CREATE TABLE `lms_user_log_master` (
  `user_log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lms_user_master`
--

CREATE TABLE `lms_user_master` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(150) NOT NULL,
  `email_id` varchar(200) NOT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `department_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `is_first_login` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lms_user_master`
--

INSERT INTO `lms_user_master` (`user_id`, `user_name`, `email_id`, `phone_number`, `department_id`, `role_id`, `student_id`, `is_first_login`) VALUES
(1, 'Anurag Mishra', 'amit@tcetmumbai.in', '8080590516', 1, 1, 0, 1),
(2, 'Amit Kumar', 'anurag@tcetmumbai.in', '8080590516', 1, 2, 0, 1),
(3, 'Ashutosh Pandey', 'asdf@tcetmumbai.in', '234', 2, 2, 0, 1),
(4, 'Ashutosh', '1032251400@tcetmumbai.in', '9702420582', 1, 4, 0, 1),
(5, 'Preet Prajapati', 'preet@tcetmumbai.in', '8080590516', 3, 5, 0, 1),
(6, 'Akash Pandey', 'aakash@tcetmumbai.in', '8080590516', 3, 3, 0, 1),
(7, 'pallavi', '654576568@tcetmumbai.in', '546789765', 13, 5, 0, 1),
(8, 'john doe', '126785432@tcetmumbai.in', '8977743214', 8, 5, 0, 1),
(9, 'Pallavi', '1324567@tcetmumbai.in', '978655435', 13, 5, 0, 0),
(10, 'john', '5977783@tcetmumbai.in', '9875432356', 10, 5, 0, 0),
(11, 'pallavi', '545667789@tcetmumbai.in', '553443267', 12, 5, 0, 1),
(12, 'pallavi', '788954@tcetmumbai.in', '9864267892', 12, 5, 0, 0),
(13, 'amit', '356789064@tcetmumbai.in', '9856341245', 9, 5, 0, 0),
(14, 'ashutosh', '1235679@tcetmumbai.in', '9876543121', 10, 5, 0, 0),
(15, 'amit', '13245678@tcetmumbai.in', '6878934567', 7, 5, 0, 0),
(16, 'rita', '14525376@tcetmumbai.in', '8967453423', 10, 5, 0, 1),


-- --------------------------------------------------------

--
-- Table structure for table `unaided_sub`
--

CREATE TABLE `unaided_sub` (
  `id` int(11) NOT NULL,
  `sub` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `unaided_sub`
--

INSERT INTO `unaided_sub` (`id`, `sub`) VALUES
(1, 'IT'),
(2, 'CS'),
(3, 'ELECTRONICS');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lms_audit_log`
--
ALTER TABLE `lms_audit_log`
  ADD PRIMARY KEY (`audit_id`),
  ADD KEY `action_type` (`action_type`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `performed_at` (`performed_at`);

--
-- Indexes for table `lms_batch_master`
--
ALTER TABLE `lms_batch_master`
  ADD PRIMARY KEY (`batch_id`);

--
-- Indexes for table `lms_cgpa_master`
--
ALTER TABLE `lms_cgpa_master`
  ADD PRIMARY KEY (`cgpa_id`);

--
-- Indexes for table `lms_class_master`
--
ALTER TABLE `lms_class_master`
  ADD PRIMARY KEY (`class_id`);

--
-- Indexes for table `lms_coordinator`
--
ALTER TABLE `lms_coordinator`
  ADD PRIMARY KEY (`coordinator_id`);

--
-- Indexes for table `lms_credit_ledger`
--
ALTER TABLE `lms_credit_ledger`
  ADD PRIMARY KEY (`credit_id`);

--
-- Indexes for table `lms_department_master`
--
ALTER TABLE `lms_department_master`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `lms_division_master`
--
ALTER TABLE `lms_division_master`
  ADD PRIMARY KEY (`division_id`);

--
-- Indexes for table `lms_eligibility_log`
--
ALTER TABLE `lms_eligibility_log`
  ADD PRIMARY KEY (`eligibility_log_id`);

--
-- Indexes for table `lms_enrollment`
--
ALTER TABLE `lms_enrollment`
  ADD PRIMARY KEY (`enrollment_id`);

--
-- Indexes for table `lms_login`
--
ALTER TABLE `lms_login`
  ADD PRIMARY KEY (`login_id`);

--
-- Indexes for table `lms_mentor_student_mapping`
--
ALTER TABLE `lms_mentor_student_mapping`
  ADD PRIMARY KEY (`mapping_id`);

--
-- Indexes for table `lms_menu_allocation_master`
--
ALTER TABLE `lms_menu_allocation_master`
  ADD PRIMARY KEY (`menu_allocation_id`);

--
-- Indexes for table `lms_menu_master`
--
ALTER TABLE `lms_menu_master`
  ADD PRIMARY KEY (`menu_id`);

--
-- Indexes for table `lms_minorcourse`
--
ALTER TABLE `lms_minorcourse`
  ADD PRIMARY KEY (`course_id`);

--
-- Indexes for table `lms_minorsubject`
--
ALTER TABLE `lms_minorsubject`
  ADD PRIMARY KEY (`subject_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `semester_id` (`semester_id`);

--
-- Indexes for table `lms_minor_certificates`
--
ALTER TABLE `lms_minor_certificates`
  ADD PRIMARY KEY (`certificate_id`);

--
-- Indexes for table `lms_nptel_records`
--
ALTER TABLE `lms_nptel_records`
  ADD PRIMARY KEY (`nptel_id`);

--
-- Indexes for table `lms_offline_marks_entry`
--
ALTER TABLE `lms_offline_marks_entry`
  ADD PRIMARY KEY (`entry_id`),
  ADD UNIQUE KEY `uniq_offline_marks` (`student_id`,`semester_id`,`course_name`);

--
-- Indexes for table `lms_research_records`
--
ALTER TABLE `lms_research_records`
  ADD PRIMARY KEY (`research_id`);

--
-- Indexes for table `lms_role_master`
--
ALTER TABLE `lms_role_master`
  ADD PRIMARY KEY (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lms_login`
--
ALTER TABLE `lms_login`
  MODIFY `login_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
