<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Manage time slots | AutoCare</title>
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
                    <h1>Manage time slots</h1>
                    <p>Create availability and manage capacity.</p>
                </div>
            </div>
            <section class="panel">
                <h2>Add a time slot</h2>
                <form method="post" class="filter-form slot-form">
                    <label>Date<input type="date" name="slot_date" required /></label
                    ><label>Start time<input type="time" name="start_time" required /></label
                    ><label>End time<input type="time" name="end_time" required /></label
                    ><label>Capacity<input type="number" name="capacity" min="1" value="1" required /></label
                    ><button class="btn btn-primary" type="submit">Add time slot</button>
                </form>
            </section>
            <section class="panel">
                <div class="table-scroll">
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Capacity</th>
                                <th>Booked</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="6">
                                    <div class="table-scroll">
                                        <form method="post" class="slot-edit-form">
                                            <input type="hidden" name="update_id" value="" /><label
                                                ><span class="sr-only">Date</span
                                                ><input type="date" name="slot_date" value="" required /></label
                                            ><label
                                                ><span class="sr-only">Start</span
                                                ><input type="time" name="start_time" value="" required /></label
                                            ><label
                                                ><span class="sr-only">End</span
                                                ><input type="time" name="end_time" value="" required /></label
                                            ><label
                                                ><span class="sr-only">Capacity</span
                                                ><input
                                                    type="number"
                                                    name="capacity"
                                                    min="1"
                                                    value=""
                                                    required /></label
                                            ><span> booked</span
                                            ><button class="table-button" type="submit">Save</button>
                                        </form>
                                        <form method="post" class="slot-delete-form">
                                            <input type="hidden" name="delete_id" value="" /><button
                                                class="table-button danger"
                                                type="submit"
                                            >
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6">No time slots yet.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </body>
</html>
