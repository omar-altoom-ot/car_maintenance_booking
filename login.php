<?php

session_start();

require 'classes/User.php';

if($_SERVER ['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['email']) && isset($_POST['password'])){
    $email = trim($_POST ['email']);
    $password = trim($_POST ['password']);

    $user = new User($email, $password);
        if($user->verification()){
            if($user->getRole() == 'admin'){
                header('Location: admin/dashboard.php');
                exit();
            } else {
                header('Location: my-bookings.php');
                exit();
            }
        }
    }
}



?>



<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Log in | AutoCare</title>
        <link rel="stylesheet" href="assets/style.css?v=20261023-r2" />
    </head>

    <body class="auth-page">
        <header class="site-header">
            <a class="brand" href="index.php">Auto<span>Care</span></a
            ><a href="index.php">Back to home</a>
        </header>
        <main class="auth-layout">
            <section class="auth-card">
                <span class="eyebrow">YOUR ACCOUNT</span>
                <h1>Welcome back</h1>
                <p>Sign in to manage your appointments.</p>

                <form method="post">
                    <label>Email address</label>
                    <input type="email" name="email" value="" required />
                    <label>Password</label>
                    <input type="password" name="password" required />
                    <button class="btn btn-primary full" type="submit">Log in</button>
                </form>
                <p class="auth-switch">New to AutoCare? <a href="register.php">Create an account</a></p>
            </section>
        </main>
    </body>
</html>
