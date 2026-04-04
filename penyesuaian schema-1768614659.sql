CREATE TABLE IF NOT EXISTS `Foundations` (
	`foundation_id` varchar(8) NOT NULL,
	`foundation_name` varchar(64) NOT NULL,
	`email` varchar(64) NOT NULL,
	`phone` int NOT NULL,
	`address` text NOT NULL,
	`status` int NOT NULL,
	PRIMARY KEY (`foundation_id`)
);

CREATE TABLE IF NOT EXISTS `Departments` (
	`department_id` int NOT NULL,
	`department_name` varchar(32) NOT NULL,
	`description` varchar(128) NOT NULL,
	`created_at` datetime,
	`updated_at` datetime,
	`code` varchar(8) NOT NULL,
	`parent_id` int NOT NULL,
	`check_in` time NOT NULL,
	`check_out` time NOT NULL,
	`deleted_at` datetime,
	`foundation_id` varchar(8) NOT NULL,
	PRIMARY KEY (`department_id`)
);

CREATE TABLE IF NOT EXISTS `Job_Positions` (
	`position_id` varchar(10) NOT NULL,
	`level` varchar(20),
	`salary_grade` decimal(10,2),
	`title` varchar(50),
	`description` text,
	`created_at` datetime,
	`updated_at` datetime,
	PRIMARY KEY (`position_id`)
);

CREATE TABLE IF NOT EXISTS `user_types` (
	`user_type_id` varchar(8) NOT NULL,
	`user_type_name` varchar(32) NOT NULL,
	`description` text NOT NULL,
	PRIMARY KEY (`user_type_id`)
);

CREATE TABLE IF NOT EXISTS `Users` (
	`user_id` varchar(16) NOT NULL,
	`foundation_id` varchar(8) NOT NULL,
	`name` varchar(255),
	`user_type_id` varchar(255),
	`phone` varchar(255),
	`password` varchar(255),
	`created_at` datetime,
	`updated_at` datetime,
	`last_login` datetime,
	`profile_picture` varchar(255),
	`email` varchar(255) UNIQUE,
	`email_verified_at` datetime,
	`active` boolean,
	`remember_token` varchar(100) NOT NULL,
	PRIMARY KEY (`user_id`)
);

CREATE TABLE IF NOT EXISTS `roles` (
	`role_id` int NOT NULL,
	`role_name` varchar(32) NOT NULL,
	`description` text NOT NULL,
	PRIMARY KEY (`role_id`)
);

CREATE TABLE IF NOT EXISTS `list_menu_features` (
	`menu_id` int NOT NULL,
	`parent_id` int NOT NULL,
	`caption_eng` varchar(64) NOT NULL,
	`caption_indo` varchar(64) NOT NULL,
	`seq_level` int NOT NULL,
	`seq_order` int NOT NULL,
	`icon` varchar(32) NOT NULL,
	`url` varchar(128) NOT NULL,
	`description` text NOT NULL,
	PRIMARY KEY (`menu_id`)
);

CREATE TABLE IF NOT EXISTS `user_type_roles` (
	`user_type_id` varchar(8) NOT NULL,
	`role_id` int NOT NULL,
	`menu` int NOT NULL,
	`menu_id` int NOT NULL,
	`role_status` bool NOT NULL,
	PRIMARY KEY (`user_type_id`, `role_id`, `menu_id`)
);

CREATE TABLE IF NOT EXISTS `education_levels` (
	`education_level_id` int NOT NULL,
	`level` int NOT NULL,
	`create_at` datetime NOT NULL,
	`create_by` varchar(32) NOT NULL,
	`update_at` datetime NOT NULL,
	`update_by` int NOT NULL,
	PRIMARY KEY (`education_level_id`)
);

CREATE TABLE IF NOT EXISTS `Employees` (
	`employee_id` int NOT NULL,
	`hire_date` date,
	`user_id` varchar(16) NOT NULL,
	`fullname` varchar(100) NOT NULL,
	`join_date` date NOT NULL,
	`resign_date` date NOT NULL,
	`emp_code` varchar(20) NOT NULL,
	`gender` varchar(1) NOT NULL,
	`npwp` varchar(20) NOT NULL,
	`place_of_birth` varchar(32) NOT NULL,
	`date_of_birth` date NOT NULL,
	`marital_status` varchar(5) NOT NULL,
	`religion_id` int NOT NULL,
	`education_level_id` int NOT NULL,
	`created_at` datetime,
	`updated_at` datetime,
	`address` text,
	`phone_number` varchar(255) NOT NULL,
	`email` varchar(255) NOT NULL,
	`status` varchar(255) NOT NULL,
	`deleted_at` datetime,
	PRIMARY KEY (`employee_id`)
);

CREATE TABLE IF NOT EXISTS `pay_grade` (
	`pay_grade_id` int NOT NULL,
	`name` varchar(255),
	`currency` varchar(255),
	`pay_schedule` varchar(255),
	PRIMARY KEY (`pay_grade_id`)
);

