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

    public function sendMessage($chatId, $text, $keyboard = null): string
    {
        $parametres = [
            'chat_id' => $chatId,
            'text' => $text
        ];

        if ($keyboard)
        {
            $parametres = array_merge($parametres, [
               'reply_markup' => json_encode($keyboard)
            ]);
        }

        return $this->send('sendMessage', $parametres);
    }
}