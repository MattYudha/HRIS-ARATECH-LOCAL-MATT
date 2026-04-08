@extends('layouts.dashboard')

@section('content')
<div class="page-heading">
    <h3>Create Letter Template</h3>
</div>
<div class="page-content">
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('letter-templates.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Template Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="2">{{ old('description') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                            <select class="form-control" id="type" name="type" required>
                                <option value="official" {{ old('type') == 'official' ? 'selected' : '' }}>Official Letter</option>
                                <option value="memo" {{ old('type') == 'memo' ? 'selected' : '' }}>Memorandum</option>
                                <option value="notice" {{ old('type') == 'notice' ? 'selected' : '' }}>Notice</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Template Content <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="content" name="content" rows="10" required>{{ old('content') }}</textarea>
                            <small class="form-text text-muted">You can use HTML tags for formatting</small>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Create Template</button>
                            <a href="{{ route('letter-templates.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
