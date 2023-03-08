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

    public function getAll($count = null)
    {
        return $this->db->getAll('users', $count);
    }

    public function getOne($id)
    {
        return $this->db->getOne('users', $id);
    }

    public function login()
    {
        $token = 'not implemented yet';
        return $this->db->getOne('users', null, $token);
    }

    public function create($lastId)
    {
        $payload = Util::processPayload(['email', 'password']);
        $token = Util::generateToken($payload, $lastId);
        $tkdate = Util::generateExpirationDate();
        $email = $payload['email'];

        $query = "INSERT INTO users (email, token, token_expire_date) VALUES ('$email', '$token', '$tkdate')";
        $response = $this->db->insertOne($query);

        if ($response['status'] === 'SUCCESS') {
            $response['token'] = $token;
            http_response_code(201);
        }

        return $response;
    }

    public function remove($id)
    {
        $response = $this->db->remove('users', $id);

        if ($response['status'] === 'SUCCESS') {
            http_response_code(200);
        }

        return $response;
    }
}
