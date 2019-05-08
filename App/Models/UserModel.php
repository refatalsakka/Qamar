<?php

namespace App\Models;

use System\Model;

class UserModel extends Model
{

    public function comment()
    {
        return $this->hasOne('App\Models\Comment');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }
}