<?php

function autoload($classe)
{
    $classe = $_SERVER['DOCUMENT_ROOT'] . DS . 'Classes' . DS . str_replace('\\', DS, $classe) . '.php';
    if (file_exists($classe) && !is_dir($classe)) {
        include $classe;
    }
}

spl_autoload_register('autoload');