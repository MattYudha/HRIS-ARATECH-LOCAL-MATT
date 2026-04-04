-- ============================================
-- Combined HRIS Schema - Full Implementation
-- Database: hrappsprod
-- Generated: 2025-12-27
-- ============================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Select database
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
-- USER MANAGEMENT & AUTHENTICATION
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

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` varchar(16) NOT NULL,
  `foundation_id` varchar(8) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `user_type_id` varchar(8) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `foundation_id` (`foundation_id`),
  KEY `user_type_id` (`user_type_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`foundation_id`) REFERENCES `foundations` (`foundation_id`),
  CONSTRAINT `users_ibfk_2` FOREIGN KEY (`user_type_id`) REFERENCES `user_types` (`user_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Password: Password123! (bcrypt hash)
INSERT INTO `users` VALUES
('USR0001', 'FND001', 'Admin User', 'UT001', '081234567890', '$2y$12$LGl8.2f4yeT8FmxqJH8gvOLKhvF4FfF8lE3fLqGfFaOxNvPYqHZIa', NOW(), NOW(), NOW(), NULL, 'admin@aratechnology.id', NOW(), 1, NULL),
('USR0002', 'FND001', 'IT Manager', 'UT002', '081234567891', '$2y$12$LGl8.2f4yeT8FmxqJH8gvOLKhvF4FfF8lE3fLqGfFaOxNvPYqHZIa', NOW(), NOW(), NULL, NULL, 'manager.it@aratechnology.id', NOW(), 1, NULL),
('USR0003', 'FND001', 'HR Manager', 'UT002', '081234567892', '$2y$12$LGl8.2f4yeT8FmxqJH8gvOLKhvF4FfF8lE3fLqGfFaOxNvPYqHZIa', NOW(), NOW(), NULL, NULL, 'manager.hr@aratechnology.id', NOW(), 1, NULL),
('USR0004', 'FND001', 'John Developer', 'UT003', '081234567893', '$2y$12$LGl8.2f4yeT8FmxqJH8gvOLKhvF4FfF8lE3fLqGfFaOxNvPYqHZIa', NOW(), NOW(), NULL, NULL, 'john.dev@aratechnology.id', NOW(), 1, NULL),
('USR0005', 'FND001', 'Jane HR', 'UT003', '081234567894', '$2y$12$LGl8.2f4yeT8FmxqJH8gvOLKhvF4FfF8lE3fLqGfFaOxNvPYqHZIa', NOW(), NOW(), NULL, NULL, 'jane.hr@aratechnology.id', NOW(), 1, NULL);

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
(5, NULL, 'Payroll', 'Penggajian', 1, 5, 'money', '/payroll', 'Payroll management');

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
('UT001', 1, 1, 1, 1),
('UT001', 1, 2, 2, 1),
('UT001', 1, 3, 3, 1),
('UT001', 1, 4, 4, 1),
('UT001', 1, 5, 5, 1),
('UT002', 2, 1, 1, 1),
('UT002', 2, 2, 2, 1),
('UT002', 2, 3, 3, 1),
('UT003', 3, 1, 1, 1),
('UT003', 3, 3, 3, 1);

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
  `hire_date` date DEFAULT NULL,
  `user_id` varchar(16) DEFAULT NULL,
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
  CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  CONSTRAINT `employees_ibfk_2` FOREIGN KEY (`education_level_id`) REFERENCES `education_levels` (`education_level_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `employees` VALUES
(1, '2020-01-15', 'USR0001', 'Admin User', '2020-01-15', NULL, 'EMP001', 'M', '1234567890', 'Jakarta', '1985-05-20', 'M', 1, 4, NOW(), NOW(), 'Jakarta Selatan', '081234567890', 'admin@aratechnology.id', 'active', NULL),
(2, '2021-03-10', 'USR0002', 'IT Manager', '2021-03-10', NULL, 'EMP002', 'M', '1234567891', 'Bandung', '1988-08-15', 'M', 1, 4, NOW(), NOW(), 'Bandung', '081234567891', 'manager.it@aratechnology.id', 'active', NULL),
(3, '2021-06-01', 'USR0003', 'HR Manager', '2021-06-01', NULL, 'EMP003', 'F', '1234567892', 'Surabaya', '1990-12-10', 'S', 1, 4, NOW(), NOW(), 'Surabaya', '081234567892', 'manager.hr@aratechnology.id', 'active', NULL),
(4, '2022-01-20', 'USR0004', 'John Developer', '2022-01-20', NULL, 'EMP004', 'M', '1234567893', 'Jakarta', '1995-03-25', 'S', 1, 4, NOW(), NOW(), 'Jakarta Barat', '081234567893', 'john.dev@aratechnology.id', 'active', NULL),
(5, '2022-05-15', 'USR0005', 'Jane HR', '2022-05-15', NULL, 'EMP005', 'F', '1234567894', 'Yogyakarta', '1993-07-18', 'S', 1, 3, NOW(), NOW(), 'Yogyakarta', '081234567894', 'jane.hr@aratechnology.id', 'active', NULL);

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
(1, 'KTP'),
(2, 'Passport'),
(3, 'Driver License'),
(4, 'NPWP');

DROP TABLE IF EXISTS `document_identity`;
CREATE TABLE `document_identity` (
  `id` int NOT NULL AUTO_INCREMENT,
  `identity_type_id` int DEFAULT NULL,
  `user_id` varchar(16) DEFAULT NULL,
  `identity_number` varchar(20) DEFAULT NULL,
  `file_name` text,
  `description` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `identity_type_id` (`identity_type_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `document_identity_ibfk_1` FOREIGN KEY (`identity_type_id`) REFERENCES `identity_types` (`identity_types_id`),
  CONSTRAINT `document_identity_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `document_identity` VALUES
(1, 1, 'USR0001', '3201012001010001', 'ktp_usr0001.pdf', 'KTP Identity', NOW(), NOW()),
(2, 4, 'USR0001', '1234567890', 'npwp_usr0001.pdf', 'NPWP Tax ID', NOW(), NOW());

DROP TABLE IF EXISTS `bank_account`;
CREATE TABLE `bank_account` (
  `bank_account_id` int NOT NULL AUTO_INCREMENT,
  `user_id` varchar(16) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `account_no` varchar(255) DEFAULT NULL,
  `account_holder` varchar(255) DEFAULT NULL,
  `status` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`bank_account_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `bank_account_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `bank_account` VALUES
(1, 'USR0001', 'BCA', '1234567890', 'Admin User', 1, NOW(), NOW()),
(2, 'USR0002', 'Mandiri', '2345678901', 'IT Manager', 1, NOW(), NOW()),
(3, 'USR0003', 'BNI', '3456789012', 'HR Manager', 1, NOW(), NOW());

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
(1, 'APT001', 'Annual Leave'),
(2, 'APT001', 'Sick Leave'),
(3, 'APT001', 'Emergency Leave'),
(4, 'APT002', 'Regular Overtime'),
(5, 'APT003', 'Transportation'),
(6, 'APT003', 'Medical');

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

INSERT INTO `approved` VALUES
(1, 1, '2025-12-20', 'approved', 2);

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
(1, 1, 1, 5000000.00, NULL, 1),
(2, 1, 2, 1000000.00, NULL, 1),
(3, 2, 1, 3500000.00, NULL, 1),
(4, 2, 2, 750000.00, NULL, 1),
(5, 4, 1, 2000000.00, NULL, 1),
(6, 4, 2, 500000.00, NULL, 1);

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

-- ============================================
-- INVENTORY MANAGEMENT (HRIS2 UNIQUE)
-- ============================================

DROP TABLE IF EXISTS `inventory_categories`;
CREATE TABLE `inventory_categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `inventory_categories` VALUES
(1, 'Electronics', 'Electronic devices and equipment', NOW(), NOW()),
(2, 'Furniture', 'Office furniture', NOW(), NOW()),
(3, 'Stationery', 'Office supplies', NOW(), NOW());

DROP TABLE IF EXISTS `inventories`;
CREATE TABLE `inventories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `inventory_category_id` int DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `quantity` int DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventory_category_id` (`inventory_category_id`),
  CONSTRAINT `inventories_ibfk_1` FOREIGN KEY (`inventory_category_id`) REFERENCES `inventory_categories` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `inventories` VALUES
(1, 1, 'Laptop Dell Latitude', 'i5 8GB RAM', 10, 'IT Room', '2024-01-15', 'available', NOW(), NOW()),
(2, 1, 'Monitor LG 24 inch', 'Full HD LED', 15, 'IT Room', '2024-01-15', 'available', NOW(), NOW()),
(3, 2, 'Office Desk', 'Standard office desk', 20, 'Warehouse', '2023-06-10', 'available', NOW(), NOW());

DROP TABLE IF EXISTS `inventory_usage_logs`;
CREATE TABLE `inventory_usage_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `inventory_id` int DEFAULT NULL,
  `employee_id` int DEFAULT NULL,
  `borrowed_date` datetime DEFAULT NULL,
  `returned_date` datetime DEFAULT NULL,
  `notes` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventory_id` (`inventory_id`),
  KEY `employee_id` (`employee_id`),
  CONSTRAINT `inventory_usage_logs_ibfk_1` FOREIGN KEY (`inventory_id`) REFERENCES `inventories` (`id`),
  CONSTRAINT `inventory_usage_logs_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `inventory_usage_logs` VALUES
(1, 1, 4, '2024-03-01 09:00:00', NULL, 'Assigned for development work', NOW(), NOW()),
(2, 2, 4, '2024-03-01 09:00:00', NULL, 'Paired with laptop', NOW(), NOW());

-- ============================================
-- INCIDENT MANAGEMENT (HRIS2 UNIQUE)
-- ============================================

DROP TABLE IF EXISTS `incidents`;
CREATE TABLE `incidents` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `incident_date` date DEFAULT NULL,
  `description` text,
  `severity` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `action_taken` text,
  `reported_by` varchar(16) DEFAULT NULL,
  `resolved_by` varchar(16) DEFAULT NULL,
  `resolved_at` datetime DEFAULT NULL,
  `notes` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  KEY `reported_by` (`reported_by`),
  KEY `resolved_by` (`resolved_by`),
  CONSTRAINT `incidents_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`),
  CONSTRAINT `incidents_ibfk_2` FOREIGN KEY (`reported_by`) REFERENCES `users` (`user_id`),
  CONSTRAINT `incidents_ibfk_3` FOREIGN KEY (`resolved_by`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `incidents` VALUES
(1, 4, 'Safety', '2025-12-20', 'Minor slip in office corridor', 'low', 'resolved', 'Added warning signs', 'USR0003', 'USR0001', '2025-12-21 10:00:00', 'No injuries', NOW(), NOW());

-- ============================================
-- TASK MANAGEMENT (HRIS2 UNIQUE)
-- ============================================

DROP TABLE IF EXISTS `tasks`;
CREATE TABLE `tasks` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `assigned_to` int DEFAULT NULL,
  `due_date` datetime DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `assigned_to` (`assigned_to`),
  CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`assigned_to`) REFERENCES `employees` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `tasks` VALUES
(1, 'Complete year-end performance review', 'Review all employee performance for 2025', 3, '2025-12-31 17:00:00', 'in_progress', NOW(), NOW(), NULL),
(2, 'Update employee handbook', 'Review and update company policies', 3, '2026-01-15 17:00:00', 'pending', NOW(), NOW(), NULL),
(3, 'Fix authentication bug', 'Resolve login issue reported by users', 4, '2025-12-28 17:00:00', 'in_progress', NOW(), NOW(), NULL);

-- ============================================
-- LETTER/DOCUMENT MANAGEMENT (HRIS2 UNIQUE)
-- ============================================

DROP TABLE IF EXISTS `letter_templates`;
CREATE TABLE `letter_templates` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `content` longtext,
  `type` varchar(255) DEFAULT NULL,
  `is_active` tinyint DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `letter_templates` VALUES
(1, 'Employment Letter', 'Standard employment letter template', '<p>This is to certify that {{employee_name}} is employed at {{company_name}}.</p>', 'employment', 1, NOW(), NOW()),
(2, 'Reference Letter', 'Employee reference letter', '<p>{{employee_name}} has been a valued employee since {{join_date}}.</p>', 'reference', 1, NOW(), NOW());

DROP TABLE IF EXISTS `letter_configurations`;
CREATE TABLE `letter_configurations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `company_name` varchar(255) DEFAULT NULL,
  `company_address` varchar(255) DEFAULT NULL,
  `company_phone` varchar(255) DEFAULT NULL,
  `company_email` varchar(255) DEFAULT NULL,
  `company_website` varchar(255) DEFAULT NULL,
  `letterhead_footer` text,
  `letter_number_format` varchar(255) DEFAULT NULL,
  `current_number` int DEFAULT NULL,
  `is_active` tinyint DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `letter_configurations` VALUES
(1, 'Ara Technology', 'Jakarta, Indonesia', '021-1234567', 'contact@aratechnology.id', 'https://aratechnology.id', 'Official Letterhead', 'LTR/{{YEAR}}/{{NUMBER}}', 100, 1, NOW(), NOW());

DROP TABLE IF EXISTS `letters`;
CREATE TABLE `letters` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` varchar(16) DEFAULT NULL,
  `approver_id` varchar(16) DEFAULT NULL,
  `letter_template_id` int DEFAULT NULL,
  `letter_number` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `content` longtext,
  `letter_type` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_date` date DEFAULT NULL,
  `approved_date` datetime DEFAULT NULL,
  `printed_date` datetime DEFAULT NULL,
  `notes` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `approver_id` (`approver_id`),
  KEY `letter_template_id` (`letter_template_id`),
  CONSTRAINT `letters_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  CONSTRAINT `letters_ibfk_2` FOREIGN KEY (`approver_id`) REFERENCES `users` (`user_id`),
  CONSTRAINT `letters_ibfk_3` FOREIGN KEY (`letter_template_id`) REFERENCES `letter_templates` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `letters` VALUES
(1, 'USR0004', 'USR0003', 1, 'LTR/2025/101', 'Employment Certificate', '<p>This is to certify that John Developer is employed at Ara Technology.</p>', 'employment', 'approved', '2025-12-20', '2025-12-21 10:00:00', '2025-12-22 09:00:00', 'Requested for bank purposes', NOW(), NOW());

DROP TABLE IF EXISTS `letter_archives`;
CREATE TABLE `letter_archives` (
  `id` int NOT NULL AUTO_INCREMENT,
  `month` int DEFAULT NULL,
  `year` int DEFAULT NULL,
  `total_letters` int DEFAULT NULL,
  `approved_letters` int DEFAULT NULL,
  `printed_letters` int DEFAULT NULL,
  `summary` longtext,
  `generated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `letter_archives` VALUES
(1, 12, 2025, 5, 4, 3, 'December 2025 summary: 5 letters created, 4 approved, 3 printed', '2025-12-26 10:00:00', NOW(), NOW());

-- ============================================
-- DIGITAL SIGNATURE (HRIS2 UNIQUE)
-- ============================================

DROP TABLE IF EXISTS `signatures`;
CREATE TABLE `signatures` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` varchar(16) DEFAULT NULL,
  `signable_type` varchar(255) DEFAULT NULL,
  `signable_id` int DEFAULT NULL,
  `signature_image` longtext,
  `signature_hash` text,
  `signed_date` datetime DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `is_verified` tinyint DEFAULT NULL,
  `verification_token` varchar(255) DEFAULT NULL,
  `verified_at` datetime DEFAULT NULL,
  `signature_reason` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `signatures_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `signatures` VALUES
(1, 'USR0003', 'App\\Models\\Letter', 1, 'data:image/png;base64,iVBORw0KGgoAAAANS...', 'abc123hash', '2025-12-21 10:00:00', '192.168.1.1', 'Mozilla/5.0', 1, NULL, '2025-12-21 10:00:00', 'Approval', NOW(), NOW());

DROP TABLE IF EXISTS `signature_verifications`;
CREATE TABLE `signature_verifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `signature_id` int DEFAULT NULL,
  `verified_by_id` varchar(16) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `remarks` text,
  `verification_date` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `signature_id` (`signature_id`),
  KEY `verified_by_id` (`verified_by_id`),
  CONSTRAINT `signature_verifications_ibfk_1` FOREIGN KEY (`signature_id`) REFERENCES `signatures` (`id`),
  CONSTRAINT `signature_verifications_ibfk_2` FOREIGN KEY (`verified_by_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `signature_verifications` VALUES
(1, 1, 'USR0001', 'verified', 'Signature verified successfully', '2025-12-21 10:05:00', NOW(), NOW());

-- ============================================
-- KPI MANAGEMENT SYSTEM
-- ============================================

DROP TABLE IF EXISTS `kpi_period`;
CREATE TABLE `kpi_period` (
  `period_id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date_start` date DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  `frequency` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`period_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `kpi_period` VALUES
(1, 'Q4-2025', 'Quarter 4 2025', '2025-10-01', '2025-12-31', 'quarterly', 'active', NOW(), NOW()),
(2, 'Q1-2026', 'Quarter 1 2026', '2026-01-01', '2026-03-31', 'quarterly', 'planned', NOW(), NOW());

DROP TABLE IF EXISTS `kpi_scale`;
CREATE TABLE `kpi_scale` (
  `scale_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `min_value` decimal(5,2) DEFAULT NULL,
  `max_value` decimal(5,2) DEFAULT NULL,
  `step` decimal(5,2) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`scale_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `kpi_scale` VALUES
(1, '1-5 Scale', 1.00, 5.00, 0.50, 'Standard 1 to 5 rating scale'),
(2, '1-100 Scale', 1.00, 100.00, 1.00, 'Percentage-based scale');

DROP TABLE IF EXISTS `kpi_scale_level`;
CREATE TABLE `kpi_scale_level` (
  `level_id` int NOT NULL AUTO_INCREMENT,
  `scale_id` int DEFAULT NULL,
  `value` decimal(5,2) DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`level_id`),
  KEY `scale_id` (`scale_id`),
  CONSTRAINT `kpi_scale_level_ibfk_1` FOREIGN KEY (`scale_id`) REFERENCES `kpi_scale` (`scale_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `kpi_scale_level` VALUES
(1, 1, 1.00, 'Poor', 'Performance below expectations'),
(2, 1, 2.00, 'Fair', 'Performance needs improvement'),
(3, 1, 3.00, 'Good', 'Performance meets expectations'),
(4, 1, 4.00, 'Very Good', 'Performance exceeds expectations'),
(5, 1, 5.00, 'Excellent', 'Outstanding performance');

DROP TABLE IF EXISTS `kpi_category`;
CREATE TABLE `kpi_category` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `kpi_category` VALUES
(1, 'PROD', 'Productivity', 'Work output and efficiency'),
(2, 'QUAL', 'Quality', 'Quality of work delivered'),
(3, 'TEAM', 'Teamwork', 'Collaboration and team contribution');

DROP TABLE IF EXISTS `kpi_indicator`;
CREATE TABLE `kpi_indicator` (
  `indicator_id` int NOT NULL AUTO_INCREMENT,
  `category_id` int DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `calc_method` varchar(255) DEFAULT NULL,
  `formula` varchar(255) DEFAULT NULL,
  `direction` varchar(255) DEFAULT NULL,
  `default_weight` decimal(5,2) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`indicator_id`),
  UNIQUE KEY `kpi_indicator_code_unique` (`code`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `kpi_indicator_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `kpi_category` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `kpi_indicator` VALUES
(1, 1, 'PROD-001', 'Tasks Completed', 'Number of tasks completed on time', 'tasks', 'count', 'sum', 'maximize', 30.00, 1),
(2, 2, 'QUAL-001', 'Quality Rating', 'Average quality rating of deliverables', 'rating', 'average', 'avg', 'maximize', 40.00, 1),
(3, 3, 'TEAM-001', 'Team Collaboration', 'Peer feedback score', 'rating', 'average', 'avg', 'maximize', 30.00, 1);

DROP TABLE IF EXISTS `kpi_template`;
CREATE TABLE `kpi_template` (
  `template_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `department_id` int DEFAULT NULL,
  `position_id` varchar(10) DEFAULT NULL,
  `pay_grade_id` int DEFAULT NULL,
  `scale_id` int DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`template_id`),
  KEY `scale_id` (`scale_id`),
  CONSTRAINT `kpi_template_ibfk_1` FOREIGN KEY (`scale_id`) REFERENCES `kpi_scale` (`scale_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `kpi_template` VALUES
(1, 'Software Developer KPI', 'Standard KPI for developers', 1, 'POS004', 4, 1, 1, NOW(), NOW()),
(2, 'Manager KPI', 'Standard KPI for managers', NULL, NULL, 2, 1, 1, NOW(), NOW());

DROP TABLE IF EXISTS `kpi_template_item`;
CREATE TABLE `kpi_template_item` (
  `template_item_id` int NOT NULL AUTO_INCREMENT,
  `template_id` int DEFAULT NULL,
  `indicator_id` int DEFAULT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `target_value` decimal(10,2) DEFAULT NULL,
  `target_text` varchar(255) DEFAULT NULL,
  `baseline_value` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`template_item_id`),
  KEY `template_id` (`template_id`),
  KEY `indicator_id` (`indicator_id`),
  CONSTRAINT `kpi_template_item_ibfk_1` FOREIGN KEY (`template_id`) REFERENCES `kpi_template` (`template_id`),
  CONSTRAINT `kpi_template_item_ibfk_2` FOREIGN KEY (`indicator_id`) REFERENCES `kpi_indicator` (`indicator_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `kpi_template_item` VALUES
(1, 1, 1, 30.00, 20.00, 'Complete 20 tasks per quarter', 15.00),
(2, 1, 2, 40.00, 4.00, 'Maintain quality rating above 4.0', 3.50),
(3, 1, 3, 30.00, 4.00, 'Team collaboration score above 4.0', 3.50);

DROP TABLE IF EXISTS `employee_kpi`;
CREATE TABLE `employee_kpi` (
  `emp_kpi_id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int DEFAULT NULL,
  `period_id` int DEFAULT NULL,
  `template_id` int DEFAULT NULL,
  `scale_id` int DEFAULT NULL,
  `reviewer_id` int DEFAULT NULL,
  `secondary_reviewer_id` int DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `submitted_at` datetime DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `locked_at` datetime DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`emp_kpi_id`),
  KEY `employee_id` (`employee_id`),
  KEY `period_id` (`period_id`),
  KEY `template_id` (`template_id`),
  KEY `scale_id` (`scale_id`),
  CONSTRAINT `employee_kpi_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`),
  CONSTRAINT `employee_kpi_ibfk_2` FOREIGN KEY (`period_id`) REFERENCES `kpi_period` (`period_id`),
  CONSTRAINT `employee_kpi_ibfk_3` FOREIGN KEY (`template_id`) REFERENCES `kpi_template` (`template_id`),
  CONSTRAINT `employee_kpi_ibfk_4` FOREIGN KEY (`scale_id`) REFERENCES `kpi_scale` (`scale_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `employee_kpi` VALUES
(1, 4, 1, 1, 1, 2, NULL, 'approved', '2025-12-15 10:00:00', '2025-12-20 15:00:00', NULL, 'Good performance', NOW(), NOW());

DROP TABLE IF EXISTS `employee_kpi_item`;
CREATE TABLE `employee_kpi_item` (
  `emp_kpi_item_id` int NOT NULL AUTO_INCREMENT,
  `emp_kpi_id` int DEFAULT NULL,
  `indicator_id` int DEFAULT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `target_value` decimal(10,2) DEFAULT NULL,
  `target_text` varchar(255) DEFAULT NULL,
  `baseline_value` decimal(10,2) DEFAULT NULL,
  `calc_method` varchar(255) DEFAULT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `direction` varchar(255) DEFAULT NULL,
  `sort_order` int DEFAULT NULL,
  PRIMARY KEY (`emp_kpi_item_id`),
  KEY `emp_kpi_id` (`emp_kpi_id`),
  KEY `indicator_id` (`indicator_id`),
  CONSTRAINT `employee_kpi_item_ibfk_1` FOREIGN KEY (`emp_kpi_id`) REFERENCES `employee_kpi` (`emp_kpi_id`),
  CONSTRAINT `employee_kpi_item_ibfk_2` FOREIGN KEY (`indicator_id`) REFERENCES `kpi_indicator` (`indicator_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `employee_kpi_item` VALUES
(1, 1, 1, 30.00, 20.00, 'Complete 20 tasks per quarter', 15.00, 'count', 'tasks', 'maximize', 1),
(2, 1, 2, 40.00, 4.00, 'Maintain quality rating above 4.0', 3.50, 'average', 'rating', 'maximize', 2),
(3, 1, 3, 30.00, 4.00, 'Team collaboration score above 4.0', 3.50, 'average', 'rating', 'maximize', 3);

DROP TABLE IF EXISTS `kpi_checkin`;
CREATE TABLE `kpi_checkin` (
  `checkin_id` int NOT NULL AUTO_INCREMENT,
  `emp_kpi_item_id` int DEFAULT NULL,
  `checkin_date` date DEFAULT NULL,
  `actual_value` decimal(10,2) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`checkin_id`),
  KEY `emp_kpi_item_id` (`emp_kpi_item_id`),
  CONSTRAINT `kpi_checkin_ibfk_1` FOREIGN KEY (`emp_kpi_item_id`) REFERENCES `employee_kpi_item` (`emp_kpi_item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `kpi_checkin` VALUES
(1, 1, '2025-12-10', 18.00, 'Good progress on tasks', 4, NOW()),
(2, 2, '2025-12-10', 4.20, 'High quality deliverables', 4, NOW());

DROP TABLE IF EXISTS `kpi_evidence`;
CREATE TABLE `kpi_evidence` (
  `evidence_id` int NOT NULL AUTO_INCREMENT,
  `emp_kpi_item_id` int DEFAULT NULL,
  `checkin_id` int DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `uploaded_by` int DEFAULT NULL,
  `uploaded_at` datetime DEFAULT NULL,
  PRIMARY KEY (`evidence_id`),
  KEY `emp_kpi_item_id` (`emp_kpi_item_id`),
  KEY `checkin_id` (`checkin_id`),
  CONSTRAINT `kpi_evidence_ibfk_1` FOREIGN KEY (`emp_kpi_item_id`) REFERENCES `employee_kpi_item` (`emp_kpi_item_id`),
  CONSTRAINT `kpi_evidence_ibfk_2` FOREIGN KEY (`checkin_id`) REFERENCES `kpi_checkin` (`checkin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `kpi_review`;
CREATE TABLE `kpi_review` (
  `review_id` int NOT NULL AUTO_INCREMENT,
  `emp_kpi_id` int DEFAULT NULL,
  `reviewer_id` int DEFAULT NULL,
  `review_stage` varchar(255) DEFAULT NULL,
  `review_date` date DEFAULT NULL,
  `comment` text,
  `overall_rating` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`review_id`),
  KEY `emp_kpi_id` (`emp_kpi_id`),
  CONSTRAINT `kpi_review_ibfk_1` FOREIGN KEY (`emp_kpi_id`) REFERENCES `employee_kpi` (`emp_kpi_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `kpi_review` VALUES
(1, 1, 2, 'final', '2025-12-20', 'Solid performance throughout the quarter', 4.10);

DROP TABLE IF EXISTS `kpi_score`;
CREATE TABLE `kpi_score` (
  `score_id` int NOT NULL AUTO_INCREMENT,
  `emp_kpi_id` int DEFAULT NULL,
  `calc_date` datetime DEFAULT NULL,
  `method` varchar(255) DEFAULT NULL,
  `weighted_score` decimal(5,2) DEFAULT NULL,
  `rating_value` decimal(5,2) DEFAULT NULL,
  `rating_label` varchar(255) DEFAULT NULL,
  `normalized_score` decimal(5,2) DEFAULT NULL,
  `details_json` varchar(255) DEFAULT NULL,
  `locked` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`score_id`),
  KEY `emp_kpi_id` (`emp_kpi_id`),
  CONSTRAINT `kpi_score_ibfk_1` FOREIGN KEY (`emp_kpi_id`) REFERENCES `employee_kpi` (`emp_kpi_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `kpi_score` VALUES
(1, 1, '2025-12-20 15:00:00', 'weighted_average', 82.00, 4.10, 'Very Good', 82.00, '{}', 1);

DROP TABLE IF EXISTS `kpi_approval`;
CREATE TABLE `kpi_approval` (
  `approval_id` int NOT NULL AUTO_INCREMENT,
  `emp_kpi_id` int DEFAULT NULL,
  `step` int DEFAULT NULL,
  `approver_id` int DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `action_at` datetime DEFAULT NULL,
  PRIMARY KEY (`approval_id`),
  KEY `emp_kpi_id` (`emp_kpi_id`),
  CONSTRAINT `kpi_approval_ibfk_1` FOREIGN KEY (`emp_kpi_id`) REFERENCES `employee_kpi` (`emp_kpi_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `kpi_approval` VALUES
(1, 1, 1, 2, 'approved', 'Approved by IT Manager', '2025-12-20 15:00:00');

DROP TABLE IF EXISTS `kpi_evaluations`;
CREATE TABLE `kpi_evaluations` (
  `evaluation_id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int DEFAULT NULL,
  `evaluation_period` varchar(20) DEFAULT NULL,
  `score` decimal(5,2) DEFAULT NULL,
  `comments` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`evaluation_id`),
  KEY `employee_id` (`employee_id`),
  CONSTRAINT `kpi_evaluations_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `kpi_evaluations` VALUES
(1, 4, 'Q4-2025', 4.10, 'Good performance in all areas', NOW(), NOW());

DROP TABLE IF EXISTS `performance_reviews`;
CREATE TABLE `performance_reviews` (
  `review_id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int DEFAULT NULL,
  `reviewer_id` varchar(16) DEFAULT NULL,
  `period` varchar(255) DEFAULT NULL,
  `overall_score` decimal(5,2) DEFAULT NULL,
  `achievements` text,
  `areas_improvement` text,
  `goals_next_period` text,
  `comments` text,
  `status` varchar(255) DEFAULT NULL,
  `reviewed_at` datetime DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `approved_by` varchar(16) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`review_id`),
  KEY `employee_id` (`employee_id`),
  KEY `reviewer_id` (`reviewer_id`),
  KEY `approved_by` (`approved_by`),
  CONSTRAINT `performance_reviews_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`),
  CONSTRAINT `performance_reviews_ibfk_2` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`user_id`),
  CONSTRAINT `performance_reviews_ibfk_3` FOREIGN KEY (`approved_by`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `performance_reviews` VALUES
(1, 4, 'USR0002', 'Q4-2025', 4.10, 'Completed all assigned projects on time', 'Could improve documentation', 'Lead a small project team', 'Overall strong performance', 'approved', '2025-12-20 15:00:00', '2025-12-21 10:00:00', 'USR0001', NOW(), NOW());

-- ============================================
-- SYSTEM TABLES (Laravel Framework)
-- ============================================

DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext,
  `expiration` int DEFAULT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) DEFAULT NULL,
  `expiration` int DEFAULT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) DEFAULT NULL,
  `connection` text,
  `queue` text,
  `payload` longtext,
  `exception` longtext,
  `failed_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) DEFAULT NULL,
  `payload` longtext,
  `attempts` tinyint DEFAULT NULL,
  `reserved_at` int DEFAULT NULL,
  `available_at` int DEFAULT NULL,
  `created_at` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `total_jobs` int DEFAULT NULL,
  `pending_jobs` int DEFAULT NULL,
  `failed_jobs` int DEFAULT NULL,
  `failed_job_ids` longtext,
  `options` mediumtext,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int DEFAULT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) DEFAULT NULL,
  `batch` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1);

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` varchar(16) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` longtext,
  `last_activity` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

COMMIT;

-- ============================================
-- END OF SCHEMA
-- ============================================
