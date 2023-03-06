<?php

namespace Util;

use Exception;

class Util
{
    public static function processPayload($keys)
    {
        $payload = [];
        foreach ($keys as $key) {
            $exists = array_key_exists($key, $_POST);
            if (!$exists) {
                http_response_code(400);
                throw new Exception('missing expected arguments.');
            }
            $payload[$key] = $_POST[$key];
        }

        return $payload;
    }
    public static function generateToken($payload)
    {
        $fakeToken = implode('', array_reverse(str_split($payload['password']))) . $payload['login'] . random_int(999, 999999);
        return $fakeToken;
    }
    public static function generateExpirationDate($days = 1)
    {
        $date = date('Y-m-d');
        return date('Y-m-d', strtotime($date . "+ $days days"));
    }
}