CREATE TABLE IF NOT EXISTS `employee_positions` (
	`created_at` datetime,
	`updated_at` datetime,
	`pay_grade_id` int NOT NULL,
	`is_supervisor` boolean NOT NULL,
	`employee_position_id` int NOT NULL,
	`employee_id` int NOT NULL,
	`position_id` varchar(10) NOT NULL,
	`department_id` int NOT NULL,
	`start_date` date NOT NULL,
	`end_date` date NOT NULL,
	`sk_file_name` text NOT NULL,
	`sk_number` varchar(32) NOT NULL,
	`base_on_salary` int NOT NULL,
	PRIMARY KEY (`employee_position_id`)
);

CREATE TABLE IF NOT EXISTS `employee_families` (
	`nik` varchar(16) NOT NULL,
	`no_kk` varchar(16) NOT NULL,
	`fullname` varchar(64) NOT NULL,
	`place_of_birth` varchar(32) NOT NULL,
	`date_of_birth` date NOT NULL,
	`employee_id` int NOT NULL,
	`gender` varchar(1) NOT NULL,
	`status_active` int NOT NULL,
	`relationship` varchar(32) NOT NULL,
	PRIMARY KEY (`nik`)
);

CREATE TABLE IF NOT EXISTS `identity_types` (
	`identity_types_id` int NOT NULL,
	`identity_type_name` varchar(64) NOT NULL,
	PRIMARY KEY (`identity_types_id`)
);

