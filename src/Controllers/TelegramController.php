<?php

namespace App\Controllers;

use App\Services\TelegramService;

class TelegramController
{
    public function setWebhook()
    {
        $service = new TelegramService();

        echo file_get_contents($service->send('setWebhook', [
            'url' => 'https://morcynkk.ru/webhook.php'
        ]));
    }

    public function deleteWebhook()
    {
        $service = new TelegramService();

        echo file_get_contents($service->send('deleteWebhook'));
    }
}