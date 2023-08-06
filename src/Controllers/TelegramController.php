<?php

namespace App\Controllers;

use App\Config\TelegramConfig;

class TelegramController
{
    public function setWebhook()
    {
        echo file_get_contents(TelegramConfig::URL . 'setWebhook?url=https://morcynkk.ru/getWebhookData');
    }

    public function getWebhookData()
    {
        echo file_get_contents(TelegramConfig::URL . 'getWebhookInfo');
    }
}