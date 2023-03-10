<?php

require 'bootstrap.php';

// O Router lida com o processo de roteamento da request -> recurso
use Router\Router;

class App
{

    // O objeto de retorno da classe App
    private string $return;
    // O objeto de response da classe App
    private string $response;
    // O router
    private Router $router;

    public function __construct()
    {
        $this->response = $this->start();

        //instancia o router
        $this->router = new Router();
    }

    public function start()
    {
        // O Try catch monitora erros advindos das outras camadas
        try {
            $this->response = $this->router->processRequest();
            $this->return = json_encode($this->response);
        } catch (Exception $e) {
            $this->response = [
                "status" => "FAIL",
                "data" => [],
            ];

            $this->return = json_encode($this->response);
        }
    }
}

$response = json_encode($response);

echo $response;
