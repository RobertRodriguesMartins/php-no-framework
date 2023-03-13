<?php 

namespace Auth;

use Error;
use Service\User;

class Auth
{
    //o token do objeto request
    private string $authorization;
    //serviço do usuário para lidar com o banco
    private User $userService;
    //o objeto de resposta do serviço
    private $response;

    public function __construct($rawtoken, User $userS)
    {
        $this->userService = $userS;
        $this->prepareToken($rawtoken);
    }

    public function prepareToken($rawtoken)
    {
        $splitedTokenArray = explode('Bearer ', trim($rawtoken));
        
        $this->authorization = isset($splitedTokenArray[1]) ? $splitedTokenArray[1] : 'not defined';
    }

    public function checkUser()
    {
        $this->response = $this->userService->getOne($this->authorization, 'token');
        if ($this->response['status'] === 'FAIL') {
            http_response_code(401);
            throw new Error('invalid token');
        } else {
            $this->checkTokendate();
        }
    }

    public function checkTokenDate()
    {
        if ($this->response['data'][0]['token_expire_date'] === date('Ymd')) {
            http_response_code(401);
            throw new Error('refresh your token.');
        }
    }
}

