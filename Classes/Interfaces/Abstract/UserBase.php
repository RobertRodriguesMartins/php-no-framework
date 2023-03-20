<?php

namespace Interfaces\Abstract;

use DB\MySql;
use Interfaces\UserContract;

abstract class UserBase implements UserContract
{
    public string $user_request_email = '';
    public string $user_email = '';
    public int $id_user = 0;
    public string $user_request_password = '';
    public string $user_password = '';
    public string $user_token = '';
    public string $user_token_expire = '';
    public string $user_request_token = '';
    //algum serviço que implemente User;
    protected UserBase | MySql $service;
    // objeto de resposta
    protected $response = RESPONSE;
    // o objeto de resposta do serviço user
    public $return;

    public function __construct(UserContract | MySql $service)
    {
        $this->$service = $service;
    }

    public function getOne(string $value, string $case = 'id'): array|string
    {
        return [];
    }
}
