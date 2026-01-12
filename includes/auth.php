<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/functions.php';

function is_admin_logged_in(): bool
{
    start_session();
    return !empty($_SESSION['admin_logged_in']);
}

function require_admin(): void
{
    if (!is_admin_logged_in()) {
        redirect('/admin/login');
    }
}

function admin_login(string $username, string $password): bool
{
    $config = require __DIR__ . '/../config/config.php';
    if ($username === $config['admin_username'] && $password === $config['admin_password']) {
        start_session();
        $_SESSION['admin_logged_in'] = true;
        return true;
    }
    return false;
}

function admin_logout(): void
{
    start_session();
    unset($_SESSION['admin_logged_in']);
}
