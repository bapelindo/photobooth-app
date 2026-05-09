<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Dashboard' ?> - Photobooth Pro</title>
    <?php App\Core\Session::start(); ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <style>
        :root {
            /* Modern Enterprise Light Theme */
            --bg-body: #f8fafc;
            --bg-surface: #ffffff;
            --bg-surface-hover: #f1f5f9;
            
            /* Sidebar (Dark) */
            --sidebar-bg: #0f172a;
            --sidebar-surface: #1e293b;
            --sidebar-border: #334155;
            --sidebar-text: #94a3b8;
            --sidebar-text-active: #f8fafc;
            --sidebar-active-bg: #1e293b;

            /* Colors */
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --primary-light: #eff6ff;
            --primary-text: #1e40af;

            --success: #059669;
            --success-bg: #ecfdf5;
            --success-text: #065f46;

            --warning: #d97706;
            --warning-bg: #fffbeb;
            --warning-text: #92400e;

            --danger: #dc2626;
            --danger-bg: #fef2f2;
            --danger-text: #991b1b;

            /* Text */
            --text-main: #0f172a;
            --text-muted: #64748b;
            --text-light: #94a3b8;

            /* Borders & Shadows */
            --border-color: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --radius-md: 0.5rem;
            --radius-lg: 0.75rem;
            --radius-xl: 1rem;
            
            --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Typography */
        h1, h2, h3, h4, h5, h6 { color: var(--text-main); font-weight: 600; letter-spacing: -0.025em; }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            border-right: 1px solid var(--sidebar-border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; height: 100vh;
            z-index: 40;
            transition: transform 0.3s ease;
        }

        .sidebar-brand {
            height: 64px;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            color: white;
            font-size: 1.125rem;
            font-weight: 600;
            border-bottom: 1px solid var(--sidebar-border);
            gap: 0.75rem;
        }
        .sidebar-brand .feather { color: var(--primary); }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem 1rem;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: var(--sidebar-border); border-radius: 4px; }

        .nav-group-title {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--sidebar-text);
            font-weight: 600;
            margin-bottom: 0.5rem;
            padding-left: 0.75rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 0.75rem;
            color: var(--sidebar-text);
            text-decoration: none;
            border-radius: var(--radius-md);
            font-size: 0.875rem;
            font-weight: 500;
            transition: var(--transition);
            margin-bottom: 0.125rem;
        }

        .nav-item:hover {
            color: var(--sidebar-text-active);
            background-color: var(--sidebar-surface);
        }

        .nav-item.active {
            color: var(--primary-light);
            background-color: var(--sidebar-active-bg);
            border-left: 3px solid var(--primary);
        }
        .nav-item.active .feather { color: var(--primary); }
        .nav-item .feather { width: 18px; height: 18px; transition: var(--transition); }

        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid var(--sidebar-border);
        }
        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem;
            border-radius: var(--radius-md);
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 0.875rem;
            transition: var(--transition);
        }
        .user-profile:hover {
            background-color: var(--sidebar-surface);
            color: var(--sidebar-text-active);
        }
        .user-avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            background-color: var(--primary);
            color: white;
            display: flex; align-items: center; justify-content: center;
            font-weight: 600; font-size: 0.875rem;
        }

        /* Main Content */
        .main-wrapper {
            flex: 1;
            margin-left: 260px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Top Header */
        .top-header {
            height: 64px;
            background-color: var(--bg-surface);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky;
            top: 0; z-index: 30;
        }

        .mobile-toggle {
            display: none;
            background: none; border: none;
            color: var(--text-muted);
            cursor: pointer;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        /* Main Content Area */
        .main-content {
            padding: 2rem;
            flex: 1;
            max-width: 1400px;
            width: 100%;
            margin: 0 auto;
        }

        /* Components: Cards */
        .card {
            background-color: var(--bg-surface);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }
        .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card-title { font-size: 1rem; font-weight: 600; color: var(--text-main); }
        .card-body { padding: 1.5rem; }

        /* Components: Tables */
        .table-responsive { overflow-x: auto; }
        .table { width: 100%; border-collapse: collapse; text-align: left; }
        .table th {
            padding: 0.75rem 1.5rem;
            background-color: var(--bg-body);
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid var(--border-color);
        }
        .table td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.875rem;
            color: var(--text-main);
            vertical-align: middle;
        }
        .table tbody tr:hover { background-color: var(--bg-surface-hover); }

        /* Components: Buttons */
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
            padding: 0.5rem 1rem;
            font-size: 0.875rem; font-weight: 500;
            border-radius: var(--radius-md);
            border: 1px solid transparent;
            cursor: pointer; transition: var(--transition);
            text-decoration: none;
        }
        .btn-primary { background-color: var(--primary); color: white; box-shadow: var(--shadow-sm); }
        .btn-primary:hover { background-color: var(--primary-hover); }
        .btn-secondary { background-color: white; color: var(--text-main); border-color: var(--border-color); box-shadow: var(--shadow-sm); }
        .btn-secondary:hover { background-color: var(--bg-body); border-color: #cbd5e1; }
        .btn-danger { background-color: var(--danger); color: white; }
        .btn-danger:hover { background-color: #b91c1c; }
        .btn-sm { padding: 0.375rem 0.75rem; font-size: 0.75rem; }

        /* Components: Forms */
        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-main); margin-bottom: 0.5rem; }
        .form-control {
            width: 100%; padding: 0.5rem 0.75rem;
            font-family: inherit; font-size: 0.875rem;
            color: var(--text-main); background-color: white;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
        }
        .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); }

        /* Components: Badges */
        .badge {
            display: inline-flex; align-items: center;
            padding: 0.125rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem; font-weight: 500;
        }
        .badge-success { background-color: var(--success-bg); color: var(--success-text); }
        .badge-warning { background-color: var(--warning-bg); color: var(--warning-text); }
        .badge-danger { background-color: var(--danger-bg); color: var(--danger-text); }
        .badge-primary { background-color: var(--primary-light); color: var(--primary-text); }

        /* Alerts/Toasts */
        .toast-container { position: fixed; bottom: 2rem; right: 2rem; z-index: 50; display: flex; flex-direction: column; gap: 0.5rem; }
        .toast {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 1rem 1.25rem;
            background-color: white;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-lg);
            border-left: 4px solid var(--primary);
            animation: slideInRight 0.3s ease-out;
            min-width: 300px;
        }
        .toast.success { border-left-color: var(--success); }
        .toast.error { border-left-color: var(--danger); }
        .toast-icon { display: flex; align-items: center; justify-content: center; }
        .toast.success .toast-icon { color: var(--success); }
        .toast.error .toast-icon { color: var(--danger); }
        .toast-content { flex: 1; font-size: 0.875rem; font-weight: 500; color: var(--text-main); }
        .toast-close { background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 0; display: flex; }

        @keyframes slideInRight { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes fadeOut { to { opacity: 0; } }

        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.mobile-open { transform: translateX(0); box-shadow: var(--shadow-lg); }
            .main-wrapper { margin-left: 0; }
            .mobile-toggle { display: block; }
            .top-header { padding: 0 1rem; }
            .main-content { padding: 1.5rem 1rem; }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <i data-feather="aperture"></i>
            <span>Photobooth Pro</span>
        </div>
        
        <nav class="sidebar-nav">
            <div>
                <div class="nav-group-title">Overview</div>
                <a href="<?= URLROOT ?>/admin/dashboard" class="nav-item"><i data-feather="grid"></i> Dashboard</a>
                <a href="<?= URLROOT ?>/admin/packages" class="nav-item"><i data-feather="box"></i> Packages</a>
                <a href="<?= URLROOT ?>/admin/assets" class="nav-item"><i data-feather="image"></i> Assets</a>
            </div>
            
            <div>
                <div class="nav-group-title">Operations</div>
                <a href="<?= URLROOT ?>/admin/sessions" class="nav-item"><i data-feather="camera"></i> Photo Sessions</a>
                <a href="<?= URLROOT ?>/admin/photostrips" class="nav-item"><i data-feather="layers"></i> Photostrips</a>
                <a href="<?= URLROOT ?>/admin/gallery" class="nav-item"><i data-feather="folder"></i> Gallery</a>
            </div>
            
            <div>
                <div class="nav-group-title">System</div>
                <a href="<?= URLROOT ?>/admin/reports" class="nav-item"><i data-feather="pie-chart"></i> Reports</a>
                <a href="<?= URLROOT ?>/admin/queue" class="nav-item"><i data-feather="list"></i> Queue</a>
                <a href="<?= URLROOT ?>/admin/camera" class="nav-item"><i data-feather="camera-off"></i> Camera Control</a>
                <a href="<?= URLROOT ?>/admin/settings" class="nav-item"><i data-feather="settings"></i> Settings</a>
            </div>
        </nav>

        <div class="sidebar-footer">
            <a href="<?= URLROOT ?>/logout" class="user-profile">
                <div class="user-avatar">
                    <?= substr(htmlspecialchars(App\Core\Session::get('admin_username', 'A')), 0, 1) ?>
                </div>
                <div style="flex: 1; overflow: hidden;">
                    <div style="font-weight: 600; text-overflow: ellipsis; white-space: nowrap;"><?= htmlspecialchars(App\Core\Session::get('admin_username', 'Admin')) ?></div>
                    <div style="font-size: 0.75rem; color: var(--sidebar-text);">Sign out</div>
                </div>
                <i data-feather="log-out" style="width: 16px; height: 16px;"></i>
            </a>
        </div>
    </aside>

    <!-- Overlay for mobile sidebar -->
    <div id="sidebar-overlay" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 35; backdrop-filter: blur(2px);"></div>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Top Header -->
        <header class="top-header">
            <button class="mobile-toggle" onclick="toggleSidebar()">
                <i data-feather="menu"></i>
            </button>
            <div style="flex: 1;"></div>
            <div class="header-actions">
                <button class="btn btn-secondary btn-sm" style="border-radius: 9999px; width: 36px; height: 36px; padding: 0;">
                    <i data-feather="bell" style="width: 16px; height: 16px;"></i>
                </button>
            </div>
        </header>

        <!-- Content -->
        <main class="main-content">
            <?= $content ?? '' ?>
        </main>
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toast-container"></div>

    <?php if (\App\Core\Session::has('admin_flash_message')): ?>
        <?php
            $flashType = \App\Core\Session::get('admin_flash_type', 'info');
            $flashMessage = \App\Core\Session::get('admin_flash_message');
            \App\Core\Session::unset('admin_flash_message');
            \App\Core\Session::unset('admin_flash_type');
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('<?= addslashes($flashMessage) ?>', '<?= $flashType ?>');
            });
        </script>
    <?php endif; ?>

    <script>
        // Initialize Icons
        feather.replace();

        // Sidebar Toggle
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        
        function toggleSidebar() {
            sidebar.classList.toggle('mobile-open');
            overlay.style.display = sidebar.classList.contains('mobile-open') ? 'block' : 'none';
        }

        overlay.addEventListener('click', toggleSidebar);

        // Active Navigation Setup
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const navItems = document.querySelectorAll('.nav-item');
            
            let bestMatch = null;
            let longestMatch = 0;

            const adminRootPath = new URL('<?= URLROOT ?>/admin', window.location.origin).pathname;
            const dashboardPath = new URL('<?= URLROOT ?>/admin/dashboard', window.location.origin).pathname;

            if (currentPath === adminRootPath || currentPath === adminRootPath + '/') {
                navItems.forEach(link => {
                    if (new URL(link.href).pathname === dashboardPath) bestMatch = link;
                });
            } else {
                navItems.forEach(link => {
                    const linkPath = new URL(link.href).pathname;
                    if (currentPath.startsWith(linkPath) && linkPath.length > longestMatch) {
                        longestMatch = linkPath.length;
                        bestMatch = link;
                    }
                });
            }

            if (bestMatch) bestMatch.classList.add('active');
        });

        // Toast Notification System
        function showToast(message, type = 'info') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            // Normalize type
            if (type === 'danger') type = 'error';
            
            let icon = 'info';
            if (type === 'success') icon = 'check-circle';
            if (type === 'error') icon = 'alert-circle';
            if (type === 'warning') icon = 'alert-triangle';

            toast.className = `toast ${type}`;
            toast.innerHTML = `
                <div class="toast-icon"><i data-feather="${icon}"></i></div>
                <div class="toast-content">${message}</div>
                <button class="toast-close" onclick="this.parentElement.style.animation='fadeOut 0.3s forwards'; setTimeout(() => this.parentElement.remove(), 300)"><i data-feather="x"></i></button>
            `;
            
            container.appendChild(toast);
            feather.replace();

            // Auto dismiss
            setTimeout(() => {
                if (document.body.contains(toast)) {
                    toast.style.animation = 'fadeOut 0.3s forwards';
                    setTimeout(() => toast.remove(), 300);
                }
            }, 4000);
        }
        
        // Expose showAdminMessage globally for backward compatibility
        window.showAdminMessage = showToast;
        window.showMessage = showToast;
    </script>
</body>
</html>