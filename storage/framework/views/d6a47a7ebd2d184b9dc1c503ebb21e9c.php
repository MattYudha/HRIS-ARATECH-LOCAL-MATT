<?php $__env->startSection('content'); ?>
<div class="page-heading">
    <div class="page-title mb-4">
        <div class="row">
            <div class="col-md-6">
                <h3>Office Locations</h3>
                <p class="text-subtitle text-muted">Kelola area kerja untuk kebutuhan WFO dan presensi.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <nav aria-label="breadcrumb" class="breadcrumb-header">
                    <ol class="breadcrumb justify-content-md-end">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Office Locations</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card shadow-sm">
            <div class="card-body">
                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?php echo e(session('error')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="mb-1">Daftar Lokasi Kantor</h5>
                        <small class="text-muted">Setiap lokasi dapat memiliki radius dan daftar SSID WiFi yang berbeda.</small>
                    </div>
                    <a href="<?php echo e(route('office-locations.create')); ?>" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> Add Location
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                                <th>Tipe</th>
                                <th>Alamat</th>
                                <th>Koordinat</th>
                                <th>WiFi SSID</th>
                                <th>Karyawan</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $officeLocations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $officeLocation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold"><?php echo e($officeLocation->name); ?></div>
                                        <?php if($officeLocation->notes): ?>
                                            <small class="text-muted"><?php echo e($officeLocation->notes); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-info"><?php echo e($officeLocation->type_label); ?></span>
                                    </td>
                                    <td><?php echo e($officeLocation->address ?: '-'); ?></td>
                                    <td>
                                        <?php if(!is_null($officeLocation->latitude) && !is_null($officeLocation->longitude)): ?>
                                            <div><?php echo e($officeLocation->latitude); ?>, <?php echo e($officeLocation->longitude); ?></div>
                                            <small class="text-muted">Radius: <?php echo e(number_format($officeLocation->radius)); ?> m</small>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="min-width: 220px;">
                                        <?php $__empty_2 = true; $__currentLoopData = $officeLocation->allowed_ssids ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ssid): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                            <span class="badge bg-light text-dark border mb-1"><?php echo e($ssid); ?></span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                            <span class="text-muted">Mengikuti konfigurasi default</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e(number_format($officeLocation->employees_count)); ?></td>
                                    <td>
                                        <?php if($officeLocation->status === 'active'): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="<?php echo e(route('office-locations.edit', $officeLocation)); ?>" class="btn btn-outline-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="<?php echo e(route('office-locations.destroy', $officeLocation)); ?>" method="POST" class="d-inline delete-form">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">Belum ada lokasi kantor yang tersedia.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.querySelectorAll('.delete-form').forEach((form) => {
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        window.confirmDelete(this, 'Hapus lokasi kantor ini?');
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/office-locations/index.blade.php ENDPATH**/ ?>