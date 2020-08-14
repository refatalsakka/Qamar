<?php

namespace App\Models;

use System\Model;

class UserModel extends Model
{
  protected $table = 'users';

  public function getTable()
  {
    return $this->table;
  }
}
