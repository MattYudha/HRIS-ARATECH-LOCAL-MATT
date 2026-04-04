<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <?php echo e(__('Profile')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Employee Information Card -->
            <?php if($user->employee): ?>
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-4xl">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Employee Information</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Employee ID</p>
                            <p class="font-semibold text-gray-900 dark:text-gray-100"><?php echo e($user->employee->employee_id ?? 'N/A'); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Full Name</p>
                            <p class="font-semibold text-gray-900 dark:text-gray-100"><?php echo e($user->employee->fullname); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Phone Number</p>
                            <p class="font-semibold text-gray-900 dark:text-gray-100"><?php echo e($user->employee->phone_number ?? 'N/A'); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Department</p>
                            <p class="font-semibold text-gray-900 dark:text-gray-100"><?php echo e($user->employee->department->name ?? 'N/A'); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Position/Role</p>
                            <p class="font-semibold text-gray-900 dark:text-gray-100"><?php echo e($user->employee->role->title ?? 'N/A'); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Status</p>
                            <p class="font-semibold text-gray-900 dark:text-gray-100"><?php echo e(ucfirst($user->employee->status)); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Hire Date</p>
                            <p class="font-semibold text-gray-900 dark:text-gray-100"><?php echo e($user->employee->hire_date ? $user->employee->hire_date->format('d M Y') : 'N/A'); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Address</p>
                            <p class="font-semibold text-gray-900 dark:text-gray-100"><?php echo e($user->employee->address ?? 'N/A'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <?php echo $__env->make('profile.partials.update-password-form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/profile/edit.blade.php ENDPATH**/ ?>