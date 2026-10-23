<?php
require_once __DIR__ . '/../includes.php';
require_admin();
require_once __DIR__ . '/../classes/Reservation.php';
$bookings = (new Reservation())->getAll();
$counts = ['pending' => 0, 'rejected' => 0, 'confirmed' => 0, 'total' => count($bookings)];
foreach ($bookings as $booking) {
    if (isset($counts[$booking['status']])) { $counts[$booking['status']]++; }
}
$chartItems = [
    ['key' => 'pending', 'label' => 'Pending', 'color' => '#d99a17'],
    ['key' => 'rejected', 'label' => 'Rejected', 'color' => '#d34b4b'],
    ['key' => 'confirmed', 'label' => 'Approved', 'color' => '#16834a'],
    ['key' => 'total', 'label' => 'Total reservations', 'color' => '#238a55'],
];
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin | AutoCare</title>
    <link rel="stylesheet" href="../assets/style.css?v=20261023">
</head>

<body>
    <header class="site-header"><a class="brand" href="../index.php">Auto<span>Care</span> Admin</a>
        <nav><a href="dashboard.php">Reservations</a></nav>
        <div class="header-actions"><span><?= e($_SESSION['name']) ?></span><a class="btn btn-outline"
                href="../logout.php">Log out</a></div>
    </header>
    <main class="dashboard-wrap">
        <div class="page-title-row">
            <div><span class="eyebrow">ADMIN WORKSPACE</span>
                <h1>Reservations</h1>
                <p>Review bookings and update their status.</p>
            </div>
        </div>
        <section class="stats-grid" aria-label="Reservation statistics">
            <?php foreach ($chartItems as $item): $value = $counts[$item['key']]; $percent = $counts['total'] > 0 ? (int) round(($value / $counts['total']) * 100) : 0; ?>
            <article class="stat-card">
                <div class="stat-copy"><span
                        class="stat-label"><?= e($item['label']) ?></span><strong><?= e($value) ?></strong><span
                        class="stat-share"><?= e($percent) ?>% of all reservations</span></div>
                <div class="stat-graph" role="img" aria-label="<?= e($item['label']) ?>: <?= e($value) ?> reservations">
                    <svg viewBox="0 0 180 92" aria-hidden="true">
                        <line x1="24" y1="70" x2="172" y2="70" class="graph-axis" />
                        <line x1="24" y1="12" x2="24" y2="70" class="graph-axis" />
                        <line x1="24" y1="41" x2="172" y2="41" class="graph-gridline" />
                        <rect x="77" y="<?= $value > 0 ? 70 - max(3, (int) round($percent * .58)) : 70 ?>" width="42"
                            height="<?= $value > 0 ? max(3, (int) round($percent * .58)) : 0 ?>" rx="4"
                            fill="<?= e($item['color']) ?>" /><text x="98" y="86" text-anchor="middle"
                            class="graph-label"><?= e($item['label']) ?></text>
                    </svg>
                </div>
            </article><?php endforeach; ?>
        </section>
        <section class="panel">
            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Service</th>
                            <th>Car</th>
                            <th>Date & time</th>
                            <th>Status</th>
                            <th>Update</th>
                        </tr>
                    </thead>
                    <tbody><?php foreach ($bookings as $b): ?><tr>
                            <td><?= e($b['customer_name']) ?><small><?= e($b['email']) ?></small></td>
                            <td><?= e($b['service_name']) ?></td>
                            <td><?= e($b['car_model']) ?><small><?= e($b['car_number']) ?></small></td>
                            <td><?= e($b['reservation_date']) ?><small><?= e(substr($b['reservation_time'], 0, 5)) ?></small>
                            </td>
                            <td><span class="pill <?= e($b['status']) ?>"><?= e(ucfirst($b['status'])) ?></span></td>
                            <td><?php if ($b['status'] === 'pending'): ?><form method="post"
                                    action="update-reservation.php" class="inline-form"><input type="hidden" name="id"
                                        value="<?= e($b['id']) ?>"><button name="status" value="confirmed"
                                        class="table-button">Confirm</button><button name="status" value="rejected"
                                        class="table-button danger">Reject</button></form><?php else: ?>—<?php endif; ?>
                            </td>
                        </tr><?php endforeach; ?><?php if (!$bookings): ?><tr>
                            <td colspan="6">No reservations found.</td>
                        </tr><?php endif; ?></tbody>
                </table>
            </div>
        </section>
    </main>
</body>

</html>