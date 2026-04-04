@extends('layouts.dashboard')

@section('content')

@php
    $isAdmin = in_array(session('role'), ['HR Administrator', 'Super Admin']);
@endphp

<style>
    .profile-header {
        background: linear-gradient(90deg, #1e3a8a 0%, #3b82f6 100%);
        color: white;
        padding: 2rem;
        border-radius: 0.5rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 2rem;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid rgba(255, 255, 255, 0.3);
        background-color: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .profile-avatar i {
        font-size: 4rem;
        color: #9ca3af;
    }

    .profile-info h3 {
        color: #facc15;
        margin-bottom: 0.25rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .profile-info .emp-code {
        color: #facc15;
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .profile-info .position {
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .nav-tabs-custom .nav-link {
        border: none;
        border-bottom: 3px solid transparent;
        color: #6b7280;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        padding: 1rem 1.5rem;
    }

    .nav-tabs-custom .nav-link.active {
        color: #1e3a8a;
        border-bottom-color: #facc15;
        background: transparent;
    }

    .info-table th {
        width: 250px;
        font-weight: 500;
        color: #4b5563;
        border: none;
        padding: 0.75rem 0;
    }

    .info-table td {
        border: none;
        padding: 0.75rem 0;
        color: #1f2937;
    }
</style>

<div class="page-heading">
    <div class="profile-header shadow-sm">
        <div class="profile-avatar">
            @if($employee->profile_photo)
                <img src="{{ asset('storage/' . $employee->profile_photo) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
            @else
                <i class="bi bi-person-fill"></i>
            @endif
        </div>
        <div class="profile-info">
            <div class="emp-code">{{ $employee->emp_code ?? 'N/A' }}</div>
            <h3>{{ $employee->fullname }}</h3>
            <div class="position">
                {{ $employee->department->name ?? 'N/A' }} & {{ $employee->role->title ?? 'N/A' }}
            </div>
        </div>
            <div class="ms-auto align-self-start">
                <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-warning">
                    <i class="bi bi-pencil-square"></i> Edit Profile
                </a>
            </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body p-0">
                <ul class="nav nav-tabs nav-tabs-custom border-bottom" id="profileTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="working-tab" data-bs-toggle="tab" data-bs-target="#working" type="button" role="tab" aria-controls="working" aria-selected="true">Working Information</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab" aria-controls="personal" aria-selected="false">Personal Information</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="education-tab" data-bs-toggle="tab" data-bs-target="#education" type="button" role="tab" aria-controls="education" aria-selected="false">Education</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="family-tab" data-bs-toggle="tab" data-bs-target="#family" type="button" role="tab" aria-controls="family" aria-selected="false">Family Relation</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="career-tab" data-bs-toggle="tab" data-bs-target="#career" type="button" role="tab" aria-controls="career" aria-selected="false">Career History</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="bank-tab" data-bs-toggle="tab" data-bs-target="#bank" type="button" role="tab" aria-controls="bank" aria-selected="false">Bank Account</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="training-tab" data-bs-toggle="tab" data-bs-target="#training" type="button" role="tab" aria-controls="training" aria-selected="false">Training History</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab" aria-controls="documents" aria-selected="false">Documents</button>
                    </li>
                </ul>

                <div class="tab-content p-4" id="profileTabsContent">
                    <!-- Working Information -->
                    <div class="tab-pane fade show active" id="working" role="tabpanel" aria-labelledby="working-tab">
                        <table class="table info-table">
                            <tbody>
                                <tr>
                                    <th>Join Date</th>
                                    <td>{{ $employee->hire_date ? $employee->hire_date->format('d/m/Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Resign Date</th>
                                    <td>{{ $employee->resign_date ? \Carbon\Carbon::parse($employee->resign_date)->format('d/m/Y') : '-' }}</td>
                                </tr>
                                 <tr>
                                     <th>Permanent Date</th>
                                     <td>{{ $employee->permanent_date ? $employee->permanent_date->format('d/m/Y') : '-' }}</td>
                                 </tr>
                                 <tr>
                                     <th>Contract Expiry</th>
                                     <td>{{ $employee->contract_expiry ? $employee->contract_expiry->format('d/m/Y') : '-' }}</td>
                                 </tr>
                                <tr>
                                    <th>Work Location</th>
                                    <td>{{ $employee->department->name ?? 'Head Office' }}</td>
                                </tr>
                                <tr>
                                    <th>Homebase</th>
                                    <td>Head Office</td>
                                </tr>
                                 @if($isAdmin)
                                 <tr>
                                     <th>JG / PG</th>
                                     <td>
                                         @php
                                             $latestPos = $employee->employeePositions->sortByDesc('start_date')->first();
                                         @endphp
                                         {{ $latestPos->pay_grade_id ?? '-' }}
                                     </td>
                                 </tr>
                                 @endif
                                <tr>
                                    <th>Employment Status</th>
                                    <td>{{ ucfirst($employee->employee_status ?? 'Permanent') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Personal Information -->
                    <div class="tab-pane fade" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                        <table class="table info-table">
                            <tbody>
                                <tr>
                                    <th>NIK</th>
                                    <td>{{ $employee->nik ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Full Name</th>
                                    <td>{{ $employee->fullname }}</td>
                                </tr>
                                <tr>
                                    <th>Place / Date of Birth</th>
                                    <td>{{ $employee->place_of_birth ?? '-' }} / {{ $employee->birth_date ? $employee->birth_date->format('d M Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Gender</th>
                                    <td>{{ ucfirst($employee->gender ?? '-') }}</td>
                                </tr>
                                <tr>
                                    <th>Religion</th>
                                    <td>{{ $employee->religion ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Marital Status</th>
                                    <td>{{ $employee->marital_status ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $employee->email }}</td>
                                </tr>
                                <tr>
                                    <th>Phone Number</th>
                                    <td>{{ $employee->phone_number ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td>{{ $employee->address ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Education -->
                    <div class="tab-pane fade" id="education" role="tabpanel" aria-labelledby="education-tab">
                        <table class="table info-table">
                            <tbody>
                                <tr>
                                    <th>Latest Education Level</th>
                                    <td>{{ $employee->educationLevel->level ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Family Relation -->
                    <div class="tab-pane fade" id="family" role="tabpanel" aria-labelledby="family-tab">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Relation</th>
                                        <th>NIK</th>
                                        <th>Date of Birth</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($employee->families as $family)
                                        <tr>
                                            <td>{{ $family->fullname }}</td>
                                            <td>{{ $family->relation }}</td>
                                            <td>{{ $family->nik }}</td>
                                            <td>{{ $family->date_of_birth ? \Carbon\Carbon::parse($family->date_of_birth)->format('d/m/Y') : '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No family data available.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Career History -->
                    <div class="tab-pane fade" id="career" role="tabpanel" aria-labelledby="career-tab">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Old Position</th>
                                        <th>New Position</th>
                                        <th>Reason</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($employee->mutations as $mutation)
                                        <tr>
                                            <td>{{ $mutation->mutation_date->format('d/m/Y') }}</td>
                                            <td>{{ ucfirst($mutation->type) }}</td>
                                            <td>{{ $mutation->oldDepartment->name ?? '-' }} - {{ $mutation->oldRole->title ?? '-' }}</td>
                                            <td>{{ $mutation->newDepartment->name ?? '-' }} - {{ $mutation->newRole->title ?? '-' }}</td>
                                            <td>{{ $mutation->reason }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">No career history available.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Bank Account -->
                    <div class="tab-pane fade" id="bank" role="tabpanel" aria-labelledby="bank-tab">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Bank Name</th>
                                        <th>Account No</th>
                                        <th>Account Holder</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($employee->bankAccounts as $bank)
                                        <tr>
                                            <td>{{ $bank->bank_name }}</td>
                                            <td>{{ $bank->account_no }}</td>
                                            <td>{{ $bank->account_holder }}</td>
                                            <td>
                                                @if($bank->is_primary)
                                                    <span class="badge bg-primary">Primary</span>
                                                @else
                                                    <span class="badge bg-secondary">Secondary</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No bank account information available.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Training History -->
                    <div class="tab-pane fade" id="training" role="tabpanel" aria-labelledby="training-tab">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Training Name</th>
                                        <th>Provider</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="bi bi-journal-check fs-2 d-block mb-2"></i>
                                            Training history data is not yet available for this employee.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Documents -->
                    <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Document Type</th>
                                        <th>ID Number</th>
                                        <th>Description</th>
                                        <th>File</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($employee->documentIdentities as $doc)
                                        <tr>
                                            <td>{{ $doc->identityType->name ?? 'N/A' }}</td>
                                            <td>{{ $doc->identity_number }}</td>
                                            <td>{{ $doc->description ?? '-' }}</td>
                                            <td>
                                                @if($doc->file_name)
                                                    <a href="{{ asset('storage/' . $doc->file_name) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-download"></i> View
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No documents available.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
