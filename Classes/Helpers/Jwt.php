<?php

namespace Helpers;

use Exception;

class Jwt
{
    public static function verifyToken($userEmail, $userPassword, string $userToken)
    {
        $tokenIsValid = password_verify(sha1($userEmail .
            $userPassword . PRIVATE_KEY), $userToken);

        if (!$tokenIsValid) {
            throw new Exception('invalid password');
        }
    }

    public static function generateToken($userEmail, $userPassword)
    {
        return  password_hash(sha1($userEmail .
            $userPassword . PRIVATE_KEY), PASSWORD_BCRYPT);
    }

    public static function generateExpirationDate($days = 1)
    {
        $date = date('Y-m-d');

        return date('Y-m-d', strtotime($date . "+ $days days"));
    }

    public static function checkTokenDate($tokenExpireDate)
    {
        if (date($tokenExpireDate) > date('Ymd')) {
            http_response_code(401);
            throw new Exception('refresh your token.');
        }
    }

    public static function prepareToken($rawtoken)
    {
        $splitedTokenArray = explode('Bearer ', trim($rawtoken));

        return isset($splitedTokenArray[1]) ? $splitedTokenArray[1] : 'NO_REQUEST_TOKEN';
    }
}
