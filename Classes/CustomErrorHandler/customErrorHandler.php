<?php 

function customErrorHandler($errstr) {
    echo "o erro foi: $errstr";

    echo json_encode(RESPONSE);
}

set_error_handler('customErrorHandler');
set_exception_handler('customErrorHandler');
