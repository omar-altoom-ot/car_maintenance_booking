<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Manage users | AutoCare</title>
        <link rel="stylesheet" href="../assets/style.css?v=20261023-r2" />
    </head>
    <body>
        <header class="site-header">
            <a class="brand" href="../index.php">Auto<span>Care</span> Admin</a>
            <nav>
                <a href="dashboard.php">Reservations</a><a href="users.php">Users</a
                ><a href="time-slots.php">Time slots</a>
            </nav>
            <div class="header-actions"><span></span><a class="btn btn-outline" href="../logout.php">Log out</a></div>
        </header>
        <main class="dashboard-wrap">
            <div class="page-title-row">
                <div>
                    <span class="eyebrow">ADMIN WORKSPACE</span>
                    <h1>Manage users</h1>
                    <p>Activate or deactivate customer accounts.</p>
                </div>
            </div>
            <section class="panel">
                <div class="table-scroll">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Joined</th>
                                <th>Account</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><span class="pill"></span></td>
                                <td>
                                    <form method="post">
                                        <input type="hidden" name="id" value="" /><input
                                            type="hidden"
                                            name="active"
                                            value=""
                                        /><button class="table-button" type="submit"></button>
                                    </form>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5">No customer accounts found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </body>
</html>
