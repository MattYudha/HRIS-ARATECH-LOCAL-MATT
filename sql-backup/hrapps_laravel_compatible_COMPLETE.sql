-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 10, 2025 at 02:27 PM
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
-- Database: `hrappsprod`
--

-- Select target database (prevents #1046 "No database selected" when importing)
USE `hrappsprod`;


-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `description`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'IT', 'Information Technology', 'active', '2025-12-06 11:13:25', '2025-12-06 11:13:25', NULL),
(2, 'Human Resources', 'Human Resources Department', 'active', '2025-12-06 11:13:25', '2025-12-06 11:13:25', NULL),
(3, 'Marketing', 'Marketing Department', 'active', '2025-12-06 11:13:25', '2025-12-06 11:13:25', NULL),
(4, 'Sales', 'Sales Department', 'active', '2025-12-06 11:13:25', '2025-12-06 11:13:25', NULL),
(5, 'Operations', 'Operations Department', 'active', '2025-12-06 11:13:25', '2025-12-06 11:13:25', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `birth_date` timestamp NULL DEFAULT NULL,
  `hire_date` timestamp NULL DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `supervisor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `salary` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `fullname`, `email`, `phone_number`, `address`, `birth_date`, `hire_date`, `department_id`, `role_id`, `supervisor_id`, `status`, `salary`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Administrator', 'admin@example.com', '081234567890', '123 Admin Street', NULL, '2020-01-01 05:00:00', 2, 2, NULL, 'active', 10000000.00, '2025-12-06 11:13:31', '2025-12-06 11:13:31', NULL),
(2, 'John Manager', 'manager@example.com', '081234567891', '456 Manager Ave', NULL, '2021-01-01 05:00:00', 1, 3, 1, 'active', 8000000.00, '2025-12-06 11:13:31', '2025-12-06 11:13:31', NULL),
(3, 'Employee 3', 'employee3@example.com', '081234567893', 'Street 3', NULL, '2022-01-01 05:00:00', 1, 4, 2, 'active', 6000000.00, '2025-12-06 11:13:32', '2025-12-06 11:13:32', NULL),
(4, 'Employee 4', 'employee4@example.com', '081234567894', 'Street 4', '2023-07-06 05:22:00', '2022-01-01 05:00:00', 4, 4, 2, 'active', 6000000.00, '2025-12-06 11:13:32', '2025-12-06 11:20:29', NULL),
(5, 'Employee 5', 'employee5@example.com', '081234567895', 'Street 5', NULL, '2022-01-01 05:00:00', 4, 1, 2, 'active', 6000000.00, '2025-12-06 11:13:32', '2025-12-06 11:13:32', NULL),
(6, 'Sasa Marinir', 'hr@aratechnology.id', '0765687989767', 'rumpin', '1990-12-20 05:00:00', '2025-12-01 05:00:00', 1, 1, NULL, 'active', 1000.00, '2025-12-06 11:34:53', '2025-12-06 12:36:15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employee_kpi_records`
--

CREATE TABLE `employee_kpi_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `kpi_id` bigint(20) UNSIGNED NOT NULL,
  `period` varchar(255) NOT NULL,
  `actual_value` decimal(10,2) NOT NULL,
  `target_value` decimal(10,2) NOT NULL,
  `previous_value` decimal(10,2) DEFAULT NULL,
  `status` enum('achieved','warning','critical','na') NOT NULL DEFAULT 'na',
  `notes` text DEFAULT NULL,
  `calculation_method` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `composite_score` decimal(5,2) DEFAULT NULL,
  `performance_level` enum('excellent','good','satisfactory','needs_improvement','unsatisfactory') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_kpi_records`
--

INSERT INTO `employee_kpi_records` (`id`, `employee_id`, `kpi_id`, `period`, `actual_value`, `target_value`, `previous_value`, `status`, `notes`, `calculation_method`, `created_at`, `updated_at`, `composite_score`, `performance_level`) VALUES
(1, 3, 1, '2025-12', 57.00, 100.00, NULL, 'warning', 'Auto-generated dummy record', NULL, '2025-12-06 11:14:18', '2025-12-06 11:14:18', 57.00, 'needs_improvement'),
(2, 3, 2, '2025-12', 86.00, 100.00, NULL, 'achieved', 'Auto-generated dummy record', NULL, '2025-12-06 11:14:18', '2025-12-06 11:14:18', 86.00, 'good'),
(3, 3, 3, '2025-12', 77.00, 100.00, NULL, 'achieved', 'Auto-generated dummy record', NULL, '2025-12-06 11:14:18', '2025-12-06 11:14:18', 77.00, 'good'),
(4, 3, 4, '2025-12', 65.00, 100.00, NULL, 'warning', 'Auto-generated dummy record', NULL, '2025-12-06 11:14:18', '2025-12-06 11:14:18', 65.00, 'satisfactory'),
(5, 3, 5, '2025-12', 66.00, 100.00, NULL, 'warning', 'Auto-generated dummy record', NULL, '2025-12-06 11:14:18', '2025-12-06 11:14:18', 66.00, 'satisfactory'),
(6, 3, 6, '2025-12', 81.00, 100.00, NULL, 'achieved', 'Auto-generated dummy record', NULL, '2025-12-06 11:14:18', '2025-12-06 11:14:18', 81.00, 'good'),
(7, 3, 7, '2025-12', 100.00, 100.00, NULL, 'achieved', 'Auto-generated dummy record', NULL, '2025-12-06 11:14:18', '2025-12-06 11:14:18', 100.00, 'excellent'),
(8, 3, 8, '2025-12', 54.00, 100.00, NULL, 'warning', 'Auto-generated dummy record', NULL, '2025-12-06 11:14:18', '2025-12-06 11:14:18', 54.00, 'needs_improvement'),
(9, 3, 9, '2025-12', 50.00, 100.00, NULL, 'warning', 'Auto-generated dummy record', NULL, '2025-12-06 11:14:18', '2025-12-06 11:14:18', 50.00, 'needs_improvement'),
(10, 3, 10, '2025-12', 64.00, 100.00, NULL, 'warning', 'Auto-generated dummy record', NULL, '2025-12-06 11:14:18', '2025-12-06 11:14:18', 64.00, 'satisfactory'),
(11, 4, 1, '2025-12', 98.00, 100.00, NULL, 'achieved', 'Auto-generated dummy record', NULL, '2025-12-06 11:14:18', '2025-12-06 11:14:18', 98.00, 'excellent'),
(12, 4, 2, '2025-12', 52.00, 100.00, NULL, 'warning', 'Auto-generated dummy record', NULL, '2025-12-06 11:14:18', '2025-12-06 11:14:18', 52.00, 'needs_improvement'),
(13, 4, 3, '2025-12', 84.00, 100.00, NULL, 'achieved', 'Auto-generated dummy record', NULL, '2025-12-06 11:14:18', '2025-12-06 11:14:18', 84.00, 'good'),
(14, 4, 4, '2025-12', 57.00, 100.00, NULL, 'warning', 'Auto-generated dummy record', NULL, '2025-12-06 11:14:18', '2025-12-06 11:14:18', 57.00, 'needs_improvement'),
(15, 4, 5, '2025-12', 81.00, 100.00, NULL, 'achieved', 'Auto-generated dummy record', NULL, '2025-12-06 11:14:18', '2025-12-06 11:14:18', 81.00, 'good'),
(16, 4, 6, '2025-12', 72.00, 100.00, NULL, 'warning', 'Auto-generated dummy record', NULL, '2025-12-06 11:14:18', '2025-12-06 11:14:18', 72.00, 'satisfactory'),
(17, 4, 7, '2025-12', 88.00, 100.00, NULL, 'achieved', 'Auto-generated dummy record', NULL, '2025-12-06 11:14:18', '2025-12-06 11:14:18', 88.00, 'good'),
(18, 4, 8, '2025-12', 88.00, 100.00, NULL, 'achieved', 'Auto-generated dummy record', NULL, '2025-12-06 11:14:18', '2025-12-06 11:14:18', 88.00, 'good'),
(19, 4, 9, '2025-12', 50.00, 100.00, NULL, 'warning', 'Auto-generated dummy record', NULL, '2025-12-06 11:14:18', '2025-12-06 11:14:18', 50.00, 'needs_improvement'),
(20, 4, 10, '2025-12', 96.00, 100.00, NULL, 'achieved', 'Auto-generated dummy record', NULL, '2025-12-06 11:14:18', '2025-12-06 11:14:18', 96.00, 'excellent'),
(21, 5, 1, '2025-12', 71.00, 100.00, NULL, 'warning', 'Auto-generated dummy record', NULL, '2025-12-06 11:14:18', '2025-12-06 11:14:18', 71.00, 'satisfactory'),
(22, 5, 2, '2025-12', 94.00, 100.00, NULL, 'achieved', 'Auto-generated dummy record', NULL, '2025-12-06 11:14:18', '2025-12-06 11:14:18', 94.00, 'excellent'),
(23, 5, 3, '2025-12', 62.00, 100.00, NULL, 'warning', 'Auto-generated dummy record', NULL, '2025-12-06 11:14:18', '2025-12-06 11:14:18', 62.00, 'satisfactory'),
(24, 5, 4, '2025-12', 72.00, 100.00, NULL, 'warning', 'Auto-generated dummy record', NULL, '2025-12-06 11:14:18', '2025-12-06 11:14:18', 72.00, 'satisfactory'),
(25, 5, 5, '2025-12', 97.00, 100.00, NULL, 'achieved', 'Auto-generated dummy record', NULL, '2025-12-06 11:14:18', '2025-12-06 11:14:18', 97.00, 'excellent'),
(26, 5, 6, '2025-12', 73.00, 100.00, NULL, 'warning', 'Auto-generated dummy record', NULL, '2025-12-06 11:14:18', '2025-12-06 11:14:18', 73.00, 'satisfactory'),
(27, 5, 7, '2025-12', 57.00, 100.00, NULL, 'warning', 'Auto-generated dummy record', NULL, '2025-12-06 11:14:18', '2025-12-06 11:14:18', 57.00, 'needs_improvement'),
(28, 5, 8, '2025-12', 50.00, 100.00, NULL, 'warning', 'Auto-generated dummy record', NULL, '2025-12-06 11:14:18', '2025-12-06 11:14:18', 50.00, 'needs_improvement'),
(29, 5, 9, '2025-12', 70.00, 100.00, NULL, 'warning', 'Auto-generated dummy record', NULL, '2025-12-06 11:14:18', '2025-12-06 11:14:18', 70.00, 'satisfactory'),
(30, 5, 10, '2025-12', 98.00, 100.00, NULL, 'achieved', 'Auto-generated dummy record', NULL, '2025-12-06 11:14:18', '2025-12-06 11:14:18', 98.00, 'excellent');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `incidents`
--

CREATE TABLE `incidents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `incident_date` date NOT NULL,
  `description` text NOT NULL,
  `severity` enum('low','medium','high','critical') NOT NULL DEFAULT 'low',
  `status` enum('reported','under_investigation','resolved','archived') NOT NULL DEFAULT 'reported',
  `action_taken` text DEFAULT NULL,
  `reported_by` bigint(20) UNSIGNED DEFAULT NULL,
  `resolved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `resolved_at` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventories`
--

CREATE TABLE `inventories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `inventory_category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `location` varchar(255) DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `status` enum('active','inactive','damaged') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventories`
--

INSERT INTO `inventories` (`id`, `inventory_category_id`, `name`, `description`, `quantity`, `location`, `purchase_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Ballpoint Pens', NULL, 100, NULL, NULL, 'active', '2025-12-06 11:14:17', '2025-12-06 11:14:17'),
(2, 2, 'LCD Monitor 24\\\"', NULL, 15, NULL, NULL, 'active', '2025-12-06 11:14:17', '2025-12-06 11:14:17'),
(3, 3, 'Office Chair', NULL, 20, NULL, NULL, 'active', '2025-12-06 11:14:17', '2025-12-06 11:14:17'),
(4, 4, 'MS Office License', NULL, 50, NULL, NULL, 'active', '2025-12-06 11:14:17', '2025-12-06 11:14:17'),
(5, 5, 'Cisco Switch', NULL, 3, NULL, NULL, 'active', '2025-12-06 11:14:17', '2025-12-06 11:14:17');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_categories`
--

CREATE TABLE `inventory_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_categories`
--

INSERT INTO `inventory_categories` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Office Supplies', 'Pens, papers, etc', '2025-12-06 11:13:57', '2025-12-06 11:13:57'),
(2, 'Electronics', 'Monitors, keyboards, etc', '2025-12-06 11:13:57', '2025-12-06 11:13:57'),
(3, 'Furniture', 'Desks, chairs, etc', '2025-12-06 11:13:57', '2025-12-06 11:13:57'),
(4, 'Software Licenses', 'License keys and software', '2025-12-06 11:13:57', '2025-12-06 11:13:57'),
(5, 'Network Equipment', 'Routers, switches, etc', '2025-12-06 11:13:57', '2025-12-06 11:13:57');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_usage_logs`
--

CREATE TABLE `inventory_usage_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `inventory_id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `borrowed_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `returned_date` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_usage_logs`
--

INSERT INTO `inventory_usage_logs` (`id`, `inventory_id`, `employee_id`, `borrowed_date`, `returned_date`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 3, '2025-12-06 06:29:00', '2025-12-06 06:29:00', 'Auto-generated dummy record', '2025-12-06 11:29:47', '2025-12-06 11:29:47');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kpis`
--

CREATE TABLE `kpis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `formula` text DEFAULT NULL,
  `target_value` decimal(8,2) NOT NULL DEFAULT 0.00,
  `min_value` decimal(8,2) NOT NULL DEFAULT 0.00,
  `max_value` decimal(8,2) NOT NULL DEFAULT 100.00,
  `weight` decimal(5,2) NOT NULL DEFAULT 1.00,
  `unit` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kpis`
--

INSERT INTO `kpis` (`id`, `code`, `name`, `category`, `description`, `formula`, `target_value`, `min_value`, `max_value`, `weight`, `unit`, `status`, `created_at`, `updated_at`) VALUES
(1, 'KPI001', 'Attendance Rate', 'Attendance', NULL, NULL, 0.00, 0.00, 100.00, 1.00, '%', 'active', '2025-12-06 11:13:32', '2025-12-06 11:13:32'),
(2, 'KPI002', 'Projects Completed', 'Productivity', NULL, NULL, 0.00, 0.00, 100.00, 1.00, 'count', 'active', '2025-12-06 11:13:32', '2025-12-06 11:13:32'),
(3, 'KPI003', 'Tasks On-Time', 'Productivity', NULL, NULL, 0.00, 0.00, 100.00, 1.00, '%', 'active', '2025-12-06 11:13:32', '2025-12-06 11:13:32'),
(4, 'KPI004', 'Code Quality Score', 'Quality', NULL, NULL, 0.00, 0.00, 100.00, 1.00, '%', 'active', '2025-12-06 11:13:32', '2025-12-06 11:13:32'),
(5, 'KPI005', 'Customer Satisfaction', 'Quality', NULL, NULL, 0.00, 0.00, 100.00, 1.00, '%', 'active', '2025-12-06 11:13:32', '2025-12-06 11:13:32'),
(6, 'KPI006', 'Policy Compliance', 'Department', NULL, NULL, 0.00, 0.00, 100.00, 1.00, '%', 'active', '2025-12-06 11:13:32', '2025-12-06 11:13:32'),
(7, 'KPI007', 'Training Completion', 'Behavior', NULL, NULL, 0.00, 0.00, 100.00, 1.00, '%', 'active', '2025-12-06 11:13:32', '2025-12-06 11:13:32'),
(8, 'KPI008', 'Team Collaboration', 'Behavior', NULL, NULL, 0.00, 0.00, 100.00, 1.00, 'score', 'active', '2025-12-06 11:13:32', '2025-12-06 11:13:32'),
(9, 'KPI009', 'Leave Balance', 'Leave', NULL, NULL, 0.00, 0.00, 100.00, 1.00, 'days', 'active', '2025-12-06 11:13:32', '2025-12-06 11:13:32'),
(10, 'KPI010', 'Salary Processing', 'Salary', NULL, NULL, 0.00, 0.00, 100.00, 1.00, '%', 'active', '2025-12-06 11:13:32', '2025-12-06 11:13:32');

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `leave_type` varchar(255) NOT NULL,
  `start_date` timestamp NULL DEFAULT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leave_requests`
--

INSERT INTO `leave_requests` (`id`, `employee_id`, `leave_type`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 3, 'annual', '2025-12-20 11:13:57', '2025-12-22 11:13:57', 'pending', '2025-12-06 11:13:57', '2025-12-06 11:13:57', NULL),
(2, 4, 'sick', '2025-12-04 11:13:57', '2025-12-05 11:13:57', 'approved', '2025-12-06 11:13:57', '2025-12-06 11:13:57', NULL),
(3, 4, 'Cuti Tahunan', '2025-12-26 17:00:00', '2025-12-27 17:00:00', 'pending', '2025-12-06 22:12:12', '2025-12-06 22:12:12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `letters`
--

CREATE TABLE `letters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `approver_id` bigint(20) UNSIGNED DEFAULT NULL,
  `letter_template_id` bigint(20) UNSIGNED DEFAULT NULL,
  `letter_number` varchar(255) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `letter_type` varchar(255) NOT NULL DEFAULT 'official',
  `status` enum('draft','pending','approved','printed') NOT NULL DEFAULT 'draft',
  `created_date` date NOT NULL,
  `approved_date` timestamp NULL DEFAULT NULL,
  `printed_date` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `letters`
--

INSERT INTO `letters` (`id`, `user_id`, `approver_id`, `letter_template_id`, `letter_number`, `subject`, `content`, `letter_type`, `status`, `created_date`, `approved_date`, `printed_date`, `notes`, `created_at`, `updated_at`) VALUES
(1, 4, 1, 5, '001/HR/12/2025', 'Cuti Tahunan', 'Cuti LIbur akhir taun', 'official', 'printed', '2025-12-06', '2025-12-06 12:38:09', '2025-12-06 12:38:14', NULL, '2025-12-06 11:31:29', '2025-12-06 12:38:14'),
(2, 4, 1, 4, '002/HR/12/2025', 'surat pernyataan kerja', 'mohon dibantu surat surat pernyataan kerja', 'official', 'approved', '2025-12-09', '2025-12-09 18:03:55', NULL, NULL, '2025-12-09 18:02:22', '2025-12-09 18:03:55');

-- --------------------------------------------------------

--
-- Table structure for table `letter_archives`
--

CREATE TABLE `letter_archives` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `month` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `total_letters` int(11) NOT NULL DEFAULT 0,
  `approved_letters` int(11) NOT NULL DEFAULT 0,
  `printed_letters` int(11) NOT NULL DEFAULT 0,
  `summary` longtext DEFAULT NULL,
  `generated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `letter_configurations`
--

CREATE TABLE `letter_configurations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `company_address` varchar(255) DEFAULT NULL,
  `company_phone` varchar(255) DEFAULT NULL,
  `company_email` varchar(255) DEFAULT NULL,
  `company_website` varchar(255) DEFAULT NULL,
  `letterhead_footer` text DEFAULT NULL,
  `letter_number_format` varchar(255) NOT NULL DEFAULT '{NUMBER}/{DEPT}/{MONTH}/{YEAR}',
  `current_number` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `letter_configurations`
--

INSERT INTO `letter_configurations` (`id`, `company_name`, `company_address`, `company_phone`, `company_email`, `company_website`, `letterhead_footer`, `letter_number_format`, `current_number`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'PT Aratech Indonesia', 'Jl. Gatot Subroto No. 1, Jakarta', '(021) 1234-5678', 'info@aratech.co.id', NULL, NULL, '{NUMBER}/{DEPT}/{MONTH}/{YEAR}', 2, 1, '2025-12-06 11:14:17', '2025-12-09 18:02:50');

-- --------------------------------------------------------

--
-- Table structure for table `letter_templates`
--

CREATE TABLE `letter_templates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `content` longtext NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'official',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `letter_templates`
--

INSERT INTO `letter_templates` (`id`, `name`, `description`, `content`, `type`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Surat Penawaran Kerja', 'Template surat penawaran pekerjaan untuk karyawan baru', '[COMPANY_NAME]\n\nYang Terhormat [EMPLOYEE_NAME],\n\nDengan senang hati kami menawarkan posisi [POSITION] kepada Anda. Silakan hubungi HR untuk detail lengkap.\n\nHormat kami,\nHR Department', 'official', 1, '2025-12-06 11:14:17', '2025-12-06 11:14:17'),
(2, 'Surat Kontrak Kerja', 'Template kontrak kerja permanent', 'KONTRAK KERJA\n\nDengan ini disepakati antara [COMPANY_NAME] dan [EMPLOYEE_NAME] untuk mengadakan perjanjian kerja sebagai berikut:\n\nPos: [POSITION]\nGaji: [SALARY]\nJakarta, [DATE]\n\nTanda Tangan:', 'official', 1, '2025-12-06 11:14:17', '2025-12-06 11:14:17'),
(3, 'Surat Rekomendasi', 'Template surat rekomendasi kerja', 'SURAT REKOMENDASI\n\nDengan ini saya merekomendasikan [EMPLOYEE_NAME] sebagai karyawan yang kompeten dan profesional. [EMPLOYEE_NAME] telah bekerja dengan baik selama [PERIOD].\n\nHormat kami,\n[RECOMMENDER_NAME]', 'official', 1, '2025-12-06 11:14:17', '2025-12-06 11:14:17'),
(4, 'Surat Pernyataan Kerja', 'Template sertifikat kerja', 'SURAT KETERANGAN KERJA\n\nYang bertanda tangan di bawah ini menerangkan bahwa [EMPLOYEE_NAME] bekerja di [COMPANY_NAME] sejak [START_DATE] sampai [END_DATE] sebagai [POSITION].\n\nJakarta, [DATE]\nDiminta untuk keperluan: [PURPOSE]', 'official', 1, '2025-12-06 11:14:17', '2025-12-06 11:14:17'),
(5, 'Surat Izin Cuti', 'Template surat izin cuti', 'SURAT IZIN CUTI\n\nDengan ini saya mengajukan izin cuti sebanyak [DAYS] hari mulai dari [START_DATE] sampai [END_DATE].\n\nAlasan: [REASON]\n\nHormat kami,\n[EMPLOYEE_NAME]', 'official', 1, '2025-12-06 11:14:17', '2025-12-06 11:14:17');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_02_11_091619_create_humanresourceapp_tables', 1),
(5, '2025_02_12_182302_add_role_to_users_table', 1),
(6, '2025_11_25_000001_add_supervisor_to_employees', 1),
(7, '2025_12_02_120000_make_employee_id_nullable_on_users_table', 1),
(8, '2025_12_04_110000_create_signatures_table', 1),
(9, '2025_12_04_120001_create_signature_verifications_table', 1),
(10, '2025_12_04_151932_create_inventory_categories_table', 1),
(11, '2025_12_04_151935_create_inventories_table', 1),
(12, '2025_12_04_151935_create_inventory_usage_logs_table', 1),
(13, '2025_12_04_155231_create_letter_configurations_table', 1),
(14, '2025_12_04_155232_create_letter_templates_table', 1),
(15, '2025_12_04_155232_create_letters_table', 1),
(16, '2025_12_04_155233_create_letter_archives_table', 1),
(17, '2025_12_04_181107_create_kpis_table', 1),
(18, '2025_12_04_181114_create_employee_kpi_records_table', 1),
(19, '2025_12_04_181122_create_performance_reviews_table', 1),
(20, '2025_12_04_181134_create_incidents_table', 1),
(21, '2025_12_04_add_power_user_role', 1),
(22, '2025_12_11_153908_add_signature_reason_to_signatures_table', 1),
(23, '2025_12_13_000000_add_gps_and_worktype_to_presences_table', 1),
(24, '2025_12_18_184755_change_signature_hash_to_text_in_signatures_table', 1),
(25, '2025_12_19_132422_drop_unique_index_from_signatures_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll`
--

CREATE TABLE `payroll` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `salary` decimal(10,2) NOT NULL,
  `bonuses` decimal(10,2) DEFAULT NULL,
  `deductions` decimal(10,2) DEFAULT NULL,
  `net_salary` decimal(10,2) NOT NULL,
  `pay_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `performance_reviews`
--

CREATE TABLE `performance_reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `reviewer_id` bigint(20) UNSIGNED NOT NULL,
  `period` varchar(255) NOT NULL,
  `overall_score` decimal(5,2) NOT NULL,
  `achievements` text DEFAULT NULL,
  `areas_improvement` text DEFAULT NULL,
  `goals_next_period` text DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `status` enum('draft','pending_approval','approved','rejected') NOT NULL DEFAULT 'draft',
  `reviewed_at` datetime DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `presences`
--

CREATE TABLE `presences` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `check_in` timestamp NULL DEFAULT NULL,
  `check_out` timestamp NULL DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `work_type` varchar(255) NOT NULL DEFAULT 'WFO',
  `date` date NOT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `title`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Power User', 'Full access to all system features', '2025-12-06 11:13:25', '2025-12-06 11:13:25', NULL),
(2, 'HR', 'Human Resources', '2025-12-06 11:13:25', '2025-12-06 11:13:25', NULL),
(3, 'Manager', 'Department Manager', '2025-12-06 11:13:25', '2025-12-06 11:13:25', NULL),
(4, 'Developer', 'Software Developer', '2025-12-06 11:13:25', '2025-12-06 11:13:25', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('487mhUMmOPiFXRiaMWP2alg7R9C3IRHZcjuATlUt', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoid29xdHhRUWFUYm1OWk5HZ09ESlZHN3QwbWwyTm1vQnZiT2FnazhYQSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaWduYXR1cmVzLzQvZG93bmxvYWQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6NDoicm9sZSI7czoyOiJIUiI7czoxMToiZW1wbG95ZWVfaWQiO2k6MTt9', 1765373040),
('fOXju5Ka3zm8qJGaOHQTzr4RGOuuxFmTC0t1NyvQ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTmk1eDNRZDNFdDgxbGhPeUtlREgycGdVYmEyM29WRnVxSkkzaHpxaSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1765284715),
('grWJJtEVPE1l34tyafBY95PNdKZ36GnhJ2Ry8sT0', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoieUxDcjM3bzlxbzJETEt1MTBFT0RhYlNXWldRSUtBcUZLR1VGNmFqWiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sZXR0ZXJzLzIiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6NDoicm9sZSI7czoyOiJIUiI7czoxMToiZW1wbG95ZWVfaWQiO2k6MTt9', 1765286294),
('WiFLnqqGnL5dG8aQjffSsb9kA5f9ZEu6KSzggiVl', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiN0FRb212Y1VuNkJwQ3FLU2R6TlZNYURhTUhkUHVyeWlkWUJTYXRpcSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1765370718),
('wsk7UqosYGECemvSwVcn3PcgWGtEJtBQY3ho5jgX', 4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoiNFNsMUk5c1ZMY1dqaU5WMXlyamcyc0hCRFFocGhQckxMNWJlTUVPaiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMxOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvcHJlc2VuY2VzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NDtzOjQ6InJvbGUiO3M6OToiRGV2ZWxvcGVyIjtzOjExOiJlbXBsb3llZV9pZCI7aTo0O30=', 1765290841),
('zjgYAbwYNIT1yWlImDrWFBhhuUquFa7SL3GoDTFh', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiY1U0aDh1N0ZuMkU1Y0lFeFFBZHhHdkNnZ3lMR3hZN3RkWkdBY25xMCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czo0OiJyb2xlIjtzOjI6IkhSIjtzOjExOiJlbXBsb3llZV9pZCI7aToxO30=', 1765372394);

-- --------------------------------------------------------

--
-- Table structure for table `signatures`
--

CREATE TABLE `signatures` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `signable_type` varchar(255) NOT NULL,
  `signable_id` bigint(20) UNSIGNED NOT NULL,
  `signature_image` longtext NOT NULL,
  `signature_hash` text NOT NULL,
  `signature_reason` text DEFAULT NULL,
  `signed_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `ip_address` varchar(255) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `verification_token` varchar(255) DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `signatures`
--

INSERT INTO `signatures` (`id`, `user_id`, `signable_type`, `signable_id`, `signature_image`, `signature_hash`, `signed_date`, `ip_address`, `user_agent`, `is_verified`, `verification_token`, `verified_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'App\\Models\\Letter', 1, 'data:image/png;base64,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', '73243de996a9f28539c8497282f20e8bce30163b867966b5681167478188ccfa', '2025-12-06 07:37:35', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 0, 'QbwP3tkeJhFbZ2vMkQip7Y7kqAGAYsuwxm4wJ8QQ3yg6zsUbtIE62xOxVXbViW3e', NULL, '2025-12-06 12:37:35', '2025-12-06 12:37:35'),
(2, 1, 'App\\Models\\Letter', 1, 'data:image/png;base64,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', 'b2615a7e9f57f5a9a531639f0b213b23d780dc7d831cba238b030c29d4262d33', '2025-12-06 07:39:29', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 0, 'SbwPV5mjN56HhEFuyfm2l6jw79zT0ZdvHZXMw2BQIGbksZq3nqHqqUTdIlWKMrBq', NULL, '2025-12-06 12:39:29', '2025-12-06 12:39:29'),
(3, 4, 'App\\Models\\Letter', 2, 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAlgAAADICAYAAAA0n5+2AAAQAElEQVR4Aezdv8s12UEH8CchdilSWCSwYgL5A2wELWSzlVqpYCCp1lQiFptAAulMKgmmWP8CkyIYIYWdFsLugmAECwsLOw0E3CKQQFIEXNic77vPvDvvec+9z/0xc++ZM5+XmffOnJl75pzPeeF+OTP3vh998IcAAQIECBAgQGBRAQFrUU6VESBAgMAyAmohsG0BAWvb46f1BAgQIECAQIcCAlaHg6JJBJYQUAcBAgQI3E9AwLqfvSsTIECAAAECgwoIWAcH1gECBAgQIECAwGUCAtZlbt5FgAABAgTuI+CqmxAQsDYxTBpJgAABAgQIbElAwNrSaGkrAQJLCKiDAAECqwsIWKsTuwABAgQIECCwNwEBa28jvkR/1UGAAAECBAgcFRCwjvI4SIAAAQIECGxFoKd2Clg9jYa2ECBAgAABAkMICFhDDKNOECBAYAkBdRAgsJSAgLWUpHoIECBAgAABAo8CAtYjhBcCSwiogwABAgQIREDAioKVAIGlBT69dIXqI0CAwJYEOgtYW6LTVgIEGgJ/V8reL+v/lPXtsv5ZWYWtgmAhQGBfAgLWvsZbbwmsKZAwlXW6xqtlI4HrrfL6jbJaCGxXQMsJnCkgYJ0J5nQCBA4K/NWBI5nByjEh6wCQYgIExhMQsMYbUz0icA+Bz5WLJkiVl+aSwoSsfyobmeV66txymoUAAQLbFRCwtjt2Wk6gJ4HcCjylPX9QTsq5eUYrrwlmpchCgACBsQQErK2Mp3YS6Fcgt/5aM1LfKk3Og+7lpblkJivPZ/20HP1OWS0ECBAYRkDAGmYodYTAXQQSrHLrr774/5aCr5f1S2X9SVmPLZ8oB18va0JW6iubFgIEtiKgnW0BAavtopQAgdMEWuEq73wtf5U1Qeu3y+s7Zc12eTm4JGTltqGQdZDIAQIEtiIgYG1lpLSTQH8CCUK5zVe37JulYB6msp1nrT5TyrPmeNlsLjkvz2cduu3YfNO2C7WeAIERBQSsEUdVnwjcRqAVrhKmEo4OtWA6nqD1tXLSL8vaWjIzlvoT4lrHlREgQKBrAQGr6+HRuFMEnHM3gYSg+uLfrQsO7Cdofbsc+8OyZru8vLSk/qxC1ks0CggQ6F1AwOp9hLSPQJ8CrdCToHRs9qrVk3zLMM9r5bV1PLNY+aZh63qt85URIECgC4GPPjx00Q6NIEBgWwIJPnWLT529qt+XYJaQlWezsl0fT7jyXFatYp8Aga4FzGB1PTwaR6Bbgfw/g0s3LrNfCWmtkJVr5XZhK9jlmHVEAX0isGEBAWvDg6fpBO4okG/71ZdPQKrLzt1PHQlZh96XkJVzDh1XToAAgS4EBKwuhkEjFhDIMzzvl3r+v6zZLi+7X9YCyC27uu5Ds071eafsJ0DlG4bvHTg5IevNA8cUEyBAoAsBAauLYdCIKwW+XN4/3bL62ON2ysqmZQWB32nU+R+NsmuK8g3DXysV5Nfdy8tLyxul5KtltRAgQKBLAQHr2LA4thWBfNjWbf3jusD+YgK/36gpYahRfHVR/qudPPzequgvW4XKCBAg0IOAgNXDKGjDNQJ56Ll1y+ofr6nUew8KxDrm9QlrzhjmlmErZKUtWeu22CcwvIAO9i8gYPU/Rlp4XCD/d119xj+XAs/oFIQVlla4yjNvSz6D1Wp2Qlb+P8P6WOth+/oc+wQIELi5gIB1c3IXXFAgH7qt6v6iVajsaoHMFuUB87qi3Mary9bYT5j6cVVx2lQVnbLrHAIECKwrIGCt66v29QS+UKpufdjnoei1Z1PKpXe5tGavYp31FiAJ1K9UF0pZVWSXAAEC9xcQsO4/BptsQQeN/usDbWjdRjpwquIzBaZvas7fduw3q+bnXbqdW725DZxfcq8D9buXVup9BAgQWFtAwFpbWP1rCOS2UNZW3ZnBapUru04g3rlFN68lM1drzSDlWj8vF8s3RDNzluuX3ReWf3hhzw4BAgQeHroxELC6GQoNOUMgH7it0/+zVahsEYEEnrqiNWavEqTeKhfK+vHy2loyc/WVcmDNby6W6i0ECBC4XEDAutzOO+8jkA/g+lbR1JI/mTa8Li4Q97rSzGDVZZfup/7pVmArzKXeXC8/1/CpspNbh+XFsriACgkQWERAwFqEUSU3FDg0e5UP36w3bMquLtV6/mqJ27EJVpmtyjNWrbHNtwa/VaQ/87iudUuyVG8hQIDAcgIC1nKWarqNwOsHLrPG7aoDlzpaPOrBelbp2jCbYJUfg02wquueDBPgfqPsfL2s116vVGEhQIDA7QQErNtZu9L1AvkgzgdzXVM+fM1s1CrL7bfMLw20qStjlWD1RweamGCVGatb/b7WgWYoJkCAwOUC/QWsy/vineMLmL3qZ4zz6+2ntmYeqhKsDj1DNw9WCc2n1u88AgQIdCcgYHU3JBp0QCAf0q1ndHJ6ZkTyal1HoHZP+DklYGXMMjZTqMp+q4Wpa5qxSt2tc5QRuLuABhA4R0DAOkfLufcUqD/kp7bkW2XTttfbCCQQHbtSgtT0jcBDs1UJUrnN+MVS0WtlzX55sRAgQGAMAQFrjHHcQy8OfVDnttIe+n/PPta3Zlu/lp9QNc1WZcaqEYifdSFBKs9WZcYq53z/Wam/CBAgMJiAgDXYgA7anXx4t7qWD+usrWPKlhOY+79Xqp2H2mxPP7OQEDw/t5z6fMlMY0JV1rzn+QEbBAgQGFFAwNrQqO64qZnpaHU/H9qtcmXrCfykVJ0Qldmq98t2Zrfy7c6y+dKS8Jsx+kg5kvOzXzYtBAgQGF9AwBp/jEfoYWZG6n7kw9pMSK2y/v4vyyVyC7A1JuXQsyVjM90GTLB6VugvAgSGFdCxhoCA1UBR1JXAodmRPCDdVUMHbUxmq+Zdq/enYwlVma3KLcCswu8k45UAgV0KCFi7HPZNdboVsPJhbmZk3WFMkIrxvz1xmR+U4/kmYEJVzs/YlCLLWQJOJkBgOAEBa7ghHa5DrVtRZq/WG+YpWE23AT/ZuFRCVGar8mzV58tx3wQsCBYCBAjMBQSsuYbt3gTyYV+3KR/umSmZl9u+XiDWT/12Va6Sn2iYZquybyVAgACBhoCA1UBR1I1A69uDZq+WHZ7cgs1sVdaWd301z1bVIvYJECDQEPggYDUOKCLQgUB+AqBuhtmrWuSy/TgmVOU3rDJ71aoltwF/WB3IDGJVZJcAAQIEagEBqxax34tAPvSzztuTD/z5vu3zBOKZYJXfr8qzbdmva0iAinOer8q5+VmG+Tk5Pt+3TWBVAZUT2KqAgLXVkRu/3a3bVfnAH7/ny/cwQSp2mbFKsGpdIcEpwap+virvnZ+f8+b7tgkQIECgISBgNVAUdSFQ3x7Mh38XDdtQIz798PBwSrA69qOg84AlXD34Q4AAgdMEBKzTnJx1e4H5B3v+/7sEhdu3YptXjN1T3wh8u3TttbJmxurQg+upp5xiIUCAAIFzBQSsJ8QcvotAvtk2v/D35ju2DwokEOWh9dwKbN1izRszE5hQlXCVkJWyU1czWKdKOY8Agd0LCFi7/yfQJUAdsM4NAl12asVGxSuhKmu2W5dKsJoeXBeUWkLKCGxLQGs7FxCwOh+gnTZv/iB2wsChW1g75Xne7dw2TajKrFVmr54feNyI3TxYPRaf/FLXmfpOfrMTCRAgsGcBAWvPo99n3+sPdT8s+vI4vVmKEqwSRGuvcughQSjBKrcCE8IeFvrTutZCVd+hGpckQIDAigIC1oq4qr5IoH52KGHhoooGfFNsfl769UZZW2EnVksGq9RXLmUhQIAAgXMFBKxzxZw/Caz1mlmZqe58wLs9+PCQWajMWOWbgR9/ePlPnJYMVi9fQQkBAgQInCUgYJ3F5eSVBRIk5pfY++3BeDz1q+vHfsNqbmmbAAECOxHoo5sCVh/joBUfCLz6wcvzv/f67cHMVE3B6jnGbOMHZfuLZc0zVmb4CoSFAAECvQkIWL2NyH7bk2eKPjfrfm577Slgpf9TsMqzVjOK55sJU/n9qs+Xku+Xde0lYzC/xmfnO7bHFdAzAgSuFxCwrjdUwzICdajYy+3BKVjlGavaYJKdglVuB94zdL4yNcgrAQIECBwXELCO+zh6O4H5w+25ap4/yusG15OavJVg9YtZb34227ZJgAABAkcEBKwjOA7dTKCeucmMzc0ufuMLnRqs8nzVvWesQjP/1uInUmAlQIAAgacFugxYTzfbGYMJ1A+3vzNY/6bu5Bmrp24FTsGqfv5pquPWr/N2zLdv3Q7XI0CAwKYEBKxNDdeQjc2MznwGKx/io81gJVjlW4Hzfs4HM/3tLVjN2zdtZ6ymba8E9iigzwROFhCwTqZy4koCdegY6eH2PEe29WA1D1UJvyv9M1AtAQIExhIQsMYazy325vWq0QklVdHmdr9aWpxbgfWD+6X42bKVGatnjV30L5URIEBgJwIC1k4GutNuZnYk69S8rc+Q5He8flo68zdlnfer7D5bBKtnDP4iQIDA+AIC1rbGeLTWjnJ7MGHqrTI4WVvftEuwyg+E5luBWw+RpZsWAgQIEHhKQMB6SsjxNQXmt9ASPLZ4ezBtzu3AzF7VVj8sBVOwuucPhJZmWAgQILCmgLprAQGrFrF/K4Gtz14lWOUB9nlInOzeLRtfKevvllWwKggWAgQI7E1AwNrbiPfT3zeqpmQGqyrqcjfBKjNWrWCVBn+z/PWpsr5ZVguBkwWcSIDAWAIC1ljjuZXe5Hbab80a+17ZznNK5aXbZR6s8sxV3dDMVOW3rHJefcw+AQIECOxMQMDa2YB30t169ud717drlRoSpBKYpluB2a8vlJm3PGeVNdv18S3vt/q75f5oOwECBG4mIGDdjNqFHgUye5X1cfchoaR+Huvhzn8SLBKsjt0KTLtzOzCzVpm9unOTV7l8+rhKxSolQIDA6ALPA9boHdW/bgTy38bMG9PTL7efG6wSwuZ9sU2AAAECBJ4JCFjPGPx1I4EEmKzT5TJD0kNISZvSjqdmrPI7VpmxyrlTH0Z+jcvI/dO3bQhoJYFNCghYmxy2zTa6fvbq3rNXCRB5uP5YsMrtv4SqrDl3s/gLNDyBeIFqVEGAAIHxBQSs8ce4lx4mzMyftcqH9b1mgtKWXDvBqv6/ECevhKmEqu0/vD716PzXjNH8XXGb79smQIAAgQMCAtYBGMWLC8zDVSq/9exVwkFCVb4RmGBVz6alTVmnGavcDqwDRo7vaY3ZnvqrrwQIEFhMQMB6mtIZ1wvkg7oONAk719d8vIZcN9d5KlSlloSpzFZlzXbK9r5y2Pu/AP0nQOBiAQHrYjpvPEOgnr3Kzxuc8fazT83PQOTbisdmqqZK/7Zs/F5Zczsws1dl0/IokID6uOmFAIH+BLSoZwEBq+fRGadt9exVnm9auncJA9Ns1Vul8jrUlaLnS2ZmEvI+Ukq+XNZ/LauFAAECBAgsJiBgLUapogMCCT3zQ5klSsCZeN1vRQAACWdJREFUl126PYWqBKpTZqty3QSrzFbV7bq0Dd63YQFNJ0CAwFoCAtZasuqNQAJQPXuVgJNj16ypNwFpClW5JXisvgSrPLQuWB1TevlY3F4uVUKAAAECTwoIWE8SOeGwwJNH6luBmb3K+uQbGyfMQ9UUrBqnPS9KOEiYS6jKWrfl+Yk2CBAgQIDA0gIC1tKi6psEEohenXYeXzOL9Lh50kvqmGaqplCVsmNvngervDf7x853jAABAgRGE+igPwJWB4MwaBPyLb551/K7V6eEnQSoBKMEqqy5xZiyeV2t7cyMJcBltirvb52j7DqBU8bhuit4NwECBAYRELAGGcjOupEP4vlzUQlWx77Vl/MTihKosp4aqlLvdBswv1/lNuC6/xB+vG71au9IQFMIELhSQMC6EtDbmwJ1mMrsVevEL5TC/yvrJaFqPluVoFWqsawg8LNZna/Mtm0SIECAwBEBAesIjkMXCWQ2KjNQ05sTfjI7Ne3nNefkpxX+vux8sqynLLkFOM1Wpb6+Z6tO6dE2zvnErJnzsDUrtkmAAAECtYCAVYvYv1Yg4WleRwLWtJ9jCUeZsZrfQpyO169TqMoPguYWYN5bn2N/PYGM17x2AWuuYZsAAQJHBHoNWEea7FDnAglF81CVIJVANa3z2a1WV/L+zFQJVS2d+5bNx/W+LXF1AgQIdC4gYHU+QBtt3q9X7c5MSNaq+Pnuu2VLqCoIFgIEehfQPgKnCQhYpzk56zyBj594emarcuvvU+V8t/8KgoUAAQIExhAQsMYYx9568bUjDcptpsxW5feqEq4Sso6c7tAdBepZx4zd1c1RAQECBPYgIGDtYZRv38dvl0tOASrPUmXN/vSa2Sof1gWp86Ueozpwdd58zSNAgMD9BASs+9lfeOXNvC0fzvPZqexvpvEaSoAAAQIErhEQsK7R814CBAgQIEDgAwF/vyAgYL3AYYcAgSMCbhEewXGIAAECcwEBa65hmwCBuYDbunON9bddgQCBgQQErIEGU1cIECBAgACBPgQErD7GQSuWEFDH2gIfW/sC6idAgMAoAgLWKCOpHwTWEZj//4OvrHMJtRIgQGA8gXnAGq93ekSAwLUC81mrX1xbmfcTIEBgLwIC1l5GWj8JXCbwX7O35b9A8k3CGYjNWwm4DoHtCQhY2xszLSZwS4H/ri4mYFUgdgkQINASELBaKsoIDCZwRXd+VL1XwKpA7BIgQKAlIGC1VJQRIDAJ1L+FJWBNMl4JECBwREDAOoLz4SFbBHYrUAes39ythI4TIEDgDAEB6wwspxLYoUAdsMxg7fAfgS53LKBp3QoIWN0OjYYR6EIgASvr1JjPTRteCRAgQOCwgIB12MYRAgQ+EJgHrJSMNIuV/lgJECCwuICAtTipCgkMJ/BO1aM/rfbtEiBAgEAlIGBVIHbPFHD6HgTerjr559W+XQIECBCoBASsCsQuAQIvCSRgvTcr/WzZdpuwIFgIEOhX4N4tE7DuPQKuT2AbAv+yjWZqJQECBPoQELD6GAetINC7wL9XDfRtwgpkvF09IkDgGgEB6xo97yWwH4HcJpz39tX5jm0CBAgQeFFAwHrRwx6BxQQGq6gOWGawBhtg3SFAYFkBAWtZT7URGFlg/ntYecg968j91TcCBAhcLNBxwLq4T95IgMA6Amax1nFVKwECAwoIWAMOqi4RWEmg/sFRz2GtBK3azgU0j8AJAgLWCUhOIUDgmcB3yt/z24SewyogFgIECLQEBKyWijICBE4RyDNYWU85d36ObQIECAwvIGANP8Q6SGBRge9WtZnFqkDsEiBAIAICVhS2tmovgfsJ1A+6ew7rfmPhygQIdCwgYHU8OJpGoEOBBKz5c1g/6rCNmkSAwJ0EXPZDAQHrQwtbBAicJvClcloeeM/rN8q2hQABAgQqAQGrArFLgMCTApnFSrhKyHryZCecI+BcAgRGERCwRhlJ/SBAgAABAgS6ERCwuhkKDVlCQB0ECBAgQKAHAQGrh1HQBgIECBAgQGAogSpgDdU3nSFAgAABAgQI3EVAwLoLu4sSIECAwFkCTiawMQEBa2MDprkECBAgQIBA/wICVv9jpIUElhBQBwECBAjcUEDAuiG2SxEgQIAAAQL7EBCwTh1n5xEgQIAAAQIEThQQsE6EchoBAgQIEOhRQJv6FBCw+hwXrSJAgAABAgQ2LCBgbXjwNJ0AgSUE1EGAAIHlBQSs5U3VSIAAAQIECOxcQMDa+T+AJbqvDgIECBAgQOBFAQHrRQ97BAgQIECAwBgCd+2FgHVXfhcnQIAAAQIERhQQsEYcVX0iQIDAEgLqIEDgYgEB62I6byRAgAABAgQItAUErLaLUgJLCKiDAAECBHYqIGDtdOB1mwABAgQIEFhPoO+AtV6/1UyAAAECBAgQWE1AwFqNVsUECBAgMKqAfhF4SkDAekrIcQIECBAgQIDAmQIC1plgTidAYAkBdRAgQGBsAQFr7PHVOwIECBAgQOAOAgLWHdCXuKQ6CBAgQIAAgX4FBKx+x0bLCBAgQIDA1gS091FAwHqE8EKAAAECBAgQWEpAwFpKUj0ECBBYQkAdBAgMISBgDTGMOkGAAAECBAj0JCBg9TQa2rKEgDoIECBAgMDdBQSsuw+BBhAgQIAAAQKjCbwcsEbrof4QIECAAAECBG4sIGDdGNzlCBAgQOAyAe8isCUBAWtLo6WtBAgQIECAwCYEBKxNDJNGElhCQB0ECBAgcCsBAetW0q5DgAABAgQI7EZAwDpjqJ1KgAABAgQIEDhFQMA6Rck5BAgQIECgXwEt61BAwOpwUDSJAAECBAgQ2LaAgLXt8dN6AgSWEFAHAQIEFhYQsBYGVR0BAgQIECBAQMDyb2AJAXUQIECAAAECMwEBa4ZhkwABAgQIEBhJ4H59EbDuZ+/KBAgQIECAwKACAtagA6tbBAgQWEJAHQQIXCYgYF3m5l0ECBAgQIAAgYMCAtZBGgcILCGgDgIECBDYo4CAtcdR12cCBAgQIEBgVYHuA9aqvVc5AQIECBAgQGAFAQFrBVRVEiBAgMDwAjpI4KiAgHWUx0ECBAgQIECAwPkCAtb5Zt5BgMASAuogQIDAwAIC1sCDq2sECBAgQIDAfQQErPu4L3FVdRAgQIAAAQKdCghYnQ6MZhEgQIAAgW0KaHUEBKwoWAkQIECAAAECCwoIWAtiqooAAQJLCKiDAIHtC/wKAAD///U0lXkAAAAGSURBVAMANsIYrzV7qB0AAAAASUVORK5CYII=', 'aa655b53e71f38b66d063a27cadee30376a4b5a9a9c5c4639e4dc437cad108af', '2025-12-09 13:03:06', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 0, 'SE0V1aNCk4DMXdeusfmfkSBX4N1BYVMWVhUMOUFiNSEx9g84wq2hxmgvgy3Svi36', NULL, '2025-12-09 18:03:06', '2025-12-09 18:03:06'),
(4, 1, 'App\\Models\\Letter', 2, 'data:image/png;base64,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', 'c0462ddf28738d72ab22fd8929e9ad4e155485d308012656e27d5bc43dd54d89', '2025-12-09 13:03:47', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 0, 'Z1UvOOQHoTjJuBOjlp8Lg0DOW1HbRhBpj3EkzF5cEZS5G4VbwXRMevXWftmCVg3p', NULL, '2025-12-09 18:03:47', '2025-12-09 18:03:47');

-- --------------------------------------------------------

--
-- Table structure for table `signature_verifications`
--

CREATE TABLE `signature_verifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `signature_id` bigint(20) UNSIGNED NOT NULL,
  `verified_by_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('verified','rejected','pending') NOT NULL DEFAULT 'pending',
  `remarks` text DEFAULT NULL,
  `verification_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `due_date` timestamp NULL DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `title`, `description`, `assigned_to`, `due_date`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Complete API Documentation', 'Write comprehensive API documentation', NULL, '2025-12-13 11:13:32', 'pending', '2025-12-06 11:13:32', '2025-12-06 11:13:32', NULL),
(2, 'Marketing Campaign Design', 'Design new marketing campaign', NULL, '2025-12-01 11:13:32', 'completed', '2025-12-06 11:13:32', '2025-12-06 11:13:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `employee_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `employee_id`) VALUES
(1, 'Administrator', 'admin@example.com', NULL, '$2y$12$dVitkdytOl0j2lsYbuEexuKrudJoIAVqz1eRD/rcdltHRinPweth.', NULL, '2025-12-06 11:13:25', '2025-12-06 11:16:48', '1'),
(2, 'John Manager', 'manager@example.com', NULL, '$2y$12$XZiUD6i2kwVloVun2pIBeeN4.LMHFgKzwEREwAV2UFkwXNs8hPK3G', NULL, '2025-12-06 11:13:31', '2025-12-06 11:13:31', '2'),
(3, 'Employee 3', 'employee3@example.com', NULL, '$2y$12$S4Qv4xLdz1nOwdu8sUSZ2.6pZxC6Kv8Swj44ucmnnS/F9MXBxgdQS', NULL, '2025-12-06 11:13:32', '2025-12-06 11:14:17', '3'),
(4, 'Employee 4', 'employee4@example.com', NULL, '$2y$12$wcSxHN1RbDK7npOOeRmhc.Gqu6ztr4qEtPpHLJtRGIFCWBW.sXdVe', NULL, '2025-12-06 11:13:32', '2025-12-06 11:19:15', '4'),
(5, 'Employee 5', 'employee5@example.com', NULL, '$2y$12$HsYKHkwGkoyOThfiFK/h6eEUdRMWOeMPmPqBM5i1qlfI935TDQmD2', NULL, '2025-12-06 11:13:32', '2025-12-06 11:14:17', '5'),
(6, 'Sasa Marinir', 'hr@aratechnology.id', NULL, '$2y$12$0PCP6qCmsMDMonBcqa/HeOUDwp1w2pFe9QunyccgMR1Xhh2CA3X2W', 'bL3Z049dNkCC8nD3CvcnhfODeik987nW21UcaQLwDod7AfaJitSaPWIxYH9s', '2025-12-06 11:34:54', '2025-12-06 11:35:57', '6');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employees_email_unique` (`email`),
  ADD KEY `employees_department_id_foreign` (`department_id`),
  ADD KEY `employees_role_id_foreign` (`role_id`);

--
-- Indexes for table `employee_kpi_records`
--
ALTER TABLE `employee_kpi_records`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_kpi_records_employee_id_kpi_id_period_unique` (`employee_id`,`kpi_id`,`period`),
  ADD KEY `employee_kpi_records_kpi_id_foreign` (`kpi_id`),
  ADD KEY `employee_kpi_records_period_status_index` (`period`,`status`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `incidents`
--
ALTER TABLE `incidents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `incidents_reported_by_foreign` (`reported_by`),
  ADD KEY `incidents_resolved_by_foreign` (`resolved_by`),
  ADD KEY `incidents_employee_id_type_index` (`employee_id`,`type`),
  ADD KEY `incidents_incident_date_severity_index` (`incident_date`,`severity`),
  ADD KEY `incidents_status_index` (`status`);

--
-- Indexes for table `inventories`
--
ALTER TABLE `inventories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventories_inventory_category_id_foreign` (`inventory_category_id`);

--
-- Indexes for table `inventory_categories`
--
ALTER TABLE `inventory_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `inventory_categories_name_unique` (`name`);

--
-- Indexes for table `inventory_usage_logs`
--
ALTER TABLE `inventory_usage_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_usage_logs_inventory_id_foreign` (`inventory_id`),
  ADD KEY `inventory_usage_logs_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kpis`
--
ALTER TABLE `kpis`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kpis_code_unique` (`code`);

--
-- Indexes for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leave_requests_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `letters`
--
ALTER TABLE `letters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `letters_letter_number_unique` (`letter_number`),
  ADD KEY `letters_user_id_foreign` (`user_id`),
  ADD KEY `letters_approver_id_foreign` (`approver_id`),
  ADD KEY `letters_letter_template_id_foreign` (`letter_template_id`);

--
-- Indexes for table `letter_archives`
--
ALTER TABLE `letter_archives`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `letter_configurations`
--
ALTER TABLE `letter_configurations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `letter_templates`
--
ALTER TABLE `letter_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payroll`
--
ALTER TABLE `payroll`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payroll_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `performance_reviews`
--
ALTER TABLE `performance_reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `performance_reviews_employee_id_period_unique` (`employee_id`,`period`),
  ADD KEY `performance_reviews_reviewer_id_foreign` (`reviewer_id`),
  ADD KEY `performance_reviews_approved_by_foreign` (`approved_by`),
  ADD KEY `performance_reviews_period_index` (`period`),
  ADD KEY `performance_reviews_status_index` (`status`);

--
-- Indexes for table `presences`
--
ALTER TABLE `presences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `presences_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `signatures`
--
ALTER TABLE `signatures`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `signatures_verification_token_unique` (`verification_token`),
  ADD KEY `signatures_signable_type_signable_id_index` (`signable_type`,`signable_id`),
  ADD KEY `signatures_user_id_foreign` (`user_id`),
  ADD KEY `signatures_verification_token_index` (`verification_token`);

--
-- Indexes for table `signature_verifications`
--
ALTER TABLE `signature_verifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `signature_verifications_verified_by_id_foreign` (`verified_by_id`),
  ADD KEY `signature_verifications_signature_id_status_index` (`signature_id`,`status`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tasks_assigned_to_foreign` (`assigned_to`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `employee_kpi_records`
--
ALTER TABLE `employee_kpi_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incidents`
--
ALTER TABLE `incidents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventories`
--
ALTER TABLE `inventories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `inventory_categories`
--
ALTER TABLE `inventory_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `inventory_usage_logs`
--
ALTER TABLE `inventory_usage_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kpis`
--
ALTER TABLE `kpis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `letters`
--
ALTER TABLE `letters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `letter_archives`
--
ALTER TABLE `letter_archives`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `letter_configurations`
--
ALTER TABLE `letter_configurations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `letter_templates`
--
ALTER TABLE `letter_templates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `payroll`
--
ALTER TABLE `payroll`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `performance_reviews`
--
ALTER TABLE `performance_reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `presences`
--
ALTER TABLE `presences`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `signatures`
--
ALTER TABLE `signatures`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `signature_verifications`
--
ALTER TABLE `signature_verifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  ADD CONSTRAINT `employees_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Constraints for table `employee_kpi_records`
--
ALTER TABLE `employee_kpi_records`
  ADD CONSTRAINT `employee_kpi_records_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `employee_kpi_records_kpi_id_foreign` FOREIGN KEY (`kpi_id`) REFERENCES `kpis` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `incidents`
--
ALTER TABLE `incidents`
  ADD CONSTRAINT `incidents_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `incidents_reported_by_foreign` FOREIGN KEY (`reported_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `incidents_resolved_by_foreign` FOREIGN KEY (`resolved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `inventories`
--
ALTER TABLE `inventories`
  ADD CONSTRAINT `inventories_inventory_category_id_foreign` FOREIGN KEY (`inventory_category_id`) REFERENCES `inventory_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory_usage_logs`
--
ALTER TABLE `inventory_usage_logs`
  ADD CONSTRAINT `inventory_usage_logs_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_usage_logs_inventory_id_foreign` FOREIGN KEY (`inventory_id`) REFERENCES `inventories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD CONSTRAINT `leave_requests_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`);

--
-- Constraints for table `letters`
--
ALTER TABLE `letters`
  ADD CONSTRAINT `letters_approver_id_foreign` FOREIGN KEY (`approver_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `letters_letter_template_id_foreign` FOREIGN KEY (`letter_template_id`) REFERENCES `letter_templates` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `letters_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payroll`
--
ALTER TABLE `payroll`
  ADD CONSTRAINT `payroll_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`);

--
-- Constraints for table `performance_reviews`
--
ALTER TABLE `performance_reviews`
  ADD CONSTRAINT `performance_reviews_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `performance_reviews_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `performance_reviews_reviewer_id_foreign` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `presences`
--
ALTER TABLE `presences`
  ADD CONSTRAINT `presences_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`);

--
-- Constraints for table `signatures`
--
ALTER TABLE `signatures`
  ADD CONSTRAINT `signatures_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `signature_verifications`
--
ALTER TABLE `signature_verifications`
  ADD CONSTRAINT `signature_verifications_signature_id_foreign` FOREIGN KEY (`signature_id`) REFERENCES `signatures` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `signature_verifications_verified_by_id_foreign` FOREIGN KEY (`verified_by_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `employees` (`id`);

-- --------------------------------------------------------
-- Dummy login patch (so you can login immediately after import)
-- Admin credentials: admin@example.com / Password123!
UPDATE `users` SET `password` = '$2y$12$eZG30Iv2ivxccpjhrXxbHOYp/Gtqz8/KEda1Fn4bISrGOuL2HkTSy' WHERE `email` IN (
  'admin@example.com',
  'manager@example.com',
  'employee3@example.com',
  'employee4@example.com',
  'employee5@example.com',
  'hr@aratechnology.id'
);
-- Give admin full access (Power User)
UPDATE `employees` SET `role_id` = 1 WHERE `id` = 1;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
