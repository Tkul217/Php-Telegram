<?php

$routes = new \App\Core\Route();

$routes->call('/', \App\Controllers\OrderController::class, 'index');

$routes->call('/orderCreate', \App\Controllers\OrderController::class, 'create');

$routes->call('/orderStore', \App\Controllers\OrderController::class, 'store');

$routes->call('/setWebhook', \App\Controllers\TelegramController::class, 'setWebhook');

$routes->call('/getWebhookData', \App\Controllers\TelegramController::class, 'getWebhookData');
