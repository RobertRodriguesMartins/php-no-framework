<?php

namespace Helpers;

use Exception;

class Jwt
{
    public static function verifyToken($id_user, $user_email, $user_request_password, $user_password)
    {
        $tokenIsValid = strcmp(self::generateToken($id_user, $user_email, $user_request_password),  $user_password);

        if (!!$tokenIsValid) {
            throw new Exception('invalid password');
        }
    }

    public static function generateToken($id_user, $user_email, $user_password, $salt = null)
    {
        return  sha1($id_user . $user_email .
            $user_password . PRIVATE_KEY . $salt);
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
