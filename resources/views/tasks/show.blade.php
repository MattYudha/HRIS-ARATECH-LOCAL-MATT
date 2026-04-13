@extends('layouts.dashboard')

@section('content')



<div class="page-heading">
    <div class="page-title mb-4">
        <div class="row">
            <div class="col-12 col-md-6">
                <h3>Task Detail</h3>
                <p class="text-subtitle text-muted">Detail information of selected task</p>
            </div>
            <div class="col-12 col-md-6">
                <nav class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('tasks.index') }}">Tasks</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row justify-content-center">
            <div class="col-md-8 col-12">

                <div class="card shadow-sm mb-4">
                    <div class="card-body">

                        {{-- TITLE --}}
                        <div class="mb-4">
                            <h4 class="fw-bold mb-1">{{ $task->title }}</h4>
                            <small class="text-muted">
                                Due {{ \Carbon\Carbon::parse($task->due_date)->format('d F Y') }}
                            </small>
                        </div>

                        <hr>

                        {{-- INFO --}}
                        <div class="row mb-3">
                            <div class="col-md-4 text-muted">Assigned To</div>
                            <div class="col-md-8 fw-semibold">
                                {{ $task->employee?->fullname ?? 'Unknown Employee' }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 text-muted">Status</div>
                            <div class="col-md-8">
                                @switch($task->status)
                                    @case('pending')
                                        <span class="badge bg-warning">Pending</span>
                                        @break
                                    @case('on progress')
                                        <span class="badge bg-info">On Progress</span>
                                        @break
                                    @case('done')
                                        <span class="badge bg-success">Done</span>
                                        @break
                                @endswitch
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4 text-muted">Description</div>
                            <div class="col-md-8">
                                <p class="mb-0">
                                    {{ $task->description ?? '-' }}
                                </p>
                            </div>
                        </div>
                      

                        <hr>

                        {{-- ACTION --}}
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>

                            @if (\App\Constants\Roles::isAdmin(session('role')) || session('role') === \App\Constants\Roles::MANAGER_UNIT_HEAD)
                                <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-primary">
                                    <i class="bi bi-pencil"></i> Edit Task
                                </a>
                            @endif
                        </div>

                    </div>
                </div>

                <!-- Comments Section -->
                <div class="card shadow-sm mt-4">
                    <div class="card-header pb-0">
                        <h5 class="card-title"><i class="bi bi-chat-dots me-2"></i>Comments & Monitoring</h5>
                    </div>
                    <div class="card-body">
                        <!-- Comment Form -->
                        <form action="{{ route('tasks.comments.store', $task->id) }}" method="POST" class="mb-4" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="comment" class="form-label">Add a comment / progress update</label>
                                <textarea name="comment" id="comment" rows="3" class="form-control" placeholder="Type your comment here..." required></textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label for="evidence" class="form-label">Evidence (Optional: Photo or Document)</label>
                                <input type="file" name="evidence" id="evidence" class="form-control" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                                <small class="text-muted">Max size: 10MB. Allowed: JPG, PNG, PDF, DOCX.</small>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-send me-1"></i> Post Comment
                                </button>
                            </div>
                        </form>

                        <!-- Comments List -->
                        <div class="comments-list mt-4">
                            @forelse($task->comments as $comment)
                                <div class="comment-item border-start border-3 ps-3 mb-4 {{ $comment->employee_id == session('employee_id') ? 'border-primary' : 'border-secondary' }}">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-bold text-primary">{{ $comment->employee->fullname }}</span>
                                        <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                    </div>
                                    <div class="comment-text text-dark">
                                        {!! nl2br(e($comment->comment)) !!}
                                    </div>

                                    @if($comment->evidence_path)
                                        <div class="comment-evidence mt-2">
                                            @php
                                                $extension = pathinfo($comment->evidence_path, PATHINFO_EXTENSION);
                                                $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp']);
                                                $evidenceUrl = route('tasks.comments.evidence', $comment);
                                            @endphp

                                            @if($isImage)
                                                <div class="mb-2">
                                                    <a href="{{ $evidenceUrl }}" target="_blank">
                                                        <img src="{{ $evidenceUrl }}" 
                                                             alt="Evidence" 
                                                             class="img-thumbnail" 
                                                             style="max-width: 200px; max-height: 200px; object-fit: cover;">
                                                    </a>
                                                </div>
                                            @else
                                                <div class="mb-2">
                                                    <a href="{{ $evidenceUrl }}" 
                                                       target="_blank" 
                                                       class="btn btn-sm btn-outline-info">
                                                        <i class="bi bi-file-earmark-text me-1"></i> View Evidence ({{ strtoupper($extension) }})
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    <div class="text-end mt-2">
                                        <button type="button" 
                                                class="btn btn-link btn-sm text-primary p-0 quote-btn" 
                                                data-author="{{ $comment->employee->fullname }}" 
                                                data-comment="{{ $comment->comment }}">
                                            <i class="bi bi-quote"></i> Quote
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 text-muted">
                                    <i class="bi bi-chat-square mb-2 d-block fs-2"></i>
                                    No comments yet. Start the conversation!
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const commentArea = document.getElementById('comment');
        const quoteButtons = document.querySelectorAll('.quote-btn');

        quoteButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const author = this.getAttribute('data-author');
                const comment = this.getAttribute('data-comment');
                
                const quoteText = `> ${author}: ${comment}\n\n`;
                
                commentArea.value = quoteText + commentArea.value;
                commentArea.focus();
                
                // Scroll to comment form
                commentArea.scrollIntoView({ behavior: 'smooth', block: 'center' });
            });
        });
    });
</script>
@endpush
