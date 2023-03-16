<?php

namespace Auth;

use Error;
use Services\User;

class Auth
{
    //o token do objeto request
    private string $authorization;
    //serviço do usuário para lidar com o banco
    private User $userService;
    //o objeto de resposta do serviço
    private $response;
    // o objeto de retorno to Auth para o router
    private string $return = '';

    public function __construct($rawtoken, User $userS)
    {
        $this->userService = $userS;
        $this->prepareToken($rawtoken);
    }

    public function clean()
    {
        //limpa objeto de response
        $this->response = RESPONSE;
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
            $this->clean();
        } else {
            $this->checkTokendate();
            $this->clean();
        }

        $this->return = $this->authorization;
        $this->clean();
        return $this->return;
    }

    public function checkTokenDate()
    {
        if ($this->response['data'][0]['token_expire_date'] === date('Ymd')) {
            http_response_code(401);
            throw new Error('refresh your token.');
        }
    }
}
