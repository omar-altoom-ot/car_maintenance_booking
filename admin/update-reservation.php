<?php
require_once __DIR__ . '/../includes.php';
require_admin();
require_once __DIR__ . '/../classes/Reservation.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    (new Reservation())->updateStates((int)($_POST['id'] ?? 0), $_POST['status'] ?? '');
}

header('Location: dashboard.php');
exit;
?>
