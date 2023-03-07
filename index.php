<?php

require 'bootstrap.php';

use Router\Router;

$appRouter = new Router();

$appRouter->processUrl();

try {
    $response = $appRouter->processRequest();
} catch (Exception $e) {
    $response = [
        "status" => "FAIL",
        "data" => [],
    ];
    $response = json_encode($response);
}


echo $response;
