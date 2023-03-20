<?php

namespace Helpers;

use Controllers\UserController;
use DB\MySql;
use Model\UserModel;
use Services\UserService;

class Init
{
    static function constructUser()
    {
        $userModel = new UserModel(new MySql());
        $userService = new UserService($userModel);
        $userController = new UserController($userService);
        return $userController;
    }
}
