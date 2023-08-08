<?php
// Assuming you have an autoload file that includes the necessary classes
use App\Database\DatabaseConnection;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap/app.php';

$input = file_get_contents('php://input');

$json = json_decode($input, true);

$telegram = new \App\Services\TelegramService();

$chatId = $json['message']['chat']['id'];
$message = $json['message']['text'];

if ($message === 'Найти товар') {
    sendReply($telegram, $chatId, 'Пожалуйста, введите "Заказ *идентификатор заказа*"');
}

elseif (preg_match('/^Заказ \d+$/', $message) && preg_match('/\d+/', $message, $matches)) {
    $conn = new DatabaseConnection();

    $sql = "SELECT * FROM orders WHERE product_id = " . (int) $matches[0];

    $order = $conn->connection()?->query($sql)->fetch();

    if (empty($order)) {
        $message = 'Заказ не найден';
    }

    else {
        $message = 'Ваш заказ: '
            . ' Наименование товара: ' . $order['product_name']
            . ' Цена: ' . $order['product_price']
            .  ' Количество: ' . $order['product_count'];
    }

    sendReply($telegram, $chatId, $message);
}

elseif ($message === 'Пока') {
    sendReply($telegram, $chatId, 'До встречи!');
}

else {
    $defaultMessage = 'Выберите действие';

    $keyboard = [
        'keyboard' => [
            ['Найти товар', 'Пока'],
        ],
        'resize_keyboard' => true,
        'one_time_keyboard' => true,
    ];

    sendReply($telegram, $chatId, $defaultMessage, $keyboard);
}

function sendReply($telegram, $chatId, $message, $keyboard = null)
{
    $response = file_get_contents($telegram->sendMessage($chatId, $message, $keyboard));

    file_put_contents('logger.txt', PHP_EOL . $response, FILE_APPEND);
}
