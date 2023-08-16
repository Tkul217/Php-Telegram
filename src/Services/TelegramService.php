<?php

namespace App\Services;

use App\Config\TelegramConfig;
use App\Database\DatabaseConnection;

class TelegramService
{
    private DatabaseConnection $conn;

    public function __construct()
    {
        $this->conn = new DatabaseConnection();
    }

    public function send(string $method, array $parametres = []): string
    {
        $url = TelegramConfig::getURL();

        if ($method) {
            $url .= $method;
        }

        if (!empty($parametres)) {
            foreach ($parametres as $name => $value) {
                if ($name === array_key_first($parametres)) {
                    $url .= '?' . $name . '=' . $value;
                } else {
                    $url .= '&' . $name . '=' . $value;
                }
            }
        }
        return $url;
    }

    public function callWebhook() :bool
    {
        $input = file_get_contents('php://input');

        $json = json_decode($input, true);

        $chatId = $json['message']['chat']['id'];
        $message = $json['message']['text'];

        if (is_numeric($message)) {
            return $this->findOrder($chatId, $message);
        }

        return match ($message) {
            'Найти товар' => $this->sendWebhookReply($chatId, 'Пожалуйста, введите идентификатор заказа'),
            'Получить список заказов' => $this->getOrders($chatId),
            'Получить все заказы' => $this->allOrders($chatId),
            'По наименованию товара' => $this->ordersByProductName($chatId),
            'За текущий день' => $this->ordersByPeriod($chatId, 'day'),
            'За неделю' => $this->ordersByPeriod($chatId, 'week'),
            'За месяц' => $this->ordersByPeriod($chatId, 'month'),
            'Пока' => $this->sendWebhookReply($chatId, 'До встречи!'),
            default => $this->defaultAction($chatId)
        };
    }

    public function getOrders($chatId): bool
    {
        $keyboard = [
            'keyboard' => [
                ['За текущий день', 'За неделю'],
                ['За месяц', 'По наименованию товара'],
                ['Получить все заказы']
            ],
            'resize_keyboard' => true,
            'one_time_keyboard' => true,
        ];

        return $this->sendWebhookReply($chatId, 'Укажите параметр для формирования списка заказов', $keyboard);
    }

    public function defaultAction($chatId): bool
    {
        $defaultMessage = 'Выберите действие';

        $keyboard = [
            'keyboard' => [
                ['Найти товар', 'Пока'],
                ['Получить список заказов']
            ],
            'resize_keyboard' => true,
            'one_time_keyboard' => true,
        ];

        return $this->sendWebhookReply($chatId, $defaultMessage, $keyboard);
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

    public function sendWebhookReply($chatId, $message, $keyboard = null): bool
    {
        $response = file_get_contents($this->sendMessage($chatId, $message, $keyboard));

        return file_put_contents('logger.txt', PHP_EOL . $response, FILE_APPEND);
    }

    public function ordersByPeriod($chatId, $period): bool
    {
        return match ($period) {
            'day' => $this->ordersByDay($chatId),
            'week' => $this->ordersByWeek($chatId),
            'month' => $this->sendWebhookReply($chatId, 'orders by month'),
        };
    }


    public function ordersByDay($chatId): bool
    {
        $sql = "select * from orders where date(created_at) = date(now())";

        $orders = $this->conn->connection()->query($sql)->fetchAll();

        $message = "";

        foreach ($orders as $order) {

            $message .= "Ваш заказ: "
                . " ID заказа: " . $order['id']
                . " Наименование товара: " . $order['product_name']
                . " Цена: " . $order['product_price']
                .  " Количество: " . $order['product_count'] . '%0A' . '%0A';
        }

        return $this->sendWebhookReply($chatId, $message);
    }

    public function ordersByWeek($chatId): bool
    {
        $sql = "select * from orders where date_trunc('week', created_at) = date_trunc('week', now())";

        $orders = $this->conn->connection()->query($sql)->fetchAll();

        $message = "";

        foreach ($orders as $order) {

            $message .= "Ваш заказ: "
                . " ID заказа: " . $order['id']
                . " Наименование товара: " . $order['product_name']
                . " Цена: " . $order['product_price']
                .  " Количество: " . $order['product_count'] . '%0A' . '%0A';
        }

        return $this->sendWebhookReply($chatId, $message);
    }

    public function ordersByProductName($chatId): bool
    {
        return $this->sendWebhookReply($chatId, 'orders by product name');
    }

    public function allOrders($chatId): bool
    {
        $sql = "SELECT * FROM orders";

        $orders = $this->conn->connection()->query($sql)->fetchAll();

        $message = "";

        foreach ($orders as $order) {

            $message .= "Ваш заказ: "
                . " ID товара: " . $order['id']
                . " Наименование товара: " . $order['product_name']
                . " Цена: " . $order['product_price']
                .  " Количество: " . $order['product_count'] . '%0A' . '%0A';
        }

        return $this->sendWebhookReply($chatId, $message);
    }

    public function findOrder($chatId, $orderId): bool
    {
        $sql = "SELECT * FROM orders WHERE id = $orderId";

        $order = $this->conn->connection()?->query($sql)->fetch();

        if (empty($order)) {
            $message = 'Заказ не найден';
        }

        else {
            $keyboard = [
                'keyboard' => [
                    ['Редактировать заказ с идентификатором: ' . $order['id']],
                    ['Удалить заказ с идентификатором: ' . $order['id']]
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => true,
            ];

            $message = 'Ваш заказ: '
                . ' Наименование товара: ' . $order['product_name']
                . ' Цена: ' . $order['product_price']
                .  ' Количество: ' . $order['product_count'];
        }

        /** @var TYPE_NAME $keyboard */
        return $this->sendWebhookReply($chatId, $message, $keyboard);
    }
}