<?php

namespace App\Core;

use App\Database\DatabaseConnection;
use App\Services\TelegramService;

class Controller {
    public $view;
    public $conn;
    public $telegram;

    public function __construct()
    {
        $this->view = new View();
        $this->conn = new DatabaseConnection();
        $this->telegram = new TelegramService();
    }
}