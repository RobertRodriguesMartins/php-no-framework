<?php

namespace DB;

use PDO;
use PDOException;

define('RESPONSE', [
    "status" => "FAIL",
    "data" => []
]);

class MySql
{
    private PDO $db;

    public function __construct()
    {
        $this->db = $this->setDb();
        $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $this->db->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
    }

    public function getNextAutoIncrement()
    {
        $query = "SELECT `AUTO_INCREMENT` FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'php_db'
        AND TABLE_NAME = 'users' ";

        $pdoStmt = $this->db->query($query);
        $response = $pdoStmt->fetchAll($this->db::FETCH_ASSOC);

        return $response[0]['AUTO_INCREMENT'];
    }

    public function setDb()
    {
        try {
            return new PDO('mysql:host=' . HOST . ';dbname=' . NAME . ';', USER, PASSWORD, array(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION));
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getAll($table, $count = null)
    {
        $query = "SELECT * FROM " . $table;
        $pdoStmt = $this->db->query($query);
        $data = $pdoStmt->fetchAll($this->db::FETCH_ASSOC);

        $response = RESPONSE;
        $dataLength = count($data);
        if ($dataLength > 0) {
            $response['status'] = "SUCCESS";
            $response['data'] = array_merge($response['data'], $data);
            if ($count) {
                $index = count((array)$response['data']) - 1;
                $response['lastId'] = $response['data'][$index]['id'];
            }
        } elseif ($dataLength === 0) {
            $response['status'] = "NO_DATA";
        }

        return $response;
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

        $response = RESPONSE;
        $data = [];

        if ($success) {
            $data = $pdoStmt->fetch($this->db::FETCH_ASSOC);
        }

        if ($data) {
            $response['status'] = "SUCCESS";
            $response['data'] = array_merge($response['data'], [$data]);
        }

        return $response;
    }

    public function insertOne($query)
    {
        $pdoStmt = $this->db->prepare($query);
        $pdoStmt->execute();
        $updatedRows = $pdoStmt->rowCount();

        $response = RESPONSE;

        if ($updatedRows > 0) {
            $response['status'] = "SUCCESS";
            $response['id'] = (int)$this->db->lastInsertId();
        }

        return $response;
    }

    public function edit($query)
    {
        $pdoStmt = $this->db->prepare($query);
        $pdoStmt->execute();
        $updatedRows = $pdoStmt->rowCount();

        $response = RESPONSE;

        if ($updatedRows > 0) {
            $response['status'] = "SUCCESS";
            $response['id'] = $this->db->lastInsertId();
        }

        return $response;
    }

    public function remove($table, $id)
    {
        $query = "DELETE FROM $table WHERE id = :id";
        $pdoStmt = $this->db->prepare($query);
        $pdoStmt->bindParam(':id', $id);
        $pdoStmt->execute();
        $count = $pdoStmt->rowCount();

        $response = RESPONSE;

        if ($count > 0) {
            $response['status'] = "SUCCESS";
        }
        return $response;
    }
}
