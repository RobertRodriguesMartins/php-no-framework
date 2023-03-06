<?php

require 'bootstrap.php';

use Util\Util;
use Service\User;
use DB\MySql;

var_dump($_SERVER);
echo new Util();

$userService = new User(new MySql());

var_dump($userService->all());