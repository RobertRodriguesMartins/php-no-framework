<?php

namespace Interfaces\Abstract;

use DB\MySql;
use Interfaces\UserContract;

abstract class UserBase implements UserContract
{
    public string $userEmail;
    public int $idUser;
    public string $userPassword;
    public string $userToken;
    public string $userTokenExpireDate;
    public string $userRequestToken;
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
