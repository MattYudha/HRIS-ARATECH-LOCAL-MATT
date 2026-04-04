-- ===============================================
-- HRIS Database - phpMyAdmin Compatible Version
-- Fixed: Added CREATE DATABASE and USE statements
-- Generated: 2025-12-27
-- ===============================================

CREATE DATABASE IF NOT EXISTS `hrappsprod` 
  DEFAULT CHARACTER SET utf8mb4 
  COLLATE utf8mb4_unicode_ci;

USE `hrappsprod`;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
SET time_zone = '+00:00';

-- ===============================================
-- 1. CORE FOUNDATION TABLES
-- ===============================================

DROP TABLE IF EXISTS `foundations`;
CREATE TABLE `foundations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(50) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `address` text,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `foundations_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `foundations` VALUES 
(1,'FND001','PT Ara Technology Indonesia','Jl. Contoh No. 123, Jakarta','021-12345678','info@aratechnology.id','https://aratechnology.id',NULL,1,'2025-01-01 00:00:00','2025-01-01 00:00:00');

DROP TABLE IF EXISTS `departments`;
CREATE TABLE `departments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `foundation_id` bigint unsigned DEFAULT NULL,
  `code` varchar(50) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `parent_id` bigint unsigned DEFAULT NULL,
  `manager_id` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `departments_code_unique` (`code`),
  KEY `departments_foundation_id_index` (`foundation_id`),
  KEY `departments_parent_id_index` (`parent_id`),
  KEY `departments_manager_id_index` (`manager_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `departments` VALUES 
(1,1,'IT','Information Technology','Departemen IT',NULL,NULL,1,'2025-01-01 00:00:00','2025-01-01 00:00:00');
INSERT INTO `departments` VALUES
(2,1,'HR','Human Resources','Departemen HR',NULL,NULL,1,'2025-01-01 00:00:00','2025-01-01 00:00:00');
INSERT INTO `departments` VALUES
(3,1,'FIN','Finance','Departemen Keuangan',NULL,NULL,1,'2025-01-01 00:00:00','2025-01-01 00:00:00');

DROP TABLE IF EXISTS `job_positions`;
CREATE TABLE `job_positions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(50) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `department_id` bigint unsigned DEFAULT NULL,
  `level` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `job_positions_code_unique` (`code`),
  KEY `job_positions_department_id_index` (`department_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `job_positions` VALUES 
(1,'POS001','IT Manager','Manager IT',1,'Manager',1,'2025-01-01 00:00:00','2025-01-01 00:00:00');
INSERT INTO `job_positions` VALUES
(2,'POS002','HR Manager','Manager HR',2,'Manager',1,'2025-01-01 00:00:00','2025-01-01 00:00:00');
INSERT INTO `job_positions` VALUES
(3,'POS003','Staff IT','Staff IT',1,'Staff',1,'2025-01-01 00:00:00','2025-01-01 00:00:00');
INSERT INTO `job_positions` VALUES
(4,'POS004','Staff HR','Staff HR',2,'Staff',1,'2025-01-01 00:00:00','2025-01-01 00:00:00');

DROP TABLE IF EXISTS `pay_grades`;
CREATE TABLE `pay_grades` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(50) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `min_salary` decimal(15,2) DEFAULT '0.00',
  `max_salary` decimal(15,2) DEFAULT '0.00',
  `currency` varchar(10) DEFAULT 'IDR',
  `description` text,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pay_grades_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `pay_grades` VALUES 
(1,'PG1','Grade 1',5000000.00,8000000.00,'IDR','Entry level',1,'2025-01-01 00:00:00','2025-01-01 00:00:00');
INSERT INTO `pay_grades` VALUES
(2,'PG2','Grade 2',8000000.00,12000000.00,'IDR','Mid level',1,'2025-01-01 00:00:00','2025-01-01 00:00:00');
INSERT INTO `pay_grades` VALUES
(3,'PG3','Grade 3',12000000.00,20000000.00,'IDR','Senior level',1,'2025-01-01 00:00:00','2025-01-01 00:00:00');

