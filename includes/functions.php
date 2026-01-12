<?php

function start_session(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function set_flash(string $type, string $message): void
{
    start_session();
    $_SESSION['flash'][$type][] = $message;
}

function get_flash(): array
{
    start_session();
    $messages = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $messages;
}

function sanitize(string $value): string
{
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function render(string $view, array $data = [], string $layoutPrefix = ''): void
{
    extract($data, EXTR_SKIP);
    $flash = get_flash();
    $viewPath = __DIR__ . '/../views/' . $view . '.php';
    if ($layoutPrefix) {
        include __DIR__ . '/../layout/' . $layoutPrefix . '_header.php';
    } else {
        include __DIR__ . '/../includes/header.php';
    }

    if (file_exists($viewPath)) {
        include $viewPath;
    } else {
        echo '<p>View not found.</p>';
    }

    if ($layoutPrefix) {
        include __DIR__ . '/../layout/' . $layoutPrefix . '_footer.php';
    } else {
        include __DIR__ . '/../includes/footer.php';
    }
}

function slugify(string $value): string
{
    $value = strtolower(trim($value));
    $value = preg_replace('/[^a-z0-9]+/', '-', $value);
    return trim($value, '-') ?: 'user';
}

function format_datetime(?MongoDB\BSON\UTCDateTime $dt): string
{
    if (!$dt) {
        return '';
    }
    $date = $dt->toDateTime()->setTimezone(new DateTimeZone('UTC'));
    return $date->format('Y-m-d H:i');
}

function input_value(array $source, string $key, $default = '')
{
    return isset($source[$key]) ? sanitize((string)$source[$key]) : $default;
}
