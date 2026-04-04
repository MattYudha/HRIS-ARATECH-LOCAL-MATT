<?php $__env->startSection('content'); ?>



<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Inventory Request</h3>
                <p class="text-subtitle text-muted">Perbarui data pengajuan.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('inventory-requests.index')); ?>">Inventory Requests</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    
    <section class="section">
        <div class="card">
            <div class="card-body">
                <?php if($errors->any()): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?php echo e(route('inventory-requests.update', $inventoryRequest->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <?php $isAdmin = in_array(session('role'), ['HR', 'Power User']); ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="request_type" class="form-label">Tipe Permintaan</label>
                                <select class="form-select <?php $__errorArgs = ['request_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="request_type" id="request_type" required <?php echo e(!$isAdmin && $inventoryRequest->status != 'pending' ? 'disabled' : ''); ?>>
                                    <option value="new" <?php echo e(old('request_type', $inventoryRequest->request_type) == 'new' ? 'selected' : ''); ?>>Pengadaan Baru</option>
                                    <option value="repair" <?php echo e(old('request_type', $inventoryRequest->request_type) == 'repair' ? 'selected' : ''); ?>>Perbaikan (Repair)</option>
                                    <option value="replacement" <?php echo e(old('request_type', $inventoryRequest->request_type) == 'replacement' ? 'selected' : ''); ?>>Penggantian (Replacement)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Jumlah (Quantity)</label>
                                <input type="number" class="form-control <?php $__errorArgs = ['quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="quantity" id="quantity" value="<?php echo e(old('quantity', $inventoryRequest->quantity)); ?>" min="1" required <?php echo e(!$isAdmin && $inventoryRequest->status != 'pending' ? 'readonly' : ''); ?>>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3" id="inventory_select_wrapper">
                        <label for="inventory_id" class="form-label">Pilih Barang (Jika perbaikan/penggantian)</label>
                        <select class="form-select <?php $__errorArgs = ['inventory_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="inventory_id" id="inventory_id" <?php echo e(!$isAdmin && $inventoryRequest->status != 'pending' ? 'disabled' : ''); ?>>
                            <option value="">-- Cari Barang --</option>
                            <?php $__currentLoopData = $inventories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inventory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($inventory->id); ?>" <?php echo e(old('inventory_id', $inventoryRequest->inventory_id) == $inventory->id ? 'selected' : ''); ?>>
                                    <?php echo e($inventory->name); ?> (<?php echo e($inventory->location); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="mb-3" id="item_name_wrapper">
                        <label for="item_name" class="form-label">Nama Barang (Jika barang baru)</label>
                        <input type="text" class="form-control <?php $__errorArgs = ['item_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="item_name" id="item_name" value="<?php echo e(old('item_name', $inventoryRequest->item_name)); ?>" <?php echo e(!$isAdmin && $inventoryRequest->status != 'pending' ? 'readonly' : ''); ?>>
                    </div>

                    <div class="mb-3">
                        <label for="reason" class="form-label">Alasan Permintaan</label>
                        <textarea class="form-control <?php $__errorArgs = ['reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="reason" id="reason" rows="4" required <?php echo e(!$isAdmin && $inventoryRequest->status != 'pending' ? 'readonly' : ''); ?>><?php echo e(old('reason', $inventoryRequest->reason)); ?></textarea>
                    </div>

                    <?php if($isAdmin): ?>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="status" id="status" required>
                                    <option value="pending" <?php echo e(old('status', $inventoryRequest->status) == 'pending' ? 'selected' : ''); ?>>Pending</option>
                                    <option value="approved" <?php echo e(old('status', $inventoryRequest->status) == 'approved' ? 'selected' : ''); ?>>Approved</option>
                                    <option value="rejected" <?php echo e(old('status', $inventoryRequest->status) == 'rejected' ? 'selected' : ''); ?>>Rejected</option>
                                    <option value="completed" <?php echo e(old('status', $inventoryRequest->status) == 'completed' ? 'selected' : ''); ?>>Completed</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan Admin</label>
                        <textarea class="form-control <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="notes" id="notes" rows="3"><?php echo e(old('notes', $inventoryRequest->notes)); ?></textarea>
                    </div>
                    <?php endif; ?>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Update Request</button>
                        <a href="<?php echo e(route('inventory-requests.index')); ?>" class="btn btn-outline-secondary">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function() {
        function toggleFields() {
            var type = $('#request_type').val();
            if (type === 'new') {
                $('#inventory_select_wrapper').hide();
                $('#item_name_wrapper').show();
            } else {
                $('#inventory_select_wrapper').show();
                $('#item_name_wrapper').hide();
            }
        }

        $('#request_type').change(toggleFields);
        toggleFields();
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/inventory_requests/edit.blade.php ENDPATH**/ ?>