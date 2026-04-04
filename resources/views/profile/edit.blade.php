<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Employee Information Card -->
            @if($user->employee)
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-4xl">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Employee Information</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Employee ID</p>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $user->employee->employee_id ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Full Name</p>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $user->employee->fullname }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Phone Number</p>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $user->employee->phone_number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Department</p>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $user->employee->department->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Position/Role</p>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $user->employee->role->title ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Status</p>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ ucfirst($user->employee->status) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Hire Date</p>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $user->employee->hire_date ? $user->employee->hire_date->format('d M Y') : 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Address</p>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $user->employee->address ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
