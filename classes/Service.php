<?php
require_once __DIR__ . '/../DB/config.php';

class Service {
    private $database;

    public function __construct() {
        $this->database = new Db();
    }

    public function getAll() {
        return $this->database->run_query('SELECT * FROM services WHERE is_active = 1 ORDER BY name')->fetchAll();
    }

    public function getById(int $id) {
        return $this->database->run_query('SELECT * FROM services WHERE id = ? AND is_active = 1', [$id])->fetch();
    }
}
?>