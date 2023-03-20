<?php

namespace Controllers;

use Helpers\Hidrate;
use Helpers\Payload;
use Interfaces\Abstract\UserBase;

class UserController extends UserBase
{
    public int $requestStatus = 200;
    public function __construct(UserBase $servico)
    {
        $this->service = $servico;
    }

    public function clean()
    {
        //limpa objeto de response
        $this->response = RESPONSE;
    }
    // temporário
    public function populateUserData($requestToken): void
    {
        if ($requestToken === 'NO_REQUEST_TOKEN') {
            $this->response = Payload::processPost(['email', 'password']);
            $this->user_request_email = Hidrate::email($this->response['email']);
            $this->user_request_password = $this->response['password'];
            $this->response = $this->service->getOne($this->user_request_email, 'email');
        } else {
            $this->response = $this->service->getOne($requestToken, 'token');
        }

        if ($this->response['status'] === 'SUCCESS') {
            $this->user_token = $this->response['data'][0]['user_token'];
            $this->user_email = $this->response['data'][0]['user_email'];
            $this->user_token_expire = $this->response['data'][0]['user_token_expire'];
            $this->id_user = (int) $this->response['data'][0]['id_user'];
            $this->user_password = $this->response['data'][0]['user_password'];
            $this->service->user_email = $this->user_email;
            $this->service->user_password = $this->user_password;
            $this->service->user_request_password = $this->user_request_password;
            $this->service->user_token = $this->user_token;
            $this->service->user_token_expire = $this->user_token_expire;
            $this->service->id_user = $this->id_user;
        }

        $this->clean();
    }

    public function login(): string | array
    {
        $this->response = $this->service->login();

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
