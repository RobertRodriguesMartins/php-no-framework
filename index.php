<?php

require 'bootstrap.php';

use Router\Router;

$appRouter = new Router();

$appRouter->processUrl();

try {
    $response = $appRouter->processRequest();
} catch (Exception $e) {
    $response = "error: " . $e->getMessage();
}


echo $response;
