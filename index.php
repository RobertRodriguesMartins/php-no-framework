<?php

require 'bootstrap.php';

use Router\Router;

$appRouter = new Router();

$appRouter->processUrl();

$response = $appRouter->processRequest();

echo $response;
