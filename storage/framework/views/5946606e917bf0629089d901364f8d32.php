<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>HRIS Aratech</title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Expires" content="0">

    <link rel="shortcut icon" href="<?php echo e(asset('img/HRIS ARATECH logo tr.png')); ?>" type="image/png">

    <!-- Mazer CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('mazer/assets/compiled/css/app.css')); ?>?v=<?php echo e(time()); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('mazer/assets/compiled/css/app-dark.css')); ?>?v=<?php echo e(time()); ?>">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('vendor/datatables/dataTables.bootstrap5.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('vendor/datatables/responsive.bootstrap5.min.css')); ?>">

    <link rel="stylesheet" href="<?php echo e(asset('vendor/flatpickr/flatpickr.min.css')); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">


    <style>
        /* Active highlight */
        .sidebar-item.active > a.sidebar-link {
            background: #0d6efd;
            color: #fff !important;
            border-radius: 10px;
        }
        .sidebar-item.active > a.sidebar-link i {
            color: #fff !important;
        }

        /* Menu group styles */
        .menu-group {
            background: rgba(13, 110, 253, 0.04);
            border-left: 3px solid #0d6efd;
            border-radius: 0 10px 10px 0;
            margin: 8px 10px 8px 5px;
            padding: 0;
            list-style: none;
        }
        .menu-group-header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 15px 10px 12px;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #0d6efd;
            opacity: 0.85;
            cursor: pointer;
            user-select: none;
            transition: opacity 0.2s;
        }
        .menu-group-header:hover {
            opacity: 1;
        }
        .menu-group-header i.group-icon {
            font-size: 0.85rem;
        }
        .menu-group-header .chevron {
            margin-left: auto;
            font-size: 0.7rem;
            transition: transform 0.3s ease;
        }
        .menu-group.expanded .menu-group-header .chevron {
            transform: rotate(90deg);
        }

        /* Collapse/expand items */
        .menu-group .menu-group-items {
            list-style: none;
            padding: 0 6px 0 6px;
            margin: 0;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.35s ease, padding-bottom 0.35s ease;
        }
        .menu-group.expanded .menu-group-items {
            max-height: 500px;
            padding-bottom: 6px;
        }
        .menu-group .menu-group-items .sidebar-item {
            margin: 0;
        }
        .menu-group .menu-group-items .sidebar-link {
            padding: 8px 12px;
            font-size: 0.88rem;
        }

        /* Dark mode group adjustments */
        [data-bs-theme='dark'] .menu-group {
            background: rgba(255, 255, 255, 0.04);
            border-left-color: #5e9bff;
        }
        [data-bs-theme='dark'] .menu-group-header {
            color: #7db4ff;
        }

        /* Desktop sidebar layout */
        @media screen and (min-width: 1200px) {
            #sidebar { width: 300px; transition: all 0.3s ease; overflow-x: hidden; }
            #main { margin-left: 300px; transition: all 0.3s ease; }
        }

    	.burger-btn {
  		 cursor: pointer;
    	 display: inline-flex;
    	 background: transparent;
    	 border: none;
    	 font-size: 1.5rem;
		}

        .mobile-nav-header {
            padding: 1rem 1.5rem 0;
            display: flex;
            align-items: center;
        }

        .mobile-burger-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            border-radius: 12px;
            border: 1px solid rgba(148, 163, 184, 0.24);
            background: #ffffff;
            color: #25396f;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
            transition: all 0.2s ease;
        }

        .mobile-burger-btn:hover,
        .mobile-burger-btn:focus {
            color: #0d6efd;
            border-color: rgba(13, 110, 253, 0.35);
            box-shadow: 0 12px 32px rgba(13, 110, 253, 0.15);
        }

        .mobile-burger-btn i {
            font-size: 1.35rem;
        }

        [data-bs-theme='dark'] .mobile-burger-btn {
            background: #25304a;
            color: #f8fafc;
            border-color: rgba(148, 163, 184, 0.2);
          color: #f8fafc;
        }
      
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body>
<script src="<?php echo e(asset('mazer/assets/static/js/initTheme.js')); ?>"></script>

