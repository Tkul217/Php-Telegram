<?php
// Assuming you have an autoload file that includes the necessary classes
require_once __DIR__ . '/vendor/autoload.php';

$input = file_get_contents('php://input');

$json = json_decode($input, true);

$telegram = new \App\Services\TelegramService();

$chatId = $json['message']['chat']['id'];
$messageText = 'Hello, this is your bot sending a message!';

$response = file_get_contents($telegram->sendMessage($chatId, $messageText));

file_put_contents('logger.txt', PHP_EOL . $response, FILE_APPEND);
