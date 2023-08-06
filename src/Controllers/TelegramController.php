<?php

namespace App\Controllers;

use App\Config\TelegramConfig;
use App\Services\TelegramService;

class TelegramController
{
    public function setWebhook()
    {
        $service = new TelegramService();

        echo file_get_contents($service->send('setWebhook', [
            'url' => 'https://morcynkk.ru/getWebhookData'
        ]));
    }

    public function deleteWebhook()
    {
        $service = new TelegramService();

        echo file_get_contents($service->send('deleteWebhook'));
    }

//    public function getWebhookData()
//    {
//        echo file_get_contents($this->sendMessage(
//            5530349508,
//            'sdfsdf'
//        ));
//    }
}