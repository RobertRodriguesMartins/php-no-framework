<?php

namespace Interfaces\Abstract;

use DB\MySql;
use Interfaces\ProductContract;

abstract class ProductBase implements ProductContract
{
    public int $request_id_product;
    public int $id_product;
    public string $product_name = '';
    public bool $product_status;
    public int $mark_id = 0;
    public int $store_id = 0;
    public int $category_id = 0;
    //algum serviço que implemente ProductBase;
    protected ProductBase | MySql $service;
    // objeto de resposta
    protected $response = RESPONSE;
    // o objeto de resposta do serviço user
    public $return;

    public function __construct(ProductBase | MySql $service)
    {
        $this->$service = $service;
    }
}
