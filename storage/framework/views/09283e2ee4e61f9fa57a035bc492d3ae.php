<?php $__env->startSection('content'); ?>
<div class="page-heading">
    <div class="page-title mb-4">
        <div class="row">
            <div class="col-md-6">
                <h3>Add Office Location</h3>
                <p class="text-subtitle text-muted">Tambahkan lokasi kantor baru untuk area kerja karyawan.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <nav aria-label="breadcrumb" class="breadcrumb-header">
                    <ol class="breadcrumb justify-content-md-end">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('office-locations.index')); ?>">Office Locations</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card shadow-sm">
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

                <form action="<?php echo e(route('office-locations.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lokasi</label>
                            <input type="text" name="name" class="form-control" value="<?php echo e(old('name')); ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tipe Lokasi</label>
                            <select name="location_type" class="form-select" required>
                                <option value="head_office" <?php echo e(old('location_type') == 'head_office' ? 'selected' : ''); ?>>Pusat</option>
                                <option value="branch" <?php echo e(old('location_type') == 'branch' ? 'selected' : ''); ?>>Cabang</option>
                                <option value="other" <?php echo e(old('location_type') == 'other' ? 'selected' : ''); ?>>Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="active" <?php echo e(old('status', 'active') == 'active' ? 'selected' : ''); ?>>Active</option>
                                <option value="inactive" <?php echo e(old('status') == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Alamat</label>
                            <textarea name="address" rows="3" class="form-control"><?php echo e(old('address')); ?></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Latitude</label>
                            <input type="number" name="latitude" class="form-control" value="<?php echo e(old('latitude')); ?>" step="0.0000001" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Longitude</label>
                            <input type="number" name="longitude" class="form-control" value="<?php echo e(old('longitude')); ?>" step="0.0000001" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Radius WFO (meter)</label>
                            <input type="number" name="radius" class="form-control" value="<?php echo e(old('radius', 1000)); ?>" min="10" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Daftar WiFi SSID</label>
                            <textarea name="allowed_ssids_text" rows="5" class="form-control" placeholder="Satu SSID per baris"><?php echo e(old('allowed_ssids_text')); ?></textarea>
                            <small class="text-muted">SSID ini dipakai untuk validasi WFO pada lokasi kantor terkait.</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Catatan</label>
                            <textarea name="notes" rows="3" class="form-control"><?php echo e(old('notes')); ?></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="<?php echo e(route('office-locations.index')); ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Save Location
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/office-locations/create.blade.php ENDPATH**/ ?>