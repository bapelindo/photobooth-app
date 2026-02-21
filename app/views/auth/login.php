<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Photobooth Airways</title>
    <meta name="description" content="Admin login portal for Photobooth Airways.">
    <meta name="keywords"
        content="photobooth, photobooth online, photobooth bapel, photobooth airway, virtual photobooth, photo booth jakarta, photobooth event, sewa photobooth, photobooth wedding">

    <!-- Open Graph / Social Sharing -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Admin Login - Photobooth Airways">
    <meta property="og:description" content="Admin login portal for Photobooth Airways.">
    <meta property="og:site_name" content="Photobooth Airways">
    <meta property="og:locale" content="id_ID">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="Admin Login - Photobooth Airways">
    <meta name="twitter:description" content="Admin login for Photobooth Airways.">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #f4f7fa;
            --primary-color: #4F46E5;
            --primary-hover: #4338CA;
            --text-color: #374151;
            --border-color: #E5E7EB;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background: white;
            padding: 3rem;
            border-radius: 1rem;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            color: var(--text-color);
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 2.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-control {
            display: block;
            width: 100%;
            padding: .875rem 1rem;
            font-size: 1rem;
            border: 1px solid var(--border-color);
            border-radius: .5rem;
            box-sizing: border-box;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
        }

        .btn {
            display: block;
            width: 100%;
            padding: .875rem;
            font-size: 1rem;
            font-weight: 600;
            color: #fff;
            background-color: var(--primary-color);
            border: none;
            border-radius: .5rem;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn:hover {
            background-color: var(--primary-hover);
        }

        .error-message {
            background-color: #FEE2E2;
            color: #B91C1C;
            padding: 0.875rem 1rem;
            border-radius: 0.5rem;
            text-align: center;
            margin-bottom: 1.5rem;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <?php if (App\Core\Session::has('error_message')): ?>
            <div class="error-message">
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