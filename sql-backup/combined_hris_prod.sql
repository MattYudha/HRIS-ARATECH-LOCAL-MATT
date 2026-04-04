-- Combined HRIS Schema (All Lowercase)
-- Mengadopsi struktur GOHR2 dengan penambahan fitur unik HRIS2
-- Semua nama tabel dan field menggunakan huruf kecil (lowercase)

-- ============================================
-- FOUNDATION & ORGANIZATION STRUCTURE
-- ============================================

foundations {
	foundation_id varchar(8) pk
	foundation_name varchar(64)
	email varchar(64)
	phone int
	address text
	status integer
}

departments {
	department_id int pk
	foundation_id varchar(8) *> foundations.foundation_id
	department_name varchar(32)
	description varchar(128)
	created_at datetime null
	updated_at datetime null
	code varchar(8)
	parent_id int
	check_in time
	check_out time
	deleted_at datetime null
}

job_positions {
	position_id varchar(10) pk
	level varchar(20) null
	salary_grade decimal(10,2) null
	title varchar(50) null
	description text null
	created_at datetime null
	updated_at datetime null
}

-- ============================================
-- USER MANAGEMENT & AUTHENTICATION
-- ============================================

user_types {
	user_type_id varchar(8) pk
	user_type_name varchar(32)
	description text
}

users {
	user_id varchar(16) pk null
	foundation_id varchar(8) *> foundations.foundation_id
	name varchar null
	user_type_id varchar null *> user_types.user_type_id
	phone varchar null
	password varchar null
	created_at datetime null
	updated_at datetime null
	last_login datetime null
	profile_picture varchar null
	email varchar null unique
	email_verified_at datetime null
	active boolean null
	remember_token varchar(100)
}

roles {
	role_id int pk
	role_name varchar(32)
	description text
}

list_menu_features {
	menu_id int pk
	parent_id int
	caption_eng varchar(64)
	caption_indo varchar(64)
	seq_level int
	seq_order int
	icon varchar(32)
	url varchar(128)
	description text
}

user_type_roles {
	user_type_id varchar(8) pk *> user_types.user_type_id
	role_id int pk *> roles.role_id
	menu integer
	menu_id int pk *> list_menu_features.menu_id
	role_status bool
}

-- ============================================
-- EMPLOYEE MANAGEMENT
-- ============================================

education_levels {
	education_level_id int pk
	level integer
	create_at datetime
	create_by varchar(32)
	update_at datetime
	update_by integer
}

employees {
	employee_id int pk
	hire_date date null
	user_id varchar(16) > users.user_id
	fullname varchar(100)
	join_date date
	resign_date date
	emp_code varchar(20)
	gender varchar(1)
	npwp varchar(20)
	place_of_birth varchar(32)
	date_of_birth date
	marital_status varchar(5)
	religion_id int
	education_level_id int *> education_levels.education_level_id
	created_at datetime null
	updated_at datetime null
	address text null
	phone_number varchar(255)
	email varchar(255)
	status varchar(255)
	deleted_at datetime null
}

pay_grade {
	pay_grade_id int pk
	name varchar null
	currency varchar null
	pay_schedule varchar null
}

employee_positions {
	employee_position_id int pk
	employee_id int > employees.employee_id
	position_id varchar(10) *> job_positions.position_id
	department_id int *> departments.department_id
	start_date date
	end_date date
	sk_file_name text
	sk_number varchar(32)
	base_on_salary int
	created_at datetime null
	updated_at datetime null
	pay_grade_id int *> pay_grade.pay_grade_id
	is_supervisor boolean
}

employee_families {
	nik varchar(16) pk
	no_kk varchar(16)
	fullname varchar(64)
	place_of_birth varchar(32)
	date_of_birth date
	employee_id int *> employees.employee_id
	gender varchar(1)
	status_active int
	relationship varchar(32)
}

-- ============================================
-- DOCUMENT & IDENTITY MANAGEMENT
-- ============================================

identity_types {
	identity_types_id int pk
	identity_type_name varchar(64)
}

