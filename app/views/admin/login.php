<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? 'Admin Login'; ?></title>
    <link rel="stylesheet" href="<?= URLROOT; ?>/css/admin.css">
</head>
<body>
    <div class="login-container">
        <form action="<?= URLROOT; ?>/admin/login" method="post" class="login-form">
            <h2>Admin Login</h2>
            <?php if (isset($data['error'])): ?>
                <div class="error-message"><?= $data['error']; ?></div>
            <?php endif; ?>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
    </div>
</body>
</html>