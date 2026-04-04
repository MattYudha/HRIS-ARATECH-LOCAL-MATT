# Dummy Data Summary for HR Application Dashboard

## Overview
Comprehensive dummy data has been generated across all HR application modules to enable dashboard demonstration and testing.

---

## Data Generated

### 1. Employees (5 new employees + existing ones)
**New Employees Created:**
- **Sarah Johnson** - HR Department, HR Role, Salary: 8,000,000
  - Email: sarah.johnson@aratech.com
  - Password: password123
  
- **Michael Chen** - IT Department, Developer Role, Salary: 12,000,000
  - Email: michael.chen@aratech.com
  - Reports to: Employee #1
  
- **Lisa Anderson** - Sales Department, Salesperson Role, Salary: 7,000,000
  - Email: lisa.anderson@aratech.com
  - Reports to: Employee #1
  
- **David Kumar** - Finance Department, Finance Manager Role, Salary: 15,000,000
  - Email: david.kumar@aratech.com
  
- **Maria Garcia** - Marketing Department, Manager Role, Salary: 9,000,000
  - Email: maria.garcia@aratech.com

**Total Employees**: 7+ (original + 5 new)

---

### 2. Departments (2 new departments)
- **Finance** - Financial Management
- **Marketing** - Marketing and Communications
- Plus existing: HR, IT, Sales

---

### 3. Roles (1 new role)
- **Finance Manager** - Handles financial operations
- Plus existing: Manager, Developer, Salesperson, HR, Power User

---

### 4. Presences (Attendance Records)
- **Duration**: Last 30 days
- **Coverage**: All employees
- **Weekends**: Skipped
- **Check-in**: 8:00 AM (±30 minutes)
- **Check-out**: 5:00 PM (±30 minutes)
- **Status**: All marked as "present"
- **Total Records**: ~22 working days × number of employees

---

### 5. Tasks (7 tasks)
| Task Title | Assigned To | Due Date | Status |
|---|---|---|---|
| Implement Authentication System | Employee #2 | +7 days | in-progress |
| Design Marketing Campaign | Employee #7 | +5 days | pending |
| Financial Report Q4 | Employee #6 | +3 days | in-progress |
| Client Meeting Preparation | Employee #5 | +2 days | pending |
| Code Review Backend API | Employee #4 | +1 day | in-progress |
| Employee Onboarding Process | Employee #3 | +10 days | completed |
| Database Optimization | Employee #2 | +14 days | pending |

---

### 6. Inventory Usage Logs
- **Items**: Applied to all existing inventory items
- **Logs per Item**: 3-5 usage logs
- **Date Range**: 1-60 days ago
- **Borrow Duration**: 2-7 days
- **Total Logs**: ~20 logs across all items
- **Example**: Office supplies, laptops, projectors, etc.

---

### 7. Letters (Surat Resmi) - 4 letters
1. **Surat Keterangan Kerja** (Work Certificate)
   - User: Employee #3
   - Status: Approved (5 days ago)
   - Letter Number: 001/HR/12/2025
   - Type: Official
   - Approver: Power User (admin@example.com)

2. **Surat Tugas Perjalanan Dinas** (Official Travel Assignment)
   - User: Employee #4
   - Status: Pending
   - Letter Number: 002/HR/12/2025
   - Type: Official
   - Awaiting approval

3. **Memo Rapat Internal** (Internal Meeting Memo)
   - User: Employee #5
   - Status: Approved (2 days ago)
   - Letter Number: 003/HR/12/2025
   - Type: Memo
   - Approver: Power User

4. **Pengumuman Libur Nasional** (National Holiday Announcement)
   - User: Employee #6
   - Status: Printed (8 days ago)
   - Letter Number: 004/HR/12/2025
   - Type: Notice
   - Approver: Power User
   - Print Date: 10 days ago

---

### 8. Digital Signatures (3 signatures)
- **Letters Signed**: 3 approved/printed letters
- **Signature Type**: Base64 encoded PNG images
- **Hash Method**: SHA-256
- **Verification Status**: All verified
- **Verification Records**: Each signature has verification log
- **Verified By**: Power User (admin@example.com)
- **IP Address**: 127.0.0.1 (localhost)
- **User Agent**: Mozilla/5.0

---

## Testing Credentials

### Power User (Full Access) ✓ TESTED
- **Email**: admin@example.com
- **Password**: Password123!
- **Role**: Power User (all features)
- **Access**: All modules (HR, Finance, IT, etc.)
- **Status**: Verified - Password works correctly

### HR User
- **Email**: hr@example.com
- **Password**: Password123!
- **Role**: HR

### New Employee Accounts
All new employees can login with:
- **Password**: password123
- **Accounts**:
  - sarah.johnson@aratech.com
  - michael.chen@aratech.com
  - lisa.anderson@aratech.com
  - david.kumar@aratech.com
  - maria.garcia@aratech.com

---

## How to Use Dummy Data

### 1. View Employee Management
- Navigate to Employee Management
- See all 7+ employees with their departments and roles
- Check presence records for attendance tracking

### 2. View Attendance Dashboard
- Check Presences module
- See detailed attendance records for all employees
- View check-in/check-out times

### 3. View Task Management
- Navigate to Tasks module
- See tasks in different statuses (pending, in-progress, completed)
- View assigned tasks and due dates

### 4. View Inventory Management
- Check Inventory module
- View inventory usage history
- See borrow/return dates

### 5. View Letter Management
- Navigate to Letters/Mailing module
- See letters in different statuses:
  - Pending (needs approval)
  - Approved (ready to print)
  - Printed (completed)
- Test letter approval workflow

### 6. View Digital Signatures
- Navigate to Signature Logs
- See verified signatures
- View verification history and details
- Download signed documents

---

## Data Relationships

```
Employees → Departments → Roles
          → Presences (attendance)
          → Tasks (assigned)
          → Letters (created)
          → Digital Signatures (signed)

Inventory → InventoryUsageLog (borrowed by employees)

Letters → Digital Signatures (cryptographic signing)
        → Signature Verifications (approval trail)
```

---

## Dashboard Demonstration Points

1. **Employee Overview**: Shows department structure and reporting relationships
2. **Attendance Metrics**: 30 days of presence data per employee
3. **Task Pipeline**: Mixed status tasks for workflow demonstration
4. **Inventory Status**: Active inventory usage and return tracking
5. **Letter Workflow**: Complete letter lifecycle from draft to printed
6. **Signature Audit Trail**: Digital signature verification logs

---

## Notes

- All dates are relative to "now" (Carbon::now())
- All passwords for new employees: `password123`
- Power User has access to all features for demonstration
- Data is realistic but randomized for variety
- Weekends are excluded from attendance records
- Letter numbers follow format: NNN/DEPT/MM/YYYY

---

## To Regenerate Data

If you need to reset and regenerate all dummy data:

```bash
php artisan migrate:fresh --seed
# Then run the specific seeder:
php artisan db:seed --class=DummyDataForDashboardSeeder
```

Or run the comprehensive seeder:
```bash
php artisan db:seed --class=DatabaseSeeder
```

---

**Last Updated**: December 4, 2025
**Seeder File**: `database/seeders/DummyDataForDashboardSeeder.php`
