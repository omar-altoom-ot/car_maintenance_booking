<?php
require_once __DIR__ . "/../DB/config.php";

class Service
{
    private $database;

    public function __construct()
    {
        $this->database = new Db();
    }

    
}
?>
