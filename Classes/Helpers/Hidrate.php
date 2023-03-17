<?php

namespace Helpers;

use Error;

class Regex
{
    protected const REGEX_EMAIL = '/^[^@\s]*@[^@]+\.\S+/';
}


class Hidrate extends Regex
{
    static string $input;
    static bool $response;
    static $return;

    public static function email($email): string
    {
        self::$response = preg_match(self::REGEX_EMAIL, $email);

        if (self::$response) {
            return $email;
        }

        throw new Error('invalid email pattern');
    }
}
