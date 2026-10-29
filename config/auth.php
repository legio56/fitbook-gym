<?php
require_once __DIR__ . '/database.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function flash(string $key, ?string $message = null): ?string
{
    if ($message !== null) {
        $_SESSION['_flash'][$key] = $message;
        return null;
    }

    $value = $_SESSION['_flash'][$key] ?? null;
    unset($_SESSION['_flash'][$key]);
    return $value;
}

function require_login(string $loginPath = 'login.php'): void
{
    if (empty($_SESSION['user_id'])) {
        flash('error', 'Please log in to continue.');
        redirect($loginPath);
    }
}

function require_admin(string $loginPath = 'login.php'): void
{
    require_login($loginPath);
    if (($_SESSION['role'] ?? '') !== 'admin') {
        flash('error', 'Administrator access is required.');
        redirect('../index.php');
    }
}
