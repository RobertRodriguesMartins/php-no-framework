<?php

namespace Controllers;

use Interfaces\Abstract\UserBase;
use Interfaces\UserContract;

class UserController extends UserBase
{
    public function __construct(UserContract $servico)
    {
        $this->service = $servico;
    }

    public function getOne(string $value, string $case = 'id'): string | array
    {
        $this->response = $this->service->getOne($value, $case);

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
