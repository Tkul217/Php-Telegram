<?php

namespace App\Services;

use App\Config\TelegramConfig;
use App\Database\DatabaseConnection;

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

    public function sendWebhookReply($chatId, $message, $keyboard = null): void
    {
        $response = file_get_contents($this->sendMessage($chatId, $message, $keyboard));

        file_put_contents('logger.txt', PHP_EOL . $response, FILE_APPEND);
    }

    public function ordersByDay($chatId): void
    {
        $this->sendWebhookReply($chatId, 'orders by day');
    }

    public function ordersByWeek($chatId): void
    {
        $this->sendWebhookReply($chatId, 'orders by week');
    }

    public function ordersByMonth($chatId): void
    {
        $this->sendWebhookReply($chatId, 'orders by month');
    }

    public function ordersByProductName($chatId): void
    {
        $this->sendWebhookReply($chatId, 'orders by product name');
    }

    public function allOrders($chatId): void
    {
        $conn = new DatabaseConnection();

        $sql = "SELECT * FROM orders";

        $orders = $conn->connection()->query($sql)->fetchAll();

        $message = "";

        foreach ($orders as $order) {

            $message .= "Ваш заказ: "
                . " ID товара: " . $order['product_id']
                . " Наименование товара: " . $order['product_name']
                . " Цена: " . $order['product_price']
                .  " Количество: " . $order['product_count'] . '%0A' . '%0A';
        }

        $this->sendWebhookReply($chatId, $message);
    }
}