document_identity {
	id int pk increments
	identity_type_id int *> identity_types.identity_types_id
	user_id varchar(16) *> users.user_id
	identity_number varchar(20)
	file_name text
	description text
	created_at datetime null
	updated_at datetime null
}

bank_account {
	bank_account_id int pk
	user_id varchar(16) *> users.user_id
	bank_name varchar
	account_no varchar
	account_holder varchar
	status integer
	created_at datetime null
	updated_at datetime null
}

-- ============================================
-- ATTENDANCE MANAGEMENT
-- ============================================

attendance {
	attendance_id int pk
	employee_id int *> employees.employee_id
	status varchar
	work_date date
	check_in datetime
	check_out datetime
	work_location varchar
	notes varchar
	lat double
	long double
	created_at datetime null
	updated_at datetime null
	deleted_at datetime null
}

-- ============================================
-- LEAVE & APPROVAL MANAGEMENT
-- ============================================

approval_types {
	approval_type_id varchar pk
	approval_type varchar
	created_at datetime
	created_by varchar(32)
	updated_at datetime
	updated_by varchar
}

category_approvals {
	category_id int pk
	approval_type_id varchar *> approval_types.approval_type_id
	category varchar
}

approval_requests {
	approval_id int pk
	employee_id int *> employees.employee_id
	approval_type_id varchar *> approval_types.approval_type_id
	approval_status varchar
	approval_date date
	amount integer
	attachment_url varchar
	created_at datetime
	created_by varchar(32)
	updated_at datetime
	updated_by varchar
	category_id integer *> category_approvals.category_id
	start_date datetime
	end_date datetime
	reason text
	deleted_at datetime null
}

approved {
	approved_id int pk
	approval_id int *> approval_requests.approval_id
	approval_date date null
	approval_status varchar(20) null
	approved_by integer *> employee_positions.employee_position_id
}

-- ============================================
-- PAYROLL MANAGEMENT
-- ============================================

payroll_period {
	period_id int pk
	period_code varchar
	date_start date
	date_end date
	pay_date date
	status varchar
	created_at datetime null
	updated_at datetime null
}

pay_component {
	component_id int pk
	code varchar
	name varchar
	type varchar
	calc_basis integer
	taxable bool
	statutory bool
	created_at datetime null
	updated_at datetime null
}

pay_grade_component {
	pgc_id int pk
	pay_grade_id int > pay_grade.pay_grade_id
	component_id int *> pay_component.component_id
	default_amount decimal
	default_rate decimal
	is_active bool
}

employee_pay_component {
	epc_id int pk
	employee_id int > employees.employee_id
	component_id int > pay_component.component_id
	amount_override decimal
	rate_override decimal
	effective_from date
	effective_to date
}

payslip {
	payslip_id int pk
	employee_id int > employees.employee_id
	period_id int *> payroll_period.period_id
	gross_amount decimal
	total_deduction decimal
	net_amount decimal
	generated_at datetime
	status varchar
	created_at datetime null
	updated_at datetime null
	deleted_at datetime null
}

payslip_line {
	line_id int pk
	payslip_id int > payslip.payslip_id
	component_id int > pay_component.component_id
	quantity decimal
	rate decimal
	amount decimal
	source_type varchar
	source_id int
}

-- ============================================
-- KPI MANAGEMENT SYSTEM
-- ============================================

kpi_period {
	period_id int pk
	code varchar
	name varchar
	date_start date
	date_end date
	frequency varchar
	status varchar
	created_at datetime null
	updated_at datetime null
}

kpi_scale {
	scale_id int pk
	name varchar
	min_value decimal
	max_value decimal
	step decimal
	description varchar
}

kpi_scale_level {
	level_id int pk
	scale_id int > kpi_scale.scale_id
	value decimal
	label varchar
	description varchar
}

kpi_category {
	category_id int pk
	code varchar
	name varchar
	description varchar
}

