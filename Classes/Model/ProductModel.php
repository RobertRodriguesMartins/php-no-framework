<?php

namespace Model;

use DB\MySql;
use Interfaces\Abstract\ProductBase;

class ProductModel extends ProductBase
{
    private MySql $db;

    public function __construct(Mysql $db)
    {
        $this->db = $db;
    }

    public function clean()
    {
        //limpa objeto de response
        $this->response = RESPONSE;
    }

    public function create(): string | array
    {
        $this->response = $this->db->insertOne('adkm');

        $this->return = $this->response;
        return $this->return;
    }

    public function getOne(): string | array
    {
        $this->response = $this->db->getOne(
            'product',
            $this->request_id_product
        );

        $this->return = $this->response;
        $this->clean();
        return $this->return;
    }

    public function getAll(): string | array
    {

        $this->response = $this->db->getAll('product');

        $this->return = $this->response;
        $this->clean();
        return $this->return;
    }
}
