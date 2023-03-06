<?php

namespace Service;

use DB\MySql;

class User
{
    private MySql $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function all()
    {
        return $this->db->getAll('users');
    }
}