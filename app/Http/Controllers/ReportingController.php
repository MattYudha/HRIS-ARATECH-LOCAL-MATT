<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeKPIRecord;
use App\Models\KPI;
use App\Models\PerformanceReview;
use App\Models\Incident;
use App\Models\LetterConfiguration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportingController extends Controller
{
    /**
     * Monthly Performance Recap
     */
    public function monthlyRecap()
    {
        $user = Auth::user();
        $roleTitle = $user->employee?->role->title ?? null;
        $period = request('period', now()->format('Y-m'));
        $department = $user->employee?->department;

        // Access: Manager / Unit Head/HR Administrator/Super Admin see broader data; other roles see diri sendiri
        if (!in_array($roleTitle, ['Manager / Unit Head', 'HR Administrator', 'Super Admin'])) {
            if (!$user->employee) {
                abort(403, 'Unauthorized access');
            }
            $employees = collect([$user->employee]);
        } else {
            // Get employees (department-based for managers, all for HR Administrator/Super Admin)
            if ($roleTitle === 'Manager / Unit Head') {
                $employees = Employee::where('department_id', $department->id ?? 0)->get();
            } else {
                $employees = Employee::all();
            }
        }

        // Get KPI records using Eloquent with eager loading to avoid N+1
        $employeeIds = $employees->pluck('id');
        $allRecords = EmployeeKPIRecord::whereIn('employee_id', $employeeIds)
            ->where('period', $period)
            ->get()
            ->groupBy('employee_id');

        $kpiData = [];
        foreach ($employees as $emp) {
            $records = $allRecords->get($emp->id, collect());

            if ($records->count() > 0) {
                // Use centralized weighted calculation
                $summary = \App\Services\KPICalculationService::calculateWeightedScore($records);
                
                $kpiData[] = [
                    'employee' => $emp,
                    'composite_score' => $summary['score'],
                    'performance_level' => $summary['level'],
                    'achievements' => $records->where('status', 'achieved')->count(),
                    'warnings' => $records->where('status', 'warning')->count(),
                    'critical' => $records->where('status', 'critical')->count(),
                ];
            }
        }

        // Sort by composite score
        usort($kpiData, function($a, $b) {
            return $b['composite_score'] <=> $a['composite_score'];
        });

        return view('reports.monthly-recap', compact('period', 'kpiData'));
    }

    /**
     * Executive Dashboard
     */
    public function executiveDashboard()
    {
        $user = Auth::user();
        $roleTitle = $user->employee?->role->title ?? null;

        if (!in_array($roleTitle, ['HR Administrator', 'Super Admin'])) {
            abort(403, 'Only HR Administrator can access executive dashboard');
        }

        $period = request('period', now()->format('Y-m'));
        $departmentFilter = request('department_id');
        $roleFilter = request('role_id');

        // Get all relevant employees first
        $employeeQuery = Employee::query();
        if ($departmentFilter) {
            $employeeQuery->where('department_id', $departmentFilter);
        }
        if ($roleFilter) {
            $employeeQuery->where('role_id', $roleFilter);
        }
        $employees = $employeeQuery->get();

        // Get all records for these employees in this period
        $allRecords = EmployeeKPIRecord::with('kpi')
            ->whereIn('employee_id', $employees->pluck('id'))
            ->where('period', $period)
            ->get()
            ->groupBy('employee_id');

        // Calculate weighted scores for each employee
        $performanceData = $employees->map(function($emp) use ($allRecords) {
            $records = $allRecords->get($emp->id, collect());
            if ($records->isEmpty()) {
                return null;
            }
            $summary = \App\Services\KPICalculationService::calculateWeightedScore($records);
            return (object) [
                'employee' => $emp,
                'composite_score' => $summary['score'],
                'performance_level' => $summary['level'],
            ];
        })->filter()->sortByDesc('composite_score');

        // Top performers
        $topPerformers = $performanceData->take(5);

        // Bottom performers
        $bottomPerformers = $performanceData->reverse()->take(5);

        // Department averages
        $departments = [];
        $allDepartments = \App\Models\Department::has('employees')->get();
        
        foreach ($allDepartments as $dept) {
            $deptEmployees = $dept->employees;
            $deptScores = $performanceData->whereIn('employee.id', $deptEmployees->pluck('id'));
            
            if ($deptScores->isEmpty()) {
                continue;
            }
            
            $departments[] = [
                'name' => $dept->name,
                'avg_score' => round($deptScores->avg('composite_score'), 2),
                'employee_count' => $deptScores->count(),
            ];
        }

        // Overall statistics
        $totalEmployees = Employee::count();
        $excellentCount = $performanceData->where('performance_level', 'excellent')->count();
        $goodCount = $performanceData->where('performance_level', 'good')->count();
        $satisfactoryCount = $performanceData->where('performance_level', 'satisfactory')->count();
        $needsImprovementCount = $performanceData->where('performance_level', 'needs_improvement')->count();
        $unsatisfactoryCount = $performanceData->where('performance_level', 'unsatisfactory')->count();

        // Recent incidents
        $recentIncidents = Incident::where('status', '!=', 'resolved')
            ->orderByDesc('incident_date')
            ->limit(5)
            ->with('employee')
            ->get();

        // Pass departments and roles for filter dropdowns
        $allDepartments = \App\Models\Department::orderBy('name')->get();
        $allRoles = \App\Models\Role::orderBy('title')->get();

        return view('reports.executive-dashboard', compact(
            'period',
            'topPerformers',
            'bottomPerformers',
            'departments',
            'totalEmployees',
            'excellentCount',
            'goodCount',
            'recentIncidents',
            'allDepartments',
            'allRoles',
            'departmentFilter',
            'roleFilter'
        ));
    }

    /**
     * Export KPI Report to PDF
     */
    public function exportPDF($id)
    {
        $user = Auth::user();
        $employee = Employee::findOrFail($id);
        $config = LetterConfiguration::first() ?: new LetterConfiguration([
            'company_name' => 'ARATECHNOLOGY',
            'company_address' => 'Jakarta, Indonesia',
        ]);

        if ($user->id !== $employee->user_id && !in_array($user->employee?->role->title ?? null, ['HR Administrator', 'Super Admin', 'Manager / Unit Head'])) {
            abort(403, 'Unauthorized');
        }

        $period = request('period', now()->format('Y-m'));
        $records = EmployeeKPIRecord::where('employee_id', $employee->id)
            ->where('period', $period)
            ->get();

        if ($records->count() === 0) {
            abort(404, 'No KPI records found for this employee in the specified period');
        }

        // Use centralized weighted calculation for the aggregate
        $summary = \App\Services\KPICalculationService::calculateWeightedScore($records);
        
        $record = (object) [
            'employee' => $employee,
            'period' => $period,
            'composite_score' => $summary['score'],
            'performance_level' => $summary['level'],
            'created_at' => now(),
        ];
        
        // Calculate category scores (averages for detail)
        $categoryScores = [];
        foreach ($records as $r) {
            $kpi = $r->kpi;
            if ($kpi) {
                if (!isset($categoryScores[$kpi->category])) {
                    $categoryScores[$kpi->category] = [];
                }
                $categoryScores[$kpi->category][] = $r->composite_score;
            }
        }
        
        $record->attendance_score = !empty($categoryScores['Attendance']) ? array_sum($categoryScores['Attendance']) / count($categoryScores['Attendance']) : 0;
        $record->tasks_score = !empty($categoryScores['Productivity']) ? array_sum($categoryScores['Productivity']) / count($categoryScores['Productivity']) : 0;
        $record->compliance_score = !empty($categoryScores['Department']) ? array_sum($categoryScores['Department']) / count($categoryScores['Department']) : 0;
        $record->quality_score = !empty($categoryScores['Quality']) ? array_sum($categoryScores['Quality']) / count($categoryScores['Quality']) : 0;
        $record->conduct_score = !empty($categoryScores['Behavior']) ? array_sum($categoryScores['Behavior']) / count($categoryScores['Behavior']) : 0;
        
        // Calculate real metrics from service
        $kpiService = new \App\Services\KPICalculationService($employee, $period);
        $flatMetrics = $kpiService->getFlatMetrics();
        
        $record->present_days = $flatMetrics['attendance.present_days'] ?? 0;
        $record->absent_days = $flatMetrics['attendance.absent_days'] ?? 0;
        $record->late_count = $flatMetrics['attendance.late_count'] ?? 0;
        $record->tasks_completed = $flatMetrics['productivity.completed_tasks_count'] ?? 0;
        $record->on_time_percentage = $flatMetrics['productivity.on_time_delivery_rate'] ?? 0;

        $incidents = Incident::where('employee_id', $employee->id)
            ->orderByDesc('incident_date')
            ->get();

        $pdf = Pdf::loadView('reports/kpi-pdf', compact('record', 'incidents', 'config'));

        return $pdf->download('KPI_Report_' . $employee->fullname . '_' . $period . '.pdf');
    }

    /**
     * Export Monthly Recap to CSV
     */
    public function exportCSV()
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            abort(403, 'User not linked to employee.');
        }

        $roleTitle = $employee->role->title ?? null;
        $period = request('period', now()->format('Y-m'));
        
        // Define data visibility based on role using Eloquent
        if (in_array($roleTitle, ['HR Administrator', 'Super Admin', 'Super Admin'])) {
            // HR Administrator/Super Admin/Super Admin see everything
            $records = EmployeeKPIRecord::with(['employee.department', 'kpi'])
                ->where('period', $period)
                ->orderBy('employee_id')
                ->orderBy('kpi_id')
                ->get();
        } elseif ($roleTitle === 'Manager / Unit Head') {
            // Manager / Unit Head see their department
            $records = EmployeeKPIRecord::with(['employee.department', 'kpi'])
                ->whereHas('employee', function($q) use ($employee) {
                    $q->where('department_id', $employee->department_id ?? 0);
                })
                ->where('period', $period)
                ->orderBy('employee_id')
                ->orderBy('kpi_id')
                ->get();
        } else {
            // Others only see their own
            $records = EmployeeKPIRecord::with(['employee.department', 'kpi'])
                ->where('employee_id', $employee->id)
                ->where('period', $period)
                ->orderBy('kpi_id')
                ->get();
        }

        $filename = 'KPI_Report_' . str_replace(' ', '_', $roleTitle) . '_' . $period . '.csv';
        $handle = fopen('php://memory', 'r+');

        // Header
        fputcsv($handle, [
            'Employee',
            'Department',
            'KPI',
            'Actual Value',
            'Target Value',
            'Status',
            'Performance Level',
            'Period'
        ]);

        // Data
        foreach ($records as $record) {
            fputcsv($handle, [
                $record->employee->fullname ?? 'N/A',
                $record->employee->department?->name ?? 'N/A',
                $record->kpi->name ?? 'N/A',
                $record->actual_value,
                $record->target_value,
                $record->status,
                $record->performance_level,
                $record->period
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
