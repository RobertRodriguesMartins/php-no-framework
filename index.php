<?php

require 'bootstrap.php';
// O Router lida com o processo de roteamento da request -> recurso
use Router\Router;

class App
{

    // O objeto de retorno da classe App
    public string $return;
    // O objeto de response da classe App que recebe a resposta do Router
    private $response;
    // O objeto Router
    private Router $router;

    public function __construct()
    {
        //define um valor padrão para o objeto de response
        $this->response = RESPONSE;
        //instancia o router e automaticamente prepara o objeto de request
        $this->router = new Router();
        //inicia o processo de busca do endpoint
        $this->return = $this->start();
    }

    public function start()
    {
        $this->response = $this->router->processRequest();
        return json_encode($this->response);
    }
}

$app = new App();

$return = $app->return;

echo $return;
