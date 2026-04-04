-- ============================================
-- MISSING TABLES PATCH
-- Add employee_families and other missing tables
-- Database: hrappsprod
-- ============================================

USE `hrappsprod`;

-- ============================================
-- EMPLOYEE FAMILIES (Family Members)
-- ============================================

DROP TABLE IF EXISTS `employee_families`;
CREATE TABLE `employee_families` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int DEFAULT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `no_kk` varchar(20) DEFAULT NULL,
  `fullname` varchar(100) NOT NULL,
  `relationship` varchar(50) NOT NULL COMMENT 'Spouse, Child, Parent, Sibling',
  `gender` enum('M','F') DEFAULT NULL,
  `place_of_birth` varchar(100) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `education` varchar(100) DEFAULT NULL,
  `occupation` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `is_dependent` tinyint(1) DEFAULT 1 COMMENT 'Is this person a dependent for tax/insurance?',
  `is_emergency_contact` tinyint(1) DEFAULT 0,
  `notes` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  KEY `nik` (`nik`),
  CONSTRAINT `employee_families_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Employee family members and dependents';

-- Insert dummy data
INSERT INTO `employee_families` 
  (`employee_id`, `nik`, `no_kk`, `fullname`, `relationship`, `gender`, `place_of_birth`, `date_of_birth`, `is_dependent`, `is_emergency_contact`, `created_at`, `updated_at`) 
VALUES
  (1, '3201012001010001', '3201010101010001', 'Maria Susanti', 'Spouse', 'F', 'Jakarta', '1987-03-15', 1, 1, NOW(), NOW()),
  (1, '3201012010050001', '3201010101010001', 'Ahmad Pratama', 'Child', 'M', 'Jakarta', '2010-05-20', 1, 0, NOW(), NOW()),
  (1, '3201012012080001', '3201010101010001', 'Siti Rahmawati', 'Child', 'F', 'Jakarta', '2012-08-15', 1, 0, NOW(), NOW()),
  (2, '3273011990120001', '3273010505050001', 'Dewi Lestari', 'Spouse', 'F', 'Bandung', '1990-12-10', 1, 1, NOW(), NOW()),
  (3, '3578011992030001', '3578020202020001', 'Budi Santoso', 'Spouse', 'M', 'Surabaya', '1992-03-25', 1, 1, NOW(), NOW()),
  (3, '3578012015060001', '3578020202020001', 'Ani Wijaya', 'Child', 'F', 'Surabaya', '2015-06-18', 1, 0, NOW(), NOW());

-- ============================================
-- PRESENCES (Enhanced Attendance Tracking)
-- ============================================

DROP TABLE IF EXISTS `presences`;
CREATE TABLE `presences` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` int DEFAULT NULL,
  `date` date NOT NULL,
  `check_in` time DEFAULT NULL,
  `check_in_location` varchar(255) DEFAULT NULL,
  `check_in_latitude` decimal(10,8) DEFAULT NULL,
  `check_in_longitude` decimal(11,8) DEFAULT NULL,
  `check_in_photo` varchar(255) DEFAULT NULL,
  `check_out` time DEFAULT NULL,
  `check_out_location` varchar(255) DEFAULT NULL,
  `check_out_latitude` decimal(10,8) DEFAULT NULL,
  `check_out_longitude` decimal(11,8) DEFAULT NULL,
  `check_out_photo` varchar(255) DEFAULT NULL,
  `work_type` enum('WFO','WFH','Remote','Field') DEFAULT 'WFO',
  `status` enum('Present','Late','Absent','Leave','Holiday') DEFAULT 'Present',
  `working_hours` decimal(5,2) DEFAULT NULL COMMENT 'Calculated working hours',
  `overtime_hours` decimal(5,2) DEFAULT NULL,
  `notes` text,
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  KEY `date` (`date`),
  KEY `status` (`status`),
  CONSTRAINT `presences_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Enhanced presence/attendance tracking';

-- Insert dummy data
INSERT INTO `presences` 
  (`employee_id`, `date`, `check_in`, `check_in_latitude`, `check_in_longitude`, `check_out`, `check_out_latitude`, `check_out_longitude`, `work_type`, `status`, `working_hours`, `created_at`, `updated_at`)
VALUES
  (1, CURDATE(), '08:00:00', -6.2088, 106.8456, '17:00:00', -6.2088, 106.8456, 'WFO', 'Present', 9.0, NOW(), NOW()),
  (2, CURDATE(), '08:15:00', -6.9175, 107.6191, NULL, NULL, NULL, 'WFO', 'Present', NULL, NOW(), NOW()),
  (4, CURDATE(), '08:00:00', -6.1751, 106.8650, '17:00:00', -6.1751, 106.8650, 'WFH', 'Present', 9.0, NOW(), NOW()),
  (1, DATE_SUB(CURDATE(), INTERVAL 1 DAY), '08:00:00', -6.2088, 106.8456, '17:00:00', -6.2088, 106.8456, 'WFO', 'Present', 9.0, NOW(), NOW()),
  (2, DATE_SUB(CURDATE(), INTERVAL 1 DAY), '08:30:00', -6.9175, 107.6191, '17:30:00', -6.9175, 107.6191, 'WFO', 'Late', 9.0, NOW(), NOW());

-- ============================================
-- EMPLOYEE DOCUMENTS (Additional documents)
-- ============================================

DROP TABLE IF EXISTS `employee_documents`;
CREATE TABLE `employee_documents` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int DEFAULT NULL,
  `document_type` varchar(50) NOT NULL COMMENT 'Contract, Certificate, License, etc',
  `document_name` varchar(255) NOT NULL,
  `document_number` varchar(100) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `issuing_authority` varchar(255) DEFAULT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  KEY `document_type` (`document_type`),
  CONSTRAINT `employee_documents_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Employee documents and certificates';

