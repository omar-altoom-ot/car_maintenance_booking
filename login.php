<?php
require_once 'includes.php';
require_once 'classes/User.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new User($_POST['email'] ?? '', $_POST['password'] ?? '');
    if ($user->verification()) {
        if ($_SESSION['role'] === 'admin') {
            header('Location: admin/dashboard.php');
        } else {
            header('Location: my-bookings.php');
        }
        exit;
    }
    $error = 'Email or password is incorrect, or the account is inactive.';
}
?>




<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log in | AutoCare</title>
    <link rel="stylesheet" href="assets/style.css?v=20261023">
</head>

<body class="auth-page">
    <header class="site-header"><a class="brand" href="index.php">Auto<span>Care</span></a><a href="index.php">Back to
            home</a></header>
    <main class="auth-layout">
        <section class="auth-card"><span class="eyebrow">YOUR ACCOUNT</span>
            <h1>Welcome back</h1>
            <p>Sign in to manage your appointments.</p>
            <?php if ($error): ?><p class="error"><?= e($error) ?></p>
            <?php endif; ?>
            <form method="post">
                <label>Email address</label>
                <input type="email" name="email"value="<?= e($_POST['email'] ?? '') ?>" required>
                <label>Password</label>
                <input type="password" name="password" required>
                <button class="btn btn-primary full" type="submit">Log in</button>
            </form>
            <p class="auth-switch">New to AutoCare? <a href="register.php">Create an account</a></p>
        </section>
    </main>
</body>

</html>