-- ===============================================
-- 2. USERS & AUTHENTICATION
-- ===============================================

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_employee_id_index` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` VALUES 
(1,1,'Admin User','admin@aratechnology.id',NULL,'$2y$12$LGl8.2f4yeT8FmxqJH8gvOLKhvF4FfF8lE3fLqGfFaOxNvPYqHZIa',NULL,'2025-01-01 00:00:00','2025-01-01 00:00:00');
INSERT INTO `users` VALUES
(2,2,'Budi Santoso','budi@aratechnology.id',NULL,'$2y$12$LGl8.2f4yeT8FmxqJH8gvOLKhvF4FfF8lE3fLqGfFaOxNvPYqHZIa',NULL,'2025-01-01 00:00:00','2025-01-01 00:00:00');
INSERT INTO `users` VALUES
(3,3,'Siti Aminah','siti@aratechnology.id',NULL,'$2y$12$LGl8.2f4yeT8FmxqJH8gvOLKhvF4FfF8lE3fLqGfFaOxNvPYqHZIa',NULL,'2025-01-01 00:00:00','2025-01-01 00:00:00');
INSERT INTO `users` VALUES
(4,4,'Andi Wijaya','andi@aratechnology.id',NULL,'$2y$12$LGl8.2f4yeT8FmxqJH8gvOLKhvF4FfF8lE3fLqGfFaOxNvPYqHZIa',NULL,'2025-01-01 00:00:00','2025-01-01 00:00:00');
INSERT INTO `users` VALUES
(5,5,'Dewi Lestari','dewi@aratechnology.id',NULL,'$2y$12$LGl8.2f4yeT8FmxqJH8gvOLKhvF4FfF8lE3fLqGfFaOxNvPYqHZIa',NULL,'2025-01-01 00:00:00','2025-01-01 00:00:00');

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===============================================
-- 3. EMPLOYEE MANAGEMENT
-- ===============================================

DROP TABLE IF EXISTS `education_levels`;
CREATE TABLE `education_levels` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(50) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `level` int DEFAULT '0',
  `description` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `education_levels` VALUES 
(1,'SD','SD/Sederajat',1,NULL,'2025-01-01 00:00:00','2025-01-01 00:00:00');
INSERT INTO `education_levels` VALUES
(2,'SMP','SMP/Sederajat',2,NULL,'2025-01-01 00:00:00','2025-01-01 00:00:00');
INSERT INTO `education_levels` VALUES
(3,'SMA','SMA/Sederajat',3,NULL,'2025-01-01 00:00:00','2025-01-01 00:00:00');
INSERT INTO `education_levels` VALUES
(4,'D3','Diploma 3',4,NULL,'2025-01-01 00:00:00','2025-01-01 00:00:00');
INSERT INTO `education_levels` VALUES
(5,'S1','Sarjana (S1)',5,NULL,'2025-01-01 00:00:00','2025-01-01 00:00:00');
INSERT INTO `education_levels` VALUES
(6,'S2','Magister (S2)',6,NULL,'2025-01-01 00:00:00','2025-01-01 00:00:00');
INSERT INTO `education_levels` VALUES
(7,'S3','Doktor (S3)',7,NULL,'2025-01-01 00:00:00','2025-01-01 00:00:00');

DROP TABLE IF EXISTS `employees`;
CREATE TABLE `employees` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `code` varchar(50) DEFAULT NULL,
  `fullname` varchar(255) NOT NULL,
  `gender` enum('Male','Female') DEFAULT NULL,
  `place_of_birth` varchar(100) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `npwp` varchar(20) DEFAULT NULL,
  `marital_status` enum('Single','Married','Divorced','Widowed') DEFAULT 'Single',
  `religion` varchar(50) DEFAULT NULL,
  `education_level_id` bigint unsigned DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text,
  `join_date` date DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `resign_date` date DEFAULT NULL,
  `status` enum('Active','Inactive','Resigned') DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employees_code_unique` (`code`),
  UNIQUE KEY `employees_nik_unique` (`nik`),
  KEY `employees_user_id_index` (`user_id`),
  KEY `employees_education_level_id_index` (`education_level_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `employees` VALUES 
