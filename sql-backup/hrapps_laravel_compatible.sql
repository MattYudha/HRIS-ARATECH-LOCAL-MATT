-- ============================================
-- Combined HRIS Schema - Laravel Compatible
-- Database: hrappsprod
-- Generated: 2025-12-27
-- Compatible with Laravel authentication
-- ============================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

USE `hrappsprod`;

-- ============================================
-- FOUNDATION & ORGANIZATION STRUCTURE
-- ============================================

DROP TABLE IF EXISTS `foundations`;
CREATE TABLE `foundations` (
  `foundation_id` varchar(8) NOT NULL,
  `foundation_name` varchar(64) DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  `phone` int DEFAULT NULL,
  `address` text,
  `status` int DEFAULT NULL,
  PRIMARY KEY (`foundation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `foundations` VALUES
('FND001', 'Ara Technology Foundation', 'contact@aratechnology.id', 621234567, 'Jakarta, Indonesia', 1),
('FND002', 'Innovation Hub Foundation', 'info@innovationhub.id', 621234568, 'Bandung, Indonesia', 1);

DROP TABLE IF EXISTS `departments`;
CREATE TABLE `departments` (
  `department_id` int NOT NULL AUTO_INCREMENT,
  `foundation_id` varchar(8) DEFAULT NULL,
  `department_name` varchar(32) DEFAULT NULL,
  `description` varchar(128) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `code` varchar(8) DEFAULT NULL,
  `parent_id` int DEFAULT NULL,
  `check_in` time DEFAULT NULL,
  `check_out` time DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`department_id`),
  KEY `foundation_id` (`foundation_id`),
  CONSTRAINT `departments_ibfk_1` FOREIGN KEY (`foundation_id`) REFERENCES `foundations` (`foundation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `departments` VALUES
(1, 'FND001', 'IT Department', 'Information Technology', NOW(), NOW(), 'IT', NULL, '08:00:00', '17:00:00', NULL),
(2, 'FND001', 'HR Department', 'Human Resources', NOW(), NOW(), 'HR', NULL, '08:00:00', '17:00:00', NULL),
(3, 'FND001', 'Finance', 'Finance & Accounting', NOW(), NOW(), 'FIN', NULL, '08:00:00', '17:00:00', NULL),
(4, 'FND001', 'Operations', 'Operations Department', NOW(), NOW(), 'OPS', NULL, '08:00:00', '17:00:00', NULL);

DROP TABLE IF EXISTS `job_positions`;
CREATE TABLE `job_positions` (
  `position_id` varchar(10) NOT NULL,
  `level` varchar(20) DEFAULT NULL,
  `salary_grade` decimal(10,2) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `description` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`position_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `job_positions` VALUES
('POS001', 'Executive', 5000000.00, 'CEO', 'Chief Executive Officer', NOW(), NOW()),
('POS002', 'Manager', 3500000.00, 'IT Manager', 'IT Department Manager', NOW(), NOW()),
('POS003', 'Manager', 3500000.00, 'HR Manager', 'HR Department Manager', NOW(), NOW()),
('POS004', 'Staff', 2000000.00, 'Software Developer', 'Software Development', NOW(), NOW()),
('POS005', 'Staff', 1800000.00, 'HR Staff', 'HR Administration', NOW(), NOW());

-- ============================================
-- USER MANAGEMENT & AUTHENTICATION (Laravel Compatible)
-- ============================================

DROP TABLE IF EXISTS `user_types`;
CREATE TABLE `user_types` (
  `user_type_id` varchar(8) NOT NULL,
  `user_type_name` varchar(32) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`user_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `user_types` VALUES
('UT001', 'Administrator', 'System Administrator with full access'),
('UT002', 'Manager', 'Department Manager'),
('UT003', 'Employee', 'Regular Employee');

-- Laravel-compatible users table with id as primary key
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` int DEFAULT NULL COMMENT 'Link to employees table',
  `foundation_id` varchar(8) DEFAULT NULL,
  `user_type_id` varchar(8) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `last_login` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `foundation_id` (`foundation_id`),
  KEY `user_type_id` (`user_type_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`foundation_id`) REFERENCES `foundations` (`foundation_id`),
  CONSTRAINT `users_ibfk_2` FOREIGN KEY (`user_type_id`) REFERENCES `user_types` (`user_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Password: Password123!
INSERT INTO `users` (`id`, `foundation_id`, `user_type_id`, `name`, `email`, `email_verified_at`, `password`, `phone`, `profile_picture`, `active`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'FND001', 'UT001', 'Admin User', 'admin@aratechnology.id', NOW(), '$2y$12$LGl8.2f4yeT8FmxqJH8gvOLKhvF4FfF8lE3fLqGfFaOxNvPYqHZIa', '081234567890', NULL, 1, NOW(), NOW(), NOW()),
(2, 'FND001', 'UT002', 'IT Manager', 'manager.it@aratechnology.id', NOW(), '$2y$12$LGl8.2f4yeT8FmxqJH8gvOLKhvF4FfF8lE3fLqGfFaOxNvPYqHZIa', '081234567891', NULL, 1, NULL, NOW(), NOW()),
(3, 'FND001', 'UT002', 'HR Manager', 'manager.hr@aratechnology.id', NOW(), '$2y$12$LGl8.2f4yeT8FmxqJH8gvOLKhvF4FfF8lE3fLqGfFaOxNvPYqHZIa', '081234567892', NULL, 1, NULL, NOW(), NOW()),
(4, 'FND001', 'UT003', 'John Developer', 'john.dev@aratechnology.id', NOW(), '$2y$12$LGl8.2f4yeT8FmxqJH8gvOLKhvF4FfF8lE3fLqGfFaOxNvPYqHZIa', '081234567893', NULL, 1, NULL, NOW(), NOW()),
(5, 'FND001', 'UT003', 'Jane HR', 'jane.hr@aratechnology.id', NOW(), '$2y$12$LGl8.2f4yeT8FmxqJH8gvOLKhvF4FfF8lE3fLqGfFaOxNvPYqHZIa', '081234567894', NULL, 1, NULL, NOW(), NOW());

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `role_id` int NOT NULL AUTO_INCREMENT,
  `role_name` varchar(32) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `roles` VALUES
(1, 'Power User', 'Full system access'),
(2, 'Manager', 'Department management access'),
(3, 'Employee', 'Basic employee access'),
(4, 'HR Admin', 'HR administration access');

DROP TABLE IF EXISTS `list_menu_features`;
CREATE TABLE `list_menu_features` (
  `menu_id` int NOT NULL AUTO_INCREMENT,
  `parent_id` int DEFAULT NULL,
  `caption_eng` varchar(64) DEFAULT NULL,
  `caption_indo` varchar(64) DEFAULT NULL,
  `seq_level` int DEFAULT NULL,
  `seq_order` int DEFAULT NULL,
  `icon` varchar(32) DEFAULT NULL,
  `url` varchar(128) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `list_menu_features` VALUES
(1, NULL, 'Dashboard', 'Dasbor', 1, 1, 'dashboard', '/dashboard', 'Main dashboard'),
(2, NULL, 'Employees', 'Karyawan', 1, 2, 'users', '/employees', 'Employee management'),
(3, NULL, 'Attendance', 'Kehadiran', 1, 3, 'calendar', '/attendance', 'Attendance management'),
(4, NULL, 'Leave', 'Cuti', 1, 4, 'clock', '/leave', 'Leave management'),
(5, NULL, 'Payroll', 'Penggajian', 1, 5, 'money', '/payroll', 'Payroll management'),
(6, NULL, 'KPI', 'KPI', 1, 6, 'chart', '/kpi', 'KPI management'),
(7, NULL, 'Inventory', 'Inventaris', 1, 7, 'box', '/inventory', 'Inventory management'),
(8, NULL, 'Letters', 'Surat', 1, 8, 'file-text', '/letters', 'Letter management'),
(9, NULL, 'Reports', 'Laporan', 1, 9, 'bar-chart', '/reports', 'Reports');

DROP TABLE IF EXISTS `user_type_roles`;
CREATE TABLE `user_type_roles` (
  `user_type_id` varchar(8) NOT NULL,
  `role_id` int NOT NULL,
  `menu_id` int NOT NULL,
  `menu` int DEFAULT NULL,
  `role_status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`user_type_id`, `role_id`, `menu_id`),
  KEY `role_id` (`role_id`),
  KEY `menu_id` (`menu_id`),
  CONSTRAINT `user_type_roles_ibfk_1` FOREIGN KEY (`user_type_id`) REFERENCES `user_types` (`user_type_id`),
  CONSTRAINT `user_type_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`),
  CONSTRAINT `user_type_roles_ibfk_3` FOREIGN KEY (`menu_id`) REFERENCES `list_menu_features` (`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `user_type_roles` VALUES
('UT001', 1, 1, 1, 1), ('UT001', 1, 2, 2, 1), ('UT001', 1, 3, 3, 1), ('UT001', 1, 4, 4, 1),
('UT001', 1, 5, 5, 1), ('UT001', 1, 6, 6, 1), ('UT001', 1, 7, 7, 1), ('UT001', 1, 8, 8, 1), ('UT001', 1, 9, 9, 1),
('UT002', 2, 1, 1, 1), ('UT002', 2, 2, 2, 1), ('UT002', 2, 3, 3, 1), ('UT002', 2, 4, 4, 1), ('UT002', 2, 6, 6, 1),
('UT003', 3, 1, 1, 1), ('UT003', 3, 3, 3, 1), ('UT003', 3, 4, 4, 1);

-- ============================================
-- EMPLOYEE MANAGEMENT
-- ============================================

DROP TABLE IF EXISTS `education_levels`;
CREATE TABLE `education_levels` (
  `education_level_id` int NOT NULL AUTO_INCREMENT,
  `level` int DEFAULT NULL,
  `create_at` datetime DEFAULT NULL,
  `create_by` varchar(32) DEFAULT NULL,
  `update_at` datetime DEFAULT NULL,
  `update_by` int DEFAULT NULL,
  PRIMARY KEY (`education_level_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `education_levels` VALUES
(1, 1, NOW(), 'system', NOW(), NULL),
(2, 2, NOW(), 'system', NOW(), NULL),
(3, 3, NOW(), 'system', NOW(), NULL),
(4, 4, NOW(), 'system', NOW(), NULL);

DROP TABLE IF EXISTS `employees`;
CREATE TABLE `employees` (
  `employee_id` int NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL COMMENT 'Laravel users.id',
  `hire_date` date DEFAULT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `join_date` date DEFAULT NULL,
  `resign_date` date DEFAULT NULL,
  `emp_code` varchar(20) DEFAULT NULL,
  `gender` varchar(1) DEFAULT NULL,
  `npwp` varchar(20) DEFAULT NULL,
  `place_of_birth` varchar(32) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `marital_status` varchar(5) DEFAULT NULL,
  `religion_id` int DEFAULT NULL,
  `education_level_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `address` text,
  `phone_number` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT 'active',
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`employee_id`),
  KEY `user_id` (`user_id`),
  KEY `education_level_id` (`education_level_id`),
  CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `employees_ibfk_2` FOREIGN KEY (`education_level_id`) REFERENCES `education_levels` (`education_level_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `employees` VALUES
(1, 1, '2020-01-15', 'Admin User', '2020-01-15', NULL, 'EMP001', 'M', '1234567890', 'Jakarta', '1985-05-20', 'M', 1, 4, NOW(), NOW(), 'Jakarta Selatan', '081234567890', 'admin@aratechnology.id', 'active', NULL),
(2, 2, '2021-03-10', 'IT Manager', '2021-03-10', NULL, 'EMP002', 'M', '1234567891', 'Bandung', '1988-08-15', 'M', 1, 4, NOW(), NOW(), 'Bandung', '081234567891', 'manager.it@aratechnology.id', 'active', NULL),
(3, 3, '2021-06-01', 'HR Manager', '2021-06-01', NULL, 'EMP003', 'F', '1234567892', 'Surabaya', '1990-12-10', 'S', 1, 4, NOW(), NOW(), 'Surabaya', '081234567892', 'manager.hr@aratechnology.id', 'active', NULL),
(4, 4, '2022-01-20', 'John Developer', '2022-01-20', NULL, 'EMP004', 'M', '1234567893', 'Jakarta', '1995-03-25', 'S', 1, 4, NOW(), NOW(), 'Jakarta Barat', '081234567893', 'john.dev@aratechnology.id', 'active', NULL),
(5, 5, '2022-05-15', 'Jane HR', '2022-05-15', NULL, 'EMP005', 'F', '1234567894', 'Yogyakarta', '1993-07-18', 'S', 1, 3, NOW(), NOW(), 'Yogyakarta', '081234567894', 'jane.hr@aratechnology.id', 'active', NULL);

-- Update users table with employee_id backlink
UPDATE `users` SET `employee_id` = 1 WHERE `id` = 1;
UPDATE `users` SET `employee_id` = 2 WHERE `id` = 2;
UPDATE `users` SET `employee_id` = 3 WHERE `id` = 3;
UPDATE `users` SET `employee_id` = 4 WHERE `id` = 4;
UPDATE `users` SET `employee_id` = 5 WHERE `id` = 5;

DROP TABLE IF EXISTS `pay_grade`;
CREATE TABLE `pay_grade` (
  `pay_grade_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `currency` varchar(255) DEFAULT NULL,
  `pay_schedule` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`pay_grade_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `pay_grade` VALUES
(1, 'Executive', 'IDR', 'monthly'),
(2, 'Manager', 'IDR', 'monthly'),
(3, 'Senior Staff', 'IDR', 'monthly'),
(4, 'Staff', 'IDR', 'monthly');

DROP TABLE IF EXISTS `employee_positions`;
CREATE TABLE `employee_positions` (
  `employee_position_id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int DEFAULT NULL,
  `position_id` varchar(10) DEFAULT NULL,
  `department_id` int DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `sk_file_name` text,
  `sk_number` varchar(32) DEFAULT NULL,
  `base_on_salary` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `pay_grade_id` int DEFAULT NULL,
  `is_supervisor` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`employee_position_id`),
  KEY `employee_id` (`employee_id`),
  KEY `position_id` (`position_id`),
  KEY `department_id` (`department_id`),
  KEY `pay_grade_id` (`pay_grade_id`),
  CONSTRAINT `employee_positions_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`),
  CONSTRAINT `employee_positions_ibfk_2` FOREIGN KEY (`position_id`) REFERENCES `job_positions` (`position_id`),
  CONSTRAINT `employee_positions_ibfk_3` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`),
  CONSTRAINT `employee_positions_ibfk_4` FOREIGN KEY (`pay_grade_id`) REFERENCES `pay_grade` (`pay_grade_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `employee_positions` VALUES
(1, 1, 'POS001', 1, '2020-01-15', NULL, NULL, 'SK/001/2020', 5000000, NOW(), NOW(), 1, 1),
(2, 2, 'POS002', 1, '2021-03-10', NULL, NULL, 'SK/002/2021', 3500000, NOW(), NOW(), 2, 1),
(3, 3, 'POS003', 2, '2021-06-01', NULL, NULL, 'SK/003/2021', 3500000, NOW(), NOW(), 2, 1),
(4, 4, 'POS004', 1, '2022-01-20', NULL, NULL, 'SK/004/2022', 2000000, NOW(), NOW(), 4, 0),
(5, 5, 'POS005', 2, '2022-05-15', NULL, NULL, 'SK/005/2022', 1800000, NOW(), NOW(), 4, 0);

DROP TABLE IF EXISTS `employee_families`;
CREATE TABLE `employee_families` (
  `nik` varchar(16) NOT NULL,
  `no_kk` varchar(16) DEFAULT NULL,
  `fullname` varchar(64) DEFAULT NULL,
  `place_of_birth` varchar(32) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `employee_id` int DEFAULT NULL,
  `gender` varchar(1) DEFAULT NULL,
  `status_active` int DEFAULT NULL,
  `relationship` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`nik`),
  KEY `employee_id` (`employee_id`),
  CONSTRAINT `employee_families_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `employee_families` VALUES
('3201012001010001', '3201010101010001', 'Maria Wife', 'Jakarta', '1987-03-15', 1, 'F', 1, 'Spouse'),
('3201012010050001', '3201010101010001', 'Child One', 'Jakarta', '2010-05-20', 1, 'M', 1, 'Child');

-- ============================================
-- DOCUMENT & IDENTITY MANAGEMENT
-- ============================================

DROP TABLE IF EXISTS `identity_types`;
CREATE TABLE `identity_types` (
  `identity_types_id` int NOT NULL AUTO_INCREMENT,
  `identity_type_name` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`identity_types_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `identity_types` VALUES
(1, 'KTP'), (2, 'Passport'), (3, 'Driver License'), (4, 'NPWP');

DROP TABLE IF EXISTS `document_identity`;
CREATE TABLE `document_identity` (
  `id` int NOT NULL AUTO_INCREMENT,
  `identity_type_id` int DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `identity_number` varchar(20) DEFAULT NULL,
  `file_name` text,
  `description` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `identity_type_id` (`identity_type_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `document_identity_ibfk_1` FOREIGN KEY (`identity_type_id`) REFERENCES `identity_types` (`identity_types_id`),
  CONSTRAINT `document_identity_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `document_identity` VALUES
(1, 1, 1, '3201012001010001', 'ktp_usr0001.pdf', 'KTP Identity', NOW(), NOW()),
(2, 4, 1, '1234567890', 'npwp_usr0001.pdf', 'NPWP Tax ID', NOW(), NOW());

DROP TABLE IF EXISTS `bank_account`;
CREATE TABLE `bank_account` (
  `bank_account_id` int NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `account_no` varchar(255) DEFAULT NULL,
  `account_holder` varchar(255) DEFAULT NULL,
  `status` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`bank_account_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `bank_account_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `bank_account` VALUES
(1, 1, 'BCA', '1234567890', 'Admin User', 1, NOW(), NOW()),
(2, 2, 'Mandiri', '2345678901', 'IT Manager', 1, NOW(), NOW()),
(3, 3, 'BNI', '3456789012', 'HR Manager', 1, NOW(), NOW());

-- ============================================
-- ATTENDANCE MANAGEMENT
-- ============================================

DROP TABLE IF EXISTS `attendance`;
CREATE TABLE `attendance` (
  `attendance_id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `work_date` date DEFAULT NULL,
  `check_in` datetime DEFAULT NULL,
  `check_out` datetime DEFAULT NULL,
  `work_location` varchar(255) DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `lat` double DEFAULT NULL,
  `long` double DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`attendance_id`),
  KEY `employee_id` (`employee_id`),
  CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `attendance` VALUES
(1, 1, 'present', '2025-12-27', '2025-12-27 08:00:00', '2025-12-27 17:00:00', 'Office', 'On time', -6.2088, 106.8456, NOW(), NOW(), NULL),
(2, 2, 'present', '2025-12-27', '2025-12-27 08:15:00', NULL, 'Office', 'Checked in', -6.2088, 106.8456, NOW(), NOW(), NULL),
(3, 4, 'present', '2025-12-27', '2025-12-27 08:00:00', '2025-12-27 17:00:00', 'WFH', 'Work from home', -6.1751, 106.8650, NOW(), NOW(), NULL);

-- ============================================
-- LEAVE & APPROVAL MANAGEMENT
-- ============================================

DROP TABLE IF EXISTS `approval_types`;
CREATE TABLE `approval_types` (
  `approval_type_id` varchar(255) NOT NULL,
  `approval_type` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(32) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`approval_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `approval_types` VALUES
('APT001', 'Leave', NOW(), 'system', NOW(), 'system'),
('APT002', 'Overtime', NOW(), 'system', NOW(), 'system'),
('APT003', 'Reimbursement', NOW(), 'system', NOW(), 'system'),
('APT004', 'Permission', NOW(), 'system', NOW(), 'system');

DROP TABLE IF EXISTS `category_approvals`;
CREATE TABLE `category_approvals` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `approval_type_id` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`category_id`),
  KEY `approval_type_id` (`approval_type_id`),
  CONSTRAINT `category_approvals_ibfk_1` FOREIGN KEY (`approval_type_id`) REFERENCES `approval_types` (`approval_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `category_approvals` VALUES
(1, 'APT001', 'Annual Leave'), (2, 'APT001', 'Sick Leave'), (3, 'APT001', 'Emergency Leave'),
(4, 'APT002', 'Regular Overtime'), (5, 'APT003', 'Transportation'), (6, 'APT003', 'Medical');

DROP TABLE IF EXISTS `approval_requests`;
CREATE TABLE `approval_requests` (
  `approval_id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int DEFAULT NULL,
  `approval_type_id` varchar(255) DEFAULT NULL,
  `approval_status` varchar(255) DEFAULT NULL,
  `approval_date` date DEFAULT NULL,
  `amount` int DEFAULT NULL,
  `attachment_url` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(32) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `reason` text,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`approval_id`),
  KEY `employee_id` (`employee_id`),
  KEY `approval_type_id` (`approval_type_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `approval_requests_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`),
  CONSTRAINT `approval_requests_ibfk_2` FOREIGN KEY (`approval_type_id`) REFERENCES `approval_types` (`approval_type_id`),
  CONSTRAINT `approval_requests_ibfk_3` FOREIGN KEY (`category_id`) REFERENCES `category_approvals` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `approval_requests` VALUES
(1, 4, 'APT001', 'approved', '2025-12-20', NULL, NULL, NOW(), 'EMP004', NOW(), NULL, 1, '2025-12-28 00:00:00', '2025-12-30 00:00:00', 'Family vacation', NULL),
(2, 5, 'APT001', 'pending', NULL, NULL, NULL, NOW(), 'EMP005', NOW(), NULL, 2, '2025-12-27 00:00:00', '2025-12-27 00:00:00', 'Not feeling well', NULL);

DROP TABLE IF EXISTS `approved`;
CREATE TABLE `approved` (
  `approved_id` int NOT NULL AUTO_INCREMENT,
  `approval_id` int DEFAULT NULL,
  `approval_date` date DEFAULT NULL,
  `approval_status` varchar(20) DEFAULT NULL,
  `approved_by` int DEFAULT NULL,
  PRIMARY KEY (`approved_id`),
  KEY `approval_id` (`approval_id`),
  KEY `approved_by` (`approved_by`),
  CONSTRAINT `approved_ibfk_1` FOREIGN KEY (`approval_id`) REFERENCES `approval_requests` (`approval_id`),
  CONSTRAINT `approved_ibfk_2` FOREIGN KEY (`approved_by`) REFERENCES `employee_positions` (`employee_position_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `approved` VALUES (1, 1, '2025-12-20', 'approved', 2);

-- ============================================
-- PAYROLL MANAGEMENT
-- ============================================

DROP TABLE IF EXISTS `payroll_period`;
CREATE TABLE `payroll_period` (
  `period_id` int NOT NULL AUTO_INCREMENT,
  `period_code` varchar(255) DEFAULT NULL,
  `date_start` date DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  `pay_date` date DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`period_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `payroll_period` VALUES
(1, '2025-12', '2025-12-01', '2025-12-31', '2026-01-05', 'open', NOW(), NOW()),
(2, '2025-11', '2025-11-01', '2025-11-30', '2025-12-05', 'closed', NOW(), NOW());

DROP TABLE IF EXISTS `pay_component`;
CREATE TABLE `pay_component` (
  `component_id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `calc_basis` int DEFAULT NULL,
  `taxable` tinyint(1) DEFAULT NULL,
  `statutory` tinyint(1) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`component_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `pay_component` VALUES
(1, 'BASIC', 'Basic Salary', 'earning', 0, 1, 0, NOW(), NOW()),
(2, 'ALLOW_TRANS', 'Transportation Allowance', 'earning', 0, 1, 0, NOW(), NOW()),
(3, 'ALLOW_MEAL', 'Meal Allowance', 'earning', 0, 1, 0, NOW(), NOW()),
(4, 'DED_TAX', 'Income Tax', 'deduction', 1, 0, 1, NOW(), NOW()),
(5, 'DED_BPJS_TK', 'BPJS Ketenagakerjaan', 'deduction', 1, 0, 1, NOW(), NOW()),
(6, 'DED_BPJS_KES', 'BPJS Kesehatan', 'deduction', 1, 0, 1, NOW(), NOW());

DROP TABLE IF EXISTS `pay_grade_component`;
CREATE TABLE `pay_grade_component` (
  `pgc_id` int NOT NULL AUTO_INCREMENT,
  `pay_grade_id` int DEFAULT NULL,
  `component_id` int DEFAULT NULL,
  `default_amount` decimal(15,2) DEFAULT NULL,
  `default_rate` decimal(5,2) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`pgc_id`),
  KEY `pay_grade_id` (`pay_grade_id`),
  KEY `component_id` (`component_id`),
  CONSTRAINT `pay_grade_component_ibfk_1` FOREIGN KEY (`pay_grade_id`) REFERENCES `pay_grade` (`pay_grade_id`),
  CONSTRAINT `pay_grade_component_ibfk_2` FOREIGN KEY (`component_id`) REFERENCES `pay_component` (`component_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `pay_grade_component` VALUES
(1, 1, 1, 5000000.00, NULL, 1), (2, 1, 2, 1000000.00, NULL, 1),
(3, 2, 1, 3500000.00, NULL, 1), (4, 2, 2, 750000.00, NULL, 1),
(5, 4, 1, 2000000.00, NULL, 1), (6, 4, 2, 500000.00, NULL, 1);

DROP TABLE IF EXISTS `employee_pay_component`;
CREATE TABLE `employee_pay_component` (
  `epc_id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int DEFAULT NULL,
  `component_id` int DEFAULT NULL,
  `amount_override` decimal(15,2) DEFAULT NULL,
  `rate_override` decimal(5,2) DEFAULT NULL,
  `effective_from` date DEFAULT NULL,
  `effective_to` date DEFAULT NULL,
  PRIMARY KEY (`epc_id`),
  KEY `employee_id` (`employee_id`),
  KEY `component_id` (`component_id`),
  CONSTRAINT `employee_pay_component_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`),
  CONSTRAINT `employee_pay_component_ibfk_2` FOREIGN KEY (`component_id`) REFERENCES `pay_component` (`component_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `payslip`;
CREATE TABLE `payslip` (
  `payslip_id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int DEFAULT NULL,
  `period_id` int DEFAULT NULL,
  `gross_amount` decimal(15,2) DEFAULT NULL,
  `total_deduction` decimal(15,2) DEFAULT NULL,
  `net_amount` decimal(15,2) DEFAULT NULL,
  `generated_at` datetime DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`payslip_id`),
  KEY `employee_id` (`employee_id`),
  KEY `period_id` (`period_id`),
  CONSTRAINT `payslip_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`),
  CONSTRAINT `payslip_ibfk_2` FOREIGN KEY (`period_id`) REFERENCES `payroll_period` (`period_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `payslip` VALUES
(1, 1, 2, 6000000.00, 600000.00, 5400000.00, '2025-12-01', 'paid', NOW(), NOW(), NULL),
(2, 2, 2, 4250000.00, 425000.00, 3825000.00, '2025-12-01', 'paid', NOW(), NOW(), NULL);

DROP TABLE IF EXISTS `payslip_line`;
CREATE TABLE `payslip_line` (
  `line_id` int NOT NULL AUTO_INCREMENT,
  `payslip_id` int DEFAULT NULL,
  `component_id` int DEFAULT NULL,
  `quantity` decimal(10,2) DEFAULT NULL,
  `rate` decimal(15,2) DEFAULT NULL,
  `amount` decimal(15,2) DEFAULT NULL,
  `source_type` varchar(255) DEFAULT NULL,
  `source_id` int DEFAULT NULL,
  PRIMARY KEY (`line_id`),
  KEY `payslip_id` (`payslip_id`),
  KEY `component_id` (`component_id`),
  CONSTRAINT `payslip_line_ibfk_1` FOREIGN KEY (`payslip_id`) REFERENCES `payslip` (`payslip_id`),
  CONSTRAINT `payslip_line_ibfk_2` FOREIGN KEY (`component_id`) REFERENCES `pay_component` (`component_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `payslip_line` VALUES
(1, 1, 1, 1.00, 5000000.00, 5000000.00, 'pay_grade', 1),
(2, 1, 2, 1.00, 1000000.00, 1000000.00, 'pay_grade', 1),
(3, 1, 4, 1.00, 600000.00, 600000.00, 'calculation', NULL);

-- Continue with remaining tables in next section...
-- (Including: Inventory, Incidents, Tasks, Letters, Signatures, KPI tables, and Laravel system tables)

