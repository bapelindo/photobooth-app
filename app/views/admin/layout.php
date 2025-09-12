<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - <?= $title ?? 'Photobooth App' ?></title>
    <?php App\Core\Session::start(); ?>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <style>
        :root {
            /* Dark Theme Colors */
            --bg-color: #0f172a;
            --bg-secondary: #1e293b;
            --bg-tertiary: #334155;
            
            --sidebar-bg: #1e293b;
            --sidebar-secondary: #334155;
            --sidebar-text: #cbd5e1;
            --sidebar-active: #3b82f6;
            
            --card-bg: #1e293b;
            --card-secondary: #334155;
            --card-hover: #475569;
            
            --primary-color: #3b82f6;
            --primary-hover: #2563eb;
            --primary-light: #60a5fa;
            
            --secondary-color: #64748b;
            --secondary-hover: #475569;
            
            --success-color: #10b981;
            --success-light: #34d399;
            --warning-color: #f59e0b;
            --warning-light: #fbbf24;
            --error-color: #ef4444;
            --error-light: #f87171;
            
            --text-color: #f1f5f9;
            --text-secondary: #e2e8f0;
            --text-muted: #94a3b8;
            --text-dim: #64748b;
            
            --border-color: #334155;
            --border-light: #475569;
            --border-dark: #1e293b;
            
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.3), 0 2px 4px -2px rgb(0 0 0 / 0.2);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.4), 0 4px 6px -2px rgb(0 0 0 / 0.3);
            --shadow-dark: 0 20px 25px -5px rgb(0 0 0 / 0.5), 0 10px 10px -5px rgb(0 0 0 / 0.2);
            
            --border-radius: 0.75rem;
            --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            
            /* Gradients */
            --gradient-primary: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            --gradient-card: linear-gradient(135deg, var(--card-bg) 0%, var(--card-secondary) 100%);
            --gradient-sidebar: linear-gradient(180deg, var(--sidebar-bg) 0%, #0f172a 100%);
            --gradient-bg: linear-gradient(135deg, var(--bg-color) 0%, var(--bg-secondary) 100%);
        }
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--gradient-bg);
            color: var(--text-color);
            margin: 0;
            line-height: 1.6;
            min-height: 100vh;
        }
        .sidebar {
            width: 280px;
            background: var(--gradient-sidebar);
            padding: 0;
            display: flex;
            flex-direction: column;
            box-shadow: var(--shadow-dark);
            transition: var(--transition);
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 100;
            scroll-behavior: smooth;
            border-right: 1px solid var(--border-color);
        }

        /* Custom scrollbar for sidebar */
        .nav-links::-webkit-scrollbar {
            width: 6px;
        }

        .nav-links::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .nav-links::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .nav-links::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        .nav-links::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                padding: 0;
                overflow: hidden;
                transform: translateX(-100%);
            }
            .sidebar.mobile-open {
                width: 280px;
                padding: 2rem 0;
                transform: translateX(0);
                z-index: 1000;
            }
        }
        .sidebar-header {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-color);
            margin: 0;
            padding: 2rem 1.5rem 1.5rem 1.5rem;
            position: relative;
            border-bottom: 1px solid var(--border-color);
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .sidebar-header::after {
            display: none;
        }
        .nav-links {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            flex: 1;
            padding: 1rem 0.75rem 6rem 0.75rem;
            min-height: 0;
            overflow-y: auto;
        }

        .nav-group {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .nav-group-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 0.5rem 1rem 0.25rem 1rem;
            margin-bottom: 0.25rem;
            position: relative;
        }

        .nav-group-label::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 1rem;
            right: 1rem;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(148, 163, 184, 0.3), transparent);
        }
        .nav-links a {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            padding: 0.875rem 1rem;
            border-radius: 0.5rem;
            color: var(--sidebar-text);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: var(--transition);
            position: relative;
            margin: 0.125rem 0;
            line-height: 1.4;
            border: 1px solid transparent;
        }

        .nav-links a:hover {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            color: var(--text-color);
            transform: translateX(2px);
            border-color: var(--primary-light);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }

        .nav-links a.active {
            background: var(--gradient-primary);
            color: var(--text-color);
            box-shadow: 0 4px 16px rgba(59, 130, 246, 0.5);
            border-color: var(--primary-light);
        }

        .nav-links a.active:hover {
            background: linear-gradient(135deg, var(--primary-hover) 0%, #1d4ed8 100%);
            transform: translateX(0);
        }
        .nav-links a .feather {
            width: 20px;
            height: 20px;
            transition: var(--transition);
            flex-shrink: 0;
        }

        .nav-links a:hover .feather,
        .nav-links a.active .feather {
            transform: scale(1.05);
        }

        .nav-links a.nav-loading {
            opacity: 0.7;
            pointer-events: none;
        }

        .nav-links a.nav-loading::after {
            content: '';
            position: absolute;
            right: 1rem;
            width: 16px;
            height: 16px;
            border: 2px solid transparent;
            border-top: 2px solid #ffffff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        .user-info {
            margin-top: auto;
            padding: 0.5rem;
            border-top: 1px solid var(--border-color);
            background: var(--card-secondary);
        }

        .user-info a {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            text-decoration: none;
            color: var(--sidebar-text);
            font-weight: 500;
            padding: 0.875rem 1rem;
            border-radius: 0.5rem;
            transition: var(--transition);
            font-size: 0.9rem;
            line-height: 1.4;
            border: 1px solid transparent;
        }

        .user-info a:hover {
            background: linear-gradient(135deg, var(--error-color) 0%, #dc2626 100%);
            color: var(--text-color);
            transform: translateX(2px);
            border-color: var(--error-light);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        }

        .user-info a .feather {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }
        .main-content {
            padding: 2rem 2.5rem;
            background: var(--bg-color);
            margin-left: 280px;
            min-height: 100vh;
            position: relative;
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 1rem;
                margin-left: 0;
            }
        }

        .mobile-menu-btn {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1001;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem;
            cursor: pointer;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .mobile-menu-btn:hover {
            background-color: var(--primary-hover);
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: block;
            }
        }
        .page-header {
            margin-bottom: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            background: var(--gradient-card);
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
        }

        .page-header h1 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
            color: var(--text-color);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .page-header h1 .feather {
            color: var(--primary-color);
        }

        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 1.75rem;
                margin-left: 3rem;
            }
        }
        .card {
            background: var(--gradient-card);
            border-radius: 1rem;
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
            overflow: hidden;
            transition: var(--transition);
        }

        .card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
            border-color: var(--border-light);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 1rem 1.5rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
            color: var(--text-color);
        }
        th {
            background: linear-gradient(135deg, var(--card-secondary) 0%, var(--bg-tertiary) 100%);
            font-weight: 600;
            color: var(--text-secondary);
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            text-align: center;
            cursor: pointer;
            border: none;
            padding: 0.75rem 1.5rem;
            font-size: 0.925rem;
            border-radius: 0.75rem;
            text-decoration: none;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary { 
            color: #fff; 
            background: linear-gradient(135deg, var(--primary-color), var(--primary-hover)); 
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        .btn-primary:hover { 
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
        }

        .btn-secondary { 
            color: var(--text-color); 
            background: linear-gradient(135deg, var(--card-secondary) 0%, var(--bg-tertiary) 100%); 
            border: 1px solid var(--border-light); 
        }
        .btn-secondary:hover { 
            background: linear-gradient(135deg, var(--bg-tertiary) 0%, var(--card-hover) 100%);
            transform: translateY(-1px);
            border-color: var(--border-light);
        }

        .btn-danger { 
            color: #fff; 
            background: linear-gradient(135deg, var(--error-color), #dc2626);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }
        .btn-danger:hover { 
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.4);
        }

        .btn-sm { 
            padding: 0.5rem 1rem; 
            font-size: 0.875rem; 
        }

        .action-links { display: flex; gap: 0.5rem; }

        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: .5rem; font-weight: 500; }
        .form-control {
            display: block; 
            width: 100%; 
            padding: 0.875rem 1rem; 
            font-size: 1rem;
            color: var(--text-color); 
            background: var(--card-bg);
            border: 2px solid var(--border-color); 
            border-radius: 0.75rem;
            box-sizing: border-box; 
            transition: var(--transition);
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2);
            transform: translateY(-1px);
            background: var(--card-secondary);
        }

        .form-control:hover {
            border-color: var(--border-light);
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }
        /* Alert Messages */
        .alert {
            padding: 1rem 1.25rem;
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
            border: 1px solid transparent;
            font-weight: 500;
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.2) 0%, rgba(52, 211, 153, 0.2) 100%);
            border: 1px solid var(--success-color);
            color: var(--success-light);
        }

        .alert-error {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.2) 0%, rgba(248, 113, 113, 0.2) 100%);
            border: 1px solid var(--error-color);
            color: var(--error-light);
        }

        .alert-warning {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.2) 0%, rgba(251, 191, 36, 0.2) 100%);
            border: 1px solid var(--warning-color);
            color: var(--warning-light);
        }

        /* Loading States */
        .loading {
            opacity: 0.7;
            pointer-events: none;
            position: relative;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin-left: -10px;
            margin-top: -10px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Animations */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .slide-in {
            animation: slideIn 0.3s ease-out;
        }

        /* Prevent layout shifts */
        .sidebar,
        .main-content {
            will-change: auto;
        }

        @media (prefers-reduced-motion: no-preference) {
            .sidebar {
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
        }
    </style>
</head>
<body>
    <button class="mobile-menu-btn" onclick="toggleSidebar()">
        <i data-feather="menu"></i>
    </button>
    
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            📸 Photobooth Pro
        </div>
        <nav class="nav-links">
            <!-- Main Navigation -->
            <div class="nav-group">
                <a href="<?= URLROOT; ?>/admin/dashboard"><i data-feather="home"></i> Dashboard</a>
                <a href="<?= URLROOT; ?>/admin/packages"><i data-feather="package"></i> Packages</a>
                <a href="<?= URLROOT; ?>/admin/assets"><i data-feather="image"></i> Assets</a>
            </div>
            
            <!-- Content Management -->
            <div class="nav-group">
                <div class="nav-group-label">Content</div>
                <a href="<?= URLROOT; ?>/admin/sessions"><i data-feather="play"></i> Photo Sessions</a>
                <a href="<?= URLROOT; ?>/admin/photostrips"><i data-feather="layers"></i> Photostrips</a>
                <a href="<?= URLROOT; ?>/admin/gallery"><i data-feather="grid"></i> Legacy Gallery</a>
            </div>
            
            <!-- System Management -->
            <div class="nav-group">
                <div class="nav-group-label">System</div>
                <a href="<?= URLROOT; ?>/admin/reports"><i data-feather="bar-chart-2"></i> Reports</a>
                <a href="<?= URLROOT; ?>/admin/queue"><i data-feather="list"></i> Queue Management</a>
                <a href="<?= URLROOT; ?>/admin/camera"><i data-feather="camera"></i> Camera Control</a>
                <a href="<?= URLROOT; ?>/admin/settings"><i data-feather="settings"></i> Settings</a>
            </div>
        </nav>
        <div class="user-info">
             <a href="<?= URLROOT; ?>/logout">
                <i data-feather="log-out"></i>
                <span>Logout (<?= htmlspecialchars(App\Core\Session::get('admin_username', 'Admin')) ?>)</span>
            </a>
        </div>
    </aside>

    <main class="main-content">
        <?= $content ?? '' ?>
    </main>
    <script>
        // Initialize Feather icons
        feather.replace();
        
        // Mobile sidebar toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('mobile-open');
        }
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const menuBtn = document.querySelector('.mobile-menu-btn');
            
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !menuBtn.contains(event.target)) {
                    sidebar.classList.remove('mobile-open');
                }
            }
        });
        
        // Set active navigation state and add animations
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-links a');
            
            let bestMatch = null;
            let longestMatch = 0;

            const dashboardPath = new URL('<?= URLROOT; ?>/admin/dashboard').pathname;
            const adminRootPath = new URL('<?= URLROOT; ?>/admin').pathname;
            const adminRootPathWithSlash = adminRootPath + '/';

            if (currentPath === adminRootPath || currentPath === adminRootPathWithSlash) {
                navLinks.forEach(link => {
                    if (new URL(link.href).pathname === dashboardPath) {
                        bestMatch = link;
                    }
                });
            } else {
                navLinks.forEach(link => {
                    const linkPath = new URL(link.href).pathname;
                    if (currentPath.startsWith(linkPath) && linkPath.length > longestMatch) {
                        longestMatch = linkPath.length;
                        bestMatch = link;
                    }
                });
            }

            if (bestMatch) {
                bestMatch.classList.add('active');
                bestMatch.scrollIntoView({ block: 'center' });
            }

            navLinks.forEach((link, index) => {
                // Add staggered animation
                link.style.animationDelay = `${index * 0.1}s`;
                link.classList.add('slide-in');
                
                // Prevent sidebar movement on navigation
                link.addEventListener('click', function(e) {
                    // Remove loading class from other links
                    navLinks.forEach(l => l.classList.remove('nav-loading'));
                    // Add loading to current link
                    this.classList.add('nav-loading');
                });
            });
            
            // Add loading states to buttons
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(btn => {
                if (btn.type === 'submit' || btn.classList.contains('btn-primary')) {
                    btn.addEventListener('click', function(e) {
                        if (!this.classList.contains('loading')) {
                            this.classList.add('loading');
                            setTimeout(() => {
                                this.classList.remove('loading');
                            }, 2000);
                        }
                    });
                }
            });
            
            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-20px)';
                    setTimeout(() => {
                        alert.remove();
                    }, 300);
                }, 5000);
            });
        });
        
        // Responsive adjustments
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth > 768) {
                sidebar.classList.remove('mobile-open');
            }
        });
        
        // Add smooth scrolling for better UX
        document.documentElement.style.scrollBehavior = 'smooth';
        
        // Performance monitoring
        window.addEventListener('load', function() {
            const loadTime = performance.timing.loadEventEnd - performance.timing.navigationStart;
            if (loadTime > 3000) {
                console.warn('Slow page load detected:', loadTime + 'ms');
            }
        });
    </script>
</body>
</html>