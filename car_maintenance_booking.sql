-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 22, 2026 at 01:21 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `car_maintenance_booking`
--

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

CREATE TABLE `reservation` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `car_model` varchar(100) NOT NULL,
  `car_number` varchar(30) NOT NULL,
  `reservation_date` date NOT NULL,
  `reservation_time` time NOT NULL,
  `status` enum('pending','confirmed','rejected','cancelled') NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservation`
--

INSERT INTO `reservation` (`id`, `user_id`, `service_id`, `car_model`, `car_number`, `reservation_date`, `reservation_time`, `status`, `notes`, `created_at`) VALUES
(1, 1, 1, 'Toyota Corolla', '12-34567', '2026-10-25', '10:00:00', 'pending', 'Oil change required', '2026-10-22 08:57:54'),
(2, 2, 2, 'Hyundai Elantra', '23-45678', '2026-10-25', '11:00:00', 'confirmed', 'Check the brakes', '2026-10-22 08:57:54'),
(3, 1, 3, 'Kia Sportage', '34-56789', '2026-10-26', '12:00:00', 'pending', 'Engine inspection', '2026-10-22 08:57:54'),
(4, 2, 4, 'Honda Civic', '45-67890', '2026-10-27', '09:00:00', 'cancelled', 'Reservation cancelled', '2026-10-22 08:57:54');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `is_active` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `description`, `price`, `is_active`) VALUES
(1, 'Oil Change', 'Engine oil and oil filter replacement', 25.00, 1),
(2, 'Brake Inspection', 'Check brake pads and brake system', 15.00, 1),
(3, 'Engine Maintenance', 'General engine inspection and maintenance', 50.00, 1),
(4, 'Tire Replacement', 'Replace damaged or worn tires', 40.00, 1),
(5, 'Car Wash', 'Exterior and interior car cleaning', 10.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `is_active` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `password`, `role`, `is_active`, `created_at`) VALUES
(1, 'Omar Altoom', 'omar@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 1, '2026-10-22 08:57:54'),
(2, 'Ahmad Ali', 'ahmad@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 1, '2026-10-22 08:57:54'),
(3, 'Admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1, '2026-10-22 08:57:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

CREATE TABLE `time_slot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slot_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `capacity` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_slot_date_start` (`slot_date`,`start_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `reservation` ADD `slot_id` int(11) DEFAULT NULL AFTER `service_id`;
INSERT INTO `time_slot` (`slot_date`,`start_time`,`end_time`,`capacity`)
SELECT `reservation_date`,`reservation_time`,ADDTIME(`reservation_time`,'01:00:00'),GREATEST(1,SUM(`status` IN ('pending','confirmed'))) FROM `reservation` GROUP BY `reservation_date`,`reservation_time`;
UPDATE `reservation` r JOIN `time_slot` t ON t.slot_date=r.reservation_date AND t.start_time=r.reservation_time SET r.slot_id=t.id;
ALTER TABLE `reservation` ADD KEY `idx_reservation_slot_status` (`slot_id`,`status`), ADD KEY `idx_reservation_user_slot` (`user_id`,`slot_id`);

-- Reservation Management System: capacity-based time slots.
CREATE TABLE `time_slot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slot_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `capacity` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_slot_date_start` (`slot_date`,`start_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `reservation` ADD `slot_id` int(11) DEFAULT NULL AFTER `service_id`;
INSERT INTO `time_slot` (`slot_date`,`start_time`,`end_time`,`capacity`)
SELECT `reservation_date`,`reservation_time`,ADDTIME(`reservation_time`,'01:00:00'),GREATEST(1,SUM(`status` IN ('pending','confirmed'))) FROM `reservation` GROUP BY `reservation_date`,`reservation_time`;
UPDATE `reservation` r JOIN `time_slot` t ON t.slot_date=r.reservation_date AND t.start_time=r.reservation_time SET r.slot_id=t.id;
ALTER TABLE `reservation` ADD KEY `idx_reservation_slot_status` (`slot_id`,`status`), ADD KEY `idx_reservation_user_slot` (`user_id`,`slot_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
