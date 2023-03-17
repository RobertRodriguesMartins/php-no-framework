<?php

namespace Controllers;

use Helpers\Hidrate;
use Helpers\Jwt;
use Helpers\Payload;
use Interfaces\Abstract\UserBase;
use Interfaces\UserContract;

class UserController extends UserBase
{
    public function __construct(UserContract $servico)
    {
        $this->service = $servico;
    }

    public function get(): string | array
    {
        $this->response = Payload::processPost(['email', 'password']);
        $this->email = Hidrate::email($this->response['email']);
        $this->password = $this->response['password'];


        $this->response = $this->service->getOne($this->email, 'email');

        var_dump($this->response);
        // $this->response = Jwt::verifyToken()

        $this->return = $this->response;
        return $this->return;
    }

    public function login(array $userData): string | array
    {
        $this->response = $this->service->login($userData);

        $this->return = $this->response;
        return $this->return;
    }

    public function create(): string | array
    {
        $this->response = $this->service->create();

        $this->return = $this->response;
        return $this->return;
    }
}
