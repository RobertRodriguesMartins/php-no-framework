<?php

namespace Service;

use DB\MySql;
use Util\Util;
use Error;

class User
{
    // O serviço User chama o model do banco
    private MySql $db;
    // o objeto de resposta do model
    private $response = RESPONSE;
    // o objeto de resposta do serviço user
    private $return;

    public function __construct()
    {
        $this->db = new MySql();
    }

    public function clean()
    {
        //limpa objeto de response
        $this->response = RESPONSE;
    }

    public function getAll()
    {
        $this->response = $this->db->getAll('users');

        $this->return = $this->response;
        $this->clean();
        return $this->return;
    }

    public function getOne($value, $case = 'id')
    {
        $this->response = $this->db->getOne('users', $value, $case);

        $this->return = $this->response;
        $this->clean();
        return $this->return;
    }

    public function getByEmail()
    {
        $payload = Util::processPayload(['email']);
        $this->response = $this->db->getOne('users', $payload['email'], 'email');

        $this->return = $this->response;
        $this->clean();
        return $this->return;
    }

    public function login($userData)
    {
        $payload = Util::processPayload(['email', 'password']);
        Util::verifyToken($payload, $userData['token']);

        $refreshToken = Util::generateToken($payload);
        $expire_date = Util::generateExpirationDate();
        $id = $userData['id'];

        $query = "UPDATE users SET token = '$refreshToken', token_expire_date = '$expire_date' WHERE id = '$id'";

        $this->response = $this->db->edit($query);

        if ($this->response['status'] === 'SUCCESS') {
            $this->response['data'] = [array_merge($userData, ['token' => $refreshToken])];
            unset($this->response['token_expire_date']);
        } else {
            throw new Error("error while trying to update token in user table");
        }

        $this->return = $this->response;
        $this->clean();
        return $this->return;
    }

    public function create()
    {
        $payload = Util::processPayload(['email', 'password']);
        $token = Util::generateToken($payload);
        $tkdate = Util::generateExpirationDate();
        $email = $payload['email'];

        $query = "INSERT INTO users (email, token, token_expire_date) VALUES ('$email', '$token', '$tkdate')";
        $this->response = $this->db->insertOne($query);

        if ($this->response['status'] === 'SUCCESS') {
            $this->response['data'] = [array_merge($this->response['data'], ['email' => $email, 'token' => $token])];
            http_response_code(201);
        }

        $this->return = $this->response;
        $this->clean();
        return $this->return;
    }

    public function remove($value, $case = 'id')
    {
        $this->response = $this->db->remove('users', $value, $case);

        if ($this->response['status'] === 'SUCCESS') {
            http_response_code(200);
        }

        $this->return = $this->response;
        $this->clean();
        return $this->return;
    }
}
