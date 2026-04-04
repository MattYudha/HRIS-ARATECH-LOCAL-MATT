<?php $__env->startSection('content'); ?>
<div class="page-heading mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('inventory-dispatches.index')); ?>">Dispatches</a></li>
            <li class="breadcrumb-item active" aria-current="page">Release Item</li>
        </ol>
    </nav>
    <h3>Release Inventory Item</h3>
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

            <form action="<?php echo e(route('inventory-dispatches.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="inventory_id" class="form-label">Inventory Item <span class="text-danger">*</span></label>
                        <select name="inventory_id" id="inventory_id" class="form-select" required>
                            <option value="">-- Select Item --</option>
                            <?php $__currentLoopData = $inventories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($inv->id); ?>" <?php echo e(old('inventory_id') == $inv->id ? 'selected' : ''); ?>>
                                    <?php echo e($inv->name); ?> (Available: <?php echo e($inv->quantity); ?> <?php echo e($inv->item_type == 'habis_pakai' ? '[Consumable]' : '[Asset]'); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="employee_id" class="form-label">Released To (Employee) <span class="text-danger">*</span></label>
                        <select name="employee_id" id="employee_id" class="form-select" required>
                            <option value="">-- Select Employee --</option>
                            <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($emp->id); ?>" <?php echo e(old('employee_id') == $emp->id ? 'selected' : ''); ?>>
                                    <?php echo e($emp->fullname); ?> (<?php echo e($emp->department->name ?? '-'); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" id="quantity" class="form-control" min="1" value="<?php echo e(old('quantity', 1)); ?>" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="area" class="form-label">Destination Area</label>
                        <input type="text" name="area" id="area" class="form-control" value="<?php echo e(old('area')); ?>" placeholder="e.g. Lobby, Server Room">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="room" class="form-label">Destination Room</label>
                        <input type="text" name="room" id="room" class="form-control" value="<?php echo e(old('room')); ?>" placeholder="e.g. R-101">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="dispatch_date" class="form-label">Release Date <span class="text-danger">*</span></label>
                        <input type="date" name="dispatch_date" id="dispatch_date" class="form-control" value="<?php echo e(date('Y-m-d')); ?>" required>
                    </div>

                    <div class="col-12 mb-3">
                        <label for="notes" class="form-label">Notes / Purpose</label>
                        <textarea name="notes" id="notes" class="form-control" rows="3"><?php echo e(old('notes')); ?></textarea>
                    </div>
                </div>

                <div class="alert alert-info py-2">
                    <i class="bi bi-info-circle me-2"></i> A unique barcode will be automatically generated for this dispatch upon saving.
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Save & Generate Barcode</button>
                    <a href="<?php echo e(route('inventory-dispatches.index')); ?>" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/inventory_dispatches/create.blade.php ENDPATH**/ ?>