<?php

namespace Helpers;

use Exception;

class Payload
{
    public static function processPost($keys)
    {
        $payload = [];
        $inputType = $_POST;

        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $inputType = $keys;
            $keys = array_keys($keys);
        }

        foreach ($keys as $key) {
            $exists = array_key_exists($key, $inputType);

            if (!$exists) {
                http_response_code(400);
                throw new Exception('missing expected arguments.');
            }

            $payload[$key] = $inputType[$key];
        }

        return $payload;
    }

    public static function processPut()
    {
        $_PUT = [];
        parse_str(file_get_contents("php://input"), $_PUT);

        foreach ($_PUT as $key => $value) {
            unset($_PUT[$key]);

            $_PUT[str_replace('amp;', '', $key)] = $value;
        }

        return $_PUT;
    }
}
