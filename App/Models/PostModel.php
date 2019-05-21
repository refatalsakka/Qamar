<?php

namespace App\Models;

use System\Model;

class PostModel extends Model
{
    protected $table = 'posts';

    public function all() {
        return $this->select()->from($this->table)->fetchAll();
    }

    public function comments($id)
    {
        return $this->hasMany('Comment', $id);
    }
}