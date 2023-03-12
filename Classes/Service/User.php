<?php

namespace Service;

use DB\MySql;
use Util\Util;

class User
{
    // O serviço User chama o model do banco
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

    public function getByEmail()
    {
        $payload = Util::processPayload(['email']);
        return $this->db->getOne('users', $payload['email'], 'email');
    }

    public function login($userData)
    {
        $payload = Util::processPayload(['email', 'password']);
        Util::verifyToken($payload, $userData['token']);

        $refreshToken = Util::generateToken($payload);
        $expire_date = Util::generateExpirationDate();
        $id = $userData['id'];

        $query = "UPDATE users SET token = '$refreshToken', token_expire_date = '$expire_date' WHERE id = '$id'";
        return $this->db->edit($query);
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
            $response['data'] = [array_merge($response['data'], ['email' => $email, 'token' => $token])];
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
