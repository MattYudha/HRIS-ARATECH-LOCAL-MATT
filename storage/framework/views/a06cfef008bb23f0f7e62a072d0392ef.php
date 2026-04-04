<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e(config('app.name', 'Laravel')); ?></title>

        <!-- Favicon -->
        <link rel="shortcut icon" href="<?php echo e(asset('img/HRIS ARATECH logo tr.png')); ?>" type="image/png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
        <link rel="stylesheet" href="<?php echo e(asset('vendor/bootstrap-icons/font/bootstrap-icons.min.css')); ?>">
        <script src="<?php echo e(asset('mazer/assets/static/js/initTheme.js')); ?>"></script>

        <style>
            /* ===== LIGHT MODE (default) ===== */
            html.light body.guest-body {
                background-color: #f3f4f6;
                color: #111827;
            }
            html.light .login-wrapper {
                background-color: #f3f4f6;
            }
            html.light .login-card {
                background-color: #ffffff;
                border: 1px solid #e5e7eb;
                box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            }
            html.light .login-card input[type="email"],
            html.light .login-card input[type="password"] {
                background-color: #ffffff;
                border-color: #d1d5db;
                color: #111827;
            }
            html.light .login-card label,
            html.light .login-card .input-label {
                color: #374151;
            }
            html.light .login-subtitle {
                color: #6b7280;
            }
            html.light .login-company {
                color: #9ca3af;
            }
            html.light .remember-text {
                color: #6b7280;
            }
            html.light .forgot-link {
                color: #4f46e5;
            }
            html.light .forgot-link:hover {
                color: #4338ca;
            }
            html.light .theme-toggle-btn {
                background-color: rgba(255,255,255,0.9);
                border-color: #d1d5db;
                color: #f59e0b;
            }
            html.light .theme-toggle-btn:hover {
                background-color: #f3f4f6;
            }
            html.light .login-card input[type="checkbox"] {
                border-color: #d1d5db;
                background-color: #ffffff;
            }

            /* ===== DARK MODE ===== */
            html.dark body.guest-body {
                background-color: #111827;
                color: #f9fafb;
            }
            html.dark .login-wrapper {
                background-color: #111827;
            }
            html.dark .login-card {
                background-color: #1f2937;
                border: 1px solid #374151;
                box-shadow: 0 4px 6px -1px rgba(0,0,0,0.4);
            }
            html.dark .login-card input[type="email"],
            html.dark .login-card input[type="password"] {
                background-color: #111827;
                border-color: #4b5563;
                color: #f9fafb;
            }
            html.dark .login-card input[type="email"]:focus,
            html.dark .login-card input[type="password"]:focus {
                border-color: #6366f1;
                box-shadow: 0 0 0 2px rgba(99,102,241,0.3);
            }
            html.dark .login-card label,
            html.dark .login-card .input-label {
                color: #d1d5db;
            }
            html.dark .login-subtitle {
                color: #9ca3af;
            }
            html.dark .login-company {
                color: #6b7280;
            }
            html.dark .remember-text {
                color: #9ca3af;
            }
            html.dark .forgot-link {
                color: #818cf8;
            }
            html.dark .forgot-link:hover {
                color: #a5b4fc;
            }
            html.dark .theme-toggle-btn {
                background-color: rgba(55,65,81,0.9);
                border-color: #4b5563;
                color: #fbbf24;
            }
            html.dark .theme-toggle-btn:hover {
                background-color: #4b5563;
            }
            html.dark .login-card input[type="checkbox"] {
                border-color: #4b5563;
                background-color: #111827;
            }

            /* Toggle button base */
            .theme-toggle-btn {
                padding: 10px;
                border-radius: 50%;
                border: 1px solid;
                cursor: pointer;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                justify-content: center;
                backdrop-filter: blur(8px);
                box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            }
            .theme-toggle-btn:hover {
                transform: scale(1.1);
            }
            .theme-toggle-btn i {
                font-size: 18px;
                line-height: 1;
            }
        </style>
    </head>
    <body class="font-sans antialiased guest-body">
        <!-- Theme Toggle Button -->
        <div style="position:fixed; top:16px; right:16px; z-index:9999;">
            <button id="theme-toggle" type="button" class="theme-toggle-btn" title="Toggle Dark/Light Mode">
                <i id="theme-toggle-icon" class="bi bi-sun-fill"></i>
            </button>
        </div>

        <!-- Login Content -->
        <div class="login-wrapper" style="min-height:100vh; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:24px 0; transition: background-color 0.3s ease;">
            <div class="login-card" style="width:100%; max-width:28rem; padding:24px; border-radius:8px; transition: all 0.3s ease;">
                <?php echo e($slot); ?>

            </div>
        </div>

        <script>
            const themeToggle = document.getElementById('theme-toggle');
            const themeToggleIcon = document.getElementById('theme-toggle-icon');
            const html = document.documentElement;

            function applyTheme(theme) {
                html.classList.remove('dark', 'light');
                html.classList.add(theme);
                html.setAttribute('data-bs-theme', theme);

                if (theme === 'dark') {
                    themeToggleIcon.className = 'bi bi-moon-fill';
                } else {
                    themeToggleIcon.className = 'bi bi-sun-fill';
                }
            }

            // Apply saved theme on load
            applyTheme(localStorage.getItem('theme') || 'dark');

            themeToggle.addEventListener('click', () => {
                const current = localStorage.getItem('theme') || 'dark';
                const next = current === 'dark' ? 'light' : 'dark';
                localStorage.setItem('theme', next);
                applyTheme(next);
            });
        </script>
    </body>
</html><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/layouts/guest.blade.php ENDPATH**/ ?>