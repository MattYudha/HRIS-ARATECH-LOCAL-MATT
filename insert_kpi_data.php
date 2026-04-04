<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';

use Illuminate\Support\Facades\DB;

$app->make('Illuminate\Contracts\Http\Kernel');
$db = DB::getFacadeRoot();

// Get all employees and KPIs
$employees = $db->table('employees')->get();
$kpis = $db->table('kpis')->get();

echo "Found " . count($employees) . " employees and " . count($kpis) . " KPIs\n";

$period = date('Y-m'); // Current month
$inserted = 0;
$skipped = 0;

foreach ($employees as $emp) {
    $categoryScores = [];
    
    foreach ($kpis as $kpi) {
        // Generate realistic value based on category
        $actualValue = generateValue($kpi->category, $kpi->target_value);
        $achievement = ($actualValue / $kpi->target_value) * 100;
        $status = $achievement >= 100 ? 'achieved' : ($achievement >= 80 ? 'warning' : 'critical');
        
        // Check if record already exists
        $exists = $db->table('employee_kpi_records')
            ->where('employee_id', $emp->id)
            ->where('kpi_id', $kpi->id)
            ->where('period', $period)
            ->exists();
        
        if (!$exists) {
            $db->table('employee_kpi_records')->insert([
                'employee_id' => $emp->id,
                'kpi_id' => $kpi->id,
                'period' => $period,
                'target_value' => $kpi->target_value,
                'actual_value' => $actualValue,
                'status' => $status,
                'performance_level' => 'pending',
                'comments' => ucfirst($status) . ' - ' . $kpi->name,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            $inserted++;
            
            // Collect for composite score
            if (!isset($categoryScores[$kpi->category])) {
                $categoryScores[$kpi->category] = [];
            }
            $categoryScores[$kpi->category][] = $actualValue;
        } else {
            $skipped++;
        }
    }
    
    // Calculate composite score
    $weights = [
        'Attendance' => 0.25,
        'Productivity' => 0.35,
        'Department' => 0.15,
        'Behavior' => 0.10,
        'Quality' => 0.15,
        'Leave' => 0.0,
        'Salary' => 0.0,
    ];
    
    $compositeScore = 0;
    foreach ($categoryScores as $category => $scores) {
        $avgScore = array_sum($scores) / count($scores);
        $weight = $weights[$category] ?? 0;
        $compositeScore += $avgScore * $weight;
    }
    
    // Determine performance level
    if ($compositeScore >= 90) {
        $performanceLevel = 'excellent';
    } elseif ($compositeScore >= 75) {
        $performanceLevel = 'good';
    } elseif ($compositeScore >= 60) {
        $performanceLevel = 'satisfactory';
    } elseif ($compositeScore >= 45) {
        $performanceLevel = 'needs_improvement';
    } else {
        $performanceLevel = 'unsatisfactory';
    }
    
    // Update composite scores
    $db->table('employee_kpi_records')
        ->where('employee_id', $emp->id)
        ->where('period', $period)
        ->update([
            'composite_score' => $compositeScore,
            'performance_level' => $performanceLevel,
        ]);
}

echo "\n✓ Inserted: $inserted KPI records\n";
echo "✓ Skipped: $skipped (already exist)\n";
echo "✓ Period: $period\n";
echo "\nDummy KPI data created successfully!\n";

function generateValue($category, $target)
{
    $rand = rand(1, 100);
    
    switch ($category) {
        case 'Attendance':
            // 70% hit target, 20% warning, 10% critical
            if ($rand <= 70) {
                return rand((int)($target * 0.95), (int)$target);
            } elseif ($rand <= 90) {
                return rand((int)($target * 0.80), (int)($target * 0.95));
            } else {
                return rand(0, (int)($target * 0.80));
            }
        
        case 'Productivity':
            // 65% hit target, 25% warning, 10% critical
            if ($rand <= 65) {
                return rand((int)($target * 0.90), (int)($target + 5));
            } elseif ($rand <= 90) {
                return rand((int)($target * 0.70), (int)($target * 0.90));
            } else {
                return rand(0, (int)($target * 0.70));
            }
        
        case 'Leave':
            // Lower is better - absences should be minimized
            return rand(0, (int)($target + 2));
        
        case 'Salary':
            // Consistent
            return $target;
        
        case 'Department':
            // 60% hit target
            if ($rand <= 60) {
                return rand((int)($target * 0.95), (int)$target);
            } elseif ($rand <= 90) {
                return rand((int)($target * 0.75), (int)($target * 0.95));
            } else {
                return rand(0, (int)($target * 0.75));
            }
        
        case 'Behavior':
            // 75% hit target
            if ($rand <= 75) {
                return rand((int)($target * 0.95), (int)$target);
            } elseif ($rand <= 95) {
                return rand((int)($target * 0.85), (int)($target * 0.95));
            } else {
                return rand(0, (int)($target * 0.85));
            }
        
        case 'Quality':
            // 70% hit target
            if ($rand <= 70) {
                return rand((int)($target * 0.93), (int)$target);
            } elseif ($rand <= 95) {
                return rand((int)($target * 0.75), (int)($target * 0.93));
            } else {
                return rand(0, (int)($target * 0.75));
            }
        
        default:
            return rand(60, 95);
    }
}
