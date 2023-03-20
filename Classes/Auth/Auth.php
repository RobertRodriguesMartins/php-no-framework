<?php

namespace Auth;

use Controllers\UserController;
use Interfaces\Abstract\UserBase;
use Helpers\Jwt;
use Exception;

class Auth
{
    private string $authorization;
    //serviço do usuário com o controle da request e a chamada aos serviços do usuário
    private UserController $userController;

    public function __construct($rawtoken, UserBase $userS)
    {
        $this->userController = $userS;
        $this->setAuthorization($rawtoken);
        if ($this->authorization === 'NO_REQUEST_TOKEN') {
            $this->prepareUserData();
        } else {
            $this->prepareUserData($this->authorization);
        }
    }

    public function setAuthorization($rawtoken)
    {
        $this->authorization = Jwt::prepareToken($rawtoken);
    }

    public function prepareUserData($requestToken = 'NO_REQUEST_TOKEN')
    {
        $this->userController->populateUserData($requestToken);
    }

    public function checkUser()
    {
        try {
            Jwt::verifyToken(
                $this->userController->user_request_password,
                $this->userController->user_token
            );
            Jwt::checkTokendate($this->userController->user_token_expire);
        } catch (\Throwable $th) {
            $_SERVER['status_code'] = 401;
            throw new Exception($th->getMessage());
        }
    }
}
