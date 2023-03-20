<?php

namespace Controllers;

use Interfaces\Abstract\ProductBase;

class ProductController extends ProductBase
{
    public function __construct(ProductBase $servico)
    {
        $this->service = $servico;
    }

    public function clean()
    {
        //limpa objeto de response
        $this->response = RESPONSE;
    }

    public function getOne(): array|string
    {
        $this->service->request_id_product = $this->request_id_product;
        $this->response = $this->service->getOne();

        $this->return = $this->response;
        $this->clean();
        return $this->return;
    }

    public function getAll(): string | array
    {
        $this->response = $this->service->getAll();

        $this->return = $this->response;
        $this->clean();
        return $this->return;
    }

    public function create(): string | array
    {
        $this->response = $this->service->create();

        $this->return = $this->response;
        $this->clean();
        return $this->return;
    }
}