-- Insert dummy data
INSERT INTO `employee_documents` 
  (`employee_id`, `document_type`, `document_name`, `document_number`, `issue_date`, `expiry_date`, `created_at`, `updated_at`)
VALUES
  (1, 'Contract', 'Employment Contract', 'EC/2020/001', '2020-01-15', '2025-01-15', NOW(), NOW()),
  (1, 'Certificate', 'Project Management Certificate', 'PMP-123456', '2021-06-20', NULL, NOW(), NOW()),
  (2, 'Contract', 'Employment Contract', 'EC/2021/002', '2021-03-10', '2026-03-10', NOW(), NOW()),
  (4, 'Contract', 'Employment Contract', 'EC/2022/004', '2022-01-20', '2025-01-20', NOW(), NOW());

-- ============================================
-- LEAVE BALANCES (Leave quota tracking)
-- ============================================

DROP TABLE IF EXISTS `leave_balances`;
CREATE TABLE `leave_balances` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int DEFAULT NULL,
  `year` int NOT NULL,
  `leave_type` varchar(50) NOT NULL COMMENT 'Annual, Sick, etc',
  `total_days` decimal(5,2) NOT NULL DEFAULT 12.00,
  `used_days` decimal(5,2) NOT NULL DEFAULT 0.00,
  `remaining_days` decimal(5,2) GENERATED ALWAYS AS ((`total_days` - `used_days`)) STORED,
  `carried_forward` decimal(5,2) DEFAULT 0.00,
  `notes` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_year_type` (`employee_id`,`year`,`leave_type`),
  KEY `employee_id` (`employee_id`),
  CONSTRAINT `leave_balances_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Employee leave balance tracking';

-- Insert dummy data for current year
INSERT INTO `leave_balances` 
  (`employee_id`, `year`, `leave_type`, `total_days`, `used_days`, `carried_forward`, `created_at`, `updated_at`)
VALUES
  (1, YEAR(CURDATE()), 'Annual Leave', 12.00, 3.00, 2.00, NOW(), NOW()),
  (1, YEAR(CURDATE()), 'Sick Leave', 12.00, 1.00, 0.00, NOW(), NOW()),
  (2, YEAR(CURDATE()), 'Annual Leave', 12.00, 0.00, 0.00, NOW(), NOW()),
  (2, YEAR(CURDATE()), 'Sick Leave', 12.00, 0.00, 0.00, NOW(), NOW()),
  (3, YEAR(CURDATE()), 'Annual Leave', 12.00, 2.00, 0.00, NOW(), NOW()),
  (4, YEAR(CURDATE()), 'Annual Leave', 12.00, 3.00, 0.00, NOW(), NOW()),
  (5, YEAR(CURDATE()), 'Annual Leave', 12.00, 0.00, 0.00, NOW(), NOW());

-- ============================================
-- EMPLOYEE CONTACTS (Emergency & References)
-- ============================================

DROP TABLE IF EXISTS `employee_contacts`;
CREATE TABLE `employee_contacts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int DEFAULT NULL,
  `contact_type` enum('Emergency','Reference','Next of Kin') NOT NULL DEFAULT 'Emergency',
  `name` varchar(100) NOT NULL,
  `relationship` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `phone_alt` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text,
  `is_primary` tinyint(1) DEFAULT 0,
  `notes` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  CONSTRAINT `employee_contacts_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Emergency contacts and references';

-- Insert dummy data
INSERT INTO `employee_contacts` 
  (`employee_id`, `contact_type`, `name`, `relationship`, `phone`, `email`, `is_primary`, `created_at`, `updated_at`)
VALUES
  (1, 'Emergency', 'Maria Susanti', 'Spouse', '081234567899', 'maria@email.com', 1, NOW(), NOW()),
  (2, 'Emergency', 'Dewi Lestari', 'Spouse', '081234567898', 'dewi@email.com', 1, NOW(), NOW()),
  (3, 'Emergency', 'Budi Santoso', 'Spouse', '081234567897', 'budi@email.com', 1, NOW(), NOW()),
  (4, 'Emergency', 'Parent Name', 'Parent', '081234567896', NULL, 1, NOW(), NOW()),
  (5, 'Emergency', 'Sibling Name', 'Sibling', '081234567895', NULL, 1, NOW(), NOW());

-- ============================================
-- Verification queries
-- ============================================

-- Check if tables were created
SELECT 
  'employee_families' as table_name, COUNT(*) as record_count FROM employee_families
UNION ALL
SELECT 'presences', COUNT(*) FROM presences
UNION ALL
SELECT 'employee_documents', COUNT(*) FROM employee_documents
UNION ALL
SELECT 'leave_balances', COUNT(*) FROM leave_balances
UNION ALL
SELECT 'employee_contacts', COUNT(*) FROM employee_contacts;

