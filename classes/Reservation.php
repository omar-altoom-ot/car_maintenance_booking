<?php
require_once __DIR__ . "/../DB/config.php";

class Reservation
{
    private $database;

    public function __construct()
    {
        $this->database = new Db();
    }

    public function getAll($search = "", $status = "")
    {
        $sql = "SELECT reservation.*, user.name AS customer_name, user.email, services.name AS service_name
                FROM reservation
                JOIN user ON reservation.user_id = user.id
                JOIN services ON reservation.service_id = services.id
                WHERE 1=1";
        $values = [];

        if ($search !== "") {
            $sql .= " AND (user.name LIKE ? OR user.email LIKE ?)";
            $values[] = "%" . $search . "%";
            $values[] = "%" . $search . "%";
        }

        if (in_array($status, ["pending", "confirmed", "rejected", "cancelled"], true)) {
            $sql .= " AND reservation.status = ?";
            $values[] = $status;
        }

        $sql .= " ORDER BY reservation.created_at DESC";
        return $this->database->run_query($sql, $values)->fetchAll();
    }

    public function getByUser($user_id)
    {
        return $this->database->run_query(
            "SELECT reservation.*, services.name AS service_name, services.price
             FROM reservation JOIN services ON reservation.service_id = services.id
             WHERE reservation.user_id = ? ORDER BY reservation.reservation_date DESC, reservation.reservation_time DESC",
            [$user_id]
        )->fetchAll();
    }

    public function getStats()
    {
        $stats = [];
        $stats["total"] = (int)$this->database->run_query("SELECT COUNT(*) FROM reservation")->fetchColumn();
        $stats["pending"] = (int)$this->database->run_query("SELECT COUNT(*) FROM reservation WHERE status='pending'")->fetchColumn();
        $stats["confirmed"] = (int)$this->database->run_query("SELECT COUNT(*) FROM reservation WHERE status='confirmed'")->fetchColumn();
        $stats["rejected"] = (int)$this->database->run_query("SELECT COUNT(*) FROM reservation WHERE status='rejected'")->fetchColumn();
        $stats["cancelled"] = (int)$this->database->run_query("SELECT COUNT(*) FROM reservation WHERE status='cancelled'")->fetchColumn();
        $stats["users"] = (int)$this->database->run_query("SELECT COUNT(*) FROM user WHERE role='user'")->fetchColumn();
        return $stats;
    }

    public function getAvailableSlots()
    {
        $slots = $this->database->run_query(
            "SELECT * FROM time_slot WHERE is_active = 1 AND TIMESTAMP(slot_date, start_time) > NOW() ORDER BY slot_date, start_time"
        )->fetchAll();
        $available = [];

        foreach ($slots as $slot) {
            $booked = (int)$this->database->run_query(
                "SELECT COUNT(*) FROM reservation WHERE slot_id = ? AND status IN ('pending','confirmed')",
                [$slot["id"]]
            )->fetchColumn();
            $slot["remaining"] = (int)$slot["capacity"] - $booked;
            if ($slot["remaining"] > 0) {
                $available[] = $slot;
            }
        }

        return $available;
    }

    public function create($user_id, $service_id, $slot_id, $car_model, $car_number, $notes = "")
    {
        $car_model = trim($car_model);
        $car_number = trim($car_number);
        $notes = trim($notes);
        if ($car_model === "" || strlen($car_model) > 100 || $car_number === "" || strlen($car_number) > 30) {
            return false;
        }

        $service = $this->database->run_query(
            "SELECT id FROM services WHERE id = ? AND is_active = 1", [$service_id]
        )->fetch();
        $slot = $this->database->run_query(
            "SELECT * FROM time_slot WHERE id = ? AND is_active = 1 AND TIMESTAMP(slot_date, start_time) > NOW()",
            [$slot_id]
        )->fetch();
        if (!$service || !$slot) {
            return false;
        }

        $booked = (int)$this->database->run_query(
            "SELECT COUNT(*) FROM reservation WHERE slot_id = ? AND status IN ('pending','confirmed')",
            [$slot_id]
        )->fetchColumn();
        if ($booked >= (int)$slot["capacity"]) {
            return false;
        }

        $sameBooking = $this->database->run_query(
            "SELECT id FROM reservation WHERE user_id = ? AND slot_id = ? AND status IN ('pending','confirmed')",
            [$user_id, $slot_id]
        )->fetch();
        if ($sameBooking) {
            return false;
        }

        $this->database->run_query(
            "INSERT INTO reservation (user_id, service_id, slot_id, car_model, car_number, reservation_date, reservation_time, status, notes)
             VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', ?)",
            [$user_id, $service_id, $slot_id, $car_model, $car_number, $slot["slot_date"], $slot["start_time"], $notes]
        );
        return true;
    }

