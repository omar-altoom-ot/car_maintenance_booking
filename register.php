<?php
require_once 'includes.php';
require_once 'classes/User.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new User();
    if (($_POST['password'] ?? '') !== ($_POST['confirm'] ?? '')) {
        $error = 'Passwords do not match.';
    } elseif ($user->register($_POST['name'] ?? '', $_POST['email'] ?? '', $_POST['password'] ?? '')) {
        header('Location: login.php?registered=1');
        exit;
    } else {
        $error = 'Please check your details. Use a valid email and a password of at least 6 characters.';
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create account | AutoCare</title>
    <link rel="stylesheet" href="assets/style.css?v=20261023">
</head>

<body class="auth-page">
    <header class="site-header"><a class="brand" href="index.php">Auto<span>Care</span></a><a href="index.php">Back to
            home</a></header>
    <main class="auth-layout">
        <section class="auth-card"><span class="eyebrow">GET STARTED</span>
            <h1>Create account</h1>
            <p>Create an account to book car services.</p><?php if ($error): ?><p class="error"><?= e($error) ?></p>
            <?php endif; ?><form method="post"><label>Full name</label><input name="name"
                    value="<?= e($_POST['name'] ?? '') ?>" required><label>Email address</label><input type="email"
                    name="email" value="<?= e($_POST['email'] ?? '') ?>" required><label>Password</label><input
                    type="password" name="password" minlength="6" required><label>Confirm password</label><input
                    type="password" name="confirm" minlength="6" required><button class="btn btn-primary full"
                    type="submit">Create account</button></form>
            <p class="auth-switch">Already have an account? <a href="login.php">Log in</a></p>
        </section>
    </main>
</body>

</html>