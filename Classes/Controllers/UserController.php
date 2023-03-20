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
            $this->userEmail = Hidrate::email($this->response['email']);
            $this->userPassword = $this->response['password'];
            $this->response = $this->service->getOne($this->userEmail, 'email');
        } else {
            $this->response = $this->service->getOne($requestToken, 'token');
        }

        if ($this->response['status'] === 'SUCCESS') {
            $this->userToken = $this->response['data'][0]['user_token'];
            $this->userEmail = $this->response['data'][0]['user_email'];
            $this->userTokenExpireDate = $this->response['data'][0]['user_token_expire'];
            $this->idUser = (int) $this->response['data'][0]['id_user'];
            $this->service->userEmail = $this->userEmail;
            $this->service->userPassword = $this->userPassword;
            $this->service->userToken = $this->userToken;
            $this->service->userTokenExpireDate = $this->userTokenExpireDate;
            $this->service->idUser = $this->idUser;
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
