<?php

$routes = new \App\Core\Route();

$routes->call('/', \App\Controllers\OrderController::class, 'index');

$routes->call('/orderCreate', \App\Controllers\OrderController::class, 'create');

$routes->call('/orderStore', \App\Controllers\OrderController::class, 'store');

$routes->call('/setWebhook', \App\Controllers\TelegramController::class, 'setWebhook');

$routes->call('/deleteWebhook', \App\Controllers\TelegramController::class, 'deleteWebhook');

$routes->call('/callWebhook', \App\Controllers\TelegramController::class, 'callWebhook');