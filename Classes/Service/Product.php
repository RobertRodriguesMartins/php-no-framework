<?php

namespace Service;

use DB\MySql;
use Util\Util;

class Product
{
    private MySql $db;

    public function __construct()
    {
        $this->db = new MySql();
    }

    public function getAll()
    {
        return $this->db->getAll('products');
    }

    public function create()
    {
        $payload = Util::processPayload(['name', 'quantity', 'price']);

        $name = $payload['name'];
        $qt = $payload['quantity'];
        $price = $payload['price'];

        $query = "INSERT INTO products (name, quantity, price) VALUES ('$name', '$qt', '$price')";
        $response = $this->db->insertOne($query);

        return $response;
    }
}
