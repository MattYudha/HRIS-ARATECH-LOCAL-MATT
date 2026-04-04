<?php $__env->startSection('content'); ?>



<div class="page-heading mb-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('inventories.index')); ?>">Inventories</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Inventory Item</li>
        </ol>
    </nav>

    <h3>Edit Inventory Item</h3>
</div>

<div class="page-content">
    <section class="section">
        <div class="row justify-content-center">
            <div class="col-12">
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

                        <form action="<?php echo e(route('inventories.update', $inventory)); ?>" method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>

                            <div class="row">
                                
                                <div class="col-md-4 mb-3">
                                    <label for="name" class="form-label">
                                        Item Name <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        name="name"
                                        id="name"
                                        class="form-control"
                                        value="<?php echo e(old('name', $inventory->name)); ?>"
                                        required
                                        placeholder="Enter item name"
                                    >
                                </div>

                                
                                <div class="col-md-4 mb-3">
                                    <label for="inventory_category_id" class="form-label">
                                        Category <span class="text-danger">*</span>
                                    </label>
                                    <select
                                        name="inventory_category_id"
                                        id="inventory_category_id"
                                        class="form-select"
                                        required
                                    >
                                        <option value="">-- Select Category --</option>
                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option
                                                value="<?php echo e($category->id); ?>"
                                                <?php echo e(old('inventory_category_id', $inventory->inventory_category_id) == $category->id ? 'selected' : ''); ?>

                                            >
                                                <?php echo e($category->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>

                                
                                <div class="col-md-4 mb-3">
                                    <label for="item_type" class="form-label">
                                        Item Type <span class="text-danger">*</span>
                                    </label>
                                    <select
                                        name="item_type"
                                        id="item_type"
                                        class="form-select"
                                        required
                                    >
                                        <option value="habis_pakai" <?php echo e(old('item_type', $inventory->item_type) == 'habis_pakai' ? 'selected' : ''); ?>>Habis Pakai (Consumable)</option>
                                        <option value="tidak_habis_pakai" <?php echo e(old('item_type', $inventory->item_type) == 'tidak_habis_pakai' ? 'selected' : ''); ?>>Tidak Habis Pakai (Asset)</option>
                                    </select>
                                </div>

                                
                                <div class="col-md-6 mb-3">
                                    <label for="quantity" class="form-label">
                                        Quantity <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        type="number"
                                        name="quantity"
                                        id="quantity"
                                        class="form-control"
                                        min="0"
                                        value="<?php echo e(old('quantity', $inventory->quantity)); ?>"
                                        required
                                        placeholder="Enter quantity"
                                    >
                                </div>

                                
                                <div class="col-md-6 mb-3">
                                    <label for="min_stock_threshold" class="form-label">
                                        Min Stock Threshold
                                    </label>
                                    <input
                                        type="number"
                                        name="min_stock_threshold"
                                        id="min_stock_threshold"
                                        class="form-control"
                                        min="0"
                                        value="<?php echo e(old('min_stock_threshold', $inventory->min_stock_threshold)); ?>"
                                        placeholder="Enter Min Stock Threshold"
                                    >
                                    <small class="text-muted">Set to 0 to disable low stock alerts.</small>
                                </div>

                                
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label">
                                        Status <span class="text-danger">*</span>
                                    </label>
                                    <select
                                        name="status"
                                        id="status"
                                        class="form-select"
                                        required
                                    >
                                        <option value="active" <?php echo e(old('status', $inventory->status) == 'active' ? 'selected' : ''); ?>>Active</option>
                                        <option value="inactive" <?php echo e(old('status', $inventory->status) == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                                        <option value="damaged" <?php echo e(old('status', $inventory->status) == 'damaged' ? 'selected' : ''); ?>>Damaged</option>
                                    </select>
                                </div>

                                
                                <div class="col-md-4 mb-3">
                                    <label for="location" class="form-label">Main Location</label>
                                    <input
                                        type="text"
                                        name="location"
                                        id="location"
                                        class="form-control"
                                        value="<?php echo e(old('location', $inventory->location)); ?>"
                                        placeholder="Enter location"
                                    >
                                </div>

                                
                                <div class="col-md-4 mb-3">
                                    <label for="area" class="form-label">Area</label>
                                    <input
                                        type="text"
                                        name="area"
                                        id="area"
                                        class="form-control"
                                        value="<?php echo e(old('area', $inventory->area)); ?>"
                                        placeholder="Enter area"
                                    >
                                </div>

                                
                                <div class="col-md-4 mb-3">
                                    <label for="room" class="form-label">Room</label>
                                    <input
                                        type="text"
                                        name="room"
                                        id="room"
                                        class="form-control"
                                        value="<?php echo e(old('room', $inventory->room)); ?>"
                                        placeholder="Enter room"
                                    >
                                </div>

                                
                                <div class="col-md-6 mb-3">
                                    <label for="purchase_date" class="form-label">Purchase Date</label>
                                    <input
                                        type="date"
                                        name="purchase_date"
                                        id="purchase_date"
                                        class="form-control"
                                        value="<?php echo e(old('purchase_date', optional($inventory->purchase_date)->format('Y-m-d'))); ?>"
                                    >
                                </div>

                                
                                <div class="col-12 mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea
                                        name="description"
                                        id="description"
                                        class="form-control"
                                        rows="4"
                                        placeholder="Enter description"
                                    ><?php echo e(old('description', $inventory->description)); ?></textarea>
                                </div>
                            </div>

                            
                            <div class="d-flex gap-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i> Update
                                </button>
                                <a href="<?php echo e(route('inventories.index')); ?>" class="btn btn-secondary">
                                    <i class="bi bi-x-circle me-1"></i> Cancel
                                </a>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/inventories/edit.blade.php ENDPATH**/ ?>