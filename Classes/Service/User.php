<?php

namespace Service;

use DB\MySql;
use Util\Util;

class User
{
    private MySql $db;

    public function __construct()
    {
        $this->db = new MySql();
    }

    public function getAll()
    {
        return $this->db->getAll('users');
    }

    public function create()
    {
        $payload = Util::processPayload(['login', 'password']);
        $token = Util::generateToken($payload);
        $tkdate = Util::generateExpirationDate();
        $login = $payload['login'];

        $query = "INSERT INTO users (login, token, token_expire_date) VALUES ('$login', '$token', '$tkdate')";
        $response = $this->db->insertOne($query);

        if (is_array($response)) {
            $response['token'] = $token;
        }

        return $response;
    }
}
