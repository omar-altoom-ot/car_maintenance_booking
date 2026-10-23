AutoCare - PHP and MySQL

Setup:
1. Copy the car_maintenance_booking folder into your XAMPP htdocs folder.
2. Start Apache and MySQL.
3. Open phpMyAdmin and import car_maintenance_booking.sql.
4. Check DB/config.php if your MySQL username or password is different.
5. Open http://localhost/car_maintenance_booking/index.php

The project includes User, Service, and Reservation classes.
Register a normal account from the site. Existing sample SQL users can be used only if their password hash matches the password you know.
The admin role is redirected to admin/dashboard.php after login.
