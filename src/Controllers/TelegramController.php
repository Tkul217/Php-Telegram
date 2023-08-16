<?php

namespace App\Controllers;

use App\Core\Controller;

class TelegramController extends Controller
{
    public function setWebhook()
    {
        echo file_get_contents($this->telegram->send('setWebhook', [
            'url' => 'https://morcynkk.ru/callWebhook'
        ]));
    }

    public function deleteWebhook()
    {
        echo file_get_contents($this->telegram->send('deleteWebhook'));
    }

    public function callWebhook(): void
    {
        $this->telegram->callWebhook();
    }
}