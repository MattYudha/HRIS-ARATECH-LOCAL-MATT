<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>KPI Report - {{ $record->employee->fullname }}</title>
    <style>
        @page {
            margin: 1cm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.4;
            font-size: 11pt;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .company-name {
            font-size: 18pt;
            font-weight: bold;
            color: #1a365d;
            text-transform: uppercase;
        }
        .company-info {
            font-size: 9pt;
            color: #4a5568;
        }
        .report-title {
            text-align: center;
            font-size: 16pt;
            font-weight: bold;
            margin: 20px 0;
            color: #2b6cb0;
            border-bottom: 2px solid #2b6cb0;
            padding-bottom: 5px;
        }
        .section-header {
            background-color: #ebf8ff;
            color: #2c5282;
            padding: 8px 12px;
            font-weight: bold;
            font-size: 12pt;
            margin-bottom: 10px;
            border-left: 5px solid #3182ce;
        }
        .info-table td {
            padding: 6px 10px;
            border-bottom: 1px solid #edf2f7;
        }
        .label {
            font-weight: bold;
            width: 30%;
            color: #4a5568;
            background-color: #f7fafc;
        }
        .value {
            width: 70%;
        }
        .summary-box {
            background-color: #2b6cb0;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            margin: 20px 0;
        }
        .summary-score {
            font-size: 32pt;
            font-weight: bold;
            display: block;
        }
        .summary-label {
            font-size: 12pt;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .kpi-table th {
            background-color: #edf2f7;
            color: #2d3748;
            padding: 10px;
            text-align: left;
            font-size: 10pt;
            border-bottom: 2px solid #cbd5e0;
        }
        .kpi-table td {
            padding: 10px;
            border-bottom: 1px solid #edf2f7;
            vertical-align: middle;
        }
        .progress-container {
            width: 100%;
            background-color: #edf2f7;
            height: 12px;
            border-radius: 6px;
            overflow: hidden;
        }
        .progress-bar {
            height: 100%;
            background-color: #4299e1;
        }
        .score-box {
            font-weight: bold;
            text-align: right;
            color: #2b6cb0;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 9pt;
            color: white;
            font-weight: bold;
        }
        .badge-excellent { background-color: #38a169; }
        .badge-good { background-color: #3182ce; }
        .badge-satisfactory { background-color: #d69e2e; }
        .badge-improvement { background-color: #dd6b20; }
        .badge-poor { background-color: #e53e3e; }

        .signature-table {
            margin-top: 50px;
        }
        .signature-table td {
            width: 50%;
            text-align: center;
            vertical-align: bottom;
            padding-top: 80px;
        }
        .signature-line {
            width: 70%;
            border-top: 1px solid #333;
            margin: 0 auto;
            margin-bottom: 5px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 8pt;
            color: #a0aec0;
            text-align: center;
            border-top: 1px solid #edf2f7;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td width="150">
                @if($config->logo_path && file_exists(public_path($config->logo_path)))
                    <img src="{{ public_path($config->logo_path) }}" height="60">
                @else
                    <img src="{{ public_path('img/HRIS ARATECH logo tr.png') }}" height="60">
                @endif
            </td>
            <td align="right">
                <div class="company-name">{{ $config->company_name ?? 'ARATECHNOLOGY' }}</div>
                <div class="company-info">
                    {{ $config->company_address }}<br>
                    @if($config->company_phone) Tel: {{ $config->company_phone }} @endif
                    @if($config->company_email) | Email: {{ $config->company_email }} @endif
                </div>
            </td>
        </tr>
    </table>

    <div class="report-title">MONTHLY KPI REPORT</div>

    <div class="section-header">Employee Personal Data</div>
    <table class="info-table">
        <tr>
            <td class="label">Name</td>
            <td class="value">{{ $record->employee->fullname }}</td>
            <td class="label">Period</td>
            <td class="value">{{ \Carbon\Carbon::createFromFormat('Y-m', $record->period)->format('F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Employee ID</td>
            <td class="value">{{ $record->employee->employee_id ?? 'ARA-'.str_pad($record->employee->id, 4, '0', STR_PAD_LEFT) }}</td>
            <td class="label">Department</td>
            <td class="value">{{ $record->employee->department->name }}</td>
        </tr>
        <tr>
            <td class="label">Position</td>
            <td class="value">{{ $record->employee->role?->title ?? 'N/A' }}</td>
            <td class="label">Supervisor</td>
            <td class="value">{{ $record->employee->supervisor?->fullname ?? 'Management' }}</td>
        </tr>
    </table>

    <div class="summary-box">
        <span class="summary-label">OVERALL PERFORMANCE SCORE</span>
        <span class="summary-score">{{ number_format($record->composite_score, 1) }}%</span>
        <span class="badge {{ 
            $record->composite_score >= 90 ? 'badge-excellent' : 
            ($record->composite_score >= 75 ? 'badge-good' : 
            ($record->composite_score >= 60 ? 'badge-satisfactory' : 
            ($record->composite_score >= 45 ? 'badge-improvement' : 'badge-poor')))
        }}">
            @if($record->composite_score >= 90) EXCELLENT PERFORMANCE
            @elseif($record->composite_score >= 75) GOOD PERFORMANCE
            @elseif($record->composite_score >= 60) SATISFACTORY PERFORMANCE
            @elseif($record->composite_score >= 45) NEEDS IMPROVEMENT
            @else UNSATISFACTORY PERFORMANCE
            @endif
        </span>
    </div>

    <div class="section-header">KPI Item Breakdown</div>
    <table class="kpi-table">
        <thead>
            <tr>
                <th width="30%">KPI Category</th>
                <th width="45%">Achievement Level</th>
                <th width="25%" align="right">Score (%)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Attendance</strong></td>
                <td>
                    <div class="progress-container">
                        <div class="progress-bar" style="width: {{ $record->attendance_score }}%;"></div>
                    </div>
                </td>
                <td align="right" class="score-box">{{ number_format($record->attendance_score, 1) }}%</td>
            </tr>
            <tr>
                <td><strong>Productivity / Tasks</strong></td>
                <td>
                    <div class="progress-container">
                        <div class="progress-bar" style="width: {{ $record->tasks_score }}%;"></div>
                    </div>
                </td>
                <td align="right" class="score-box">{{ number_format($record->tasks_score, 1) }}%</td>
            </tr>
            <tr>
                <td><strong>Quality</strong></td>
                <td>
                    <div class="progress-container">
                        <div class="progress-bar" style="width: {{ $record->quality_score }}%;"></div>
                    </div>
                </td>
                <td align="right" class="score-box">{{ number_format($record->quality_score, 1) }}%</td>
            </tr>
            <tr>
                <td><strong>Conduct & Behavior</strong></td>
                <td>
                    <div class="progress-container">
                        <div class="progress-bar" style="width: {{ $record->conduct_score }}%;"></div>
                    </div>
                </td>
                <td align="right" class="score-box">{{ number_format($record->conduct_score, 1) }}%</td>
            </tr>
            <tr>
                <td><strong>Department Compliance</strong></td>
                <td>
                    <div class="progress-container">
                        <div class="progress-bar" style="width: {{ $record->compliance_score }}%;"></div>
                    </div>
                </td>
                <td align="right" class="score-box">{{ number_format($record->compliance_score, 1) }}%</td>
            </tr>
        </tbody>
    </table>

    @if(isset($incidents) && $incidents->count() > 0)
    <div class="section-header" style="background-color: #fff5f5; color: #c53030; border-left-color: #f56565;">Recorded Incidents</div>
    <table class="kpi-table" style="font-size: 9pt;">
        <thead>
            <tr>
                <th width="15%">Date</th>
                <th width="20%">Type</th>
                <th width="15%">Severity</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach($incidents as $incident)
            <tr>
                <td>{{ $incident->incident_date->format('d/m/Y') }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $incident->type)) }}</td>
                <td>{{ ucfirst($incident->severity) }}</td>
                <td>{{ $incident->description }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <table class="signature-table">
        <tr>
            <td>
                <div class="signature-line"></div>
                <strong>{{ $record->employee->fullname }}</strong><br>
                Employee
            </td>
            <td>
                <div class="signature-line"></div>
                <strong>{{ $record->employee->supervisor?->fullname ?? 'Human Resources Department' }}</strong><br>
                Manager / Unit Head / HR AdministratorD
            </td>
        </tr>
    </table>

    <div class="footer">
        Generated on {{ date('d F Y H:i:s') }} | Ref: KPI/{{ $record->employee->id }}/{{ str_replace('-', '', $record->period) }}
    </div>
</body>
</html>
