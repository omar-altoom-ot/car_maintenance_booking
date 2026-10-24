<?php
require_once 'includes.php';
require_login();
require_once 'classes/Reservation.php';
require_once 'classes/Service.php';
$reservation = new Reservation();
$services = (new Service())->getAll();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cancel_id'])) {
        $ok = $reservation->cancel((int)$_POST['cancel_id'], (int)$_SESSION['user_id']);
        $success = $ok ? 'Reservation cancelled.' : '';
        $error = $ok ? '' : 'This reservation cannot be cancelled.';
    } else {
        $carModel = is_string($_POST['car_model'] ?? null) ? $_POST['car_model'] : '';
        $carNumber = is_string($_POST['car_number'] ?? null) ? $_POST['car_number'] : '';
        $notes = is_string($_POST['notes'] ?? null) ? $_POST['notes'] : '';
        $ok = $reservation->create((int)($_SESSION['user_id']), (int)($_POST['service_id'] ?? 0), (int)($_POST['slot_id'] ?? 0), $carModel, $carNumber, $notes);
        $success = $ok ? 'Reservation submitted.' : '';
        $error = $ok ? '' : 'Choose a valid service and available time slot, then check the car details.';
    }
}

$slots = $reservation->getAvailableSlots();
$bookings = $reservation->getByUser((int)$_SESSION['user_id']);
?>
<!doctype html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>My bookings | AutoCare</title><link rel="stylesheet" href="assets/style.css?v=20261023-r2"></head>
<body>
<header class="site-header"><a class="brand" href="index.php">Auto<span>Care</span></a><nav><a href="index.php">Home</a><a href="my-bookings.php">My bookings</a></nav><div class="header-actions"><span><?= e($_SESSION['name'] ?? '') ?></span><a class="btn btn-outline" href="logout.php">Log out</a></div></header>
<main class="dashboard-wrap">
    <div class="page-title-row"><div><span class="eyebrow">YOUR GARAGE</span><h1>My bookings</h1><p>Book a service and track your appointments.</p></div></div>
    <section class="panel"><h2>Book a service</h2>
        <?php if ($success): ?><p class="success"><?= e($success) ?></p><?php endif; ?>
        <?php if ($error): ?><p class="error"><?= e($error) ?></p><?php endif; ?>
        <form method="post" class="booking-form">
            <div><label>Service</label><select name="service_id" required><option value="">Choose a service</option><?php foreach ($services as $service): ?><option value="<?= e($service['id']) ?>"><?= e($service['name']) ?> — $<?= e($service['price']) ?></option><?php endforeach; ?></select></div>
            <div><label>Car model</label><input name="car_model" required placeholder="Toyota Corolla"></div>
            <div><label>Car number</label><input name="car_number" required placeholder="12-34567"></div>
            <div><label for="slot_id">Available time slot</label><select id="slot_id" name="slot_id" required><option value="">Choose an available slot</option><?php foreach ($slots as $slot): ?><option value="<?= e($slot['id']) ?>"><?= e($slot['slot_date']) ?> · <?= e(substr($slot['start_time'],0,5)) ?>–<?= e(substr($slot['end_time'],0,5)) ?> (<?= e($slot['remaining']) ?> spots left)</option><?php endforeach; ?></select></div>
            <div class="wide"><label>Notes</label><textarea name="notes" placeholder="Any details about your car"></textarea></div>
            <button class="btn btn-primary" type="submit">Create booking</button>
        </form>
    </section>
    <section class="panel"><h2>Appointment history</h2><div class="table-scroll"><table><thead><tr><th>Service</th><th>Date &amp; time</th><th>Car</th><th>Price</th><th>Status</th><th>Action</th></tr></thead><tbody>
        <?php foreach ($bookings as $booking): ?><tr><td><?= e($booking['service_name']) ?></td><td><?= e($booking['reservation_date']) ?><small><?= e(substr($booking['reservation_time'],0,5)) ?></small></td><td><?= e($booking['car_model']) ?><small><?= e($booking['car_number']) ?></small></td><td>$<?= e($booking['price']) ?></td><td><span class="pill <?= e($booking['status']) ?>"><?= e(ucfirst($booking['status'])) ?></span></td><td><?php if (in_array($booking['status'], ['pending','confirmed'], true) && strtotime($booking['reservation_date'].' '.$booking['reservation_time']) > time()): ?><form method="post"><input type="hidden" name="cancel_id" value="<?= e($booking['id']) ?>"><button class="table-button danger" type="submit">Cancel</button></form><?php else: ?>—<?php endif; ?></td></tr><?php endforeach; ?>
        <?php if (!$bookings): ?><tr><td colspan="6">No bookings yet.</td></tr><?php endif; ?>
    </tbody></table></div></section>
</main></body></html>
