<?php

namespace Services;

use Helpers\Jwt;
use Interfaces\Abstract\ProductBase;

class ProductService extends ProductBase
{
    private ProductBase $model;

    public function __construct(ProductBase $model)
    {
        $this->model = $model;
    }

    public function clean()
    {
        //limpa objeto de response
        $this->response = RESPONSE;
    }
    public function getAll(): array | string
    {
        $this->response = $this->model->getAll();

        $this->return = $this->response;
        $this->clean();
        return $this->return;
    }

    public function getOne(): array | string
    {
        $this->model->request_id_product = $this->request_id_product;
        $this->response = $this->model->getOne();

        $this->return = $this->response;
        $this->clean();
        return $this->return;
    }

    public function create(): array | string
    {
        $this->response = $this->model->getOne();

        $this->return = $this->response;
        $this->clean();
        return $this->return;
    }
}
