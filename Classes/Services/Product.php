<?php

namespace Services;

use DB\MySql;
use Helpers\Payload;

class Product
{
    //conexão com o banco
    private MySql $db;
    // o objeto de resposta do model
    private $response = RESPONSE;
    // o objeto de resposta do serviço user
    private $return;

    public function __construct()
    {
        $this->db = new MySql();
    }

    public function clean()
    {
        //limpa objeto de response
        $this->response = RESPONSE;
    }

    public function getAll()
    {
        $this->response = $this->db->getAll('products');

        $this->return = $this->response;
        $this->clean();
        return $this->return;
    }

    public function getOne($id)
    {
        $this->response = $this->db->getOne('products', $id);

        $this->return = $this->response;
        $this->clean();
        return $this->return;
    }

    public function getByName($name)
    {
        $this->response = $this->db->getOne('products', (string)$name, 'name');

        $this->return = $this->response;
        $this->clean();
        return $this->return;
    }

    public function create()
    {
        $payload = Payload::processPost(['name', 'quantity', 'price']);

        $name = $payload['name'];
        $qt = (int)$payload['quantity'];
        $price = (float)$payload['price'];

        $checkIfProductAlreadyExists = $this->getByName($name);

        if ($checkIfProductAlreadyExists['status'] === 'SUCCESS') {
            return ['status' => 'FAIL', 'data' => []];
        }

        $query = "INSERT INTO products (name, quantity, price) VALUES ('$name', '$qt', '$price')";
        $this->response = $this->db->insertOne($query);

        $newPayload['name'] = $payload['name'];
        $newPayload['quantity'] = (int)$payload['quantity'];
        $newPayload['price'] = (float)$payload['price'];

        if ($this->response['status'] === 'SUCCESS') {
            http_response_code(201);
            $this->response['data'] = [array_merge($this->response['data'], $newPayload)];
        }

        $this->return = $this->response;
        $this->clean();
        return $this->return;
    }

    public function edit($product)
    {
        $payload = Payload::processPut();

        $name = isset($payload['name']) ? $payload['name'] : $product['name'];
        $qt = isset($payload['quantity']) ? (int)$payload['quantity'] : $product['quantity'];
        $price = isset($payload['price']) ? (float)$payload['price'] : $product['price'];
        $id = $product['id'];

        if (isset($payload['name'])) {
            $checkIfProductNameAlreadyExists = $this->getByName($payload['name']);
            if ($checkIfProductNameAlreadyExists['status'] === 'SUCCESS') {
                return ['status' => 'FAIL', 'data' => []];
            }
        }

        $query = "UPDATE products SET name = '$name', quantity = '$qt', price = '$price' WHERE id = $id";
        $this->response = $this->db->edit($query);

        if ($this->response['status'] === 'SUCCESS') {
            http_response_code(200);
            $payload['quantity'] = $qt;
            $payload['price'] = $price;
            $this->response['data'][0] = array_merge($this->response['data'], $product, $payload);
        }

        $this->return = $this->response;
        $this->clean();
        return $this->return;
    }

    public function remove($id)
    {
        $this->response = $this->db->remove('products', $id);

        if ($this->response['status'] === 'SUCCESS') {
            http_response_code(200);
        }

        $this->return = $this->response;
        $this->clean();
        return $this->return;
    }
}
