<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; background-color: #f8f9fa; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-container { background: white; padding: 40px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 100%; max-width: 360px; }
        h2 { text-align: center; color: #343a40; margin-bottom: 2rem; }
        .form-group { margin-bottom: 1.5rem; }
        .form-control { display: block; width: 100%; padding: .75rem; font-size: 1rem; border: 1px solid #ced4da; border-radius: .25rem; box-sizing: border-box; }
        .btn { display: block; width: 100%; padding: .75rem; font-size: 1rem; color: #fff; background-color: #007bff; border: none; border-radius: .25rem; cursor: pointer; }
        .btn:hover { background-color: #0069d9; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <?php if (App\Core\Session::has('error_message')): ?>
            <div style="color: red; text-align: center; margin-bottom: 1rem;">
                <?= App\Core\Session::get('error_message'); ?>
            </div>
            <?php App\Core\Session::destroy('error_message'); ?>
        <?php endif; ?>
        <form action="<?= URLROOT; ?>/login" method="POST">
            <div class="form-group">
                <input type="text" name="username" class="form-control" placeholder="Username" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
    </div>
</body>
</html>