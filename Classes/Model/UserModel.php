<?php

namespace Model;

use DB\MySql;
use Interfaces\Abstract\UserBase;

class UserModel extends UserBase
{
    private MySql $db;

    public function __construct(Mysql $db)
    {
        $this->db = $db;
    }

    public function clean()
    {
        //limpa objeto de response
        $this->response = RESPONSE;
    }

    public function login(): string | array
    {
        $query = "UPDATE user SET user_token = ?, user_token_expire = ? WHERE id_user = ?";

        $this->response = $this->db->edit($query, array($this->user_token, $this->user_token_expire, $this->id_user));

        $this->return = $this->response;
        return $this->return;
    }

    public function create(): string | array
    {
        $this->response = $this->db->insertOne('adkm');

        $this->return = $this->response;
        return $this->return;
    }

    public function getOne(string $value, string $case = 'id'): string | array
    {

        $this->response = $this->db->getOne('user', $value, $case);

        $this->return = $this->response;
        $this->clean();
        return $this->return;
    }
}