kpi_indicator {
	indicator_id int pk
	category_id int > kpi_category.category_id
	code varchar unique
	name varchar
	description varchar
	unit varchar
	calc_method varchar
	formula varchar
	direction varchar
	default_weight decimal
	is_active bool
}

kpi_template {
	template_id int pk
	name varchar
	description varchar
	department_id int
	position_id varchar(10)
	pay_grade_id int
	scale_id int > kpi_scale.scale_id
	is_active bool
	created_at datetime null
	updated_at datetime null
}

kpi_template_item {
	template_item_id int pk
	template_id int > kpi_template.template_id
	indicator_id int > kpi_indicator.indicator_id
	weight decimal
	target_value decimal
	target_text varchar
	baseline_value decimal
}

employee_kpi {
	emp_kpi_id int pk
	employee_id int *> employees.employee_id
	period_id int > kpi_period.period_id
	template_id int null > kpi_template.template_id
	scale_id int > kpi_scale.scale_id
	reviewer_id integer
	secondary_reviewer_id int
	status varchar
	submitted_at datetime
	approved_at datetime
	locked_at datetime
	notes varchar
	created_at datetime null
	updated_at datetime null
}

employee_kpi_item {
	emp_kpi_item_id int pk
	emp_kpi_id int > employee_kpi.emp_kpi_id
	indicator_id int > kpi_indicator.indicator_id
	weight decimal
	target_value decimal
	target_text varchar
	baseline_value decimal
	calc_method varchar
	unit varchar
	direction varchar
	sort_order int
}

kpi_checkin {
	checkin_id int pk
	emp_kpi_item_id int > employee_kpi_item.emp_kpi_item_id
	checkin_date date
	actual_value decimal
	comment varchar
	created_by int
	created_at datetime
}

kpi_evidence {
	evidence_id int pk
	emp_kpi_item_id int > employee_kpi_item.emp_kpi_item_id
	checkin_id int > kpi_checkin.checkin_id
	title varchar
	url varchar
	uploaded_by int
	uploaded_at datetime
}

kpi_review {
	review_id int pk
	emp_kpi_id int > employee_kpi.emp_kpi_id
	reviewer_id int
	review_stage varchar
	review_date date
	comment text
	overall_rating decimal
}

kpi_score {
	score_id int pk
	emp_kpi_id int > employee_kpi.emp_kpi_id
	calc_date datetime
	method varchar
	weighted_score decimal
	rating_value decimal
	rating_label varchar
	normalized_score decimal
	details_json varchar
	locked bool
}

kpi_approval {
	approval_id int pk
	emp_kpi_id int > employee_kpi.emp_kpi_id
	step int
	approver_id int
	action varchar
	comment varchar
	action_at datetime
}

-- ============================================
-- PERFORMANCE EVALUATION
-- ============================================

kpi_evaluations {
	evaluation_id int pk
	employee_id int > employees.employee_id
	evaluation_period varchar(20) null
	score decimal(5,2) null
	comments text null
	created_at datetime null
	updated_at datetime null
}

performance_reviews {
	review_id int pk
	employee_id int > employees.employee_id
	reviewer_id varchar(16) > users.user_id
	period varchar(255)
	overall_score decimal(5,2)
	achievements text
	areas_improvement text
	goals_next_period text
	comments text
	status varchar
	reviewed_at datetime
	approved_at datetime
	approved_by varchar(16) > users.user_id
	created_at datetime null
	updated_at datetime null
}

-- ============================================
-- INVENTORY MANAGEMENT (HRIS2 UNIQUE)
-- ============================================

inventory_categories {
	id int pk
	name varchar(255)
	description text
	created_at datetime null
	updated_at datetime null
}

inventories {
	id int pk
	inventory_category_id int > inventory_categories.id
	name varchar(255)
	description text
	quantity int
	location varchar(255)
	purchase_date date
	status varchar
	created_at datetime null
	updated_at datetime null
}

inventory_usage_logs {
	id int pk
	inventory_id int > inventories.id
	employee_id int > employees.employee_id
	borrowed_date datetime
	returned_date datetime
	notes text
	created_at datetime null
	updated_at datetime null
}

