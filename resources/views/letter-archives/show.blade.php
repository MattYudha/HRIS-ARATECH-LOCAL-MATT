@extends('layouts.dashboard')

@section('content')



<div class="page-heading">
    <h3>Archive Details - {{ $letterArchive->month }}/{{ $letterArchive->year }}</h3>
</div>
<div class="page-content">
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <a href="{{ route('letter-archives.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Letters</h5>
                    <h2 class="text-primary">{{ $letterArchive->total_letters }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Approved</h5>
                    <h2 class="text-success">{{ $letterArchive->approved_letters }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Printed</h5>
                    <h2 class="text-info">{{ $letterArchive->printed_letters }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Generated</h5>
                    <p class="small">{{ $letterArchive->generated_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5>Summary</h5>
        </div>
        <div class="card-body">
            {!! $letterArchive->summary ?? '<p class="text-muted">No summary available</p>' !!}
        </div>
    </div>
</div>
</div>
@endsection
