<?php
$input = file_get_contents('php://input');

$json = json_decode($input, true);

file_put_contents('logger.txt', $input,
    $json, FILE_APPEND);