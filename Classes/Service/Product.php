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

    public function getOne($id)
    {
        return $this->db->getOne('products', $id);
    }

    public function create()
    {
        $payload = Util::processPayload(['name', 'quantity', 'price']);

        $name = $payload['name'];
        $qt = $payload['quantity'];
        $price = $payload['price'];

        $query = "INSERT INTO products (name, quantity, price) VALUES ('$name', '$qt', '$price')";
        $response = $this->db->insertOne($query);

        if (is_array($response)) {
            http_response_code(201);
        }

        return $response;
    }

    public function edit($product)
    {
        parse_str(file_get_contents("php://input"), $_PUT);

        foreach ($_PUT as $key => $value) {
            unset($_PUT[$key]);

            $_PUT[str_replace('amp;', '', $key)] = $value;
        }

        define('_PUT', $_PUT);

        $payloadKeys = array_keys($_PUT);
        $payload = Util::processPayload($payloadKeys);

        $name = isset($payload['name']) ?? $product['name'];
        $qt = isset($payload['quantity']) ?? $product['quantity'];
        $price = isset($payload['price']) ?? $product['price'];
        $id = $product['id'];

        $query = "UPDATE products SET name = '$name', quantity = '$qt', price = '$price' WHERE id = $id";
        $response = $this->db->edit($query);

        if (is_array($response)) {
            http_response_code(201);
        }

        return $response;
    }
}
