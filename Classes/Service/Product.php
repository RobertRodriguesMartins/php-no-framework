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
        $qt = (int)$payload['quantity'];
        $price = (float)$payload['price'];

        $query = "INSERT INTO products (name, quantity, price) VALUES ('$name', '$qt', '$price')";
        $response = $this->db->insertOne($query);

        $newPayload['name'] = $payload['name'];
        $newPayload['quantity'] = (int)$payload['quantity'];
        $newPayload['price'] = (float)$payload['price'];

        if ($response['status'] === 'SUCCESS') {
            http_response_code(201);
            $response['data'] = [array_merge($response['data'], $newPayload)];
        }

        return $response;
    }

    public function edit($product)
    {
        $_PUT = [];
        parse_str(file_get_contents("php://input"), $_PUT);

        foreach ($_PUT as $key => $value) {
            unset($_PUT[$key]);

            $_PUT[str_replace('amp;', '', $key)] = $value;
        }

        $payload = Util::processPayload($_PUT);

        $name = isset($payload['name']) ? $payload['name'] : $product['name'];
        $qt = isset($payload['quantity']) ? (int)$payload['quantity'] : $product['quantity'];
        $price = isset($payload['price']) ? (float)$payload['price'] : $product['price'];
        $id = $product['id'];

        $query = "UPDATE products SET name = '$name', quantity = '$qt', price = '$price' WHERE id = $id";
        $response = $this->db->edit($query);

        if ($response['status'] === 'SUCCESS') {
            http_response_code(200);
            $payload['quantity'] = $qt;
            $payload['price'] = $price;
            $response['data'][0] = array_merge($response['data'], $product, $payload);
            unset($response['id']);
        }

        return $response;
    }

    public function remove($id)
    {
        $response = $this->db->remove('products', $id);

        if ($response['status'] === 'SUCCESS') {
            http_response_code(200);
        }

        return $response;
    }
}
