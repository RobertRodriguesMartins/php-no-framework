<?php

require './Classes/ErrorHandler/ErrorHandler.php';

// Conexão com o banco
define('HOST', '127.0.0.1');
define('NAME', 'php_db');
define('PORT', 3306);
define('USER', 'root');
define('PASSWORD', 'password');

// raiz do projeto e variável de DS para fazer split na URI
define('DIR_APP', '/var/www/html/api');
define('DS', '/');

// constante padrão para a resposta em caso de erro
define('RESPONSE', [
    "status" => "FAIL",
    "data" => []
]);

//chave privada para a geração do token
define('PRIVATE_KEY', 'robert123orreadafile');

// autoload das classes 
if (file_exists('autoload.php')) {
    include 'autoload.php';
} else {
    die('Falha ao carregar autoload!');
}
