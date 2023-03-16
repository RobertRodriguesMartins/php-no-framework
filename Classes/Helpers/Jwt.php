<?php

namespace Helpers;

use Exception;

class Jwt
{
    public static function generateToken($payload)
    {
        $token = sha1((string)$payload['password'] .
            (string)$payload['email'] . PRIVATE_KEY . time());

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
}
