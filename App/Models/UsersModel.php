<?php

namespace App\Models;

use System\Model;

class UsersModel extends Model
{
    public function getUsers()
    {
        return $this->select('first_name')->from('users')->fetchAll();
    }
}