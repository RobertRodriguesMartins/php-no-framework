<?php

namespace DB;

use PDO;
use PDOException;

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

        return $data;
    }

    public function getOne($table, $id)
    {
        $query = "SELECT * FROM " . $table . " WHERE id = :id";
        $pdoStmt = $this->db->prepare($query);
        $pdoStmt->bindParam(':id', $id);
        $success = $pdoStmt->execute();

        if ($success) {
            $data = $pdoStmt->fetch($this->db::FETCH_ASSOC);
        }

        return $data ?? 'user does not exists.';
    }

    public function insertOne($query)
    {
        $pdoStmt = $this->db->prepare($query);
        $pdoStmt->execute();
        $updatedRows = $pdoStmt->rowCount();

        if ($updatedRows > 0) {
            return [
                'id' => $this->db->lastInsertId(),
            ];
        }
        return 'error';
    }

    public function edit($query)
    {
        $pdoStmt = $this->db->prepare($query);
        $pdoStmt->execute();
        $updatedRows = $pdoStmt->rowCount();

        if ($updatedRows > 0) {
            return [
                'id' => $this->db->lastInsertId(),
            ];
        }
        return 'error';
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
