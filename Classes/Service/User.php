<?php

namespace Service;

use DB\MySql;

class User
{
    private MySql $db;

    public function __construct()
    {
        $this->db = new MySql();
    }

    public function getAll()
    {
        return $this->db->getAll('users');
    }
}
