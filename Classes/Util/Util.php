<?php

namespace Util;

use Exception;

class Util
{
    public static function processPayload($keys)
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

    public static function generateToken($payload)
    {
        $token = password_hash((string)$payload['password'] .
            (string)$payload['email'] . PRIVATE_KEY, PASSWORD_BCRYPT);

        return $token;
    }

    public static function verifyToken($payload, $dbToken)
    {
        $isValidPassword = password_verify((string)$payload['password'] .
            (string)$payload['email'] . PRIVATE_KEY, $dbToken);

        if (!$isValidPassword) {
            throw new Exception('invalid password');
        }
    }

    public static function generateExpirationDate($days = 1)
    {
        $date = date('Y-m-d');

        return date('Y-m-d', strtotime($date . "+ $days days"));
    }

    public static function processPutPayload()
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
