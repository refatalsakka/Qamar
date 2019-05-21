<?php

namespace App\Models;

use System\Model;

class UserModel extends Model
{
    protected $table = 'users';

    public function permissions($id)
    {
        return $this->hasMany('UsersGroupPermissions', $id, 'id', 'users_group_id');
    } 

    public function comments($id)
    {
        return $this->hasMany('Comment', $id);
    } 
}