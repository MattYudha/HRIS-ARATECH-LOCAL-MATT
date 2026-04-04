@extends('layouts.dashboard')

@section('content')



<div class="page-heading">
    <div class="page-title mb-4">
        <div class="row">
            <div class="col-md-6">
                <h3>Create Employee</h3>
                <p class="text-subtitle text-muted">Add new employee data</p>
            </div>
            <div class="col-md-6 text-md-end">
                <nav aria-label="breadcrumb" class="breadcrumb-header">
                    <ol class="breadcrumb justify-content-md-end">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('employees.index') }}">Employees</a>
                        </li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-12">

                <div class="card shadow-sm">
                    <div class="card-body">

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('employees.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <!-- LEFT -->
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">NIK (Nomor Induk Karyawan)</label>
                                                <input type="text" name="nik"
                                                    class="form-control"
                                                    value="{{ old('nik') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">NPWP</label>
                                                <input type="text" name="npwp"
                                                    class="form-control"
                                                    value="{{ old('npwp') }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Fullname</label>
                                        <input type="text" name="fullname"
                                            class="form-control"
                                            value="{{ old('fullname') }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Status Karyawan</label>
                                        <select name="employee_status" class="form-select" required>
                                            @foreach(\App\Models\Employee::getAvailableStatuses() as $key => $label)
                                                <option value="{{ $key }}" {{ old('employee_status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email"
                                            class="form-control"
                                            value="{{ old('email') }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Phone Number</label>
                                        <input type="text" name="phone_number"
                                            class="form-control"
                                            value="{{ old('phone_number') }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Address</label>
                                        <input type="text" name="address"
                                            class="form-control"
                                            value="{{ old('address') }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Place of Birth</label>
                                        <input type="text" name="place_of_birth"
                                            class="form-control"
                                            value="{{ old('place_of_birth') }}">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Birth Date</label>
                                        <input type="date" name="birth_date"
                                            class="form-control"
                                            value="{{ old('birth_date') }}" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Gender</label>
                                                <select name="gender" class="form-select">
                                                    <option value="">-- Select --</option>
                                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Religion</label>
                                                <input type="text" name="religion" class="form-control" value="{{ old('religion') }}" placeholder="e.g. Islam">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Marital Status</label>
                                                <select name="marital_status" class="form-select">
                                                    <option value="">-- Select --</option>
                                                    <option value="single" {{ old('marital_status') == 'single' ? 'selected' : '' }}>Single</option>
                                                    <option value="married" {{ old('marital_status') == 'married' ? 'selected' : '' }}>Married</option>
                                                    <option value="divorced" {{ old('marital_status') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">User Password</label>
                                        <input type="password" name="password"
                                            class="form-control"
                                            placeholder="Min. 8 characters" required>
                                        <small class="text-muted">This password will be used for the employee's login account.</small>
                                    </div>
                                </div>

                                <!-- RIGHT -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Hire Date</label>
                                        <input type="date" name="hire_date"
                                            class="form-control"
                                            value="{{ old('hire_date') }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Department</label>
                                        <select name="department_id" id="department_id" class="form-select" required>
                                            <option value="">-- Select Department --</option>
                                            @foreach($departments as $department)
                                                <option value="{{ $department->id }}"
                                                    data-manager-id="{{ $department->manager_id }}"
                                                    {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                                    {{ $department->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Office Location</label>
                                        <select name="office_location_id" class="form-select" required>
                                            <option value="">-- Select Office Location --</option>
                                            @foreach($officeLocations as $officeLocation)
                                                <option value="{{ $officeLocation->id }}"
                                                    {{ old('office_location_id') == $officeLocation->id ? 'selected' : '' }}>
                                                    {{ $officeLocation->name }} ({{ $officeLocation->type_label }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Lokasi kerja ini dipakai untuk pengaturan WFO dan presensi.</small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Role</label>
                                        <select name="role_id" class="form-select" required>
                                            <option value="">-- Select Role --</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}"
                                                    {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                                    {{ $role->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Resign Date</label>
                                                <input type="date" name="resign_date" class="form-control" value="{{ old('resign_date') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Permanent Date</label>
                                                <input type="date" name="permanent_date" class="form-control" value="{{ old('permanent_date') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Contract Expiry</label>
                                                <input type="date" name="contract_expiry" class="form-control" value="{{ old('contract_expiry') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Supervisor (for KPI & Approvals)</label>
                                        <select name="supervisor_id" id="supervisor_id" class="form-select">
                                            <option value="">-- Select Supervisor --</option>
                                            @foreach($employees as $emp)
                                                <option value="{{ $emp->id }}"
                                                    {{ old('supervisor_id') == $emp->id ? 'selected' : '' }}>
                                                    {{ $emp->fullname }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">KPI and approval requests will be sent to this person.</small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-select" required>
                                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>
                                                Active
                                            </option>
                                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                                Inactive
                                            </option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Salary</label>
                                        <input type="number" name="salary"
                                            class="form-control"
                                            value="{{ old('salary') }}" required>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left"></i> Back
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Save Employee
                                </button>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const departmentSelect = document.getElementById('department_id');
    const supervisorSelect = document.getElementById('supervisor_id');

    departmentSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const managerId = selectedOption.getAttribute('data-manager-id');

        if (managerId) {
            // Auto-select the manager in the supervisor dropdown
            supervisorSelect.value = managerId;
        } else {
            // Optional: reset or leave as is if no manager assigned to department
            // supervisorSelect.value = "";
        }
    });

    // Trigger on load if there's already a selection (e.g., from old input)
    if (departmentSelect.value) {
        const selectedOption = departmentSelect.options[departmentSelect.selectedIndex];
        const managerId = selectedOption.getAttribute('data-manager-id');
        if (managerId && !supervisorSelect.value) {
            supervisorSelect.value = managerId;
        }
    }
});
</script>
@endpush

@endsection
