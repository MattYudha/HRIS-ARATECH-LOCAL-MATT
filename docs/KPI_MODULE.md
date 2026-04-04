# KPI & Reporting Module Documentation

## Overview
The KPI & Reporting Module is a comprehensive performance management system that tracks, calculates, and reports on employee Key Performance Indicators (KPIs) across 7 categories with 26+ individual metrics.

## Module Components

### 1. Database Layer

#### Tables
- **kpis**: Master KPI definitions with categories, formulas, targets, and weights
- **employee_kpi_records**: Monthly/periodic KPI snapshots for each employee
- **performance_reviews**: Qualitative reviews paired with KPI data
- **incidents**: Behavioral incidents and compliance tracking

#### Relationships
- Employee → Many EmployeeKPIRecords
- Employee → Many PerformanceReviews  
- Employee → Many Incidents
- KPI → Many EmployeeKPIRecords

### 2. Models

#### EmployeeKPIRecord
Stores individual KPI metrics for each employee per period.

**Key Attributes:**
- `employee_id` - FK to employees
- `kpi_id` - FK to kpis
- `period` - YYYY-MM format
- `actual_value` - Measured performance
- `target_value` - Expected performance
- `status` - achieved|warning|critical|na
- `composite_score` - Weighted aggregate score (0-100)
- `performance_level` - excellent|good|satisfactory|needs_improvement|unsatisfactory

**Methods:**
- `getAchievementPercentage()` - Returns (actual/target)*100
- `getVariance()` - Returns actual - target

#### KPI
Master KPI definitions.

**Categories (7 total):**
1. **Attendance** (5 metrics)
   - Attendance Rate, Punctuality, Tardiness, Absence Rate, Early Checkout

2. **Productivity** (5 metrics)
   - Task Completion Rate, On-Time Delivery, Overdue Rate, Active Tasks, Pending Tasks

3. **Leave** (2 metrics)
   - Total Leave Days, Leave Types breakdown

4. **Salary** (2 metrics)
   - Base Salary, Salary Grade

5. **Department** (2 metrics)
   - Department Avg Attendance, Department Avg Task Completion

6. **Behavior** (4 metrics)
   - Compliance Score, Document Signing Speed, Signature Verification Rate, Incident Count

7. **Quality** (2 metrics)
   - Quality metrics based on task completion quality

### 3. Controllers

#### KPIController
Manages KPI data display and calculations.

**Actions:**
- `dashboard()` - Personal KPI dashboard for current user
- `show($id)` - Individual employee KPI report (with auth checks)
- `team()` - Manager's team KPI overview (manager+ only)
- `department()` - Department-wide KPI analysis (manager+ only)
- `recalculate($id)` - Manual KPI recalculation (HR+ only)

#### ReportingController
Generates reports and exports.

**Actions:**
- `monthlyRecap()` - Monthly performance summary (manager+ only)
- `executiveDashboard()` - HR executive dashboard (HR+ only)
- `exportPDF($id)` - Download individual KPI report as PDF
- `exportCSV()` - Export all KPI data as CSV

### 4. Services

#### KPICalculationService
Calculates all KPI metrics from raw data.

**Calculation Methods:**
- `calculateAttendanceMetrics()` - From presence records
- `calculateProductivityMetrics()` - From task records
- `calculateLeaveMetrics()` - From leave requests
- `calculateSalaryMetrics()` - From employee payroll
- `calculateDepartmentMetrics()` - Aggregated department stats
- `calculateBehaviorMetrics()` - From incidents and signatures
- `calculateQualityMetrics()` - From task completion quality

**Composite Score Formula:**
```
Score = (Attendance×0.25 + Productivity×0.35 + Compliance×0.15 + Quality×0.15 + Conduct×0.10) / 100
```

### 5. Views

#### KPI Views
Located in `resources/views/kpi/`

- **dashboard.blade.php** - Personal KPI dashboard with summary cards and metric tables
- **show.blade.php** - Detailed individual KPI report with period selector
- **team.blade.php** - Team performance rankings with distribution analysis  
- **department.blade.php** - Department summary with statistics and employee rankings

#### Reporting Views
Located in `resources/views/reports/`

- **monthly-recap.blade.php** - Monthly performance summary with rankings
- **executive-dashboard.blade.php** - Executive overview with top/bottom performers
- **kpi-pdf.blade.php** - Professional PDF template for KPI reports

**Features in All Views:**
- Period selector (month picker) for historical data
- Color-coded performance badges
- Progress bars with percentages
- Statistical calculations
- Export functionality

### 6. Routes

```
GET     /kpi/dashboard                          → KPIController@dashboard (auth)
GET     /kpi/employee/{id}                      → KPIController@show (auth)
GET     /kpi/team                               → KPIController@team (manager+)
GET     /kpi/department                         → KPIController@department (manager+)
POST    /kpi/recalculate/{id}                   → KPIController@recalculate (hr+)

GET     /reports/monthly-recap                  → ReportingController@monthlyRecap (manager+)
GET     /reports/executive                      → ReportingController@executiveDashboard (hr+)
GET     /reports/{id}/export-pdf                → ReportingController@exportPDF (auth)
GET     /reports/export-csv                     → ReportingController@exportCSV (manager+)
```

## Authorization Matrix

