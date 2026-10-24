<?php
require_once __DIR__ . '/../includes.php';
require_admin();
require_once __DIR__ . '/../classes/Reservation.php';
$reservation = new Reservation();
$error = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_id'])) {
        $ok = $reservation->deleteSlot((int)$_POST['delete_id']);
        $message = $ok ? 'Time slot deleted.' : '';
        $error = $ok ? '' : 'A slot with pending or confirmed reservations cannot be deleted.';
    } elseif (isset($_POST['update_id'])) {
        $ok = $reservation->updateSlot((int)$_POST['update_id'], $_POST['slot_date'] ?? '', $_POST['start_time'] ?? '', $_POST['end_time'] ?? '', (int)($_POST['capacity'] ?? 0));
        $message = $ok ? 'Time slot updated.' : '';
        $error = $ok ? '' : 'Check the date, time range, and capacity.';
    } else {
        $ok = $reservation->addSlot($_POST['slot_date'] ?? '', $_POST['start_time'] ?? '', $_POST['end_time'] ?? '', (int)($_POST['capacity'] ?? 0));
        $message = $ok ? 'Time slot added.' : '';
        $error = $ok ? '' : 'Enter a future time, a valid time range, and capacity of at least one.';
    }
}
$slots = $reservation->getSlots();
?>
<!doctype html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Manage time slots | AutoCare</title><link rel="stylesheet" href="../assets/style.css?v=20261023-r2"></head><body>
<header class="site-header"><a class="brand" href="../index.php">Auto<span>Care</span> Admin</a><nav><a href="dashboard.php">Reservations</a><a href="users.php">Users</a><a href="time-slots.php">Time slots</a></nav><div class="header-actions"><span><?= e($_SESSION['name'] ?? '') ?></span><a class="btn btn-outline" href="../logout.php">Log out</a></div></header>
<main class="dashboard-wrap"><div class="page-title-row"><div><span class="eyebrow">ADMIN WORKSPACE</span><h1>Manage time slots</h1><p>Create availability and manage capacity.</p></div></div><?php if ($message): ?><p class="success"><?= e($message) ?></p><?php endif; ?><?php if ($error): ?><p class="error"><?= e($error) ?></p><?php endif; ?>
<section class="panel"><h2>Add a time slot</h2><form method="post" class="filter-form slot-form"><label>Date<input type="date" name="slot_date" min="<?= date('Y-m-d') ?>" required></label><label>Start time<input type="time" name="start_time" required></label><label>End time<input type="time" name="end_time" required></label><label>Capacity<input type="number" name="capacity" min="1" value="1" required></label><button class="btn btn-primary" type="submit">Add time slot</button></form></section>
<section class="panel"><div class="table-scroll"><table><thead><tr><th>Date</th><th>Start</th><th>End</th><th>Capacity</th><th>Booked</th><th>Action</th></tr></thead><tbody><?php foreach ($slots as $slot): ?><tr><td colspan="6"><form method="post" class="slot-edit-form"><input type="hidden" name="update_id" value="<?= e($slot['id']) ?>"><label><span class="sr-only">Date</span><input type="date" name="slot_date" value="<?= e($slot['slot_date']) ?>" required></label><label><span class="sr-only">Start</span><input type="time" name="start_time" value="<?= e(substr($slot['start_time'],0,5)) ?>" required></label><label><span class="sr-only">End</span><input type="time" name="end_time" value="<?= e(substr($slot['end_time'],0,5)) ?>" required></label><label><span class="sr-only">Capacity</span><input type="number" name="capacity" min="1" value="<?= e($slot['capacity']) ?>" required></label><span><?= e($slot['booked']) ?> booked</span><button class="table-button" type="submit">Save</button></form><form method="post" class="slot-delete-form"><input type="hidden" name="delete_id" value="<?= e($slot['id']) ?>"><button class="table-button danger" type="submit">Delete</button></form></td></tr><?php endforeach; ?><?php if (!$slots): ?><tr><td colspan="6">No time slots yet.</td></tr><?php endif; ?></tbody></table></div></section></main></body></html>
