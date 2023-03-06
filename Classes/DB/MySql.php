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
            return new PDO('mysql:host=' . HOST . ';dbname=' . NAME . ';', USER, PASSWORD);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getAll($table)
    {
        $query = 'SELECT * FROM :tb';
        $pdoStmt = $this->db->prepare($query);
        $pdoStmt->bindParam(':tb', $table);
        $data = $pdoStmt->fetchAll($this->db::FETCH_ASSOC);

        return $data;
    }
}