| Feature | Employee | Manager | HR | Power User |
|---------|----------|---------|----|----|
| View Own KPI | ✓ | ✓ | ✓ | ✓ |
| View Team KPI | ✗ | ✓ | ✓ | ✓ |
| View Department KPI | ✗ | ✓ | ✓ | ✓ |
| Executive Dashboard | ✗ | ✗ | ✓ | ✓ |
| Export PDF | ✓ (own) | ✓ (own/team) | ✓ | ✓ |
| Export CSV | ✗ | ✓ | ✓ | ✓ |
| Recalculate KPI | ✗ | ✗ | ✓ | ✓ |

## Performance Levels

Based on composite score:

| Level | Range | Badge Color |
|-------|-------|------------|
| Excellent | 90-100 | Green |
| Good | 75-89 | Blue |
| Satisfactory | 60-74 | Yellow |
| Needs Improvement | 45-59 | Orange |
| Unsatisfactory | <45 | Red |

## Data Entry & Calculation

### Automatic Calculation
KPI values are calculated from:
- **Attendance**: Presence records (check-in/out times)
- **Productivity**: Task statuses and completion dates
- **Leave**: Approved leave requests
- **Salary**: Employee payroll data
- **Department**: Aggregated from employee metrics
- **Behavior**: Incidents and digital signatures
- **Quality**: Task completion quality metrics

### Manual Adjustment
HR can manually recalculate KPIs via `/kpi/recalculate/{id}` endpoint.

## Seeders

### KPISeeder
Seeds 26 master KPI definitions across 7 categories:
```bash
php artisan db:seed --class=KPISeeder
```

### EmployeeKPIRecordsSeeder
Generates realistic KPI records for all employees (current + 2 previous months):
```bash
php artisan db:seed --class=EmployeeKPIRecordsSeeder
```

**Features:**
- Realistic distribution (70% achievement, 20% warning, 10% critical)
- Category-specific calculation algorithms
- Weighted composite scoring
- Performance level assignment
- Historical data for trend analysis

## Usage Examples

### View Personal KPI Dashboard
```
GET /kpi/dashboard
```
Shows current month's personal KPI with summary cards and detailed metrics.

### View Team Performance
```
GET /kpi/team?period=2025-12
```
Shows all team members ranked by performance in December 2025.

### Export Team Data
```
GET /reports/export-csv?period=2025-12
```
Downloads all team KPI data as CSV file.

### Generate PDF Report
```
GET /reports/{employee_id}/export-pdf?period=2025-12
```
Downloads individual KPI report as professional PDF.

## Integration Points

### Presence Module
Used for attendance metrics calculation.

### Task Management Module
Used for productivity metrics calculation.

### Leave Management Module
Used for leave metrics calculation.

### Incident Management Module
Used for behavior metrics and compliance scoring.

### Digital Signature Module
Used for conduct and compliance metrics.

## Features

### Dashboard Features
- **Summary Cards**: Composite score, performance level, achievements, warnings
- **Category Breakdowns**: Detailed metrics for each KPI category
- **Performance Visualization**: Progress bars, status badges, trend indicators
- **Period Navigation**: Month selector for historical data
- **Export Options**: PDF and CSV export for reports
- **Incident Tracking**: Lists active/resolved incidents affecting performance

### Reporting Features
- **Performance Rankings**: Top and bottom performers visualization
- **Distribution Analysis**: Performance level distribution charts
- **Statistics**: Average, highest, lowest, median, standard deviation
- **Department Comparison**: Cross-department performance analysis
- **Trend Analysis**: Historical performance tracking
- **Executive Insights**: Key metrics for decision making

## Customization

### Adding New KPI Categories
1. Create new KPI records in database
2. Add calculation method to KPICalculationService
3. Update weight formula in calculateCompositeScore()
4. Update seeder with new metrics

### Modifying Weight Formula
Edit `KPICalculationService::calculateCompositeScore()` method to adjust category weights.

### Changing Performance Levels
Edit `KPICalculationService::getPerformanceLevel()` method to adjust score thresholds.

### Customizing Views
All views use Bootstrap and Font Awesome. Edit individual view files to customize styling.

## Maintenance

### Database Optimization
- Indexes on: employee_id, period, created_at
- Unique constraint on: (employee_id, kpi_id, period)

### Caching
Consider implementing caching for:
- Executive dashboard aggregations
- Department-wide statistics
- Historical trend calculations

### Archival
Implement periodic archival for old KPI records to maintain performance.

## Troubleshooting

### KPI Records Not Showing
1. Verify presence/task/leave records exist
2. Run seeder: `php artisan db:seed --class=EmployeeKPIRecordsSeeder`
3. Check employee-user relationship
4. Verify period format (YYYY-MM)

### Dashboard Not Loading
1. Clear cache: `php artisan cache:clear`
2. Verify user has employee record
3. Check authorization middleware
4. Review logs for errors

### Export Issues
1. Verify Barryvdh\DomPDF is installed
2. Check file permissions for export directory
3. Ensure sufficient server memory for large exports

## Performance Considerations

- KPI calculations are on-demand (not cached by default)
- Large exports may require increased PHP memory
- Consider pagination for executive dashboard
- Pre-calculate monthly snapshots during off-peak hours

## Future Enhancements

- [ ] Real-time KPI dashboard with WebSocket updates
- [ ] Advanced analytics with ML predictions
- [ ] Mobile app integration
- [ ] Goal-setting module with KPI alignment
- [ ] Performance improvement plan tracking
- [ ] 360-degree feedback integration
- [ ] Compensation tie-in with KPI performance
- [ ] Automated alerting for performance drops
