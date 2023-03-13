<?php

function customErrorHandler($errnum = 'numero', $errstr = 'erro desconhecido', $errfile = 'arquivo?', $errline = 'linha')
{
    // echo "o erro $errstr: foi encontrado em $errfile na linha $errline. codigo: $errnum";

    $statusCode = http_response_code();

    if ($statusCode === 200) {
        http_response_code(400);
    }

    echo json_encode(RESPONSE);
}

set_error_handler('customErrorHandler');
set_exception_handler('customErrorHandler');
