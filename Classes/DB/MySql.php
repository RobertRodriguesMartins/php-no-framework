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
    }

    public function setDb()
    {
        try {
            return new PDO('mysql:host=' . HOST . ';dbname=' . NAME . ';', USER, PASSWORD, array(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION));
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getAll($table)
    {
        $query = "SELECT * FROM " . $table;
        $pdoStmt = $this->db->query($query);
        $data = $pdoStmt->fetchAll($this->db::FETCH_ASSOC);

        $response = RESPONSE;
        $dataLength = count($data);
        if ($dataLength > 0) {
            $response['status'] = "SUCCESS";
            $response['data'] = array_merge($response['data'], $data);
        } elseif ($dataLength === 0) {
            $response['status'] = "NO_DATA";
        }

        return $response;
    }

    public function getOne($table, $id)
    {
        $query = "SELECT * FROM " . $table . " WHERE id = :id";
        $pdoStmt = $this->db->prepare($query);
        $pdoStmt->bindParam(':id', $id);
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
            $response['id'] = $this->db->lastInsertId();
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

        if ($count > 0) {
            return [
                'status' => 'success'
            ];
        }
        return 'error';
    }
}
