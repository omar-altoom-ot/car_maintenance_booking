# AutoCare Reservation Management System

AutoCare is a PHP and MySQL reservation system for car maintenance. Customers can create accounts, browse services, choose available capacity-based time slots, manage their reservations, and cancel before a slot starts. Administrators can review and filter reservations, manage users and time slots, and view dashboard statistics and a Chart.js status chart.

## Requirements

- XAMPP with PHP 8.0 or later and MySQL/MariaDB
- A web browser

## Installation

1. Place the project folder under `xampp/htdocs`.
2. Start Apache and MySQL from the XAMPP Control Panel.
3. In phpMyAdmin, import `car_maintenance_booking.sql`. It creates the database tables, sample accounts, and matching time slots.
4. For an existing database created from an older version, select `car_maintenance_booking` in phpMyAdmin and run `DB/migration_reservation_management.sql` once. It adds the capacity-based time-slot schema and maps existing reservations to slots.
5. Open the project at `http://localhost/car_maintenance_booking/car_maintenance_booking/` (adjust the URL if your project directory has a different name).

The default local database connection is configured in `DB/config.php` for MySQL on localhost with the XAMPP `root` account and no password. Update those values if your local database uses different credentials.

## Demo accounts

The SQL seed creates `admin@example.com`, `omar@example.com`, and `ahmad@example.com`. The seed password for all three accounts is `password`.

## Main workflows

- Register, log in, and log out with session-based authentication and hashed passwords.
- Browse active maintenance services and create a pending reservation in an available future slot.
- View reservation history and cancel an owned pending or confirmed reservation before its start time.
- Search reservations by customer name or email and filter by status in the admin dashboard.
- Confirm or reject pending reservations, or cancel active reservations as an administrator.
- Activate or deactivate customer accounts. Inactive users cannot log in.
- Add future time slots with a start, end, and capacity. Slots with pending or confirmed reservations cannot be deleted.

## Business rules

- New reservations must use a future time slot with remaining capacity.
- A customer cannot book the same time slot twice.
- Pending and confirmed reservations use slot capacity; rejected and cancelled reservations do not.
- Only administrators can confirm or reject reservations.
- Users can view and cancel only their own reservations.
- The server checks roles, reservation ownership, capacity, duplicate bookings, dates, and slot deletion eligibility.

## Technologies

- PHP 8, PDO, MySQL/MariaDB, HTML, CSS, and Chart.js 4

## Version control and demonstration

Use Git to commit meaningful project changes throughout development. A project demonstration can show registration and login, service booking, capacity and validation messages, user cancellation, reservation search and status filtering, user activation controls, time-slot capacity management, and the admin dashboard chart.
