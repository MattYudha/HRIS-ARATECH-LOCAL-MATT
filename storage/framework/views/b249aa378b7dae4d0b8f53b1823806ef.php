<?php $__env->startSection('content'); ?>



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
                        <li class="breadcrumb-item"><a href="<?php echo e(route('tasks.index')); ?>">Tasks</a></li>
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

                        
                        <div class="mb-4">
                            <h4 class="fw-bold mb-1"><?php echo e($task->title); ?></h4>
                            <small class="text-muted">
                                Due <?php echo e(\Carbon\Carbon::parse($task->due_date)->format('d F Y')); ?>

                            </small>
                        </div>

                        <hr>

                        
                        <div class="row mb-3">
                            <div class="col-md-4 text-muted">Assigned To</div>
                            <div class="col-md-8 fw-semibold">
                                <?php echo e($task->employee?->fullname ?? 'Unknown Employee'); ?>

                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 text-muted">Status</div>
                            <div class="col-md-8">
                                <?php switch($task->status):
                                    case ('pending'): ?>
                                        <span class="badge bg-warning">Pending</span>
                                        <?php break; ?>
                                    <?php case ('on progress'): ?>
                                        <span class="badge bg-info">On Progress</span>
                                        <?php break; ?>
                                    <?php case ('done'): ?>
                                        <span class="badge bg-success">Done</span>
                                        <?php break; ?>
                                <?php endswitch; ?>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4 text-muted">Description</div>
                            <div class="col-md-8">
                                <p class="mb-0">
                                    <?php echo e($task->description ?? '-'); ?>

                                </p>
                            </div>
                        </div>
                      

                        <hr>

                        
                        <div class="d-flex justify-content-between">
                            <a href="<?php echo e(route('tasks.index')); ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>

                            <?php if(in_array(session('role'), ['HR Administrator', 'Manager / Unit Head', 'Super Admin'])): ?>
                                <a href="<?php echo e(route('tasks.edit', $task->id)); ?>" class="btn btn-primary">
                                    <i class="bi bi-pencil"></i> Edit Task
                                </a>
                            <?php endif; ?>
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
                        <form action="<?php echo e(route('tasks.comments.store', $task->id)); ?>" method="POST" class="mb-4" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
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
                            <?php $__empty_1 = true; $__currentLoopData = $task->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="comment-item border-start border-3 ps-3 mb-4 <?php echo e($comment->employee_id == session('employee_id') ? 'border-primary' : 'border-secondary'); ?>">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-bold text-primary"><?php echo e($comment->employee->fullname); ?></span>
                                        <small class="text-muted"><?php echo e($comment->created_at->diffForHumans()); ?></small>
                                    </div>
                                    <div class="comment-text text-dark">
                                        <?php echo nl2br(e($comment->comment)); ?>

                                    </div>

                                    <?php if($comment->evidence_path): ?>
                                        <div class="comment-evidence mt-2">
                                            <?php
                                                $extension = pathinfo($comment->evidence_path, PATHINFO_EXTENSION);
                                                $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp']);
                                                $evidenceUrl = route('tasks.comments.evidence', $comment);
                                            ?>

                                            <?php if($isImage): ?>
                                                <div class="mb-2">
                                                    <a href="<?php echo e($evidenceUrl); ?>" target="_blank">
                                                        <img src="<?php echo e($evidenceUrl); ?>" 
                                                             alt="Evidence" 
                                                             class="img-thumbnail" 
                                                             style="max-width: 200px; max-height: 200px; object-fit: cover;">
                                                    </a>
                                                </div>
                                            <?php else: ?>
                                                <div class="mb-2">
                                                    <a href="<?php echo e($evidenceUrl); ?>" 
                                                       target="_blank" 
                                                       class="btn btn-sm btn-outline-info">
                                                        <i class="bi bi-file-earmark-text me-1"></i> View Evidence (<?php echo e(strtoupper($extension)); ?>)
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="text-end mt-2">
                                        <button type="button" 
                                                class="btn btn-link btn-sm text-primary p-0 quote-btn" 
                                                data-author="<?php echo e($comment->employee->fullname); ?>" 
                                                data-comment="<?php echo e($comment->comment); ?>">
                                            <i class="bi bi-quote"></i> Quote
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="text-center py-4 text-muted">
                                    <i class="bi bi-chat-square mb-2 d-block fs-2"></i>
                                    No comments yet. Start the conversation!
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/tasks/show.blade.php ENDPATH**/ ?>