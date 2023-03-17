<?php

namespace Services;

use Helpers\Jwt;
use DB\MySql;
use Interfaces\Abstract\UserBase;

class UserService extends UserBase
{
    private Mysql $db;

    public function __construct(Mysql $service)
    {
        $this->db = $service;
    }

    public function clean()
    {
        //limpa objeto de response
        $this->response = RESPONSE;
    }

    public function getOne(string $value, string $case = 'id'): string | array
    {

        $this->response = $this->db->getOne('user', $value, $case);

        $this->return = $this->response;
        $this->clean();
        return $this->return;
    }

    public function login(): string | array
    {
        var_dump($this->userEmail, $this->userPassword);
        $refreshToken = Jwt::generateToken($this->userEmail, $this->userPassword);
        $expire_date = Jwt::generateExpirationDate();
        $params = array($refreshToken, $expire_date, $this->idUser);

        $query = "UPDATE user SET user_token = ?, user_token_expire = ? WHERE id_user = ?";

        $this->response = $this->db->edit($query, $params);
        var_dump($this->response);
        exit();
        $this->return = $this->response;
        $this->clean();
        return $this->return;
    }

    public function create(): string | array
    {
        // $token = Jwt::generateToken($payload);
        // $tkdate = Jwt::generateExpirationDate();
        // $email = $payload['email'];

        // $query = "INSERT INTO users (email, token, token_expire_date) VALUES ('$email', '$token', '$tkdate')";
        // $this->response = $this->$service->insertOne($query);


        $this->return = $this->response;
        $this->clean();
        return $this->return;
    }
}
