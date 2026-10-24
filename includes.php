<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function require_login()
{
    if (empty($_SESSION['user_id'])) {
        header('Location: ' . (strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false ? '../login.php' : 'login.php'));
        exit;
    }
}

function require_admin()
{
    require_login();
    if (($_SESSION['role'] ?? '') !== 'admin') {
        header('Location: ' . (strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false ? '../my-bookings.php' : 'my-bookings.php'));
        exit;
    }
}
?>
