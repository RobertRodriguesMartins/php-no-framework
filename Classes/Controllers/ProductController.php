<?php

namespace Controllers;

use Helpers\Payload;
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
        $this->response = Payload::processPost([
            "product_name",
            "product_status",
            "mark_id",
            "store_id",
            "category_id"
        ]);

        $this->service->product_name = $this->response['product_name'];
        $this->service->product_status = (int) $this->response['product_status'];
        $this->service->mark_id = (int) $this->response['mark_id'];
        $this->service->store_id = (int) $this->response['store_id'];
        $this->service->category_id = (int) $this->response['category_id'];

        $this->return = $this->response;
        $this->clean();
        return $this->return;
    }
}