CREATE TABLE IF NOT EXISTS `document_identity` (
	`id` int AUTO_INCREMENT NOT NULL,
	`identity_type_id` int NOT NULL,
	`user_id` varchar(16) NOT NULL,
	`identity_number` varchar(20) NOT NULL,
	`file_name` text NOT NULL,
	`description` text NOT NULL,
	`created_at` datetime,
	`updated_at` datetime,
	PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `bank_account` (
	`bank_account_id` int NOT NULL,
	`user_id` varchar(16) NOT NULL,
	`bank_name` varchar(255) NOT NULL,
	`account_no` varchar(255) NOT NULL,
	`account_holder` varchar(255) NOT NULL,
	`status` int NOT NULL,
	`created_at` datetime,
	`updated_at` datetime,
	PRIMARY KEY (`bank_account_id`)
);

CREATE TABLE IF NOT EXISTS `Attendance` (
	`attendance_id` int NOT NULL,
	`employee_id` int NOT NULL,
	`status` varchar(255) NOT NULL,
	`work_date` date NOT NULL,
	`check_in` datetime NOT NULL,
	`check_out` datetime NOT NULL,
	`work_location` varchar(255) NOT NULL,
	`notes` varchar(255) NOT NULL,
	`lat` double NOT NULL,
	`long` double NOT NULL,
	`created_at` datetime,
	`updated_at` datetime,
	`deleted_at` datetime,
	PRIMARY KEY (`attendance_id`)
);

CREATE TABLE IF NOT EXISTS `approval_types` (
	`approval_type_id` varchar(255) NOT NULL,
	`approval_type` varchar(255) NOT NULL,
	`created_at` datetime NOT NULL,
	`created_by` varchar(32) NOT NULL,
	`updated_at` datetime NOT NULL,
	`updated_by` varchar(255) NOT NULL,
	PRIMARY KEY (`approval_type_id`)
);

CREATE TABLE IF NOT EXISTS `category_approvals` (
	`category_id` int NOT NULL,
	`approval_type_id` varchar(255) NOT NULL,
	`category` varchar(255) NOT NULL,
	PRIMARY KEY (`category_id`)
);

CREATE TABLE IF NOT EXISTS `approval_requests` (
	`approval_id` int NOT NULL,
	`employee_id` int NOT NULL,
	`approval_type_id` varchar(255) NOT NULL,
	`approval_status` varchar(255) NOT NULL,
	`approval_date` date NOT NULL,
	`amount` int NOT NULL,
	`attachment_url` varchar(255) NOT NULL,
	`created_at` datetime NOT NULL,
	`created_by` varchar(32) NOT NULL,
	`updated_at` datetime NOT NULL,
	`updated_by` varchar(255) NOT NULL,
	`category_id` int NOT NULL,
	`start_date` datetime NOT NULL,
	`end_date` datetime NOT NULL,
	`reason` text NOT NULL,
	`deleted_at` datetime,
	PRIMARY KEY (`approval_id`)
);

CREATE TABLE IF NOT EXISTS `approved` (
	`approved_id` int NOT NULL,
	`approval_id` int NOT NULL,
	`approval_date` date,
	`approval_status` varchar(20),
	`approved_by` int NOT NULL,
	PRIMARY KEY (`approved_id`)
);

CREATE TABLE IF NOT EXISTS `payroll_period` (
	`period_id` int NOT NULL,
	`period_code` varchar(255) NOT NULL,
	`date_start` date NOT NULL,
	`date_end` date NOT NULL,
	`pay_date` date NOT NULL,
	`status` varchar(255) NOT NULL,
	`created_at` datetime,
	`updated_at` datetime,
	PRIMARY KEY (`period_id`)
);

CREATE TABLE IF NOT EXISTS `pay_component` (
	`component_id` int NOT NULL,
	`code` varchar(255) NOT NULL,
	`name` varchar(255) NOT NULL,
	`type` varchar(255) NOT NULL,
	`calc_basis` int NOT NULL,
	`taxable` bool NOT NULL,
	`statutory` bool NOT NULL,
	`created_at` datetime,
	`updated_at` datetime,
	PRIMARY KEY (`component_id`)
);

CREATE TABLE IF NOT EXISTS `pay_grade_component` (
	`pgc_id` int NOT NULL,
	`pay_grade_id` int NOT NULL,
	`component_id` int NOT NULL,
	`default_amount` decimal(10,0) NOT NULL,
	`default_rate` decimal(10,0) NOT NULL,
	`is_active` bool NOT NULL,
	PRIMARY KEY (`pgc_id`)
);

CREATE TABLE IF NOT EXISTS `employee_pay_component` (
	`epc_id` int NOT NULL,
	`employee_id` int NOT NULL,
	`component_id` int NOT NULL,
	`amount_override` decimal(10,0) NOT NULL,
	`rate_override` decimal(10,0) NOT NULL,
	`effective_from` date NOT NULL,
	`effective_to` date NOT NULL,
	PRIMARY KEY (`epc_id`)
);

CREATE TABLE IF NOT EXISTS `payslip` (
	`payslip_id` int NOT NULL,
	`employee_id` int NOT NULL,
	`period_id` int NOT NULL,
	`gross_amount` decimal(10,0) NOT NULL,
	`total_deduction` decimal(10,0) NOT NULL,
	`net_amount` decimal(10,0) NOT NULL,
	`generated_at` datetime NOT NULL,
	`status` varchar(255) NOT NULL,
	`created_at` datetime,
	`updated_at` datetime,
	`deleted_at` datetime,
	PRIMARY KEY (`payslip_id`)
);

CREATE TABLE IF NOT EXISTS `payslip_line` (
	`line_id` int NOT NULL,
	`payslip_id` int NOT NULL,
	`component_id` int NOT NULL,
	`quantity` decimal(10,0) NOT NULL,
	`rate` decimal(10,0) NOT NULL,
	`amount` decimal(10,0) NOT NULL,
	`source_type` varchar(255) NOT NULL,
	`source_id` int NOT NULL,
	PRIMARY KEY (`line_id`)
);

CREATE TABLE IF NOT EXISTS `kpi_period` (
	`status` varchar(255) NOT NULL,
	`created_at` datetime,
	`updated_at` datetime,
	`period_id` int NOT NULL,
	`code` varchar(255) NOT NULL,
	`name` varchar(255) NOT NULL,
	`date_start` date NOT NULL,
	`date_end` date NOT NULL,
	`frequency` varchar(255) NOT NULL,
	PRIMARY KEY (`period_id`)
);

CREATE TABLE IF NOT EXISTS `kpi_scale` (
	`scale_id` int NOT NULL,
	`name` varchar(255) NOT NULL,
	`min_value` decimal(10,0) NOT NULL,
	`max_value` decimal(10,0) NOT NULL,
	`step` decimal(10,0) NOT NULL,
	`description` varchar(255) NOT NULL,
	PRIMARY KEY (`scale_id`)
);

CREATE TABLE IF NOT EXISTS `kpi_scale_level` (
	`level_id` int NOT NULL,
	`scale_id` int NOT NULL,
	`value` decimal(10,0) NOT NULL,
	`label` varchar(255) NOT NULL,
	`description` varchar(255) NOT NULL,
	PRIMARY KEY (`level_id`)
);

CREATE TABLE IF NOT EXISTS `kpi_category` (
	`category_id` int NOT NULL,
	`code` varchar(255) NOT NULL,
	`name` varchar(255) NOT NULL,
	`description` varchar(255) NOT NULL,
	PRIMARY KEY (`category_id`)
);

CREATE TABLE IF NOT EXISTS `kpi_indicator` (
	`indicator_id` int NOT NULL,
	`category_id` int NOT NULL,
	`code` varchar(255) NOT NULL UNIQUE,
	`name` varchar(255) NOT NULL,
	`description` varchar(255) NOT NULL,
	`unit` varchar(255) NOT NULL,
	`calc_method` varchar(255) NOT NULL,
	`formula` varchar(255) NOT NULL,
	`direction` varchar(255) NOT NULL,
	`default_weight` decimal(10,0) NOT NULL,
	`is_active` bool NOT NULL,
	PRIMARY KEY (`indicator_id`)
);

CREATE TABLE IF NOT EXISTS `kpi_template` (
	`template_id` int NOT NULL,
	`name` varchar(255) NOT NULL,
	`description` varchar(255) NOT NULL,
	`department_id` int NOT NULL,
	`position_id` varchar(10) NOT NULL,
	`pay_grade_id` int NOT NULL,
	`scale_id` int NOT NULL,
	`is_active` bool NOT NULL,
	`created_at` datetime,
	`updated_at` datetime,
	PRIMARY KEY (`template_id`)
);

CREATE TABLE IF NOT EXISTS `kpi_template_item` (
	`template_item_id` int NOT NULL,
	`template_id` int NOT NULL,
	`indicator_id` int NOT NULL,
	`weight` decimal(10,0) NOT NULL,
	`target_value` decimal(10,0) NOT NULL,
	`target_text` varchar(255) NOT NULL,
	`baseline_value` decimal(10,0) NOT NULL,
	PRIMARY KEY (`template_item_id`)
);

CREATE TABLE IF NOT EXISTS `employee_kpi` (
	`emp_kpi_id` int NOT NULL,
	`employee_id` int NOT NULL,
	`period_id` int NOT NULL,
	`template_id` int,
	`scale_id` int NOT NULL,
	`reviewer_id` int NOT NULL,
	`secondary_reviewer_id` int NOT NULL,
	`status` varchar(255) NOT NULL,
	`submitted_at` datetime NOT NULL,
	`approved_at` datetime NOT NULL,
	`locked_at` datetime NOT NULL,
	`notes` varchar(255) NOT NULL,
	`created_at` datetime,
	`updated_at` datetime,
	PRIMARY KEY (`emp_kpi_id`)
);

CREATE TABLE IF NOT EXISTS `employee_kpi_item` (
	`emp_kpi_item_id` int NOT NULL,
	`emp_kpi_id` int NOT NULL,
	`indicator_id` int NOT NULL,
	`weight` decimal(10,0) NOT NULL,
	`target_value` decimal(10,0) NOT NULL,
	`target_text` varchar(255) NOT NULL,
	`baseline_value` decimal(10,0) NOT NULL,
	`calc_method` varchar(255) NOT NULL,
	`unit` varchar(255) NOT NULL,
	`direction` varchar(255) NOT NULL,
	`sort_order` int NOT NULL,
	PRIMARY KEY (`emp_kpi_item_id`)
);

CREATE TABLE IF NOT EXISTS `kpi_checkin` (
	`checkin_id` int NOT NULL,
	`emp_kpi_item_id` int NOT NULL,
	`checkin_date` date NOT NULL,
	`actual_value` decimal(10,0) NOT NULL,
	`comment` varchar(255) NOT NULL,
	`created_by` int NOT NULL,
	`created_at` datetime NOT NULL,
	PRIMARY KEY (`checkin_id`)
);

CREATE TABLE IF NOT EXISTS `kpi_evidence` (
	`evidence_id` int NOT NULL,
	`emp_kpi_item_id` int NOT NULL,
	`checkin_id` int NOT NULL,
	`title` varchar(255) NOT NULL,
	`url` varchar(255) NOT NULL,
	`uploaded_by` int NOT NULL,
	`uploaded_at` datetime NOT NULL,
	PRIMARY KEY (`evidence_id`)
);

CREATE TABLE IF NOT EXISTS `kpi_review` (
	`review_id` int NOT NULL,
	`emp_kpi_id` int NOT NULL,
	`reviewer_id` int NOT NULL,
	`review_stage` varchar(255) NOT NULL,
	`review_date` date NOT NULL,
	`comment` text NOT NULL,
	`overall_rating` decimal(10,0) NOT NULL,
	PRIMARY KEY (`review_id`)
);

CREATE TABLE IF NOT EXISTS `kpi_score` (
	`score_id` int NOT NULL,
	`emp_kpi_id` int NOT NULL,
	`calc_date` datetime NOT NULL,
	`method` varchar(255) NOT NULL,
	`weighted_score` decimal(10,0) NOT NULL,
	`rating_value` decimal(10,0) NOT NULL,
	`rating_label` varchar(255) NOT NULL,
	`normalized_score` decimal(10,0) NOT NULL,
	`details_json` varchar(255) NOT NULL,
	`locked` bool NOT NULL,
	PRIMARY KEY (`score_id`)
);

CREATE TABLE IF NOT EXISTS `kpi_approval` (
	`approval_id` int NOT NULL,
	`emp_kpi_id` int NOT NULL,
	`step` int NOT NULL,
	`approver_id` int NOT NULL,
	`action` varchar(255) NOT NULL,
	`comment` varchar(255) NOT NULL,
	`action_at` datetime NOT NULL,
	PRIMARY KEY (`approval_id`)
);

CREATE TABLE IF NOT EXISTS `KPI_Evaluations` (
	`evaluation_id` int NOT NULL,
	`employee_id` int NOT NULL,
	`evaluation_period` varchar(20),
	`score` decimal(5,2),
	`comments` text,
	`created_at` datetime,
	`updated_at` datetime,
	PRIMARY KEY (`evaluation_id`)
);

CREATE TABLE IF NOT EXISTS `performance_reviews` (
	`review_id` int NOT NULL,
	`employee_id` int NOT NULL,
	`reviewer_id` varchar(16) NOT NULL,
	`period` varchar(255) NOT NULL,
	`overall_score` decimal(5,2) NOT NULL,
	`achievements` text NOT NULL,
	`areas_improvement` text NOT NULL,
	`goals_next_period` text NOT NULL,
	`comments` text NOT NULL,
	`status` varchar(255) NOT NULL,
	`reviewed_at` datetime NOT NULL,
	`approved_at` datetime NOT NULL,
	`approved_by` varchar(16) NOT NULL,
	`created_at` datetime,
	`updated_at` datetime,
	PRIMARY KEY (`review_id`)
);

CREATE TABLE IF NOT EXISTS `inventory_categories` (
	`id` int NOT NULL,
	`name` varchar(255) NOT NULL,
	`description` text NOT NULL,
	`created_at` datetime,
	`updated_at` datetime,
	PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `inventories` (
	`id` int NOT NULL,
	`inventory_category_id` int NOT NULL,
	`name` varchar(255) NOT NULL,
	`description` text NOT NULL,
	`quantity` int NOT NULL,
	`location` varchar(255) NOT NULL,
	`purchase_date` date NOT NULL,
	`status` varchar(255) NOT NULL,
	`created_at` datetime,
	`updated_at` datetime,
	PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `inventory_usage_logs` (
	`id` int NOT NULL,
	`inventory_id` int NOT NULL,
	`employee_id` int NOT NULL,
	`borrowed_date` datetime NOT NULL,
	`returned_date` datetime NOT NULL,
	`notes` text NOT NULL,
	`created_at` datetime,
	`updated_at` datetime,
	PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `incidents` (
	`id` int NOT NULL,
	`employee_id` int NOT NULL,
	`type` varchar(255) NOT NULL,
	`incident_date` date NOT NULL,
	`description` text NOT NULL,
	`severity` varchar(255) NOT NULL,
	`status` varchar(255) NOT NULL,
	`action_taken` text NOT NULL,
	`reported_by` varchar(16) NOT NULL,
	`resolved_by` varchar(16) NOT NULL,
	`resolved_at` datetime NOT NULL,
	`notes` text NOT NULL,
	`created_at` datetime,
	`updated_at` datetime,
	PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `tasks` (
	`id` int NOT NULL,
	`title` varchar(255) NOT NULL,
	`description` text NOT NULL,
	`assigned_to` int NOT NULL,
	`due_date` datetime NOT NULL,
	`status` varchar(255) NOT NULL,
	`created_at` datetime,
	`updated_at` datetime,
	`deleted_at` datetime,
	PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `letter_templates` (
	`id` int NOT NULL,
	`name` varchar(255) NOT NULL,
	`description` varchar(255) NOT NULL,
	`content` longtext NOT NULL,
	`type` varchar(255) NOT NULL,
	`is_active` tinyint NOT NULL,
	`created_at` datetime,
	`updated_at` datetime,
	PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `letter_configurations` (
	`id` int NOT NULL,
	`company_name` varchar(255) NOT NULL,
	`company_address` varchar(255) NOT NULL,
	`company_phone` varchar(255) NOT NULL,
	`company_email` varchar(255) NOT NULL,
	`company_website` varchar(255) NOT NULL,
	`letterhead_footer` text NOT NULL,
	`letter_number_format` varchar(255) NOT NULL,
	`current_number` int NOT NULL,
	`is_active` tinyint NOT NULL,
	`created_at` datetime,
	`updated_at` datetime,
	PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `letters` (
	`id` int NOT NULL,
	`user_id` varchar(16) NOT NULL,
	`approver_id` varchar(16) NOT NULL,
	`letter_template_id` int NOT NULL,
	`letter_number` varchar(255) NOT NULL,
	`subject` varchar(255) NOT NULL,
	`content` longtext NOT NULL,
	`letter_type` varchar(255) NOT NULL,
	`status` varchar(255) NOT NULL,
	`created_date` date NOT NULL,
	`approved_date` datetime NOT NULL,
	`printed_date` datetime NOT NULL,
	`notes` text NOT NULL,
	`created_at` datetime,
	`updated_at` datetime,
	PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `letter_archives` (
	`id` int NOT NULL,
	`month` int NOT NULL,
	`year` int NOT NULL,
	`total_letters` int NOT NULL,
	`approved_letters` int NOT NULL,
	`printed_letters` int NOT NULL,
	`summary` longtext NOT NULL,
	`generated_at` datetime NOT NULL,
	`created_at` datetime,
	`updated_at` datetime,
	PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `signatures` (
	`ip_address` varchar(255) NOT NULL,
	`user_agent` varchar(255) NOT NULL,
	`is_verified` tinyint NOT NULL,
	`verification_token` varchar(255) NOT NULL,
	`verified_at` datetime NOT NULL,
	`created_at` datetime,
	`updated_at` datetime,
	`id` int NOT NULL,
	`user_id` varchar(16) NOT NULL,
	`signable_type` varchar(255) NOT NULL,
	`signable_id` int NOT NULL,
	`signature_image` longtext NOT NULL,
	`signature_hash` varchar(255) NOT NULL,
	`signed_date` datetime NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `signature_verifications` (
	`id` int NOT NULL,
	`signature_id` int NOT NULL,
	`verified_by_id` varchar(16) NOT NULL,
	`status` varchar(255) NOT NULL,
	`remarks` text NOT NULL,
	`verification_date` datetime NOT NULL,
	`created_at` datetime,
	`updated_at` datetime,
	PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `cache` (
	`key` varchar(255) NOT NULL,
	`value` mediumtext NOT NULL,
	`expiration` int NOT NULL,
	PRIMARY KEY (`key`)
);

CREATE TABLE IF NOT EXISTS `cache_locks` (
	`key` varchar(255) NOT NULL,
	`owner` varchar(255) NOT NULL,
	`expiration` int NOT NULL,
	PRIMARY KEY (`key`)
);

CREATE TABLE IF NOT EXISTS `failed_jobs` (
	`id` int NOT NULL,
	`uuid` varchar(255) NOT NULL,
	`connection` text NOT NULL,
	`queue` text NOT NULL,
	`payload` longtext NOT NULL,
	`exception` longtext NOT NULL,
	`failed_at` datetime NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `jobs` (
	`attempts` tinyint NOT NULL,
	`reserved_at` int NOT NULL,
	`available_at` int NOT NULL,
	`created_at` int NOT NULL,
	`queue` varchar(255) NOT NULL,
	`payload` longtext NOT NULL,
	`id` int NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `job_batches` (
	`id` varchar(255) NOT NULL,
	`name` varchar(255) NOT NULL,
	`total_jobs` int NOT NULL,
	`pending_jobs` int NOT NULL,
	`failed_jobs` int NOT NULL,
	`failed_job_ids` longtext NOT NULL,
	`options` mediumtext NOT NULL,
	`cancelled_at` int NOT NULL,
	`created_at` int NOT NULL,
	`finished_at` int NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `migrations` (
	`id` int NOT NULL,
	`migration` varchar(255) NOT NULL,
	`batch` int NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
	`email` varchar(255) NOT NULL,
	`token` varchar(255) NOT NULL,
	`created_at` datetime,
	PRIMARY KEY (`email`)
);

CREATE TABLE IF NOT EXISTS `sessions` (
	`id` varchar(255) NOT NULL,
	`user_id` varchar(16) NOT NULL,
	`ip_address` varchar(45) NOT NULL,
	`user_agent` text NOT NULL,
	`payload` longtext NOT NULL,
	`last_activity` int NOT NULL,
	PRIMARY KEY (`id`)
);


ALTER TABLE `Departments` ADD CONSTRAINT `Departments_fk10` FOREIGN KEY (`fouendation_id`) REFERENCES `Foundations`(`foundation_id`);


ALTER TABLE `Users` ADD CONSTRAINT `Users_fk1` FOREIGN KEY (`foundation_id`) REFERENCES `Foundations`(`foundation_id`);

ALTER TABLE `Users` ADD CONSTRAINT `Users_fk3` FOREIGN KEY (`user_type_id`) REFERENCES `user_types`(`user_type_id`);


ALTER TABLE `user_type_roles` ADD CONSTRAINT `user_type_roles_fk0` FOREIGN KEY (`user_type_id`) REFERENCES `user_types`(`user_type_id`);

ALTER TABLE `user_type_roles` ADD CONSTRAINT `user_type_roles_fk1` FOREIGN KEY (`role_id`) REFERENCES `roles`(`role_id`);

ALTER TABLE `user_type_roles` ADD CONSTRAINT `user_type_roles_fk3` FOREIGN KEY (`menu_id`) REFERENCES `list_menu_features`(`menu_id`);

ALTER TABLE `Employees` ADD CONSTRAINT `Employees_fk2` FOREIGN KEY (`user_id`) REFERENCES `Users`(`user_id`);

ALTER TABLE `Employees` ADD CONSTRAINT `Employees_fk13` FOREIGN KEY (`education_level_id`) REFERENCES `education_levels`(`education_level_id`);

ALTER TABLE `employee_positions` ADD CONSTRAINT `employee_positions_fk2` FOREIGN KEY (`pay_grade_id`) REFERENCES `pay_grade`(`pay_grade_id`);

ALTER TABLE `employee_positions` ADD CONSTRAINT `employee_positions_fk5` FOREIGN KEY (`employee_id`) REFERENCES `Employees`(`employee_id`);

ALTER TABLE `employee_positions` ADD CONSTRAINT `employee_positions_fk6` FOREIGN KEY (`position_id`) REFERENCES `Job_Positions`(`position_id`);

ALTER TABLE `employee_positions` ADD CONSTRAINT `employee_positions_fk7` FOREIGN KEY (`department_id`) REFERENCES `Departments`(`department_id`);
ALTER TABLE `employee_families` ADD CONSTRAINT `employee_families_fk5` FOREIGN KEY (`employee_id`) REFERENCES `Employees`(`employee_id`);

ALTER TABLE `document_identity` ADD CONSTRAINT `document_identity_fk1` FOREIGN KEY (`identity_type_id`) REFERENCES `identity_types`(`identity_types_id`);

ALTER TABLE `document_identity` ADD CONSTRAINT `document_identity_fk2` FOREIGN KEY (`user_id`) REFERENCES `Users`(`user_id`);
ALTER TABLE `bank_account` ADD CONSTRAINT `bank_account_fk1` FOREIGN KEY (`user_id`) REFERENCES `Users`(`user_id`);
ALTER TABLE `Attendance` ADD CONSTRAINT `Attendance_fk1` FOREIGN KEY (`employee_id`) REFERENCES `Employees`(`employee_id`);

ALTER TABLE `category_approvals` ADD CONSTRAINT `category_approvals_fk1` FOREIGN KEY (`approval_type_id`) REFERENCES `approval_types`(`approval_type_id`);
ALTER TABLE `approval_requests` ADD CONSTRAINT `approval_requests_fk1` FOREIGN KEY (`employee_id`) REFERENCES `Employees`(`employee_id`);

ALTER TABLE `approval_requests` ADD CONSTRAINT `approval_requests_fk2` FOREIGN KEY (`approval_type_id`) REFERENCES `approval_types`(`approval_type_id`);

ALTER TABLE `approval_requests` ADD CONSTRAINT `approval_requests_fk11` FOREIGN KEY (`category_id`) REFERENCES `category_approvals`(`category_id`);
ALTER TABLE `approved` ADD CONSTRAINT `approved_fk1` FOREIGN KEY (`approval_id`) REFERENCES `approval_requests`(`approval_id`);

ALTER TABLE `approved` ADD CONSTRAINT `approved_fk4` FOREIGN KEY (`approved_by`) REFERENCES `employee_positions`(`employee_position_id`);


ALTER TABLE `pay_grade_component` ADD CONSTRAINT `pay_grade_component_fk1` FOREIGN KEY (`pay_grade_id`) REFERENCES `pay_grade`(`pay_grade_id`);

ALTER TABLE `pay_grade_component` ADD CONSTRAINT `pay_grade_component_fk2` FOREIGN KEY (`component_id`) REFERENCES `pay_component`(`component_id`);
ALTER TABLE `employee_pay_component` ADD CONSTRAINT `employee_pay_component_fk1` FOREIGN KEY (`employee_id`) REFERENCES `Employees`(`employee_id`);

ALTER TABLE `employee_pay_component` ADD CONSTRAINT `employee_pay_component_fk2` FOREIGN KEY (`component_id`) REFERENCES `pay_component`(`component_id`);
ALTER TABLE `payslip` ADD CONSTRAINT `payslip_fk1` FOREIGN KEY (`employee_id`) REFERENCES `Employees`(`employee_id`);

ALTER TABLE `payslip` ADD CONSTRAINT `payslip_fk2` FOREIGN KEY (`period_id`) REFERENCES `payroll_period`(`period_id`);
ALTER TABLE `payslip_line` ADD CONSTRAINT `payslip_line_fk1` FOREIGN KEY (`payslip_id`) REFERENCES `payslip`(`payslip_id`);

ALTER TABLE `payslip_line` ADD CONSTRAINT `payslip_line_fk2` FOREIGN KEY (`component_id`) REFERENCES `pay_component`(`component_id`);


ALTER TABLE `kpi_scale_level` ADD CONSTRAINT `kpi_scale_level_fk1` FOREIGN KEY (`scale_id`) REFERENCES `kpi_scale`(`scale_id`);

ALTER TABLE `kpi_indicator` ADD CONSTRAINT `kpi_indicator_fk1` FOREIGN KEY (`category_id`) REFERENCES `kpi_category`(`category_id`);
ALTER TABLE `kpi_template` ADD CONSTRAINT `kpi_template_fk6` FOREIGN KEY (`scale_id`) REFERENCES `kpi_scale`(`scale_id`);
ALTER TABLE `kpi_template_item` ADD CONSTRAINT `kpi_template_item_fk1` FOREIGN KEY (`template_id`) REFERENCES `kpi_template`(`template_id`);

ALTER TABLE `kpi_template_item` ADD CONSTRAINT `kpi_template_item_fk2` FOREIGN KEY (`indicator_id`) REFERENCES `kpi_indicator`(`indicator_id`);
ALTER TABLE `employee_kpi` ADD CONSTRAINT `employee_kpi_fk1` FOREIGN KEY (`employee_id`) REFERENCES `Employees`(`employee_id`);

ALTER TABLE `employee_kpi` ADD CONSTRAINT `employee_kpi_fk2` FOREIGN KEY (`period_id`) REFERENCES `kpi_period`(`period_id`);

ALTER TABLE `employee_kpi` ADD CONSTRAINT `employee_kpi_fk3` FOREIGN KEY (`template_id`) REFERENCES `kpi_template`(`template_id`);

ALTER TABLE `employee_kpi` ADD CONSTRAINT `employee_kpi_fk4` FOREIGN KEY (`scale_id`) REFERENCES `kpi_scale`(`scale_id`);
ALTER TABLE `employee_kpi_item` ADD CONSTRAINT `employee_kpi_item_fk1` FOREIGN KEY (`emp_kpi_id`) REFERENCES `employee_kpi`(`emp_kpi_id`);

ALTER TABLE `employee_kpi_item` ADD CONSTRAINT `employee_kpi_item_fk2` FOREIGN KEY (`indicator_id`) REFERENCES `kpi_indicator`(`indicator_id`);
ALTER TABLE `kpi_checkin` ADD CONSTRAINT `kpi_checkin_fk1` FOREIGN KEY (`emp_kpi_item_id`) REFERENCES `employee_kpi_item`(`emp_kpi_item_id`);
ALTER TABLE `kpi_evidence` ADD CONSTRAINT `kpi_evidence_fk1` FOREIGN KEY (`emp_kpi_item_id`) REFERENCES `employee_kpi_item`(`emp_kpi_item_id`);

ALTER TABLE `kpi_evidence` ADD CONSTRAINT `kpi_evidence_fk2` FOREIGN KEY (`checkin_id`) REFERENCES `kpi_checkin`(`checkin_id`);
ALTER TABLE `kpi_review` ADD CONSTRAINT `kpi_review_fk1` FOREIGN KEY (`emp_kpi_id`) REFERENCES `employee_kpi`(`emp_kpi_id`);
ALTER TABLE `kpi_score` ADD CONSTRAINT `kpi_score_fk1` FOREIGN KEY (`emp_kpi_id`) REFERENCES `employee_kpi`(`emp_kpi_id`);
ALTER TABLE `kpi_approval` ADD CONSTRAINT `kpi_approval_fk1` FOREIGN KEY (`emp_kpi_id`) REFERENCES `employee_kpi`(`emp_kpi_id`);
ALTER TABLE `KPI_Evaluations` ADD CONSTRAINT `KPI_Evaluations_fk1` FOREIGN KEY (`employee_id`) REFERENCES `Employees`(`employee_id`);
ALTER TABLE `performance_reviews` ADD CONSTRAINT `performance_reviews_fk1` FOREIGN KEY (`employee_id`) REFERENCES `Employees`(`employee_id`);

ALTER TABLE `performance_reviews` ADD CONSTRAINT `performance_reviews_fk2` FOREIGN KEY (`reviewer_id`) REFERENCES `Users`(`user_id`);

ALTER TABLE `performance_reviews` ADD CONSTRAINT `performance_reviews_fk12` FOREIGN KEY (`approved_by`) REFERENCES `Users`(`user_id`);

ALTER TABLE `inventories` ADD CONSTRAINT `inventories_fk1` FOREIGN KEY (`inventory_category_id`) REFERENCES `inventory_categories`(`id`);
ALTER TABLE `inventory_usage_logs` ADD CONSTRAINT `inventory_usage_logs_fk1` FOREIGN KEY (`inventory_id`) REFERENCES `inventories`(`id`);

ALTER TABLE `inventory_usage_logs` ADD CONSTRAINT `inventory_usage_logs_fk2` FOREIGN KEY (`employee_id`) REFERENCES `Employees`(`employee_id`);
ALTER TABLE `incidents` ADD CONSTRAINT `incidents_fk1` FOREIGN KEY (`employee_id`) REFERENCES `Employees`(`employee_id`);

ALTER TABLE `incidents` ADD CONSTRAINT `incidents_fk8` FOREIGN KEY (`reported_by`) REFERENCES `Users`(`user_id`);

ALTER TABLE `incidents` ADD CONSTRAINT `incidents_fk9` FOREIGN KEY (`resolved_by`) REFERENCES `Users`(`user_id`);
ALTER TABLE `tasks` ADD CONSTRAINT `tasks_fk3` FOREIGN KEY (`assigned_to`) REFERENCES `Employees`(`employee_id`);


ALTER TABLE `letters` ADD CONSTRAINT `letters_fk1` FOREIGN KEY (`user_id`) REFERENCES `Users`(`user_id`);

ALTER TABLE `letters` ADD CONSTRAINT `letters_fk2` FOREIGN KEY (`approver_id`) REFERENCES `Users`(`user_id`);

ALTER TABLE `letters` ADD CONSTRAINT `letters_fk3` FOREIGN KEY (`letter_template_id`) REFERENCES `letter_templates`(`id`);

ALTER TABLE `signatures` ADD CONSTRAINT `signatures_fk8` FOREIGN KEY (`user_id`) REFERENCES `Users`(`user_id`);
ALTER TABLE `signature_verifications` ADD CONSTRAINT `signature_verifications_fk1` FOREIGN KEY (`signature_id`) REFERENCES `signatures`(`id`);

ALTER TABLE `signature_verifications` ADD CONSTRAINT `signature_verifications_fk2` FOREIGN KEY (`verified_by_id`) REFERENCES `Users`(`user_id`);







