<?php
require_once __DIR__ . '/../DB/config.php';

class Reservation {
    private $database;

    public function __construct() {
        $this->database = new Db();
    }

    public function create(int $userId, int $serviceId, string $carModel, string $carNumber, string $date, string $time, string $notes = '') {
        $service = $this->database->run_query(
            'SELECT id FROM services WHERE id = ? AND is_active = 1',
            [$serviceId]
        )->fetch();

        if (!$service || $carModel === '' || $carNumber === '' || $date < date('Y-m-d')) {
            return false;
        }

        $this->database->run_query(
            'INSERT INTO reservation (user_id, service_id, car_model, car_number, reservation_date, reservation_time, status, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
            [$userId, $serviceId, $carModel, $carNumber, $date, $time, 'pending', trim($notes)]
        );
        return true;
    }

    public function getByUser(int $userId) {
        return $this->database->run_query(
            'SELECT r.*, s.name AS service_name, s.price FROM reservation r JOIN services s ON s.id = r.service_id WHERE r.user_id = ? ORDER BY r.reservation_date DESC, r.reservation_time DESC',
            [$userId]
        )->fetchAll();
    }

    public function getAll() {
        return $this->database->run_query(
            'SELECT r.*, u.name AS customer_name, u.email, s.name AS service_name FROM reservation r JOIN user u ON u.id = r.user_id JOIN services s ON s.id = r.service_id ORDER BY r.reservation_date DESC, r.reservation_time DESC'
        )->fetchAll();
    }

    public function updateStatus(int $id, string $status) {
        $allowed = ['pending', 'confirmed', 'rejected', 'cancelled'];
        if (!in_array($status, $allowed, true)) {
            return false;
        }

        $this->database->run_query('UPDATE reservation SET status = ? WHERE id = ?', [$status, $id]);
        return true;
    }
}
?>