<?php

require 'bootstrap.php';

use Router\Router;

$appRouter = new Router();

$appRouter->processUrl();

try {
    $response = $appRouter->processRequest();
    $response = json_decode($response, true);
    if (isset($response['lastId'])) {
        unset($response['lastId']);
    }
    $response = json_encode($response);
} catch (Exception $e) {
    $response = [
        "status" => "FAIL",
        "data" => [],
    ];
    $response = json_encode($response);
}

echo $response;
