<?php

namespace Services;

use Controllers\UserController;
use DB\MySql;
use Services\UserService;

class Init
{
    static function constructUser()
    {
        $userService = new UserService(new MySql());
        $userController = new UserController($userService);
        return $userController;
    }
}
