@extends('layouts.dashboard')

@section('content')

<div class="page-heading">
    <div class="page-title mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h3>Organizational Chart</h3>
                <p class="text-subtitle text-muted">Visual representation of department hierarchy</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="{{ route('departments.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card shadow-sm">
            <div class="card-body">
                @if($departments->isEmpty())
                    <div class="alert alert-warning">No departments found.</div>
                @else
                    <div id="org_chart_div" style="width: 100%; height: 600px; overflow: auto;"></div>
                @endif
            </div>
        </div>
    </section>
</div>

@endsection

@push('scripts')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {packages:["orgchart"]});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Node');
        data.addColumn('string', 'Parent');
        data.addColumn('string', 'ToolTip');

        // Construct rows from PHP data
        var rows = [
            @foreach($departments as $dept)
                [
                    {
                        'v': '{{ $dept->id }}',
                        'f': `<div class="p-2 border rounded shadow-sm bg-white" style="min-width: 120px;">
                                <div class="fw-bold text-primary mb-1">{{ $dept->name }}</div>
                                <div class="small text-muted mb-1">
                                    <i class="bi bi-person-badge"></i> {{ $dept->manager ? $dept->manager->fullname : 'No Manager / Unit Head' }}
                                </div>
                                <div class="badge bg-light text-dark border">
                                    <i class="bi bi-people"></i> {{ $dept->employees->count() }}
                                </div>
                              </div>`
                    },
                    '{{ $dept->parent_id ?? "" }}',
                    '{{ $dept->name }}'
                ],
            @endforeach
        ];

        data.addRows(rows);

        // Create the chart.
        var chart = new google.visualization.OrgChart(document.getElementById('org_chart_div'));
        
        // Draw the chart, setting the allowHtml option to true for the tooltips.
        chart.draw(data, {'allowHtml':true, 'size': 'medium'});
    }
</script>
<style>
    /* Custom styles to override Google Charts inline styles if needed */
    .google-visualization-orgchart-node {
        border: none !important;
        background: transparent !important;
        box-shadow: none !important;
    }
</style>
@endpush
