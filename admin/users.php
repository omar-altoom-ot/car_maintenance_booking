<?php
require_once __DIR__ . '/../includes.php';
require_admin();
require_once __DIR__ . '/../classes/User.php';
$userModel = new User();
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userModel->setActive((int)($_POST['id'] ?? 0), (int)($_POST['active'] ?? 0));
    $message = 'User account updated.';
}
$users = $userModel->getAll();
?>
<!doctype html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Manage users | AutoCare</title><link rel="stylesheet" href="../assets/style.css?v=20261023-r2"></head><body>
<header class="site-header"><a class="brand" href="../index.php">Auto<span>Care</span> Admin</a><nav><a href="dashboard.php">Reservations</a><a href="users.php">Users</a><a href="time-slots.php">Time slots</a></nav><div class="header-actions"><span><?= e($_SESSION['name'] ?? '') ?></span><a class="btn btn-outline" href="../logout.php">Log out</a></div></header>
<main class="dashboard-wrap"><div class="page-title-row"><div><span class="eyebrow">ADMIN WORKSPACE</span><h1>Manage users</h1><p>Activate or deactivate customer accounts.</p></div></div><?php if ($message): ?><p class="success"><?= e($message) ?></p><?php endif; ?>
<section class="panel"><div class="table-scroll"><table><thead><tr><th>Name</th><th>Email</th><th>Joined</th><th>Account</th><th>Action</th></tr></thead><tbody><?php foreach ($users as $user): ?><tr><td><?= e($user['name']) ?></td><td><?= e($user['email']) ?></td><td><?= e($user['created_at']) ?></td><td><span class="pill <?= $user['is_active'] ? 'confirmed' : 'rejected' ?>"><?= $user['is_active'] ? 'Active' : 'Inactive' ?></span></td><td><form method="post"><input type="hidden" name="id" value="<?= e($user['id']) ?>"><input type="hidden" name="active" value="<?= $user['is_active'] ? 0 : 1 ?>"><button class="table-button <?= $user['is_active'] ? 'danger' : '' ?>" type="submit"><?= $user['is_active'] ? 'Deactivate' : 'Activate' ?></button></form></td></tr><?php endforeach; ?><?php if (!$users): ?><tr><td colspan="5">No customer accounts found.</td></tr><?php endif; ?></tbody></table></div></section></main></body></html>
