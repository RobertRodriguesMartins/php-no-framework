<?php

namespace Helpers;

use Controllers\ProductController;
use Controllers\UserController;
use DB\MySql;
use Model\ProductModel;
use Model\UserModel;
use Services\ProductService;
use Services\UserService;

class Init
{
    static Mysql $db;

    static function setDb(MySql $db)
    {
        self::$db = $db;
    }

    static function constructUser()
    {
        $userModel = new UserModel(self::$db);
        $userService = new UserService($userModel);
        $userController = new UserController($userService);
        return $userController;
    }

    static function constructProduct()
    {
        $productModel = new ProductModel(self::$db);
        $productService = new ProductService($productModel);
        $productController = new ProductController($productService);
        return $productController;
    }
}