    public function cancel($id, $user_id)
    {
        $result = $this->database->run_query(
            "UPDATE reservation SET status='cancelled'
             WHERE id=? AND user_id=? AND status IN ('pending','confirmed')
             AND TIMESTAMP(reservation_date, reservation_time) > NOW()",
            [$id, $user_id]
        );
        return $result->rowCount() === 1;
    }

    public function updateStates($id, $status)
    {
        if ($status === "confirmed" || $status === "rejected") {
            $result = $this->database->run_query(
                "UPDATE reservation SET status=? WHERE id=? AND status='pending'",
                [$status, $id]
            );
            return $result->rowCount() === 1;
        }

        if ($status === "cancelled") {
            $result = $this->database->run_query(
                "UPDATE reservation SET status='cancelled' WHERE id=? AND status IN ('pending','confirmed')",
                [$id]
            );
            return $result->rowCount() === 1;
        }

        return false;
    }

    public function getSlots()
    {
        $slots = $this->database->run_query(
            "SELECT * FROM time_slot ORDER BY slot_date DESC, start_time DESC"
        )->fetchAll();
        foreach ($slots as $key => $slot) {
            $slots[$key]["booked"] = (int)$this->database->run_query(
                "SELECT COUNT(*) FROM reservation WHERE slot_id=? AND status IN ('pending','confirmed')",
                [$slot["id"]]
            )->fetchColumn();
        }
        return $slots;
    }

    public function addSlot($date, $start, $end, $capacity)
    {
        if (!$this->validSlot($date, $start, $end, $capacity)) {
            return false;
        }
        $exists = $this->database->run_query(
            "SELECT id FROM time_slot WHERE slot_date=? AND start_time=?", [$date, $start]
        )->fetch();
        if ($exists) {
            return false;
        }
        $this->database->run_query(
            "INSERT INTO time_slot (slot_date, start_time, end_time, capacity, is_active) VALUES (?, ?, ?, ?, 1)",
            [$date, $start, $end, $capacity]
        );
        return true;
    }

    public function updateSlot($id, $date, $start, $end, $capacity)
    {
        if (!$this->validSlot($date, $start, $end, $capacity)) {
            return false;
        }
        $slot = $this->database->run_query("SELECT * FROM time_slot WHERE id=?", [$id])->fetch();
        if (!$slot) {
            return false;
        }
        $booked = (int)$this->database->run_query(
            "SELECT COUNT(*) FROM reservation WHERE slot_id=? AND status IN ('pending','confirmed')", [$id]
        )->fetchColumn();
        if ($capacity < $booked) {
            return false;
        }
        if ($booked > 0 && ($slot["slot_date"] !== $date || substr($slot["start_time"], 0, 5) !== substr($start, 0, 5))) {
            return false;
        }
        $duplicate = $this->database->run_query(
            "SELECT id FROM time_slot WHERE slot_date=? AND start_time=? AND id<>?", [$date, $start, $id]
        )->fetch();
        if ($duplicate) {
            return false;
        }
        $this->database->run_query(
            "UPDATE time_slot SET slot_date=?, start_time=?, end_time=?, capacity=? WHERE id=?",
            [$date, $start, $end, $capacity, $id]
        );
        return true;
    }

    public function deleteSlot($id)
    {
        $booked = (int)$this->database->run_query(
            "SELECT COUNT(*) FROM reservation WHERE slot_id=? AND status IN ('pending','confirmed')", [$id]
        )->fetchColumn();
        if ($booked > 0) {
            return false;
        }
        return $this->database->run_query("DELETE FROM time_slot WHERE id=?", [$id])->rowCount() === 1;
    }

    private function validSlot($date, $start, $end, $capacity)
    {
        if ($date === "" || $start === "" || $end === "" || (int)$capacity < 1) {
            return false;
        }
        if (strtotime($date . " " . $start) <= time()) {
            return false;
        }
        return strtotime($date . " " . $end) > strtotime($date . " " . $start);
    }
}
?>
