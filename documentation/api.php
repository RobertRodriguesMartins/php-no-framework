<?php
require '/var/www/html/vendor/autoload.php';
$openapi = \OpenApi\Generator::scan(['/var/www/html/Controllers']);
var_dump($openapi);
header('Content-Type: application/json');
echo $openapi->toJson();