<div id="app">

    <!-- ================= SIDEBAR ================= -->
    <div id="sidebar">
        <div class="sidebar-wrapper">

            <div class="sidebar-header text-center d-flex flex-column align-items-center gap-2 pb-2">
                <div class="d-flex justify-content-center align-items-center w-100 px-3">
                    <a href="<?php echo e(url('/dashboard')); ?>" class="d-inline-flex justify-content-center">
                        <img src="<?php echo e(asset('img/HRIS ARATECH logo tr.png')); ?>" style="height:120px" id="sidebar-logo">
                    </a>
                </div>
            </div>

            <div class="sidebar-menu">
                <ul class="menu">

                    <?php
                        $user = Auth::user();
                        $role = session('role');
                        $isSuperAdmin = $role === \App\Constants\Roles::SUPER_ADMIN;
                        $isAdmin = in_array($role, \App\Constants\Roles::ADMIN_ROLES);
                        $isManager = $role === \App\Constants\Roles::MANAGER_UNIT_HEAD;
                        $isSupervisor = $role === \App\Constants\Roles::SUPERVISOR;
                        $isEmployee = $role === \App\Constants\Roles::EMPLOYEE;

                        $isDevOrAdmin = $isAdmin || $isSuperAdmin;
                        $isManagerOrAdmin = $isAdmin || $isSuperAdmin || $isManager;
                        $isStaff = $isManagerOrAdmin || $isSupervisor || $isEmployee;

                        $activeDashboard = request()->is('dashboard');
                        $activeEmployees = request()->is('employees*');
                        $activeEmployeeApprovals = request()->is('employee-approvals*');
                        $activeDepartments = request()->is('departments*');
                        $activeOfficeLocations = request()->is('office-locations*');
                        $activeRoles = request()->is('roles*');
                        $activeTasks = request()->is('tasks*');
                        $activeLeaveRequests = request()->is('leave-requests*');
                        $activeIncidents = request()->is('incidents*');

                        $activePayrolls = request()->is('payrolls*');
                        $activeKpiDashboard = request()->is('kpi/dashboard*') || request()->is('kpi-dashboard*');
                        $activeKpiTeam = request()->is('kpi/team*');
                        $activeKpiDepartment = request()->is('kpi/department*');
                        $activeKpiPending = request()->is('kpi/pending*') || request()->is('kpi/pending-approvals*');

                        $activeInventoryCategories = request()->is('inventory-categories*');
                        $activeInventories = request()->is('inventories*');
                        $activeInventoryUsage = request()->is('inventory-usage-logs*');
                        $activeInventoryRequests = request()->is('inventory-requests*');
                        $activeVendors = request()->is('vendors*');
                        $activeProcurements = request()->is('procurements*');
                        $activeInventoryDispatches = request()->is('inventory-dispatches*');
                        $activeLogisticsShipments = request()->is('logistics-shipments*');

                        $activeLetters = request()->is('letters*');
                        $activeLetterTemplates = request()->is('letter-templates*');
                        $activeLetterConfigs = request()->is('letter-configurations*');
                        $activeLetterArchives = request()->is('letter-archives*');
                        $activeSignatureLogs = request()->is('signature-logs*');

                        $activeReportsExec = request()->is('reports/executive*');
                        $activeReportsMonthly = request()->is('reports/monthly-recap*');

                        // Dynamic visibility checks
                        $hasInventoryAccess = $user->hasAnyAccess(['inventory', 'inventory_logs', 'inventory_usage', 'inventory_requests']);
                        $hasKpiAccess = $user->hasAccess('hr_reports');
                        $hasAttendanceAccess = $user->hasAccess('attendance');

                        // Visibility helpers
                        $isSuperAdmin = $user->isSuperAdmin();
                        $showPayrollGroup = $isSuperAdmin || $isAdmin || $isManager || $hasKpiAccess;
                        $showInventoryLogs = $isSuperAdmin || $isAdmin || $isManager || $user->hasAccess('inventory_logs');
                        $showInventoryAdmin = $isSuperAdmin || $isAdmin || $user->hasAccess('inventory');

                        $systemMenuActive = $activeRoles || request()->is('audit-trail*') || request()->is('system*');
                        $hrMenuActive = $activeEmployees || $activeEmployeeApprovals || $activeDepartments || $activeOfficeLocations || $activeTasks || $activeLeaveRequests || $activeIncidents;
                        $payrollMenuActive = $activePayrolls || $activeKpiDashboard || $activeKpiTeam || $activeKpiDepartment || $activeKpiPending;
                        $inventoryMenuActive = $activeInventoryCategories || $activeInventories || $activeInventoryUsage || $activeInventoryRequests || $activeVendors || $activeProcurements || $activeInventoryDispatches || $activeLogisticsShipments;
                        $lettersMenuActive = $activeLetters || $activeLetterTemplates || $activeLetterConfigs || $activeLetterArchives || $activeSignatureLogs;
                        $reportsMenuActive = $activeReportsExec || $activeReportsMonthly;
                        $personalMenuActive = request()->is('my-profile') || request()->is('presences') || request()->is('knowledge-base*');
                    ?>

                    <!-- DASHBOARD -->
                    <li class="sidebar-item <?php echo e($activeDashboard ? 'active' : ''); ?>">
                        <a href="<?php echo e(url('/dashboard')); ?>" class="sidebar-link">
                            <i class="bi bi-grid-fill"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <!-- SYSTEM SETTINGS (Super Admin Only) -->
                    <?php if($isSuperAdmin): ?>
                    <li class="menu-group <?php echo e($systemMenuActive ? 'expanded' : ''); ?>">
                        <div class="menu-group-header">
                            <i class="bi bi-gear-fill group-icon"></i>
                            <span>System Settings</span>
                            <i class="bi bi-chevron-right chevron"></i>
                        </div>
                        <ul class="menu-group-items">
                            <li class="sidebar-item <?php echo e($activeRoles ? 'active' : ''); ?>">
                                <a href="<?php echo e(url('/roles')); ?>" class="sidebar-link">
                                    <i class="bi bi-shield-lock"></i>
                                    <span>Roles & Permissions</span>
                                </a>
                            </li>
                            <li class="sidebar-item <?php echo e(request()->is('audit-trail*') ? 'active' : ''); ?>">
                                <a href="<?php echo e(url('/audit-trail')); ?>" class="sidebar-link">
                                    <i class="bi bi-history"></i>
                                    <span>Audit Trail</span>
                                </a>
                            </li>
                            <li class="sidebar-item <?php echo e(request()->is('system*') ? 'active' : ''); ?>">
                                <a href="<?php echo e(url('/system')); ?>" class="sidebar-link">
                                    <i class="bi bi-cpu"></i>
                                    <span>System Management</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <!-- HR Administrator MANAGEMENT -->
                    <li class="menu-group <?php echo e($hrMenuActive ? 'expanded' : ''); ?>">
                        <div class="menu-group-header">
                            <i class="bi bi-people-fill group-icon"></i>
                            <span>HR Administrator Management</span>
                            <i class="bi bi-chevron-right chevron"></i>
                        </div>
                        <ul class="menu-group-items">
                            <?php if($isAdmin || $isManager): ?>
                            <li class="sidebar-item <?php echo e($activeEmployees ? 'active' : ''); ?>">
                                <a href="<?php echo e(url('/employees')); ?>" class="sidebar-link">
                                    <i class="bi bi-people-fill"></i>
                                    <span>Employees</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if($isAdmin || $isSuperAdmin): ?>
                            <li class="sidebar-item <?php echo e($activeEmployeeApprovals ? 'active' : ''); ?>">
                                <a href="<?php echo e(url('/employee-approvals')); ?>" class="sidebar-link">
                                    <i class="bi bi-check-circle"></i>
                                    <span>Update Approvals</span>
                                </a>
                            </li>
                            <li class="sidebar-item <?php echo e($activeDepartments ? 'active' : ''); ?>">
                                <a href="<?php echo e(url('/departments')); ?>" class="sidebar-link">
                                    <i class="bi bi-building"></i>
                                    <span>Departments</span>
                                </a>
                            </li>
                            <li class="sidebar-item <?php echo e($activeOfficeLocations ? 'active' : ''); ?>">
                                <a href="<?php echo e(url('/office-locations')); ?>" class="sidebar-link">
                                    <i class="bi bi-geo-alt-fill"></i>
                                    <span>Office Locations</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <li class="sidebar-item <?php echo e($activeTasks ? 'active' : ''); ?>">
                                <a href="<?php echo e(url('/tasks')); ?>" class="sidebar-link">
                                    <i class="bi bi-list-task"></i>
                                    <span>Tasks</span>
                                </a>
                            </li>
                            <li class="sidebar-item <?php echo e($activeLeaveRequests ? 'active' : ''); ?>">
                                <a href="<?php echo e(url('/leave-requests')); ?>" class="sidebar-link">
                                    <i class="bi bi-calendar-x"></i>
                                    <span>Leave Requests</span>
                                </a>
                            </li>
                            <li class="sidebar-item <?php echo e($activeIncidents ? 'active' : ''); ?>">
                                <a href="<?php echo e(url('/incidents')); ?>" class="sidebar-link">
                                    <i class="bi bi-award"></i>
                                    <span>Incidents & Awards</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- PAYROLL & KPI -->
                    <?php if($showPayrollGroup): ?>
                    <li class="menu-group <?php echo e($payrollMenuActive ? 'expanded' : ''); ?>">
                        <div class="menu-group-header">
                            <i class="bi bi-currency-dollar group-icon"></i>
                            <span>Payroll & KPI</span>
                            <i class="bi bi-chevron-right chevron"></i>
                        </div>
                        <ul class="menu-group-items">
                            <?php if($isSuperAdmin || false || $hasKpiAccess): ?>
                            <li class="sidebar-item <?php echo e($activePayrolls ? 'active' : ''); ?>">
                                <a href="<?php echo e(url('/payrolls')); ?>" class="sidebar-link">
                                    <i class="bi bi-currency-dollar"></i>
                                    <span>Payrolls</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if($user->hasAccess('hr_reports') || $isAdmin || $isManager): ?>
                            <li class="sidebar-item <?php echo e($activeKpiDashboard ? 'active' : ''); ?>">
                                <a href="<?php echo e(url('/kpi/dashboard')); ?>" class="sidebar-link">
                                    <i class="bi bi-speedometer2"></i>
                                    <span>KPI Dashboard</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if($isSuperAdmin || $isManager): ?>
                            <li class="sidebar-item <?php echo e($activeKpiTeam ? 'active' : ''); ?>">
                                <a href="<?php echo e(url('/kpi/team')); ?>" class="sidebar-link">
                                    <i class="bi bi-people"></i>
                                    <span>Team KPI</span>
                                </a>
                            </li>
                            <li class="sidebar-item <?php echo e($activeKpiDepartment ? 'active' : ''); ?>">
                                <a href="<?php echo e(url('/kpi/department')); ?>" class="sidebar-link">
                                    <i class="bi bi-diagram-3"></i>
                                    <span>Department KPI</span>
                                </a>
                            </li>
                            <li class="sidebar-item <?php echo e($activeKpiPending ? 'active' : ''); ?>">
                                <a href="<?php echo e(url('/kpi/pending')); ?>" class="sidebar-link">
                                    <i class="bi bi-hourglass-split"></i>
                                    <span>Pending Approvals</span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <!-- INVENTORY -->
                    <li class="menu-group <?php echo e($inventoryMenuActive ? 'expanded' : ''); ?>">
                        <div class="menu-group-header">
                            <i class="bi bi-boxes group-icon"></i>
                            <span>Inventory</span>
                            <i class="bi bi-chevron-right chevron"></i>
                        </div>
                        <ul class="menu-group-items">
                            <?php if($showInventoryAdmin): ?>
                            <li class="sidebar-item <?php echo e($activeInventoryCategories ? 'active' : ''); ?>">
                                <a href="<?php echo e(url('/inventory-categories')); ?>" class="sidebar-link">
                                    <i class="bi bi-tags"></i>
                                    <span>Categories</span>
                                </a>
                            </li>
                            <li class="sidebar-item <?php echo e($activeInventories ? 'active' : ''); ?>">
                                <a href="<?php echo e(url('/inventories')); ?>" class="sidebar-link">
                                    <i class="bi bi-boxes"></i>
                                    <span>Inventories</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if($showInventoryLogs): ?>
                            <li class="sidebar-item <?php echo e($activeInventoryUsage ? 'active' : ''); ?>">
                                <a href="<?php echo e(url('/inventory-usage-logs')); ?>" class="sidebar-link">
                                    <i class="bi bi-journal-text"></i>
                                    <span>Usage Logs</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if($user->hasAccess('inventory_requests') || $isStaff): ?>
                            <li class="sidebar-item <?php echo e($activeInventoryRequests ? 'active' : ''); ?>">
                                <a href="<?php echo e(url('/inventory-requests')); ?>" class="sidebar-link">
                                    <i class="bi bi-cart-plus"></i>
                                    <span>Requests</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if($isSuperAdmin || false || $user->hasAccess('inventory')): ?>
                            <hr class="mx-3 my-1 border-light opacity-25">
                            <li class="sidebar-item <?php echo e($activeVendors ? 'active' : ''); ?>">
                                <a href="<?php echo e(url('/vendors')); ?>" class="sidebar-link">
                                    <i class="bi bi-shop"></i>
                                    <span>Vendors</span>
                                </a>
                            </li>
                            <li class="sidebar-item <?php echo e($activeProcurements ? 'active' : ''); ?>">
                                <a href="<?php echo e(url('/procurements')); ?>" class="sidebar-link">
                                    <i class="bi bi-file-earmark-medical"></i>
                                    <span>Procurements</span>
                                </a>
                            </li>
                            <li class="sidebar-item <?php echo e($activeInventoryDispatches ? 'active' : ''); ?>">
                                <a href="<?php echo e(url('/inventory-dispatches')); ?>" class="sidebar-link">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Releases & Barcode</span>
                                </a>
                            </li>
                            <li class="sidebar-item <?php echo e($activeLogisticsShipments ? 'active' : ''); ?>">
                                <a href="<?php echo e(url('/logistics-shipments')); ?>" class="sidebar-link">
                                    <i class="bi bi-truck"></i>
                                    <span>Logistics Tracking</span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>

                    <!-- LETTERS -->
                    <li class="menu-group <?php echo e($lettersMenuActive ? 'expanded' : ''); ?>">
                        <div class="menu-group-header">
                            <i class="bi bi-envelope-fill group-icon"></i>
                            <span>Letters</span>
                            <i class="bi bi-chevron-right chevron"></i>
                        </div>
                        <ul class="menu-group-items">
                            <li class="sidebar-item <?php echo e($activeLetters ? 'active' : ''); ?>">
                                <a href="<?php echo e(url('/letters')); ?>" class="sidebar-link">
                                    <i class="bi bi-envelope-fill"></i>
                                    <span>Letters</span>
                                </a>
                            </li>
                            <?php if($isAdmin): ?>
                            <li class="sidebar-item <?php echo e($activeLetterTemplates ? 'active' : ''); ?>">
                                <a href="<?php echo e(url('/letter-templates')); ?>" class="sidebar-link">
                                    <i class="bi bi-file-earmark-ruled"></i>
                                    <span>Templates</span>
                                </a>
                            </li>
                            <li class="sidebar-item <?php echo e($activeLetterConfigs ? 'active' : ''); ?>">
                                <a href="<?php echo e(url('/letter-configurations')); ?>" class="sidebar-link">
                                    <i class="bi bi-gear"></i>
                                    <span>Configurations</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if($isDevOrAdmin): ?>
                            <li class="sidebar-item <?php echo e($activeLetterArchives ? 'active' : ''); ?>">
                                <a href="<?php echo e(url('/letter-archives')); ?>" class="sidebar-link">
                                    <i class="bi bi-archive"></i>
                                    <span>Archives</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <li class="sidebar-item <?php echo e($activeSignatureLogs ? 'active' : ''); ?>">
                                <a href="<?php echo e(url('/signature-logs')); ?>" class="sidebar-link">
                                    <i class="bi bi-pen"></i>
                                    <span>Signature Logs</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- REPORTS -->
                    <?php if($isManagerOrAdmin): ?>
                    <li class="menu-group <?php echo e($reportsMenuActive ? 'expanded' : ''); ?>">
                        <div class="menu-group-header">
                            <i class="bi bi-file-earmark-text group-icon"></i>
                            <span>Reports</span>
                            <i class="bi bi-chevron-right chevron"></i>
                        </div>
                        <ul class="menu-group-items">
                            <?php if($isAdmin): ?>
                            <li class="sidebar-item <?php echo e($activeReportsExec ? 'active' : ''); ?>">
                                <a href="<?php echo e(url('/reports/executive')); ?>" class="sidebar-link">
                                    <i class="bi bi-graph-up"></i>
                                    <span>Executive Report</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <li class="sidebar-item <?php echo e($activeReportsMonthly ? 'active' : ''); ?>">
                                <a href="<?php echo e(url('/reports/monthly-recap')); ?>" class="sidebar-link">
                                    <i class="bi bi-file-earmark-text"></i>
                                    <span>Monthly Report</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <!-- PERSONAL -->
                    <li class="menu-group <?php echo e($personalMenuActive ? 'expanded' : ''); ?>">
                        <div class="menu-group-header">
                            <i class="bi bi-person-fill group-icon"></i>
                            <span>Personal</span>
                            <i class="bi bi-chevron-right chevron"></i>
                        </div>
                        <ul class="menu-group-items">

                            <li class="sidebar-item <?php echo e(request()->is('my-profile') ? 'active' : ''); ?>">
                                <a href="<?php echo e(url('/my-profile')); ?>" class="sidebar-link">
                                    <i class="bi bi-person-fill"></i>
                                    <span>My Profile</span>
                                </a>
                            </li>

                            <li class="sidebar-item <?php echo e(request()->is('presences') ? 'active' : ''); ?>">
                                <a href="<?php echo e(url('/presences')); ?>" class="sidebar-link">
                                    <i class="bi bi-table"></i>
                                    <span>Presences</span>
                                </a>
                            </li>

                            <li class="sidebar-item <?php echo e(request()->is('knowledge-base*') ? 'active' : ''); ?>">
                                <a href="<?php echo e(url('/knowledge-base')); ?>" class="sidebar-link">
                                    <i class="bi bi-book"></i>
                                    <span>Knowledge Base</span>
                                </a>
                            </li>

                            <li class="sidebar-item">
                                <a href="<?php echo e(url('/logout')); ?>" class="sidebar-link">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Logout</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                </ul>
            </div>
        </div>
    </div>
    <!-- ================= END SIDEBAR ================= -->

    <!-- ================= MAIN ================= -->
    <div id="main">

        <header class="mobile-nav-header d-xl-none mb-3">
            <a href="#" class="burger-btn mobile-burger-btn" aria-label="Buka menu navigasi">
                <i class="bi bi-list"></i>
            </a>
        </header>

        <?php echo $__env->yieldContent('content'); ?>

        <footer class="footer clearfix mb-0 text-muted">
            <div class="float-start">
                <p>2025 &copy; PT Aratech Nusantara Indonesia</p>
            </div>
            <div class="float-end">
                <p>Crafted by <a href="https://aratechnology.id">Aratech</a></p>
            </div>
        </footer>
    </div>

