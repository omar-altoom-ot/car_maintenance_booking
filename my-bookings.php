<?php
require_once 'includes.php';
require_login();
require_once 'classes/Reservation.php';
require_once 'classes/Service.php';

$reservationModel = new Reservation();
$services = (new Service())->getAll();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ok = $reservationModel->create(
        (int)$_SESSION['user_id'],
        (int)($_POST['service_id'] ?? 0),
        trim($_POST['car_model'] ?? ''),
        trim($_POST['car_number'] ?? ''),
        $_POST['reservation_date'] ?? '',
        $_POST['reservation_time'] ?? '',
        $_POST['notes'] ?? ''
    );
    if ($ok) {
        $success = 'Your reservation has been submitted.';
    } else {
        $error = 'Please check the service, car details, and date.';
    }
}
$bookings = $reservationModel->getByUser((int)$_SESSION['user_id']);
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My bookings | AutoCare</title>
    <link rel="stylesheet" href="assets/style.css?v=20261023">
</head>

<body>
    <header class="site-header"><a class="brand" href="index.php">Auto<span>Care</span></a>
        <nav><a href="index.php">Home</a><a href="my-bookings.php">My bookings</a></nav>
        <div class="header-actions"><span><?= e($_SESSION['name']) ?></span><a class="btn btn-outline"
                href="logout.php">Log out</a></div>
    </header>
    <main class="dashboard-wrap">
        <div class="page-title-row">
            <div><span class="eyebrow">YOUR GARAGE</span>
                <h1>My bookings</h1>
                <p>Book a service and track your appointments.</p>
            </div>
        </div>
        <section class="panel">
            <h2>Book a service</h2><?php if ($success): ?><p class="success"><?= e($success) ?></p>
            <?php endif; ?><?php if ($error): ?><p class="error"><?= e($error) ?></p><?php endif; ?><form method="post"
                class="booking-form">
                <div><label>Service</label><select name="service_id" required>
                        <option value="">Choose a service</option><?php foreach ($services as $service): ?><option
                            value="<?= e($service['id']) ?>"><?= e($service['name']) ?> — $<?= e($service['price']) ?>
                        </option><?php endforeach; ?>
                    </select></div>
                <div><label>Car model</label><input name="car_model" required placeholder="Toyota Corolla"></div>
                <div><label>Car number</label><input name="car_number" required placeholder="12-34567"></div>
                <div><label>Date</label><input type="date" name="reservation_date" min="<?= date('Y-m-d') ?>" required>
                </div>
                <div><label>Time</label><input type="time" name="reservation_time" required></div>
                <div class="wide"><label>Notes</label><textarea name="notes"
                        placeholder="Any details about your car"></textarea></div><button class="btn btn-primary"
                    type="submit">Create booking</button>
            </form>
        </section>
        <section class="panel">
            <h2>Appointment history</h2>
            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>Service</th>
                            <th>Date & time</th>
                            <th>Car</th>
                            <th>Price</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody><?php foreach ($bookings as $booking): ?><tr>
                            <td><?= e($booking['service_name']) ?></td>
                            <td><?= e($booking['reservation_date']) ?><small><?= e(substr($booking['reservation_time'], 0, 5)) ?></small>
                            </td>
                            <td><?= e($booking['car_model']) ?><small><?= e($booking['car_number']) ?></small></td>
                            <td>$<?= e($booking['price']) ?></td>
                            <td><span
                                    class="pill <?= e($booking['status']) ?>"><?= e(ucfirst($booking['status'])) ?></span>
                            </td>
                        </tr><?php endforeach; ?><?php if (!$bookings): ?><tr>
                            <td colspan="5">No bookings yet.</td>
                        </tr><?php endif; ?></tbody>
                </table>
            </div>
        </section>
    </main>
</body>

</html>