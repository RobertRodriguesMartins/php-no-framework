<?php

namespace Router;

use Service\User;

class Router
{

    private $request;
    private $response;

    private User $userService;

    public function __construct()
    {
        $this->userService = new User();
    }

    public function processUrl()
    {
        $requestUrl = explode(DS, $_SERVER['REQUEST_URI']);
        $this->request['resource'] = strtoupper($requestUrl[1]) ?? null;
        $this->request['specific_resource'] = strtoupper($requestUrl[2]) ?? null;
        $this->request['method'] = $_SERVER["REQUEST_METHOD"];
    }

    public function processRequest()
    {
        if ($this->request['method'] === 'POST') {
            return 'not implemented yet';
        }

        var_dump($this->request);

        switch ($this->request['resource']) {
            case 'USERS':
                $this->response = $this->userService->getAll();
                break;

            default:
                $this->response = 'not implemented yet';
                break;
        }

        return json_encode($this->response);
    }
}