-- ============================================
-- INCIDENT MANAGEMENT (HRIS2 UNIQUE)
-- ============================================

incidents {
	id int pk
	employee_id int > employees.employee_id
	type varchar(255)
	incident_date date
	description text
	severity varchar
	status varchar
	action_taken text
	reported_by varchar(16) > users.user_id
	resolved_by varchar(16) > users.user_id
	resolved_at datetime
	notes text
	created_at datetime null
	updated_at datetime null
}

-- ============================================
-- TASK MANAGEMENT (HRIS2 UNIQUE)
-- ============================================

tasks {
	id int pk
	title varchar(255)
	description text
	assigned_to int > employees.employee_id
	due_date datetime
	status varchar(255)
	created_at datetime null
	updated_at datetime null
	deleted_at datetime null
}

-- ============================================
-- LETTER/DOCUMENT MANAGEMENT (HRIS2 UNIQUE)
-- ============================================

letter_templates {
	id int pk
	name varchar(255)
	description varchar(255)
	content longtext
	type varchar(255)
	is_active tinyint
	created_at datetime null
	updated_at datetime null
}

letter_configurations {
	id int pk
	company_name varchar(255)
	company_address varchar(255)
	company_phone varchar(255)
	company_email varchar(255)
	company_website varchar(255)
	letterhead_footer text
	letter_number_format varchar(255)
	current_number int
	is_active tinyint
	created_at datetime null
	updated_at datetime null
}

letters {
	id int pk
	user_id varchar(16) > users.user_id
	approver_id varchar(16) > users.user_id
	letter_template_id int > letter_templates.id
	letter_number varchar(255)
	subject varchar(255)
	content longtext
	letter_type varchar(255)
	status varchar
	created_date date
	approved_date datetime
	printed_date datetime
	notes text
	created_at datetime null
	updated_at datetime null
}

letter_archives {
	id int pk
	month int
	year int
	total_letters int
	approved_letters int
	printed_letters int
	summary longtext
	generated_at datetime
	created_at datetime null
	updated_at datetime null
}

-- ============================================
-- DIGITAL SIGNATURE (HRIS2 UNIQUE)
-- ============================================

signatures {
	id int pk
	user_id varchar(16) > users.user_id
	signable_type varchar(255)
	signable_id int
	signature_image longtext
	signature_hash varchar(255)
	signed_date datetime
	ip_address varchar(255)
	user_agent varchar(255)
	is_verified tinyint
	verification_token varchar(255)
	verified_at datetime
	created_at datetime null
	updated_at datetime null
}

signature_verifications {
	id int pk
	signature_id int > signatures.id
	verified_by_id varchar(16) > users.user_id
	status varchar
	remarks text
	verification_date datetime
	created_at datetime null
	updated_at datetime null
}

-- ============================================
-- SYSTEM TABLES (Laravel Framework)
-- ============================================

cache {
	key varchar(255) pk
	value mediumtext
	expiration int
}

cache_locks {
	key varchar(255) pk
	owner varchar(255)
	expiration int
}

failed_jobs {
	id int pk
	uuid varchar(255)
	connection text
	queue text
	payload longtext
	exception longtext
	failed_at datetime
}

jobs {
	id int pk
	queue varchar(255)
	payload longtext
	attempts tinyint
	reserved_at int
	available_at int
	created_at int
}

job_batches {
	id varchar(255) pk
	name varchar(255)
	total_jobs int
	pending_jobs int
	failed_jobs int
	failed_job_ids longtext
	options mediumtext
	cancelled_at int
	created_at int
	finished_at int
}

migrations {
	id int pk
	migration varchar(255)
	batch int
}

password_reset_tokens {
	email varchar(255) pk
	token varchar(255)
	created_at datetime null
}

sessions {
	id varchar(255) pk
	user_id varchar(16)
	ip_address varchar(45)
	user_agent text
	payload longtext
	last_activity int
}