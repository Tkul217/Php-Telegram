<?php

namespace App\Config;

use Dotenv\Dotenv;

class TelegramConfig
{
    public static function getURL(): string
    {
        Dotenv::createImmutable(dirname(__DIR__, 2))->load();

        return 'https://api.telegram.org/bot' . $_ENV['TELEGRAM_BOT_TOKEN'] . '/';
    }
}