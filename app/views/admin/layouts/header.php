<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? 'Admin Panel'; ?></title>
    <link rel="stylesheet" href="<?= URLROOT; ?>/css/admin.css">
</head>
<body>
    <div class="admin-wrapper">
        <aside class="sidebar">
            <h3>Photobooth Admin</h3>
            <nav>
                <ul>
                    <li><a href="<?= URLROOT; ?>/admin">Dashboard</a></li>
                    <li><a href="<?= URLROOT; ?>/admin/events">Kelola Acara</a></li>
                    <li><a href="<?= URLROOT; ?>/admin/logout">Logout</a></li>
                </ul>
            </nav>
        </aside>
        <main class="main-panel">
            <header class="top-bar">
                <h1><?= $data['title']; ?></h1>
            </header>
            <div class="content-area">