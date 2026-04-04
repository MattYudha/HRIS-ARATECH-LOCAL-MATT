# WARP.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

## Project Overview

This is a Human Resource Management application built with Laravel 11, MySQL, Laravel Breeze (authentication), and the Mazer Admin Template. The system manages employees, departments, roles, tasks, payroll, attendance (presences), and leave requests.

## Development Commands

### Environment Setup
```powershell
# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file (if not exists)
copy .env.example .env

# Generate application key
php artisan key:generate

# Run database migrations
php artisan migrate

# Seed database (if seeders exist)
php artisan db:seed
```

### Development Server
```powershell
# Start all development services (server, queue worker, logs, and Vite)
composer dev

# Or start individually:
php artisan serve                    # Web server (http://localhost:8000)
php artisan queue:listen --tries=1   # Queue worker
php artisan pail --timeout=0         # Real-time logs
npm run dev                          # Vite dev server for assets
```

### Asset Management
```powershell
# Development build with hot reload
npm run dev

# Production build
npm run build
```

### Testing
```powershell
# Run all tests with Pest
php artisan test

# Run specific test file
php artisan test tests/Feature/Auth/AuthenticationTest.php

# Run tests with coverage
php artisan test --coverage
```

### Code Quality
```powershell
# Laravel Pint (code formatting)
vendor/bin/pint

# Run Pint on specific files/directories
vendor/bin/pint app/Http/Controllers
```

### Database
```powershell
# Fresh migration (drops all tables and re-migrates)
php artisan migrate:fresh

# Rollback last migration
php artisan migrate:rollback

# Create new migration
php artisan make:migration create_table_name

# Run database seeder
php artisan db:seed
```

### Caching (Production)
```powershell
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Other Useful Commands
```powershell
# Create storage symbolic link
php artisan storage:link

# List all routes
php artisan route:list

# Open Laravel Tinker REPL
php artisan tinker

# Create new controller
php artisan make:controller NameController

# Create new model with migration
php artisan make:model ModelName -m
```

## Architecture & Key Concepts

### Authentication & Authorization

**Authentication**: Uses Laravel Breeze for authentication scaffolding.

**Authorization**: Role-based access control (RBAC) implemented via custom middleware `CheckRole`.

- The `CheckRole` middleware (app/Http/Middleware/CheckRole.php) checks if the authenticated user's employee role matches the required roles for a route
- User roles are determined through the relationship: User → Employee → Role
- Role information is stored in the session (keys: `role`, `employee_id`)
- Routes are protected with `middleware(['role:HR'])` or `middleware(['role:Developer,HR'])`

**Important**: The system uses a User-Employee separation pattern:
- `users` table contains authentication credentials
- `employees` table contains HR data (salary, hire date, department, etc.)
- Users link to employees via `users.employee_id → employees.id`
- **Public registration is disabled** - users are created automatically when an employee is created through the Employee management interface (EmployeeController)
- When creating an employee, a user account is auto-generated with a random 12-character password (displayed once)

### Password Migration Strategy

The application includes a legacy password migration system documented in `docs/PASSWORD_MIGRATION.md`:
- Normal login attempts use Laravel's bcrypt hashing via `Auth::attempt()`
- Fallback mechanism exists for legacy MD5/plaintext passwords
- On successful legacy login, passwords are automatically re-hashed to bcrypt
- Implemented in `app/Http/Requests/Auth/LoginRequest.php`

### Core Domain Models

**Employee**: Central entity with soft deletes enabled
- Relationships: belongs to Department, Role; has one User
- Fields: fullname, email, phone_number, address, birth_date, hire_date, department_id, role_id, supervisor_id, status, salary

**Department**: Organizational units with soft deletes
- Fields: name, description, status

**Role**: Employee roles/positions
- Used for both job titles and access control

**Task**: Work assignments for employees
- Relationships: belongs to Employee
- Status tracking: done/pending

**Presence**: Attendance tracking
- Fields: employee_id, date, status (present/absent)
- Used for dashboard charts showing monthly attendance

**Payroll**: Salary/payment records
- Relationships: belongs to Employee

**LeaveRequest**: Employee leave/vacation requests
- Status: pending, confirmed, rejected

### Frontend Architecture

**Admin Template**: Mazer Admin Template (located in public/mazer/)

**View Structure**:
- Blade components for layouts: `AppLayout`, `GuestLayout` (app/View/Components/)
- Resource-based view directories matching controllers (departments, employees, tasks, etc.)
- Dashboard with charting for attendance data

**Asset Pipeline**: Vite for building CSS (Tailwind) and JavaScript (Alpine.js)
- Tailwind CSS configured for Laravel views
- Alpine.js for interactive components
- Entry points: resources/css/app.css, resources/js/app.js

### Database Configuration

**Default**: MySQL on localhost
- Database name: `hrapps`
- Default credentials: root with no password (XAMPP default)
- Sessions stored in database (SESSION_DRIVER=database)
- Queue connection uses database (QUEUE_CONNECTION=database)
- Cache store uses database (CACHE_STORE=database)

### Testing Setup

**Framework**: Pest PHP (not PHPUnit)
- Feature tests use `RefreshDatabase` trait automatically
- Tests organized in tests/Feature/ and tests/Unit/
- Custom Pest configuration in tests/Pest.php
- Authentication tests from Breeze included

## Important Patterns & Conventions

### Resource Controllers
Most domain entities use Laravel resource controllers with standard CRUD routes:
- `index()`, `create()`, `store()`, `show()`, `edit()`, `update()`, `destroy()`
- Routes defined with `Route::resource('entities', EntityController::class)`

### Route Middleware
Routes are grouped by authentication and role requirements:
- All routes except login require `auth` middleware
- HR-only routes: departments, roles, employees (require `role:HR`)
- Developer+HR routes: tasks, payrolls, presences, leave-requests (require `role:Developer,HR`)

### Dashboard Data
The dashboard provides:
- Task overview (all tasks)
- Presence chart endpoint at `/dashboard/presence` returning JSON monthly attendance data

### Soft Deletes
Models using soft deletes: Employee, Department
- Deleted records remain in database with `deleted_at` timestamp
- Queries automatically exclude soft-deleted records

## Database Schema Notes

Primary migration: `2025_02_11_091619_create_humanresourceapp_tables.php` creates the main HR tables.
Additional migration: `2025_02_12_182302_add_role_to_users_table.php` (may be related to role tracking).

## Development Environment

This project is configured for XAMPP on Windows:
- PHP 8.2+
- MySQL via XAMPP
- Uses PowerShell commands
- Local development URL: http://localhost:8000 (via `php artisan serve`)

## Deployment Notes

The `hostingphp.yml` file contains deployment automation commands:
```powershell
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan key:generate
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
npm install
npm run build
```