(1,1,'EMP001','Admin User','Male','Jakarta','1990-01-15','3201010101900001','123456789012345','Married','Islam',5,'+6281234567890','admin@aratechnology.id','Jl. Admin No. 1','2020-01-01','2020-01-01',NULL,'Active','2025-01-01 00:00:00','2025-01-01 00:00:00',NULL);
INSERT INTO `employees` VALUES
(2,2,'EMP002','Budi Santoso','Male','Bandung','1988-05-20','3201010101880001','123456789012346','Married','Islam',5,'+6281234567891','budi@aratechnology.id','Jl. Budi No. 2','2020-02-01','2020-02-01',NULL,'Active','2025-01-01 00:00:00','2025-01-01 00:00:00',NULL);
INSERT INTO `employees` VALUES
(3,3,'EMP003','Siti Aminah','Female','Surabaya','1992-03-10','3201010101920001','123456789012347','Married','Islam',5,'+6281234567892','siti@aratechnology.id','Jl. Siti No. 3','2020-03-01','2020-03-01',NULL,'Active','2025-01-01 00:00:00','2025-01-01 00:00:00',NULL);
INSERT INTO `employees` VALUES
(4,4,'EMP004','Andi Wijaya','Male','Medan','1995-07-25','3201010101950001','123456789012348','Single','Christian',5,'+6281234567893','andi@aratechnology.id','Jl. Andi No. 4','2021-01-01','2021-01-01',NULL,'Active','2025-01-01 00:00:00','2025-01-01 00:00:00',NULL);
INSERT INTO `employees` VALUES
(5,5,'EMP005','Dewi Lestari','Female','Yogyakarta','1993-11-30','3201010101930001','123456789012349','Single','Hindu',5,'+6281234567894','dewi@aratechnology.id','Jl. Dewi No. 5','2021-06-01','2021-06-01',NULL,'Active','2025-01-01 00:00:00','2025-01-01 00:00:00',NULL);

