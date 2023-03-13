<?php

namespace Router;

use Exception;
use Service\Product;
use Service\User;
use Auth\Auth;


class Router
{
    // o objeto que salva as informações da requisição
    private $request;
    // o objeto de resposta dos serviçoes invocados
    private $response = RESPONSE;
    // o objeto de retorno do Router
    private $return;
    // essa propriedade guarda o Agente que verifica a validade e autenticidade do token
    private Auth $authMiddleware;

    private User $userService;
    private Product $productService;

    public function __construct()
    {
        $this->processUrl();
        $this->userService = new User();
        $this->productService = new Product();
        $this->authMiddleware = new Auth($this->request['authorization'], $this->userService);
    }

    public function processUrl()
    {
        $requestUrl = array_values(explode(DS, $_SERVER['REQUEST_URI']));
        $this->request['authorization'] = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : 'not defined';
        $this->request['resource'] = isset($requestUrl[1]) ? strtoupper($requestUrl[1]) : null;
        $this->request['specific_resource'] = isset($requestUrl[2]) ? strtoupper($requestUrl[2]) : null;
        $this->request['method'] = $_SERVER["REQUEST_METHOD"];
    }

    public function clean()
    {
        //limpa objeto de response
        $this->response = RESPONSE;
    }

    public function processRequest()
    {
        if ($this->request['method'] === 'POST') {
            // as primeiras duas rotas não verificam o token
            switch ($this->request['resource']) {
                case 'USERS':
                    $checkIfUserAlreayExists = $this->userService->getByEmail();
                    if ($checkIfUserAlreayExists['status'] === 'FAIL') {
                        $this->response = $this->userService->create();
                        break;
                    }

                    http_response_code(400);
                    break;
                case 'LOGIN':
                    $requestedUser = $this->userService->getByEmail();
                    if ($requestedUser['status'] === 'SUCCESS') {
                        $this->response = $this->userService->login($requestedUser['data'][0]);
                        break;
                    }

                    http_response_code(400);
                    $this->response = $requestedUser;
                    break;
                case 'PRODUCTS':
                    $this->authMiddleware->checkUser();
                    $this->response = $this->productService->create();
                    break;
                default:
                    throw new Exception('invalid route');
            }
        } elseif ($this->request['method'] === 'GET') {
            // verificar se o usuário está logado
            $this->authMiddleware->checkUser();

            switch ($this->request['resource']) {
                case 'USERS':
                    $specific_resource = $this->request['specific_resource'];
                    if ($specific_resource) {
                        $this->response =  $this->userService->getOne((int)$specific_resource, 'id');
                        if ($this->response['status'] === 'FAIL') {
                            http_response_code(400);
                        }
                        break;
                    }

                    $this->response = $this->userService->getAll();
                    break;
                case 'PRODUCTS':
                    $specific_resource = $this->request['specific_resource'];
                    if ($specific_resource) {
                        $this->response =  $this->productService->getOne($specific_resource);
                        if ($this->response['status'] === 'FAIL') {
                            http_response_code(400);
                        }
                        break;
                    }

                    $this->response = $this->productService->getAll();
                    break;
                default:
                    throw new Exception('invalid route');
            }
        } elseif ($this->request['method'] === 'DELETE') {
            // verificar se o usuário está logado
            $this->request['authorization'] = $this->authMiddleware->checkUser();

            switch ($this->request['resource']) {
                case 'PRODUCTS':
                    $specific_resource = $this->request['specific_resource'];
                    if ($specific_resource) {
                        $this->response =  $this->productService->remove($specific_resource);
                        if ($this->response['status'] === 'FAIL') {
                            http_response_code(400);
                        }
                        break;
                    } else {
                        throw new Exception('id not specified!');
                        break;
                    }
                case 'USERS':
                    $specific_resource = $this->request['specific_resource'];
                    if ($specific_resource) {
                        $this->response =  $this->userService->remove($specific_resource);
                        if ($this->response['status'] === 'FAIL') {
                            http_response_code(400);
                        }
                        break;
                    } else {
                        throw new Exception('id not specified!');
                        http_response_code(400);
                        break;
                    }
                case 'ME':
                    $this->response =  $this->userService->remove($this->request['authorization'], 'token');
                    break;
                default:
                    throw new Exception('invalid route');
            }
        } else {
            // verificar se o usuário está logado
            $this->authMiddleware->checkUser();
            // esse caso verifica o método PUT
            switch ($this->request['resource']) {
                case 'PRODUCTS':
                    $specific_resource = $this->request['specific_resource'];

                    $product = $this->productService->getOne($specific_resource);
                    if ($product['status'] === 'FAIL') {
                        throw new Exception('invalid product id.');
                        break;
                    }

                    $this->response = $this->productService->edit($product['data'][0]);
                    if ($this->response['status'] === 'FAIL') {
                        http_response_code(400);
                    }
                    break;
                default:
                    throw new Exception('invalid route');
                    break;
            }
        }


        $this->return = $this->response;
        $this->clean();
        return $this->return;
    }
}
