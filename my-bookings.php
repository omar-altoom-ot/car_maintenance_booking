<?php

session_start();




?>



<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My bookings | AutoCare</title>
    <link rel="stylesheet" href="assets/style.css?v=20261023-r2">
</head>

<body>
    <header class="site-header">
        <a class="brand" href="index.php">Auto<span>Care</span></a>
        <nav>
            <a href="index.php">Home</a>
            <a href="my-bookings.php">My bookings</a>
        </nav>
        <div class="header-actions">
            <span></span>
            <a class="btn btn-outline" href="logout.php">Log out</a>
        </div>
    </header>

    <main class="dashboard-wrap">
        <div class="page-title-row">
            <div>
                <span class="eyebrow">YOUR GARAGE</span>
                <h1>My bookings</h1>
                <p>Book a service and track your appointments.</p>
            </div>
        </div>

        <section class="panel">
            <h2>Book a service</h2>
            <p></p>

            <p class="error"></p>

            <form method="post" class="booking-form">
                <div>
                    <label>Service</label>
                    <select name="service_id" required>
                        <option value="">Choose a service</option>
                        <option value=""></option>
                    </select>
                </div>

                <div>
                    <label>Car model</label>
                    <input name="car_model" required placeholder="Toyota Corolla">
                </div>

                <div>
                    <label>Car number</label>
                    <input name="car_number" required placeholder="12-34567">
                </div>

                <div>
                    <label for="slot_id">Available time slot</label>
                    <select id="slot_id" name="slot_id" required>
                        <option value="">Choose an available slot</option>
                    </select>
                </div>

                <div class="wide">
                    <label>Notes</label>
                    <textarea name="notes" placeholder="Any details about your car"></textarea>
                </div>

                <button class="btn btn-primary" type="submit">Create booking</button>
            </form>
        </section>

        <section class="panel">
            <h2>Appointment history</h2>

            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>Service</th>
                            <th>Date &amp; time</th>
                            <th>Car</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td></td>
                            <td><small></small></td>
                            <td>
                                <small></small>
                            </td>
                            <td></td>
                            <td>
                                <span class="pill"></span>
                            </td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="cancel_id" value="">
                                    <button class="table-button danger" type="submit">Cancel</button>
                                </form>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="6">No bookings yet.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>

</html>