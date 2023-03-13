<?php

namespace DB;

use PDO;
use Error;

class MySql
{
    // A instancia do banco de dados utilizando PDO
    private PDO $db;
    // O objeto response representando a resposta do banco
    private $response = RESPONSE;
    // O objeto de retorno do model MySql
    private array $return;

    public function __construct()
    {
        $this->db = $this->setDb();
        $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $this->db->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
    }

    public function setDb()
    {
        return new PDO('mysql:host=' . HOST . ';dbname=' . NAME .
        ';', USER, PASSWORD, array(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION));
    }

    public function getAll($table)
    {
        $query = "SELECT * FROM " . $table;
        $pdoStmt = $this->db->query($query);
        $data = $pdoStmt->fetchAll($this->db::FETCH_ASSOC);

        if ($data && count($data) > 0) {
            $this->response['status'] = "SUCCESS";
            $this->response['data'] = $data;
        } else {
            $this->response['status'] = "NO_DATA";
        }

        $this->return = $this->response;
        return $this->return;
    }

    public function getOne($table, $value, $case = 'id')
    {
        switch ($case) {
            case 'email':
                $query = "SELECT * FROM " . $table . " WHERE email = :value";
                break;
            case 'token':
                $query = "SELECT * FROM " . $table . " WHERE token = :value";
                break;
            case 'name':
                $query = "SELECT * FROM " . $table . " WHERE name = :value";
                break;

            default:
                $query = "SELECT * FROM " . $table . " WHERE id = :value";
        }

        $pdoStmt = $this->db->prepare($query);
        $pdoStmt->bindParam(':value', $value);
        $success = $pdoStmt->execute();
        $data = [];

        if ($success) {
            $data = $pdoStmt->fetch($this->db::FETCH_ASSOC);
        }

        if (is_array($data) && count($data) > 0) {
            $this->response['status'] = "SUCCESS";
            $this->response['data'] = [$data];
        }

        $this->return = $this->response;
        return $this->return;
    }

    public function insertOne($query)
    {
        $pdoStmt = $this->db->prepare($query);
        $pdoStmt->execute();
        $updatedRows = $pdoStmt->rowCount();

        if ($updatedRows > 0) {
            $this->response['status'] = "SUCCESS";
            $this->response['data'] = ['id' => (int)$this->db->lastInsertId()];
        }

        $this->return = $this->response;
        return $this->return;
    }

    public function edit($query)
    {
        $pdoStmt = $this->db->prepare($query);
        $pdoStmt->execute();
        $updatedRows = $pdoStmt->rowCount();

        if ($updatedRows > 0) {
            $this->response['status'] = "SUCCESS";
        }

        $this->return = $this->response;
        return $this->return;
    }

    public function remove($table, $id)
    {
        $query = "DELETE FROM $table WHERE id = :id";
        $pdoStmt = $this->db->prepare($query);
        $pdoStmt->bindParam(':id', $id);
        $pdoStmt->execute();
        $count = $pdoStmt->rowCount();

        if ($count > 0) {
            $this->response['status'] = "SUCCESS";
        }

        $this->return = $this->response;
        return $this->return;
    }
}
