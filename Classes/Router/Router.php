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
        if ($this->request['method'] === 'POST') {

            switch ($this->request['resource']) {
                case 'USERS':
                    $checkIfUserAlreayExists = $this->userService->getByEmail();
                    if ($checkIfUserAlreayExists['status'] === 'FAIL') {
                        $this->response = $this->userService->create();
                        break;
                    }
                    $this->response = ['status' => 'FAIL', 'data' => []];
                    break;
                case 'LOGIN':
                    $requestedUser = $this->userService->getByEmail();
                    if ($requestedUser['status'] === 'SUCCESS') {
                        $this->response = $this->userService->login($requestedUser['data'][0]);
                        break;
                    }
                    $this->response = $requestedUser;
                    break;
                case 'PRODUCTS':
                    $this->response = $this->productService->create();
                    break;
                default:
                    throw new Exception('invalid route');
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
                    throw new Exception('invalid route');
            }
        } elseif ($this->request['method'] === 'DELETE') {

            switch ($this->request['resource']) {
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
                    throw new Exception('invalid route');
            }
        } else {

            switch ($this->request['resource']) {
                case 'PRODUCTS':
                    $specific_resource = $this->request['specific_resource'];

                    $product = $this->productService->getOne($specific_resource);

                    if ($product['status'] === 'FAIL') {
                        throw new Exception('invalid product id.');
                        break;
                    }

                    $this->response = $this->productService->edit($product['data'][0]);
                    unset($this->response['data'][0]['token']);
                    break;

                default:
                    throw new Exception('invalid route');
                    break;
            }
        }
        return $this->response;
    }
}
