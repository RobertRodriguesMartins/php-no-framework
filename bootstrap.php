<?php

define('HOST', '127.0.0.1');
define('NAME', 'php_db');
define('PORT', 3306);
define('USER', 'root');
define('PASSWORD', 'password');

define('DIR_APP', '/var/www/html');
define('DS', '/');

if (file_exists('autoload.php')) {
	include 'autoload.php';
} else {
	die('Falha ao carregar autoload!');
}