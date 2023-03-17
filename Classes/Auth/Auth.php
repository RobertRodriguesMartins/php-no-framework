<?php

namespace Auth;

use Controllers\UserController;
use Interfaces\Abstract\UserBase;
use Helpers\Jwt;

class Auth
{
    private string $authorization;
    //serviço do usuário com o controle da request e a chamada aos serviços do usuário
    private UserController $userController;

    public function __construct($rawtoken, UserBase $userS)
    {
        $this->userController = $userS;
        $this->getHidratedToken($rawtoken);
        if ($this->authorization === 'NO_REQUEST_TOKEN') {
            $this->prepareUserData();
        } else {
            $this->prepareUserData($this->authorization);
        }
    }

    public function getHidratedToken($rawtoken)
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
                $this->userController->userEmail,
                $this->userController->userPassword,
                $this->userController->userToken
            );
            Jwt::checkTokendate($this->userController->userTokenExpireDate);
        } catch (\Throwable $th) {
            $this->userController->requestStatus = 400;
        }
    }
}
