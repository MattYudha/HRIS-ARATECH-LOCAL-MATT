# HR Application Testing Guide

## Quick Start

### 1. Login Credentials

**Power User (Full Access - Recommended for Testing)**
```
Email: admin@example.com
Password: Password123!
```

**HR User**
```
Email: hr@example.com
Password: Password123!
```

**Sample Employee Accounts** (all use password: `password123`)
- sarah.johnson@aratech.com
- michael.chen@aratech.com
- lisa.anderson@aratech.com
- david.kumar@aratech.com
- maria.garcia@aratech.com

---

## Dashboard Verification Checklist

### ✓ Employee Management Module
```bash
Expected: 9 employees total
- Login as Power User
- Go to Employee Management
- Verify you can see all employees
- Check departments: HR, IT, Sales, Finance, Marketing
- Test filtering by department and role
```

**Test Cases:**
- [ ] View all employees list
- [ ] Edit employee information
- [ ] View employee details
- [ ] Check reporting relationships

---

### ✓ Attendance/Presence Module
```bash
Expected: 200+ presence records (30 days for all employees)
- Navigate to Presences
- See attendance records
- Check-in: around 8:00 AM
- Check-out: around 5:00 PM
```

**Test Cases:**
- [ ] View attendance calendar
- [ ] Filter by date range
- [ ] See check-in/check-out times
- [ ] View attendance statistics
- [ ] Generate attendance report

---

### ✓ Task Management Module
```bash
Expected: 9 tasks (7 new + original 2)
- Navigate to Tasks
- See tasks in different statuses: pending, in-progress, completed
```

**Test Cases:**
- [ ] View all tasks
- [ ] Filter by status (pending, in-progress, completed)
- [ ] Create new task
- [ ] Assign task to employee
- [ ] Update task status
- [ ] View task details

---

### ✓ Inventory Module
```bash
Expected: 18 inventory usage logs
- Go to Inventory
- See inventory items with usage history
```

**Test Cases:**
- [ ] View inventory list
- [ ] Check usage logs
- [ ] See borrow/return dates
- [ ] View employee who borrowed item
- [ ] Check current stock levels

---

### ✓ Letter Management Module (Surat Resmi)
```bash
Expected: 4 letters with different statuses

Letter Status Breakdown:
- Pending: 1 letter (needs approval)
- Approved: 2 letters (ready to print)
- Printed: 1 letter (completed)
```

**Test Cases:**
- [ ] View all letters
- [ ] Filter by status
- [ ] Create new letter (as Power User)
- [ ] Approve/Reject pending letter (as Power User)
- [ ] Print approved letter
- [ ] View letter details
- [ ] Export letter to PDF

**Letter Workflow Demo:**
1. Go to Letters → Archives
2. See pending letters awaiting approval
3. Click on pending letter
4. Review content
5. Approve letter (Power User only)
6. Print letter
7. View status change

---

### ✓ Digital Signature Module
```bash
Expected: 3 digital signatures (on approved letters)
```

**Test Cases:**
- [ ] View Signature Logs
- [ ] See verified signatures list
- [ ] View signature details
- [ ] Check verification history
- [ ] Download signed document (PDF)
- [ ] Verify signature authenticity

**Signature Verification Demo:**
1. Go to Signature Logs
2. See signatures with verification status
3. Click on signature
4. View signature metadata:
   - Signed date
   - Signed by (user)
   - Verification status: ✓ Verified
   - Verified by: Power User
   - SHA-256 Hash
   - IP Address: 127.0.0.1
5. Download PDF with signature

---

## Module-by-Module Testing

### Employee Module
```
Route: /employees
Features:
- CRUD operations
- Department assignment
- Role assignment
- Supervisor assignment
- Presence tracking
```

### Department Module
```
Route: /departments
Features:
- View departments: HR, IT, Sales, Finance, Marketing
- Employee count per department
- Department status
```

### Role Module
```
Route: /roles
Features:
- View roles: Manager, Developer, Salesperson, HR, Finance Manager, Power User
- Assign roles to employees
```

### Presence Module
```
Route: /presences
Features:
- Check-in/Check-out tracking
- Attendance statistics
- Calendar view
- Date range filtering
- Export attendance reports
```

### Task Module
```
Route: /tasks
Features:
- Create tasks
- Assign to employees
- Track status
- Set due dates
- Filter by status
```

### Inventory Module
```
Route: /inventories
Features:
- View inventory items
- Track usage logs
- See borrow/return history
- Current stock levels
- Employee who borrowed item
```

### Letter Module (Surat Resmi)
```
Route: /letters
Features:
- Create new letters
- Template selection
- Letter numbering (001/HR/12/2025)
- Status workflow: Draft → Pending → Approved → Printed
- Approval workflow (Power User/HR approval)
- PDF export
```

### Signature Module
```
Route: /signatures
Features:
- Draw signature on canvas
- Cryptographic signing (SHA-256)
- Verification workflow
- Audit trail
- Signature logs
- Download signed PDF
```

---

## Performance Checks

### Database Verification
```bash
# Run from project root:
php artisan tinker

# Check data counts:
>>> App\Models\Employee::count()
9

>>> App\Models\Presence::count()
200

>>> App\Models\Task::count()
9

>>> App\Models\Letter::count()
4

>>> App\Models\Signature::count()
3
```

### API Response Times
- Employee list: < 500ms
- Presence records: < 1000ms
- Letters: < 500ms
- Signatures: < 500ms

---

## Common Issues & Solutions

### Issue: 403 Unauthorized
**Solution:** Make sure you're logged in as Power User (admin@example.com)

### Issue: Letter not showing in list
**Solution:** Check filter status. Pending letters show in "Archives"

### Issue: Signature not verifying
**Solution:** Ensure letter is approved before signing. Only approved letters can be signed.

### Issue: Employee has no presence records
**Solution:** Regenerate data using:
```bash
php artisan db:seed --class=DummyDataForDashboardSeeder
```

---

## Data Summary

| Module | Count | Status |
|---|---|---|
| Employees | 9 | ✓ Active |
| Departments | 5 | ✓ Active |
| Roles | 6 | ✓ Active |
| Presences | 200+ | ✓ Last 30 days |
| Tasks | 9 | ✓ Mixed statuses |
| Inventory Items | - | ✓ With usage logs |
| Usage Logs | 18 | ✓ Active |
| Letters | 4 | ✓ Mixed statuses |
| Signatures | 3 | ✓ Verified |

---

## Next Steps After Testing

1. **Create Custom Reports**
   - Attendance trends
   - Task completion rates
   - Inventory utilization

2. **Automate Workflows**
   - Auto-approve certain letters
   - Scheduled reports
   - Notification reminders

3. **Add Advanced Features**
   - Leave management approval
   - Payroll integration
   - Performance reviews

4. **Integration**
   - Email notifications
   - SMS alerts
   - Calendar sync

---

## Support

For issues or questions, check:
1. DUMMY_DATA_SUMMARY.md - Data overview
2. Application logs: `storage/logs/`
3. Database queries in Laravel Debugbar

---

**Last Updated**: December 4, 2025
**Recommended Browser**: Chrome/Firefox (Latest)
**Database**: MySQL 8.0+
**PHP**: 8.1+
**Laravel**: 11.x
