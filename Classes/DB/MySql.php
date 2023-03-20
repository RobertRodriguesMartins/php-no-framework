<?php

namespace DB;

use PDO;

class MySql
{
    // A instancia do banco de dados utilizando PDO
    private PDO $conn;
    // O objeto response representando a resposta do banco
    private $response = RESPONSE;
    // O objeto de retorno do model MySql
    private array $return;

    private $pdoOption = [
        PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //make the default fetch be an associative array
        PDO::ATTR_STRINGIFY_FETCHES => false
    ];

    public function __construct()
    {
        $this->conn = $this->setDb();
    }

    public function setDb()
    {
        return new PDO('mysql:host=' . HOST . ';dbname=' . NAME .
            ';', USER, PASSWORD, $this->pdoOption);
    }

    public function clean()
    {
        //limpa objeto de response
        $this->response = RESPONSE;
    }

    public function getAll($table)
    {
        $query = "SELECT * FROM " . $table;
        $pdoStmt = $this->conn->query($query);
        $data = $pdoStmt->fetchAll($this->conn::FETCH_ASSOC);

        if ($data && count($data) > 0) {
            $this->response['status'] = "SUCCESS";
            $this->response['data'] = $data;
        } else {
            $this->response['status'] = "NO_DATA";
        }

        $this->return = $this->response;
        $this->clean();
        return $this->return;
    }

    public function getOne($table, $value, $case = 'id')
    {
        switch ($case) {
            case 'email':
                $query = "SELECT * FROM " . $table . " WHERE user_email = ?";
                break;
            case 'token':
                $query = "SELECT * FROM " . $table . " WHERE user_token = ?";
                break;
            case 'name':
                $query = "SELECT * FROM " . $table . " WHERE user_name = ?";
                break;

            default:
                $query = "SELECT * FROM " . $table . " WHERE id_user = ?";
        }

        $pdoStmt = $this->conn->prepare($query);
        $success = $pdoStmt->execute([$value]);
        $data = [];

        if ($success) {
            $data = $pdoStmt->fetch($this->conn::FETCH_ASSOC);
        }

        if (is_array($data) && count($data) > 0) {
            $this->response['status'] = "SUCCESS";
            $this->response['data'] = [$data];
        }

        $this->return = $this->response;
        $this->clean();
        return $this->return;
    }

    public function insertOne($query)
    {
        $pdoStmt = $this->conn->prepare($query);
        $pdoStmt->execute();
        $updatedRows = $pdoStmt->rowCount();

        if ($updatedRows > 0) {
            $this->response['status'] = "SUCCESS";
            $this->response['data'] = ['id' => (int)$this->conn->lastInsertId()];
        }

        $this->return = $this->response;
        $this->clean();
        return $this->return;
    }

    public function edit($query, $params)
    {
        $pdoStmt = $this->conn->prepare($query);
        for ($i = 0; $i < sizeof($params); ++$i) {
            $pdoStmt->bindParam($i + 1, $params[$i]);
        }
        $pdoStmt->execute();
        $updatedRows = $pdoStmt->rowCount();

        if ($updatedRows > 0) {
            $this->response['status'] = "SUCCESS";
        }

        $this->return = $this->response;
        $this->clean();
        return $this->return;
    }

    public function remove($table, $value, $case = 'id')
    {
        switch ($case) {
            case 'token':
                $query = "DELETE FROM $table WHERE token = :value";
                break;
            default:
                $query = "DELETE FROM $table WHERE id = :value";
                break;
        }

        $pdoStmt = $this->conn->prepare($query);
        $pdoStmt->bindParam(':value', $value);
        $pdoStmt->execute();
        $count = $pdoStmt->rowCount();

        if ($count > 0) {
            $this->response['status'] = "SUCCESS";
        }

        $this->return = $this->response;
        $this->clean();
        return $this->return;
    }
}