</div>

<!-- ================= JS ================= -->
<script src="<?php echo e(asset('vendor/jquery/jquery-3.7.1.min.js')); ?>"></script>
<script src="<?php echo e(asset('vendor/datatables/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('vendor/datatables/dataTables.bootstrap5.min.js')); ?>"></script>
<script src="<?php echo e(asset('vendor/datatables/dataTables.responsive.min.js')); ?>"></script>
<script src="<?php echo e(asset('vendor/datatables/responsive.bootstrap5.min.js')); ?>"></script>
<script src="<?php echo e(asset('mazer/assets/compiled/js/app.js')); ?>?v=<?php echo e(time()); ?>"></script>
<script src="<?php echo e(asset('vendor/flatpickr/flatpickr.min.js')); ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const bodyEl = document.body;
    const sidebarWrapper = $('#sidebar .sidebar-wrapper');
    const DESKTOP_BREAKPOINT = 1200;

    function isDesktopViewport() {
        return window.innerWidth >= DESKTOP_BREAKPOINT;
    }

    function syncSidebarLayout() {
        bodyEl.classList.remove('sidebar-collapsed');

        if (isDesktopViewport()) {
            sidebarWrapper.addClass('active');
            return;
        }

        sidebarWrapper.removeClass('active');
    }

    syncSidebarLayout();
    $(window).on('resize', syncSidebarLayout);

    $(document).on('click', '.burger-btn', function(e) {
        e.preventDefault();

        if (!isDesktopViewport()) {
            sidebarWrapper.toggleClass('active');
        }
    });

    // Close mobile sidebar when clicking outside
    $(document).on('click', function(e) {
        if (window.innerWidth < 1200) {
            if (!$(e.target).closest('#sidebar').length && !$(e.target).closest('.burger-btn').length) {
                sidebarWrapper.removeClass('active');
            }
        }
    });

    flatpickr(".date", { dateFormat: "Y-m-d" });

    // Global AJAX Setup for CSRF
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Global DataTable Defaults
    $.extend(true, $.fn.dataTable.defaults, {
        responsive: true,
        language: {
            processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
            lengthMenu: "Tampilkan _MENU_ data",
            zeroRecords: "Data tidak ditemukan",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
            infoFiltered: "(disaring dari _MAX_ total data)",
            search: "Cari:",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        },
        drawCallback: function(settings) {
            // Re-initialize tooltips or any global UI components if needed
        }
    });

    // Handle AJAX Errors Globally
    $(document).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
        if (jqXHR.status === 403) {
            Swal.fire('Unauthorized', 'Anda tidak memiliki akses untuk tindakan ini.', 'error');
        } else if (jqXHR.status === 500) {
            Swal.fire('Server Error', 'Terjadi kesalahan pada server. Silakan coba lagi nanti.', 'error');
        }
    });

    // Global Delete Confirmation Helper
    window.confirmDelete = function(formOrUrl, message = 'Apakah Anda yakin ingin menghapus data ini?') {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                if (typeof formOrUrl === 'string') {
                    window.location.href = formOrUrl;
                } else {
                    formOrUrl.submit();
                }
            }
        });
        return false;
    };

    // Menu group collapse/expand toggle
    document.querySelectorAll('.menu-group-header').forEach(function(header) {
        header.addEventListener('click', function() {
            this.closest('.menu-group').classList.toggle('expanded');
        });
    });

    // Theme Toggle Logic (with null-safety)
    const themeToggle = document.getElementById('theme-toggle');
    const themeToggleIcon = document.getElementById('theme-toggle-icon');

    function updateIcon(theme) {
        if (!themeToggleIcon) return;
        if (theme === 'dark') {
            themeToggleIcon.className = 'bi bi-moon-fill';
        } else {
            themeToggleIcon.className = 'bi bi-sun-fill';
        }
    }

    // Sync icon on load
    updateIcon(localStorage.getItem('theme') || 'light');

    if (themeToggle) {
        themeToggle.addEventListener('click', (e) => {
            e.preventDefault();
            const currentTheme = localStorage.getItem('theme') || 'light';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            localStorage.setItem('theme', newTheme);

            if (newTheme === 'dark') {
                document.documentElement.setAttribute('data-bs-theme', 'dark');
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.setAttribute('data-bs-theme', 'light');
                document.documentElement.classList.remove('dark');
            }

            updateIcon(newTheme);
        });
    }
  // FIX: paksa sidebar & hamburger tetap aman di dark mode
function fixHamburgerDarkMode() {
    const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';

    if (isDark) {
        $('.mobile-nav-header').show();
        $('.burger-btn').show();
    }
}

// jalankan saat load
fixHamburgerDarkMode();

// jalankan setiap theme berubah
const observer = new MutationObserver(() => {
    fixHamburgerDarkMode();
});
observer.observe(document.documentElement, {
    attributes: true,
    attributeFilter: ['data-bs-theme']
});
</script>

<?php echo $__env->yieldPushContent('scripts'); ?>

</body>
</html>
<?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/layouts/dashboard.blade.php ENDPATH**/ ?>