<?php

namespace Services;

use Helpers\Jwt;
use DB\MySql;
use Interfaces\Abstract\UserBase;

class UserService extends UserBase
{
    private UserBase $model;

    public function __construct(UserBase $model)
    {
        $this->model = $model;
    }

    public function clean()
    {
        //limpa objeto de response
        $this->response = RESPONSE;
    }

    public function getOne(string $value, string $case = 'id'): string | array
    {

        $this->response = $this->model->getOne($value, $case);

        $this->return = $this->response;
        $this->clean();
        return $this->return;
    }

    public function login(): string | array
    {
        $this->model->user_token = Jwt::generateToken($this->user_email, $this->user_request_password, $this->id_user, time());
        $this->model->user_token_expire = Jwt::generateExpirationDate();
        $this->model->id_user = $this->id_user;

        $this->response = $this->model->login();

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
