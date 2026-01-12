<?php
$config = require __DIR__ . '/../config/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gamification</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f5f5f5; }
        header, footer { background: #222; color: #fff; padding: 12px 20px; }
        main { padding: 20px; }
        table { border-collapse: collapse; width: 100%; background: #fff; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f0f0f0; }
        .flash { padding: 10px; margin-bottom: 12px; border-radius: 4px; }
        .flash.success { background: #d1e7dd; }
        .flash.error { background: #f8d7da; }
        .btn { display: inline-block; padding: 6px 12px; background: #0d6efd; color: #fff; text-decoration: none; border-radius: 4px; }
        .btn-secondary { background: #6c757d; }
    </style>
</head>
<body>
<header>
    <strong>Gamification Frontend</strong>
</header>
<main>
<?php foreach ($flash as $type => $messages): ?>
    <?php foreach ($messages as $message): ?>
        <div class="flash <?php echo $type; ?>"><?php echo sanitize($message); ?></div>
    <?php endforeach; ?>
<?php endforeach; ?>
