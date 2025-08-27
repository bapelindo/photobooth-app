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
            --bg-color: #f4f7fa;
            --sidebar-bg: #ffffff;
            --card-bg: #ffffff;
            --primary-color: #4F46E5;
            --primary-hover: #4338CA;
            --text-color: #374151;
            --text-muted: #6B7280;
            --border-color: #E5E7EB;
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --border-radius: 0.75rem;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }
        .sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            padding: 2rem 1.5rem;
            display: flex;
            flex-direction: column;
            border-right: 1px solid var(--border-color);
            transition: width 0.3s ease;
        }
        .sidebar-header {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 2.5rem;
        }
        .nav-links {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            flex-grow: 1;
        }
        .nav-links a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease-in-out;
        }
        .nav-links a:hover, .nav-links a.active {
            background-color: #F3F4F6;
            color: var(--primary-color);
        }
        .nav-links a .feather {
            width: 20px;
            height: 20px;
        }
        .user-info {
            margin-top: auto;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
        }
        .user-info a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--text-muted);
            font-weight: 500;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease-in-out;
        }
        .user-info a:hover {
            background-color: #FEE2E2;
            color: #EF4444;
        }
        .main-content {
            flex-grow: 1;
            padding: 2.5rem;
            overflow-y: auto;
        }
        .page-header {
            margin-bottom: 2rem;
        }
        .page-header h1 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
        }
        .card {
            background-color: var(--card-bg);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
            overflow: hidden;
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
        }
        th {
            background-color: #F9FAFB;
            font-weight: 600;
            color: var(--text-muted);
            font-size: 0.875rem;
            text-transform: uppercase;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            text-align: center;
            cursor: pointer;
            border: none;
            padding: 0.625rem 1.25rem;
            font-size: 0.9rem;
            border-radius: 0.5rem;
            text-decoration: none;
            transition: all 0.2s ease-in-out;
        }
        .btn-primary { color: #fff; background-color: var(--primary-color); }
        .btn-primary:hover { background-color: var(--primary-hover); }
        .btn-secondary { color: var(--text-color); background-color: #F3F4F6; border: 1px solid var(--border-color); }
        .btn-secondary:hover { background-color: #E5E7EB; }
        .btn-danger { color: #fff; background-color: #EF4444; }
        .btn-danger:hover { background-color: #DC2626; }
        .btn-sm { padding: 0.375rem 0.75rem; font-size: 0.8rem; }

        .action-links { display: flex; gap: 0.5rem; }

        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: .5rem; font-weight: 500; }
        .form-control {
            display: block; width: 100%; padding: .75rem; font-size: 1rem;
            color: var(--text-color); background-color: #fff;
            border: 1px solid var(--border-color); border-radius: 0.5rem;
            box-sizing: border-box; transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-header">Photobooth</div>
        <nav class="nav-links">
            <a href="<?= URLROOT; ?>/admin/dashboard"><i data-feather="home"></i> Dashboard</a>
            <a href="<?= URLROOT; ?>/admin/packages"><i data-feather="package"></i> Packages</a>
            <a href="<?= URLROOT; ?>/admin/assets"><i data-feather="image"></i> Assets</a>
            <a href="<?= URLROOT; ?>/admin/gallery"><i data-feather="grid"></i> Gallery</a>
            <a href="<?= URLROOT; ?>/admin/camera"><i data-feather="camera"></i> Camera Control</a>
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
        feather.replace();
    </script>
</body>
</html>