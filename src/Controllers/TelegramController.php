<?php

namespace App\Controllers;

use App\Services\TelegramService;
use App\Database\DatabaseConnection;

class TelegramController
{
    public function setWebhook()
    {
        $service = new TelegramService();

        echo file_get_contents($service->send('setWebhook', [
            'url' => 'https://morcynkk.ru/callWebhook'
        ]));
    }

    public function deleteWebhook()
    {
        $service = new TelegramService();

        echo file_get_contents($service->send('deleteWebhook'));
    }

    public function callWebhook(): void
    {

        $input = file_get_contents('php://input');

        $json = json_decode($input, true);

        $telegram = new \App\Services\TelegramService();

        $chatId = $json['message']['chat']['id'];
        $message = $json['message']['text'];


        if ($message === 'Найти товар') {
            $telegram->sendWebhookReply($chatId, 'Пожалуйста, введите "Заказ *идентификатор товара*"');
        }

        elseif (preg_match('/^Заказ \d+$/', $message) && preg_match('/\d+/', $message, $matches)) {
            $conn = new DatabaseConnection();

            $sql = "SELECT * FROM orders WHERE product_id = " . (int) $matches[0];

            $order = $conn->connection()?->query($sql)->fetch();

            if (empty($order)) {
                $message = 'Заказ не найден';
            }

            else {
                $keyboard = [
                    'keyboard' => [
                        ['Редактировать заказ с товаром: ' . $order['product_id']],
                        ['Удалить заказ с товаром: ' . $order['product_id']]
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
            $telegram->sendWebhookReply($chatId, $message, $keyboard);
        }

        elseif ($message === 'Пока') {
            $telegram->sendWebhookReply($chatId, 'До встречи!');
        }

        else if ($message === 'Получить список заказов') {
            $keyboard = [
                'keyboard' => [
                    ['За текущий день', 'За прошлую неделю'],
                    ['За месяц', 'По наименованию товара'],
                    ['Получить все заказы']
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => true,
            ];

            $telegram->sendWebhookReply($chatId, 'Укажите параметр для формирования списка заказов', $keyboard);
        }

        else if ($message === 'За текущий день'){
            $telegram->ordersByDay($chatId);
        }
        else if ($message === 'За прошлую неделю'){
            $telegram->ordersByWeek($chatId);
        }
        else if ($message === 'За месяц'){
            $telegram->ordersByMonth($chatId);
        }
        else if ($message === 'По наименованию товара'){
            $telegram->ordersByProductName($chatId);
        }

        else if ($message === 'Получить все заказы') {
            $telegram->allOrders($chatId);
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

            $telegram->sendWebhookReply($chatId, $defaultMessage, $keyboard);
        }
    }
}