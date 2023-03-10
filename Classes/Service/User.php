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

    public function getOne($value, $case = 'id')
    {
        return $this->db->getOne('users', $value, $case);
    }

    public function getUserToken($method = 'POST')
    {
        $payload = $method === 'POST' ? Util::processPayload(['token']) : Util::processPutPayload(['token']);
        $token = isset($payload['token']) ? $payload : ['token' => ''];
        return $token;
    }

    public function getByEmail()
    {
        $payload = Util::processPayload(['email']);
        return $this->db->getOne('users', $payload['email'], 'email');
    }

    public function login($userId)
    {
        $payload = Util::processPayload(['email', 'password']);
        $token = Util::generateToken($payload, $userId);
        return $this->db->getOne('users', $token, 'token');
    }

    public function create()
    {
        $payload = Util::processPayload(['email', 'password']);
        $token = Util::generateToken($payload);
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
