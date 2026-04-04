<?php $__env->startSection('content'); ?>



<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3><i class="bi bi-cash-stack"></i> Payroll</h3>
                <p class="text-subtitle text-muted">Kelola data penggajian karyawan.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                        <li class="breadcrumb-item active">Payroll</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        
        <div class="card shadow-sm mb-3">
            <div class="card-body py-3">
                <div class="row align-items-end g-2">
                    <div class="col-md-3">
                        <label class="form-label fw-bold mb-1"><i class="bi bi-funnel"></i> Bulan</label>
                        <select id="filter-month" class="form-select form-select-sm">
                            <option value="">Semua Bulan</option>
                            <?php $months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember']; ?>
                            <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($i+1); ?>"><?php echo e($m); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold mb-1">Tahun</label>
                        <select id="filter-year" class="form-select form-select-sm">
                            <option value="">Semua</option>
                            <?php for($y = date('Y') - 2; $y <= date('Y') + 1; $y++): ?>
                                <option value="<?php echo e($y); ?>" <?php echo e($y == date('Y') ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold mb-1">Status</label>
                        <select id="filter-status" class="form-select form-select-sm">
                            <option value="">Semua</option>
                            <option value="draft">Draft</option>
                            <option value="approved">Approved</option>
                            <option value="paid">Paid</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button id="btn-filter" class="btn btn-primary btn-sm w-100">
                            <i class="bi bi-search"></i> Filter
                        </button>
                    </div>
                    <div class="col-md-3 text-end">
                        <?php if(in_array(session('role'), ['Super Admin', 'HR Administrator', 'Super Admin'])): ?>
                            <a href="<?php echo e(route('payrolls.create')); ?>" class="btn btn-success btn-sm">
                                <i class="bi bi-plus-circle"></i> Buat Payroll
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle"></i> <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="payroll-table" style="width: 100%;">
                        <thead class="table-light">
                            <tr>
                                <th>Karyawan</th>
                                <th>Periode</th>
                                <th class="text-end">Pendapatan</th>
                                <th class="text-end">Potongan</th>
                                <th class="text-end">Gaji Bersih</th>
                                <th class="text-center">Status</th>
                                <th class="text-center" style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
$(function() {
    var table = $('#payroll-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "<?php echo e(route('payrolls.index')); ?>",
            data: function(d) {
                d.filter_month = $('#filter-month').val();
                d.filter_year = $('#filter-year').val();
                d.filter_status = $('#filter-status').val();
            }
        },
        order: [[1, 'desc']],
        columns: [
            { data: 'employee_name', name: 'employee.fullname' },
            { data: 'period', name: 'period_year', orderable: true, searchable: false },
            { data: 'total_earnings', name: 'total_earnings', className: 'text-end' },
            { data: 'total_deductions', name: 'total_deductions', className: 'text-end' },
            { data: 'net_salary', name: 'net_salary', className: 'text-end fw-bold' },
            { data: 'status_badge', name: 'status', className: 'text-center', orderable: true, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
        ],
        language: {
            processing: '<div class="spinner-border text-primary spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>',
            emptyTable: 'Belum ada data payroll.',
            info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
            infoEmpty: 'Tidak ada data',
            search: '<i class="bi bi-search"></i>',
            searchPlaceholder: 'Cari karyawan...',
            paginate: { previous: '<i class="bi bi-chevron-left"></i>', next: '<i class="bi bi-chevron-right"></i>' }
        }
    });

    $('#btn-filter').on('click', function() {
        table.draw();
    });

    // Also filter on Enter key
    $('#filter-month, #filter-year, #filter-status').on('change', function() {
        table.draw();
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/payrolls/index.blade.php ENDPATH**/ ?>