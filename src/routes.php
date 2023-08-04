<?php

$routes = new \App\Core\Route();

$routes->call('/index', \App\Controllers\Controller_Main::class, 'index');