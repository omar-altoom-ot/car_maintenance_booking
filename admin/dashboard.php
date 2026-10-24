<?php




?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Admin | AutoCare</title>
        <link rel="stylesheet" href="../assets/style.css?v=20261023-r2" />
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js" defer></script>
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
                    <h1>Reservations</h1>
                    <p>Review bookings and update their status.</p>
                </div>
            </div>
            <section class="stats-grid" aria-label="Dashboard statistics">
                <article class="stat-card"><span class="stat-label">Total reservations</span><strong></strong></article>
                <article class="stat-card">
                    <span class="stat-label">Pending reservations</span><strong></strong>
                </article>
                <article class="stat-card">
                    <span class="stat-label">Confirmed reservations</span><strong></strong>
                </article>
                <article class="stat-card"><span class="stat-label">Registered users</span><strong></strong></article>
            </section>
            <section class="panel chart-panel">
                <h2>Reservations by status</h2>
                <div class="chart-area">
                    <canvas id="reservationStatusChart" role="img" aria-label="Reservation counts by status"></canvas>
                </div>
            </section>
            <section class="panel">
                <form class="filter-form" method="get">
                    <label
                        >Search by name or email<input
                            name="search"
                            value=""
                            placeholder="Customer name or email" /></label
                    ><label
                        >Filter by status<select name="status">
                            <option value="">All statuses</option>
                        </select></label
                    ><button class="btn btn-primary" type="submit">Apply filters</button
                    ><a class="btn btn-outline" href="dashboard.php">Clear</a>
                </form>
                <div class="table-scroll">
                    <table>
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Service</th>
                                <th>Car</th>
                                <th>Date &amp; time</th>
                                <th>Status</th>
                                <th>Update</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><small></small></td>
                                <td></td>
                                <td><small></small></td>
                                <td><small></small></td>
                                <td><span class="pill"></span></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
        <script>
            window.addEventListener("DOMContentLoaded", function () {
                if (!window.Chart) return;
                new Chart(document.getElementById("reservationStatusChart"), {
                    type: "bar",
                    data: {
                        labels: ["Pending", "Confirmed", "Rejected", "Cancelled"],
                        datasets: [
                            {
                                label: "Reservations",
                                data: [],
                                backgroundColor: ["#d99a17", "#16834a", "#d34b4b", "#8993a2"],
                                borderRadius: 8,
                                maxBarThickness: 64,
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
                    },
                });
            });
        </script>
    </body>
</html>
