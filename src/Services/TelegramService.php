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

    public function callWebhook() :void
    {
        $input = file_get_contents('php://input');

        $json = json_decode($input, true);

        $chatId = $json['message']['chat']['id'];
        $message = $json['message']['text'];

        $this->changeStatus($chatId);


        if ($message === 'Найти товар') {
            $this->sendWebhookReply($chatId, 'Пожалуйста, введите идентификатор заказа');
        }

        elseif (is_numeric($message)) {
            $this->findOrder($chatId, $message);
        }

        elseif ($message === 'Пока') {
            $this->sendWebhookReply($chatId, 'До встречи!');
        }

        else if ($message === 'Получить список заказов') {
            $keyboard = [
                'keyboard' => [
                    ['За текущий день', 'За неделю'],
                    ['За месяц', 'По наименованию товара'],
                    ['Получить все заказы']
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => true,
            ];

            $this->sendWebhookReply($chatId, 'Укажите параметр для формирования списка заказов', $keyboard);
        }

        else if ($message === 'За текущий день'){
            $this->ordersByDay($chatId);
        }
        else if ($message === 'За прошлую неделю'){
            $this->ordersByWeek($chatId);
        }
        else if ($message === 'За месяц'){
            $this->ordersByMonth($chatId);
        }
        else if ($message === 'По наименованию товара'){
            $this->ordersByProductName($chatId);
        }

        else if ($message === 'Получить все заказы') {
            $this->allOrders($chatId);
        }

        else {
            $defaultMessage = 'Выберите действие';

            $keyboard = [
                'keyboard' => [
                    ['Найти товар', 'Пока'],
                    ['Получить список заказов']
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => true,
            ];

            $this->sendWebhookReply($chatId, $defaultMessage, $keyboard);
        }
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

        $this->sendWebhookReply($chatId, $message);
    }

    public function ordersByWeek($chatId): void
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

        $this->sendWebhookReply($chatId, $message);
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

        $this->sendWebhookReply($chatId, $message);
    }

    public function findOrder($chatId, $orderId): void
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
        $this->sendWebhookReply($chatId, $message, $keyboard);
    }

    public function editOrder($chatId)
    {

    }

    public function changeStatus($chatId)
    {
        $message = "Hello! Here's an emoji:"; // Emoji code for a smiling face

        $this->sendWebhookReply($chatId, $message);
    }

    public function deleteOrder($chatId)
    {

    }

    public function confirmDeleteOrder($chatId)
    {

    }
}