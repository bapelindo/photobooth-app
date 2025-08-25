<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - <?= $title ?? 'Photobooth App' ?></title>
    <?php App\Core\Session::start(); // Pastikan sesi dimulai untuk mengakses data admin ?>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; background-color: #f8f9fa; color: #333; margin: 0; }
        .navbar { background-color: #343a40; padding: 1rem; }
        .navbar a { color: white; text-decoration: none; font-weight: 500; }
        .container { max-width: 960px; margin: 2rem auto; padding: 2rem; background-color: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #343a40; border-bottom: 2px solid #e9ecef; padding-bottom: 0.5rem; margin-bottom: 1.5rem; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 2rem; }
        th, td { padding: 0.75rem; text-align: left; border-bottom: 1px solid #dee2e6; }
        th { background-color: #e9ecef; }
        .btn { display: inline-block; font-weight: 400; color: #212529; text-align: center; vertical-align: middle; cursor: pointer; background-color: transparent; border: 1px solid transparent; padding: .375rem .75rem; font-size: 1rem; line-height: 1.5; border-radius: .25rem; text-decoration: none; transition: all 0.2s ease-in-out; }
        .btn-primary { color: #fff; background-color: #007bff; border-color: #007bff; }
        .btn-primary:hover { background-color: #0069d9; border-color: #0062cc; }
        .btn-secondary { color: #fff; background-color: #6c757d; border-color: #6c757d; padding: 0.25rem 0.5rem; font-size: 0.875rem; }
        .btn-danger { color: #fff; background-color: #dc3545; border-color: #dc3545; }
        .action-links a, .action-links form { display: inline-block; margin-right: 5px; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: .5rem; font-weight: 500; }
        .form-control { display: block; width: 100%; padding: .375rem .75rem; font-size: 1rem; line-height: 1.5; color: #495057; background-color: #fff; background-clip: padding-box; border: 1px solid #ced4da; border-radius: .25rem; box-sizing: border-box; }
        .asset-preview { max-width: 80px; max-height: 80px; }
        .navbar { display: flex; justify-content: space-between; align-items: center; }
        .nav-links a { margin-right: 15px; }
        .user-info { color: #adb5bd; }
        .user-info a { margin-left: 10px; }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-links">
            <a href="<?= URLROOT; ?>/admin/dashboard" style="font-weight: bold; font-size: 1.2rem;">Photobooth Admin</a>
            <a href="<?= URLROOT; ?>/admin/packages">Packages</a>
            <a href="<?= URLROOT; ?>/admin/assets">Assets</a>
            <a href="<?= URLROOT; ?>/admin/gallery">Gallery</a>
        </div>
        <div class="user-info">
            <span>Welcome, <strong><?= htmlspecialchars(App\Core\Session::get('admin_username', 'Admin')) ?></strong></span>
            <a href="<?= URLROOT; ?>/logout" class="btn btn-secondary">Logout</a>
        </div>
    </nav>
    <main class="container">
        <?= $content ?? '' ?>
    </main>
</body>
</html>