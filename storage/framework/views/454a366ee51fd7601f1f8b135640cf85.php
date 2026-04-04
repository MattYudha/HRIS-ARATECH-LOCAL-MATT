<?php $__env->startSection('content'); ?>



<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Presence Calendar</h3>
                <p class="text-subtitle text-muted">View presence records in calendar format.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('presences.index')); ?>">Presences</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Calendar</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    
    <section class="section">
        <div class="card">
            <div class="card-body">
                <!-- Month Navigation -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <a href="<?php echo e(route('presences.calendar', ['year' => $year, 'month' => $month - 1])); ?>" 
                       class="btn btn-outline-primary">
                        <i class="bi bi-chevron-left"></i> Previous Month
                    </a>
                    <h4 class="mb-0">
                        <?php
                            try {
                                $displayDate = \Carbon\Carbon::create($year, $month, 1);
                                echo $displayDate->format('F Y');
                            } catch (\Exception $e) {
                                echo \Carbon\Carbon::now()->format('F Y');
                            }
                        ?>
                    </h4>
                    <a href="<?php echo e(route('presences.calendar', ['year' => $year, 'month' => $month + 1])); ?>" 
                       class="btn btn-outline-primary">
                        Next Month <i class="bi bi-chevron-right"></i>
                    </a>
                </div>

                <!-- Calendar Grid -->
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Sun</th>
                                <th>Mon</th>
                                <th>Tue</th>
                                <th>Wed</th>
                                <th>Thu</th>
                                <th>Fri</th>
                                <th>Sat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                try {
                                    $firstDay = \Carbon\Carbon::create($year, $month, 1);
                                    $lastDay = $firstDay->copy()->endOfMonth();
                                    $startDate = $firstDay->copy()->startOfWeek();
                                    $endDate = $lastDay->copy()->endOfWeek();
                                    $currentDate = $startDate->copy();
                                    $presencesByDate = $presences->keyBy('date');
                                } catch (\Exception $e) {
                                    // Fallback to current month if date creation fails
                                    $firstDay = \Carbon\Carbon::now()->startOfMonth();
                                    $lastDay = $firstDay->copy()->endOfMonth();
                                    $startDate = $firstDay->copy()->startOfWeek();
                                    $endDate = $lastDay->copy()->endOfWeek();
                                    $currentDate = $startDate->copy();
                                    $presencesByDate = collect([]);
                                }
                            ?>
                            <?php while($currentDate->lte($endDate)): ?>
                                <tr>
                                    <?php for($i = 0; $i < 7; $i++): ?>
                                        <?php
                                            $dateStr = $currentDate->format('Y-m-d');
                                            $presence = $presencesByDate->get($dateStr);
                                            $isCurrentMonth = $currentDate->month == $month;
                                            $isToday = $currentDate->isToday();
                                        ?>
                                        <td class="calendar-day <?php echo e(!$isCurrentMonth ? 'text-muted' : ''); ?> <?php echo e($isToday ? 'bg-light' : ''); ?>" 
                                            style="height: 100px; vertical-align: top; position: relative;">
                                            <div class="fw-bold <?php echo e($isToday ? 'text-primary' : ''); ?>">
                                                <?php echo e($currentDate->day); ?>

                                            </div>
                                            <?php if($presence && $isCurrentMonth): ?>
                                                <div class="small mt-1">
                                                    <?php if($presence['check_in']): ?>
                                                        <div class="badge bg-success">In: <?php echo e($presence['check_in']); ?></div>
                                                    <?php endif; ?>
                                                    <?php if($presence['check_out']): ?>
                                                        <div class="badge bg-info mt-1">Out: <?php echo e($presence['check_out']); ?></div>
                                                    <?php endif; ?>
                                                    <?php if($presence['is_late']): ?>
                                                        <div class="badge bg-warning mt-1">Late</div>
                                                    <?php endif; ?>
                                                    <div class="badge bg-secondary mt-1"><?php echo e($presence['work_type']); ?></div>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <?php $currentDate->addDay(); ?>
                                    <?php endfor; ?>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Legend -->
                <div class="mt-3">
                    <h6>Legend:</h6>
                    <span class="badge bg-success">Check-in</span>
                    <span class="badge bg-info">Check-out</span>
                    <span class="badge bg-warning">Late</span>
                    <span class="badge bg-secondary">Work Type</span>
                </div>
            </div>
        </div>
    </section>
</div>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/presences/calendar.blade.php ENDPATH**/ ?>