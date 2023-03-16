<?php

namespace Interfaces\Abstract;

use DB\MySql;
use Interfaces\UserContract;

abstract class UserBase implements UserContract
{
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
}