DROP TABLE IF EXISTS `employee_positions`;
CREATE TABLE `employee_positions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned NOT NULL,
  `job_position_id` bigint unsigned NOT NULL,
  `department_id` bigint unsigned DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `is_current` tinyint(1) DEFAULT '1',
  `notes` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_positions_employee_id_index` (`employee_id`),
  KEY `employee_positions_job_position_id_index` (`job_position_id`),
  KEY `employee_positions_department_id_index` (`department_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `employee_positions` VALUES 
(1,1,1,1,'2020-01-01',NULL,1,NULL,'2025-01-01 00:00:00','2025-01-01 00:00:00');
INSERT INTO `employee_positions` VALUES
(2,2,1,1,'2020-02-01',NULL,1,NULL,'2025-01-01 00:00:00','2025-01-01 00:00:00');
INSERT INTO `employee_positions` VALUES
(3,3,2,2,'2020-03-01',NULL,1,NULL,'2025-01-01 00:00:00','2025-01-01 00:00:00');
INSERT INTO `employee_positions` VALUES
(4,4,3,1,'2021-01-01',NULL,1,NULL,'2025-01-01 00:00:00','2025-01-01 00:00:00');
INSERT INTO `employee_positions` VALUES
(5,5,4,2,'2021-06-01',NULL,1,NULL,'2025-01-01 00:00:00','2025-01-01 00:00:00');

-- ===============================================
-- 4. EMPLOYEE FAMILIES
-- ===============================================

DROP TABLE IF EXISTS `employee_families`;
CREATE TABLE `employee_families` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned NOT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `no_kk` varchar(20) DEFAULT NULL,
  `fullname` varchar(255) NOT NULL,
  `relationship` varchar(50) NOT NULL,
  `gender` enum('Male','Female') DEFAULT NULL,
  `place_of_birth` varchar(100) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `education` varchar(100) DEFAULT NULL,
  `occupation` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `is_dependent` tinyint(1) DEFAULT '0',
  `is_emergency_contact` tinyint(1) DEFAULT '0',
  `notes` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_families_employee_id_index` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `employee_families` VALUES 
(1,1,'3201010101850001','3201010101234567','Sari Dewi','Spouse','Female','Jakarta','1992-03-20','S1','Pegawai Swasta','+6281234560001',1,1,NULL,'2025-01-01 00:00:00','2025-01-01 00:00:00',NULL);
INSERT INTO `employee_families` VALUES
(2,1,'3201010101100001',NULL,'Ahmad Wijaya','Child','Male','Jakarta','2015-08-10','SD',NULL,NULL,1,0,NULL,'2025-01-01 00:00:00','2025-01-01 00:00:00',NULL);
INSERT INTO `employee_families` VALUES
(3,1,'3201010101180001',NULL,'Fitri Rahayu','Child','Female','Jakarta','2018-12-05','TK',NULL,NULL,1,0,NULL,'2025-01-01 00:00:00','2025-01-01 00:00:00',NULL);
INSERT INTO `employee_families` VALUES
(4,2,'3201010101900002','3201010101234568','Dewi Kusuma','Spouse','Female','Bandung','1990-07-15','D3','Guru','+6281234560002',1,1,NULL,'2025-01-01 00:00:00','2025-01-01 00:00:00',NULL);
INSERT INTO `employee_families` VALUES
(5,3,'3201010101930002','3201010101234569','Rudi Hartono','Spouse','Male','Surabaya','1990-11-25','S1','Wiraswasta','+6281234560003',1,1,NULL,'2025-01-01 00:00:00','2025-01-01 00:00:00',NULL);
INSERT INTO `employee_families` VALUES
(6,3,'3201010101150001',NULL,'Nisa Maulida','Child','Female','Surabaya','2020-05-18','TK',NULL,NULL,1,0,NULL,'2025-01-01 00:00:00','2025-01-01 00:00:00',NULL);

-- ===============================================
-- 5. EMPLOYEE CONTACTS
-- ===============================================

DROP TABLE IF EXISTS `employee_contacts`;
CREATE TABLE `employee_contacts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned NOT NULL,
  `contact_type` enum('Emergency','Family','Other') DEFAULT 'Emergency',
  `name` varchar(255) NOT NULL,
  `relationship` varchar(50) DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `phone_alt` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text,
  `is_primary` tinyint(1) DEFAULT '0',
  `notes` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_contacts_employee_id_index` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `employee_contacts` VALUES 
(1,1,'Emergency','Sari Dewi','Spouse','+6281234560001',NULL,'sari@email.com',NULL,1,NULL,'2025-01-01 00:00:00','2025-01-01 00:00:00');
INSERT INTO `employee_contacts` VALUES
(2,2,'Emergency','Dewi Kusuma','Spouse','+6281234560002',NULL,'dewikusuma@email.com',NULL,1,NULL,'2025-01-01 00:00:00','2025-01-01 00:00:00');
INSERT INTO `employee_contacts` VALUES
(3,3,'Emergency','Rudi Hartono','Spouse','+6281234560003',NULL,'rudi@email.com',NULL,1,NULL,'2025-01-01 00:00:00','2025-01-01 00:00:00');
INSERT INTO `employee_contacts` VALUES
(4,4,'Emergency','Budi Santoso','Parent','+6281234560004',NULL,NULL,NULL,1,NULL,'2025-01-01 00:00:00','2025-01-01 00:00:00');
INSERT INTO `employee_contacts` VALUES
(5,5,'Emergency','Siti Aminah','Sibling','+6281234560005',NULL,NULL,NULL,1,NULL,'2025-01-01 00:00:00','2025-01-01 00:00:00');

-- ===============================================
-- 6. EMPLOYEE DOCUMENTS
-- ===============================================

DROP TABLE IF EXISTS `employee_documents`;
CREATE TABLE `employee_documents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned NOT NULL,
  `document_type` varchar(100) NOT NULL,
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
  KEY `employee_documents_employee_id_index` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `employee_documents` VALUES 
(1,1,'Contract','Employment Contract 2024','CONT-2024-001',NULL,'2024-01-01','2025-12-31','PT Ara Technology',NULL,'2025-01-01 00:00:00','2025-01-01 00:00:00',NULL);
INSERT INTO `employee_documents` VALUES
(2,2,'Certificate','Oracle Certified Professional','OCP-2023-789',NULL,'2023-06-15','2026-06-15','Oracle Corporation',NULL,'2025-01-01 00:00:00','2025-01-01 00:00:00',NULL);
INSERT INTO `employee_documents` VALUES
(3,3,'Certificate','SHRM-CP Certification','SHRM-2023-456',NULL,'2023-09-01','2026-09-01','SHRM',NULL,'2025-01-01 00:00:00','2025-01-01 00:00:00',NULL);
INSERT INTO `employee_documents` VALUES
(4,4,'Contract','Probation Contract','PROB-2024-004',NULL,'2024-01-01','2024-06-30','PT Ara Technology','Extended to permanent','2025-01-01 00:00:00','2025-01-01 00:00:00',NULL);

-- ===============================================
-- 7. ATTENDANCE & PRESENCE
-- ===============================================

DROP TABLE IF EXISTS `attendance`;
CREATE TABLE `attendance` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned NOT NULL,
  `date` date NOT NULL,
  `check_in` time DEFAULT NULL,
  `check_out` time DEFAULT NULL,
  `status` enum('Present','Absent','Late','Leave','Holiday') DEFAULT 'Present',
  `notes` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attendance_employee_id_index` (`employee_id`),
  KEY `attendance_date_index` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `presences`;
CREATE TABLE `presences` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned NOT NULL,
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
  `work_type` enum('WFO','WFH','Hybrid') DEFAULT 'WFO',
  `status` enum('Present','Late','Half Day','Absent') DEFAULT 'Present',
  `working_hours` decimal(5,2) DEFAULT '0.00',
  `overtime_hours` decimal(5,2) DEFAULT '0.00',
  `notes` text,
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `presences_employee_id_index` (`employee_id`),
  KEY `presences_date_index` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `presences` VALUES 
(1,1,'2025-12-27','08:00:00','Office HQ',-6.21462100,106.84513300,NULL,'17:00:00','Office HQ',-6.21462100,106.84513300,NULL,'WFO','Present',9.00,0.00,NULL,NULL,NULL,'2025-12-27 01:00:00','2025-12-27 01:00:00');
INSERT INTO `presences` VALUES
(2,2,'2025-12-27','08:15:00','Office HQ',-6.21462100,106.84513300,NULL,'17:00:00','Office HQ',-6.21462100,106.84513300,NULL,'WFO','Late',8.75,0.00,'Late 15 minutes',NULL,NULL,'2025-12-27 01:15:00','2025-12-27 01:15:00');
INSERT INTO `presences` VALUES
(3,3,'2025-12-27','08:00:00','Home',-6.30000000,106.80000000,NULL,'17:00:00','Home',-6.30000000,106.80000000,NULL,'WFH','Present',9.00,0.00,NULL,NULL,NULL,'2025-12-27 01:00:00','2025-12-27 01:00:00');
INSERT INTO `presences` VALUES
(4,4,'2025-12-27','09:00:00','Office HQ',-6.21462100,106.84513300,NULL,NULL,NULL,NULL,NULL,NULL,'WFO','Present',0.00,0.00,'Check-in only',NULL,NULL,'2025-12-27 02:00:00','2025-12-27 02:00:00');
INSERT INTO `presences` VALUES
(5,5,'2025-12-27','08:30:00','Home',-7.80000000,110.40000000,NULL,'17:30:00','Home',-7.80000000,110.40000000,NULL,'WFH','Present',9.00,0.00,NULL,NULL,NULL,'2025-12-27 01:30:00','2025-12-27 01:30:00');

-- ===============================================
-- 8. LEAVE MANAGEMENT
-- ===============================================

DROP TABLE IF EXISTS `leave_balances`;
CREATE TABLE `leave_balances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned NOT NULL,
  `year` year NOT NULL,
  `leave_type` varchar(50) NOT NULL,
  `total_days` decimal(5,2) NOT NULL DEFAULT '0.00',
  `used_days` decimal(5,2) NOT NULL DEFAULT '0.00',
  `remaining_days` decimal(5,2) GENERATED ALWAYS AS ((`total_days` - `used_days`)) STORED,
  `carried_forward` decimal(5,2) DEFAULT '0.00',
  `notes` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `leave_balances_employee_id_index` (`employee_id`),
  KEY `leave_balances_year_index` (`year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `leave_balances` (`id`,`employee_id`,`year`,`leave_type`,`total_days`,`used_days`,`carried_forward`,`notes`,`created_at`,`updated_at`) VALUES 
(1,1,2025,'Annual Leave',12.00,3.00,0.00,NULL,'2025-01-01 00:00:00','2025-01-01 00:00:00');
INSERT INTO `leave_balances` (`id`,`employee_id`,`year`,`leave_type`,`total_days`,`used_days`,`carried_forward`,`notes`,`created_at`,`updated_at`) VALUES
(2,1,2025,'Sick Leave',12.00,1.00,0.00,NULL,'2025-01-01 00:00:00','2025-01-01 00:00:00');
INSERT INTO `leave_balances` (`id`,`employee_id`,`year`,`leave_type`,`total_days`,`used_days`,`carried_forward`,`notes`,`created_at`,`updated_at`) VALUES
(3,2,2025,'Annual Leave',12.00,5.00,2.00,'2 days from 2024','2025-01-01 00:00:00','2025-01-01 00:00:00');
INSERT INTO `leave_balances` (`id`,`employee_id`,`year`,`leave_type`,`total_days`,`used_days`,`carried_forward`,`notes`,`created_at`,`updated_at`) VALUES
(4,3,2025,'Annual Leave',12.00,2.00,0.00,NULL,'2025-01-01 00:00:00','2025-01-01 00:00:00');
INSERT INTO `leave_balances` (`id`,`employee_id`,`year`,`leave_type`,`total_days`,`used_days`,`carried_forward`,`notes`,`created_at`,`updated_at`) VALUES
(5,3,2025,'Sick Leave',12.00,0.00,0.00,NULL,'2025-01-01 00:00:00','2025-01-01 00:00:00');
INSERT INTO `leave_balances` (`id`,`employee_id`,`year`,`leave_type`,`total_days`,`used_days`,`carried_forward`,`notes`,`created_at`,`updated_at`) VALUES
(6,4,2025,'Annual Leave',12.00,0.00,0.00,'New employee','2025-01-01 00:00:00','2025-01-01 00:00:00');
INSERT INTO `leave_balances` (`id`,`employee_id`,`year`,`leave_type`,`total_days`,`used_days`,`carried_forward`,`notes`,`created_at`,`updated_at`) VALUES
(7,5,2025,'Annual Leave',12.00,1.00,0.00,NULL,'2025-01-01 00:00:00','2025-01-01 00:00:00');

-- ===============================================
-- 9. LARAVEL SYSTEM TABLES
-- ===============================================

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- ===============================================
-- IMPORT COMPLETE - 24 TABLES CREATED
-- ===============================================
-- Test Login: admin@aratechnology.id / Password123!
-- ===============================================
