<?php
$config = require __DIR__ . '/config/config.php';
date_default_timezone_set($config['timezone']);

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/controller/AdminUserController.php';
require_once __DIR__ . '/controller/AdminEventController.php';
require_once __DIR__ . '/controller/SiteUserController.php';
require_once __DIR__ . '/controller/SiteActionController.php';

start_session();

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = rtrim($path, '/');
$path = $path === '' ? '/' : $path;

if (str_starts_with($path, '/admin')) {
    $adminUserController = new AdminUserController();
    $adminEventController = new AdminEventController();

    if ($path === '/admin/login') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = sanitize($_POST['username'] ?? '');
            $password = sanitize($_POST['password'] ?? '');
            if (admin_login($username, $password)) {
                redirect('/admin/users');
            }
            set_flash('error', 'Invalid credentials.');
        }
        render('admin/login', [], 'admin');
        exit;
    }

    if ($path === '/admin/logout') {
        admin_logout();
        redirect('/admin/login');
    }

    require_admin();

    switch ($path) {
        case '/admin/users':
            $adminUserController->list();
            break;
        case '/admin/users/add':
            $adminUserController->add();
            break;
        case '/admin/users/edit':
            $adminUserController->edit();
            break;
        case '/admin/events/types':
            $adminEventController->typesList();
            break;
        case '/admin/events/types/add':
            $adminEventController->typesAdd();
            break;
        case '/admin/events/types/edit':
            $adminEventController->typesEdit();
            break;
        case '/admin/events/versions':
            $adminEventController->versionsList();
            break;
        case '/admin/events/versions/add':
            $adminEventController->versionsAdd();
            break;
        case '/admin/events/versions/edit':
            $adminEventController->versionsEdit();
            break;
        case '/admin/seed':
            $adminEventController->seed();
            break;
        default:
            http_response_code(404);
            echo 'Admin page not found.';
    }
    exit;
}

$siteUserController = new SiteUserController();
$siteActionController = new SiteActionController();

switch ($path) {
    case '/':
    case '/users':
        $siteUserController->list();
        break;
    case '/users/view':
        $siteUserController->view();
        break;
    case '/actions/add':
        $siteActionController->add();
        break;
    default:
        http_response_code(404);
        echo 'Page not found.';
}
