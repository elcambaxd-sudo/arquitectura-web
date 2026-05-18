<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function current_admin(): ?array
{
    if (empty($_SESSION['admin_id'])) {
        return null;
    }
    return fetch_one('SELECT id, nombre, email, rol FROM administradores WHERE id = ? AND estado = 1', [$_SESSION['admin_id']]);
}

function require_admin(): void
{
    if (!current_admin()) {
        redirect_to('admin/login.php');
    }
}

function attempt_login(string $email, string $password): bool
{
    $admin = fetch_one('SELECT * FROM administradores WHERE email = ? AND estado = 1 LIMIT 1', [$email]);
    if (!$admin || !password_verify($password, $admin['password_hash'])) {
        return false;
    }

    session_regenerate_id(true);
    $_SESSION['admin_id'] = (int) $admin['id'];
    $_SESSION['admin_name'] = $admin['nombre'];
    return true;
}

function logout_admin(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
}

