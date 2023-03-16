<?php

namespace Interfaces\Abstract;

use DB\MySql;
use Interfaces\UserContract;

abstract class UserBase implements UserContract
{
    //algum serviço que implemente User;
    public UserBase | MySql $service;
    // objeto de resposta
    public $response = RESPONSE;
    // o objeto de resposta do serviço user
    public $return;

    public function __construct(UserBase | MySql $service)
    {
        $this->$service = $service;
    }
}
