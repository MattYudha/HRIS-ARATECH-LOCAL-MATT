-- ============================================
-- HRIS DATABASE - FINAL COMPLETE VERSION
-- Database: hrappsprod
-- Version: 4.0 FINAL
-- Date: 2025-12-27
-- Laravel 10+ Compatible
-- ============================================
-- 
-- This is the SINGLE SOURCE OF TRUTH for database schema
-- Application must adapt to this database structure
-- All foreign keys are properly defined
-- All relationships are bidirectional where needed
--
-- ============================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================
-- DATABASE SELECTION
-- ============================================

DROP DATABASE IF EXISTS `hrappsprod`;
CREATE DATABASE `hrappsprod` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `hrappsprod`;

-- ============================================
-- 1. FOUNDATION & ORGANIZATION
-- ============================================

DROP TABLE IF EXISTS `foundations`;
CREATE TABLE `foundations` (
  `id` varchar(8) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Multi-organization/foundation support';

INSERT INTO `foundations` VALUES
('FND001', 'Ara Technology Foundation', 'contact@aratechnology.id', '021-1234567', 'Jakarta, Indonesia', 1, NOW(), NOW()),
('FND002', 'Innovation Hub Foundation', 'info@innovationhub.id', '021-7654321', 'Bandung, Indonesia', 1, NOW(), NOW());

DROP TABLE IF EXISTS `departments`;
CREATE TABLE `departments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `foundation_id` varchar(8) DEFAULT NULL,
  `parent_id` int DEFAULT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `check_in_time` time DEFAULT '08:00:00',
  `check_out_time` time DEFAULT '17:00:00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `foundation_id` (`foundation_id`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `departments_foundation_fk` FOREIGN KEY (`foundation_id`) REFERENCES `foundations` (`id`),
  CONSTRAINT `departments_parent_fk` FOREIGN KEY (`parent_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Organizational departments';

INSERT INTO `departments` VALUES
(1, 'FND001', NULL, 'IT', 'IT Department', 'Information Technology', '08:00:00', '17:00:00', NOW(), NOW(), NULL),
(2, 'FND001', NULL, 'HR', 'HR Department', 'Human Resources', '08:00:00', '17:00:00', NOW(), NOW(), NULL),
(3, 'FND001', NULL, 'FIN', 'Finance Department', 'Finance & Accounting', '08:00:00', '17:00:00', NOW(), NOW(), NULL),
(4, 'FND001', NULL, 'OPS', 'Operations Department', 'Operations', '08:00:00', '17:00:00', NOW(), NOW(), NULL);

DROP TABLE IF EXISTS `job_positions`;
CREATE TABLE `job_positions` (
  `id` varchar(10) NOT NULL,
  `title` varchar(100) NOT NULL,
  `level` varchar(50) DEFAULT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Job position catalog';

INSERT INTO `job_positions` VALUES
('POS001', 'CEO', 'Executive', 'Chief Executive Officer', NOW(), NOW()),
('POS002', 'IT Manager', 'Manager', 'IT Department Manager', NOW(), NOW()),
('POS003', 'HR Manager', 'Manager', 'HR Department Manager', NOW(), NOW()),
('POS004', 'Software Developer', 'Staff', 'Software Development', NOW(), NOW()),
('POS005', 'HR Staff', 'Staff', 'HR Administration', NOW(), NOW());

DROP TABLE IF EXISTS `pay_grades`;
CREATE TABLE `pay_grades` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `currency` varchar(3) DEFAULT 'IDR',
  `pay_schedule` enum('monthly','weekly','daily') DEFAULT 'monthly',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Salary grade levels';

INSERT INTO `pay_grades` VALUES
(1, 'EXEC', 'Executive', 'IDR', 'monthly', NOW(), NOW()),
(2, 'MGR', 'Manager', 'IDR', 'monthly', NOW(), NOW()),
(3, 'SR', 'Senior Staff', 'IDR', 'monthly', NOW(), NOW()),
(4, 'STAFF', 'Staff', 'IDR', 'monthly', NOW(), NOW());

-- ============================================
-- 2. USER MANAGEMENT & AUTHENTICATION (Laravel Standard)
-- ============================================

DROP TABLE IF EXISTS `user_types`;
CREATE TABLE `user_types` (
  `id` varchar(8) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `user_types` VALUES
('UT001', 'Administrator', 'System Administrator with full access', NOW(), NOW()),
('UT002', 'Manager', 'Department Manager', NOW(), NOW()),
('UT003', 'Employee', 'Regular Employee', NOW(), NOW());

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
  `phone` varchar(20) DEFAULT NULL,
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
  KEY `employee_id` (`employee_id`),
  CONSTRAINT `users_foundation_fk` FOREIGN KEY (`foundation_id`) REFERENCES `foundations` (`id`),
  CONSTRAINT `users_user_type_fk` FOREIGN KEY (`user_type_id`) REFERENCES `user_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Laravel standard users table';

-- Password: Password123! (bcrypt)
INSERT INTO `users` (`id`, `foundation_id`, `user_type_id`, `name`, `email`, `email_verified_at`, `password`, `phone`, `active`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'FND001', 'UT001', 'Admin User', 'admin@aratechnology.id', NOW(), '$2y$12$LGl8.2f4yeT8FmxqJH8gvOLKhvF4FfF8lE3fLqGfFaOxNvPYqHZIa', '081234567890', 1, NOW(), NOW(), NOW()),
(2, 'FND001', 'UT002', 'IT Manager', 'manager.it@aratechnology.id', NOW(), '$2y$12$LGl8.2f4yeT8FmxqJH8gvOLKhvF4FfF8lE3fLqGfFaOxNvPYqHZIa', '081234567891', 1, NULL, NOW(), NOW()),
(3, 'FND001', 'UT002', 'HR Manager', 'manager.hr@aratechnology.id', NOW(), '$2y$12$LGl8.2f4yeT8FmxqJH8gvOLKhvF4FfF8lE3fLqGfFaOxNvPYqHZIa', '081234567892', 1, NULL, NOW(), NOW()),
(4, 'FND001', 'UT003', 'John Developer', 'john.dev@aratechnology.id', NOW(), '$2y$12$LGl8.2f4yeT8FmxqJH8gvOLKhvF4FfF8lE3fLqGfFaOxNvPYqHZIa', '081234567893', 1, NULL, NOW(), NOW()),
(5, 'FND001', 'UT003', 'Jane HR', 'jane.hr@aratechnology.id', NOW(), '$2y$12$LGl8.2f4yeT8FmxqJH8gvOLKhvF4FfF8lE3fLqGfFaOxNvPYqHZIa', '081234567894', 1, NULL, NOW(), NOW());

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `roles` VALUES
(1, 'Power User', 'Full system access', NOW(), NOW()),
(2, 'Manager', 'Department management access', NOW(), NOW()),
(3, 'Employee', 'Basic employee access', NOW(), NOW()),
(4, 'HR Admin', 'HR administration access', NOW(), NOW());

DROP TABLE IF EXISTS `menus`;
CREATE TABLE `menus` (
  `id` int NOT NULL AUTO_INCREMENT,
  `parent_id` int DEFAULT NULL,
  `name_en` varchar(100) DEFAULT NULL,
  `name_id` varchar(100) DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `sort_order` int DEFAULT 0,
  `level` int DEFAULT 1,
  `description` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `menus_parent_fk` FOREIGN KEY (`parent_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `menus` VALUES
(1, NULL, 'Dashboard', 'Dasbor', 'dashboard', '/dashboard', 1, 1, NULL, NOW(), NOW()),
(2, NULL, 'Employees', 'Karyawan', 'users', '/employees', 2, 1, NULL, NOW(), NOW()),
(3, NULL, 'Attendance', 'Kehadiran', 'calendar', '/attendance', 3, 1, NULL, NOW(), NOW()),
(4, NULL, 'Leave', 'Cuti', 'clock', '/leave', 4, 1, NULL, NOW(), NOW()),
(5, NULL, 'Payroll', 'Penggajian', 'money', '/payroll', 5, 1, NULL, NOW(), NOW()),
(6, NULL, 'KPI', 'KPI', 'chart', '/kpi', 6, 1, NULL, NOW(), NOW()),
(7, NULL, 'Inventory', 'Inventaris', 'box', '/inventory', 7, 1, NULL, NOW(), NOW()),
(8, NULL, 'Letters', 'Surat', 'file-text', '/letters', 8, 1, NULL, NOW(), NOW()),
(9, NULL, 'Reports', 'Laporan', 'bar-chart', '/reports', 9, 1, NULL, NOW(), NOW());

DROP TABLE IF EXISTS `user_type_role_menus`;
CREATE TABLE `user_type_role_menus` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_type_id` varchar(8) NOT NULL,
  `role_id` int NOT NULL,
  `menu_id` int NOT NULL,
  `can_view` tinyint(1) DEFAULT 1,
  `can_create` tinyint(1) DEFAULT 0,
  `can_edit` tinyint(1) DEFAULT 0,
  `can_delete` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_type_role_menu_unique` (`user_type_id`,`role_id`,`menu_id`),
  KEY `role_id` (`role_id`),
  KEY `menu_id` (`menu_id`),
  CONSTRAINT `utrm_user_type_fk` FOREIGN KEY (`user_type_id`) REFERENCES `user_types` (`id`),
  CONSTRAINT `utrm_role_fk` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  CONSTRAINT `utrm_menu_fk` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `user_type_role_menus` (`user_type_id`, `role_id`, `menu_id`, `can_view`, `can_create`, `can_edit`, `can_delete`, `created_at`) VALUES
('UT001', 1, 1, 1, 1, 1, 1, NOW()), ('UT001', 1, 2, 1, 1, 1, 1, NOW()), ('UT001', 1, 3, 1, 1, 1, 1, NOW()),
('UT001', 1, 4, 1, 1, 1, 1, NOW()), ('UT001', 1, 5, 1, 1, 1, 1, NOW()), ('UT001', 1, 6, 1, 1, 1, 1, NOW()),
('UT001', 1, 7, 1, 1, 1, 1, NOW()), ('UT001', 1, 8, 1, 1, 1, 1, NOW()), ('UT001', 1, 9, 1, 1, 1, 1, NOW()),
('UT002', 2, 1, 1, 0, 0, 0, NOW()), ('UT002', 2, 2, 1, 1, 1, 0, NOW()), ('UT002', 2, 3, 1, 1, 1, 0, NOW()),
('UT002', 2, 4, 1, 1, 1, 1, NOW()), ('UT002', 2, 6, 1, 1, 1, 0, NOW()),
('UT003', 3, 1, 1, 0, 0, 0, NOW()), ('UT003', 3, 3, 1, 1, 0, 0, NOW()), ('UT003', 3, 4, 1, 1, 0, 0, NOW());

-- ============================================
-- 3. EMPLOYEE MANAGEMENT
-- ============================================

DROP TABLE IF EXISTS `education_levels`;
CREATE TABLE `education_levels` (
  `id` int NOT NULL AUTO_INCREMENT,
  `level` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `education_levels` VALUES
(1, 1, 'SD', NOW(), NOW()),
(2, 2, 'SMP', NOW(), NOW()),
(3, 3, 'SMA/SMK', NOW(), NOW()),
(4, 4, 'D3/S1/S2/S3', NOW(), NOW());

DROP TABLE IF EXISTS `employees`;
CREATE TABLE `employees` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL COMMENT 'Link to users.id',
  `code` varchar(20) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `gender` enum('M','F') NOT NULL,
  `place_of_birth` varchar(100) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `nik` varchar(20) DEFAULT NULL COMMENT 'NIK KTP',
  `npwp` varchar(20) DEFAULT NULL,
  `marital_status` enum('Single','Married','Divorced','Widowed') DEFAULT 'Single',
  `religion` varchar(50) DEFAULT NULL,
  `education_level_id` int DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text,
  `join_date` date DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `resign_date` date DEFAULT NULL,
  `status` enum('Active','Probation','Resigned','Terminated') DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `user_id` (`user_id`),
  KEY `education_level_id` (`education_level_id`),
  CONSTRAINT `employees_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `employees_education_fk` FOREIGN KEY (`education_level_id`) REFERENCES `education_levels` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Employee master data';

INSERT INTO `employees` VALUES
(1, 1, 'EMP001', 'Admin User', 'M', 'Jakarta', '1985-05-20', '3201012001010001', '123456789012345', 'Married', 'Islam', 4, '081234567890', 'admin@aratechnology.id', 'Jakarta Selatan', '2020-01-15', '2020-01-15', NULL, 'Active', NOW(), NOW(), NULL),
(2, 2, 'EMP002', 'IT Manager', 'M', 'Bandung', '1988-08-15', '3273011990120001', '123456789012346', 'Married', 'Islam', 4, '081234567891', 'manager.it@aratechnology.id', 'Bandung', '2021-03-10', '2021-03-10', NULL, 'Active', NOW(), NOW(), NULL),
(3, 3, 'EMP003', 'HR Manager', 'F', 'Surabaya', '1990-12-10', '3578011992030001', '123456789012347', 'Married', 'Islam', 4, '081234567892', 'manager.hr@aratechnology.id', 'Surabaya', '2021-06-01', '2021-06-01', NULL, 'Active', NOW(), NOW(), NULL),
(4, 4, 'EMP004', 'John Developer', 'M', 'Jakarta', '1995-03-25', '3201011995032501', '123456789012348', 'Single', 'Islam', 4, '081234567893', 'john.dev@aratechnology.id', 'Jakarta Barat', '2022-01-20', '2022-01-20', NULL, 'Active', NOW(), NOW(), NULL),
(5, 5, 'EMP005', 'Jane HR', 'F', 'Yogyakarta', '1993-07-18', '3471011993071801', '123456789012349', 'Single', 'Islam', 3, '081234567894', 'jane.hr@aratechnology.id', 'Yogyakarta', '2022-05-15', '2022-05-15', NULL, 'Active', NOW(), NOW(), NULL);

-- Update users.employee_id backlink
UPDATE `users` SET `employee_id` = 1 WHERE `id` = 1;
UPDATE `users` SET `employee_id` = 2 WHERE `id` = 2;
UPDATE `users` SET `employee_id` = 3 WHERE `id` = 3;
UPDATE `users` SET `employee_id` = 4 WHERE `id` = 4;
UPDATE `users` SET `employee_id` = 5 WHERE `id` = 5;

DROP TABLE IF EXISTS `employee_positions`;
CREATE TABLE `employee_positions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int NOT NULL,
  `department_id` int NOT NULL,
  `position_id` varchar(10) NOT NULL,
  `pay_grade_id` int DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `is_current` tinyint(1) DEFAULT 1,
  `is_supervisor` tinyint(1) DEFAULT 0,
  `base_salary` decimal(15,2) DEFAULT 0.00,
  `sk_number` varchar(50) DEFAULT NULL,
  `sk_file` varchar(255) DEFAULT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  KEY `department_id` (`department_id`),
  KEY `position_id` (`position_id`),
  KEY `pay_grade_id` (`pay_grade_id`),
  CONSTRAINT `emp_pos_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  CONSTRAINT `emp_pos_department_fk` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  CONSTRAINT `emp_pos_position_fk` FOREIGN KEY (`position_id`) REFERENCES `job_positions` (`id`),
  CONSTRAINT `emp_pos_pay_grade_fk` FOREIGN KEY (`pay_grade_id`) REFERENCES `pay_grades` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `employee_positions` VALUES
(1, 1, 1, 'POS001', 1, '2020-01-15', NULL, 1, 1, 5000000.00, 'SK/001/2020', NULL, NULL, NOW(), NOW()),
(2, 2, 1, 'POS002', 2, '2021-03-10', NULL, 1, 1, 3500000.00, 'SK/002/2021', NULL, NULL, NOW(), NOW()),
(3, 3, 2, 'POS003', 2, '2021-06-01', NULL, 1, 1, 3500000.00, 'SK/003/2021', NULL, NULL, NOW(), NOW()),
(4, 4, 1, 'POS004', 4, '2022-01-20', NULL, 1, 0, 2000000.00, 'SK/004/2022', NULL, NULL, NOW(), NOW()),
(5, 5, 2, 'POS005', 4, '2022-05-15', NULL, 1, 0, 1800000.00, 'SK/005/2022', NULL, NULL, NOW(), NOW());

DROP TABLE IF EXISTS `employee_families`;
CREATE TABLE `employee_families` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int NOT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `no_kk` varchar(20) DEFAULT NULL,
  `fullname` varchar(100) NOT NULL,
  `relationship` enum('Spouse','Child','Parent','Sibling','Other') NOT NULL,
  `gender` enum('M','F') NOT NULL,
  `place_of_birth` varchar(100) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `education` varchar(100) DEFAULT NULL,
  `occupation` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `is_dependent` tinyint(1) DEFAULT 1,
  `is_emergency_contact` tinyint(1) DEFAULT 0,
  `notes` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  CONSTRAINT `emp_families_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `employee_families` VALUES
(1, 1, '3201012001010002', '3201010101010001', 'Maria Susanti', 'Spouse', 'F', 'Jakarta', '1987-03-15', 'S1', 'Ibu Rumah Tangga', NULL, 1, 1, NULL, NOW(), NOW(), NULL),
(2, 1, '3201012010050001', '3201010101010001', 'Ahmad Pratama', 'Child', 'M', 'Jakarta', '2010-05-20', 'SMP', 'Pelajar', NULL, 1, 0, NULL, NOW(), NOW(), NULL),
(3, 1, '3201012012080001', '3201010101010001', 'Siti Rahmawati', 'Child', 'F', 'Jakarta', '2012-08-15', 'SD', 'Pelajar', NULL, 1, 0, NULL, NOW(), NOW(), NULL),
(4, 2, '3273011990120002', '3273010505050001', 'Dewi Lestari', 'Spouse', 'F', 'Bandung', '1990-12-10', 'D3', 'Pegawai Swasta', '081234567898', 1, 1, NULL, NOW(), NOW(), NULL),
(5, 3, '3578011992030002', '3578020202020001', 'Budi Santoso', 'Spouse', 'M', 'Surabaya', '1992-03-25', 'S1', 'Pegawai Swasta', '081234567897', 1, 1, NULL, NOW(), NOW(), NULL),
(6, 3, '3578012015060001', '3578020202020001', 'Ani Wijaya', 'Child', 'F', 'Surabaya', '2015-06-18', 'SD', 'Pelajar', NULL, 1, 0, NULL, NOW(), NOW(), NULL);

DROP TABLE IF EXISTS `employee_contacts`;
CREATE TABLE `employee_contacts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int NOT NULL,
  `contact_type` enum('Emergency','Reference','Next of Kin') DEFAULT 'Emergency',
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
  CONSTRAINT `emp_contacts_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `employee_contacts` VALUES
(1, 1, 'Emergency', 'Maria Susanti', 'Spouse', '081234567899', NULL, 'maria@email.com', NULL, 1, NULL, NOW(), NOW()),
(2, 2, 'Emergency', 'Dewi Lestari', 'Spouse', '081234567898', NULL, 'dewi@email.com', 1, NULL, NOW(), NOW()),
(3, 3, 'Emergency', 'Budi Santoso', 'Spouse', '081234567897', NULL, 'budi@email.com', 1, NULL, NOW(), NOW()),
(4, 4, 'Emergency', 'Parent Name', 'Parent', '081234567896', NULL, NULL, NULL, 1, NULL, NOW(), NOW()),
(5, 5, 'Emergency', 'Sibling Name', 'Sibling', '081234567895', NULL, NULL, NULL, 1, NULL, NOW(), NOW());

DROP TABLE IF EXISTS `employee_documents`;
CREATE TABLE `employee_documents` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int NOT NULL,
  `document_type` varchar(50) NOT NULL,
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
  CONSTRAINT `emp_docs_employee_fk` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `employee_documents` VALUES
(1, 1, 'Contract', 'Employment Contract', 'EC/2020/001', NULL, '2020-01-15', '2025-01-15', 'HRD', NULL, NOW(), NOW(), NULL),
(2, 1, 'Certificate', 'Project Management Certificate', 'PMP-123456', NULL, '2021-06-20', NULL, 'PMI', NULL, NOW(), NOW(), NULL),
(3, 2, 'Contract', 'Employment Contract', 'EC/2021/002', NULL, '2021-03-10', '2026-03-10', 'HRD', NULL, NOW(), NOW(), NULL),
(4, 4, 'Contract', 'Employment Contract', 'EC/2022/004', NULL, '2022-01-20', '2025-01-20', 'HRD', NULL, NOW(), NOW(), NULL);

-- ============================================
-- TO BE CONTINUED... (File too long, splitting)
-- ============================================
