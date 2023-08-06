<?php

namespace App\Services;

use App\Config\TelegramConfig;

class TelegramService
{
    public function send(string $method, array $parametres = []): string
    {
        $url = TelegramConfig::getURL();

        if ($method) {
            $url .= $method;
        }

        if ($parametres) {
            foreach ($parametres as $name => $value) {
                if ($name === array_key_first($parametres)) {
                    $url .= '?' . $name . '=' . $value;
                }
                else {
                    $url .= '&' . $name . '=' . $value;
                }
            }
        }
        return $url;
    }

    public function sendMessage($chatId, $text): string
    {
        return $this->send('sendMessage', [
            'chat_id' => $chatId,
            'text' => $text
        ]);
    }
}