-- Run once against an existing car_maintenance_booking database.
CREATE TABLE IF NOT EXISTS `time_slot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slot_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `capacity` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_slot_date_start` (`slot_date`,`start_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `reservation` ADD COLUMN IF NOT EXISTS `slot_id` int(11) DEFAULT NULL AFTER `service_id`;
INSERT INTO `time_slot` (`slot_date`,`start_time`,`end_time`,`capacity`)
SELECT `reservation_date`,`reservation_time`,ADDTIME(`reservation_time`,'01:00:00'),GREATEST(1,SUM(`status` IN ('pending','confirmed'))) FROM `reservation` GROUP BY `reservation_date`,`reservation_time`
ON DUPLICATE KEY UPDATE `capacity`=GREATEST(`capacity`,VALUES(`capacity`));
UPDATE `reservation` r JOIN `time_slot` t ON t.slot_date=r.reservation_date AND t.start_time=r.reservation_time SET r.slot_id=t.id;
CREATE INDEX IF NOT EXISTS `idx_reservation_slot_status` ON `reservation` (`slot_id`,`status`);
CREATE INDEX IF NOT EXISTS `idx_reservation_user_slot` ON `reservation` (`user_id`,`slot_id`);
