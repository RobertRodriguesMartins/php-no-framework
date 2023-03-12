<?php 

function customErrorHandler($errnum = 'numero', $errstr = 'erro desconhecido', $errfile = 'arquivo?', $errline = 'linha') {
    echo "o erro $errstr: foi encontrado em $errfile na linha $errline. codigo: $errnum";

    echo json_encode(RESPONSE);
}

set_error_handler('customErrorHandler');
set_exception_handler('customErrorHandler');
