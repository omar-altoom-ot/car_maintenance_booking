<?php
require_once __DIR__ . "/../DB/config.php";

class Reservation
{
    private $id;
    private $user_id;
    private $service_id;
    private $slot_id;
    private $car_model;
    private $car_number;
    private $reservation_date;
    private $reservation_time;
    private $status;
    private $notes;
    private $database;

    public function __construct(){
        $database = new Db();
        $this->database->run_query("select * from reservation order by id desc");
        

    }

    public function getId(){
          
    }


}
?>
