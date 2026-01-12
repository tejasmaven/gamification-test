<?php
$config = require __DIR__ . '/../config/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Gamification</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f0f2f5; }
        header, footer { background: #111; color: #fff; padding: 12px 20px; }
        .container { display: flex; }
        aside { width: 200px; background: #1c1c1c; color: #fff; min-height: calc(100vh - 104px); padding: 20px; }
        main { flex: 1; padding: 20px; }
        a { color: #0d6efd; }
        table { border-collapse: collapse; width: 100%; background: #fff; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f0f0f0; }
        .flash { padding: 10px; margin-bottom: 12px; border-radius: 4px; }
        .flash.success { background: #d1e7dd; }
        .flash.error { background: #f8d7da; }
        .btn { display: inline-block; padding: 6px 12px; background: #0d6efd; color: #fff; text-decoration: none; border-radius: 4px; }
        .btn-secondary { background: #6c757d; }
        .actions { margin-bottom: 12px; }
    </style>
</head>
<body>
<header>
    <strong>Admin Panel</strong>
</header>
<div class="container">
    <aside>
        <?php include __DIR__ . '/admin_sidebar.php'; ?>
    </aside>
    <main>
        <?php foreach ($flash as $type => $messages): ?>
            <?php foreach ($messages as $message): ?>
                <div class="flash <?php echo $type; ?>"><?php echo sanitize($message); ?></div>
            <?php endforeach; ?>
        <?php endforeach; ?>
