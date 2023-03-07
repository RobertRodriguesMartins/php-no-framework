<?php

namespace Router;

use Exception;
use Service\Product;
use Service\User;

class Router
{
    private $request;
    private $response;

    private User $userService;
    private Product $productService;

    public function __construct()
    {
        $this->userService = new User();
        $this->productService = new Product();
    }

    public function processUrl()
    {
        $requestUrl = array_values(explode(DS, $_SERVER['REQUEST_URI']));
        $this->request['resource'] = isset($requestUrl[1]) ? strtoupper($requestUrl[1]) : null;
        $this->request['specific_resource'] = isset($requestUrl[2]) ? strtoupper($requestUrl[2]) : null;
        $this->request['method'] = $_SERVER["REQUEST_METHOD"];
    }

    public function processRequest()
    {

        echo $this->request['method'];
        if ($this->request['method'] === 'POST') {
            switch ($this->request['resource']) {
                case 'USERS':
                    $this->response = $this->userService->create();
                    break;
                case 'PRODUCTS':
                    $this->response = $this->productService->create();
                    break;

                default:
                    $this->response = 'not implemented yet';
                    break;
            }
        } elseif ($this->request['method'] === 'GET') {
            switch ($this->request['resource']) {
                case 'USERS':
                    $specific_resource = $this->request['specific_resource'];
                    if ($specific_resource) {
                        $this->response =  $this->userService->getOne($specific_resource);
                        break;
                    }
                    $this->response = $this->userService->getAll();
                    break;
                case 'PRODUCTS':
                    $specific_resource = $this->request['specific_resource'];
                    if ($specific_resource) {
                        $this->response =  $this->productService->getOne($specific_resource);
                        break;
                    }
                    $this->response = $this->productService->getAll();
                    break;

                default:
                    $this->response = 'not implemented yet';
                    break;
            }
        } elseif ($this->request['method'] === 'DELETE') {
            switch ($this->request['resource']) {
                case 'USERS':
                    $this->response = 'not implemented yet.';
                    break;
                case 'PRODUCTS':
                    $specific_resource = $this->request['specific_resource'];
                    if ($specific_resource) {
                        $this->response =  $this->productService->remove($specific_resource);
                        break;
                    } else {
                        throw new Exception('id not specified!');
                        break;
                    }
                default:
                    $this->response = 'not implemented yet';
                    break;
            }
        } else {
            switch ($this->request['resource']) {
                case 'USERS':
                    $this->response = 'not implemented yet.';
                    break;
                case 'PRODUCTS':
                    $specific_resource = $this->request['specific_resource'];
                    $product = $this->productService->getOne($specific_resource);

                    if (!isset($product['id'])) {
                        throw new Exception('invalid product id.');
                        break;
                    }
                    $this->response = $this->productService->edit($product);
                    break;

                default:
                    $this->response = 'not implemented yet';
                    break;
            }
        }


        return json_encode($this->response);
    }
}
