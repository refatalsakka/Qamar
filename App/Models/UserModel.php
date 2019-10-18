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

  public function users()
  {
    return $this->join('
    users.id,
    users.username,
    users.fname,
    users.lname,
    users.sex,
    users.registration,
    users.status,
    users.img,
    address.country,
    address.zip,
    activity.is_login,
    activity.last_login
    ', $this->table, [['address', 'id', 'user_id'], ['activity', 'id', 'user_id']])->orderBy('users.registration', 'DESC')->fetchAll();
  }

  public function user($id)
  {
    return $this->hasOne('
    users.id,
    users.username,
    users.fname,
    users.lname,
    users.sex,
    users.birthday,
    users.email,
    users.password,
    users.img,
    users.registration,
    users.status,
    address.country,
    address.state,
    address.zip,
    address.city,
    address.street,
    address.house_number,
    address.additional,
    activity.is_login,
    activity.last_login,
    activity.last_logout
    ', $this->table, [['address', 'id', 'user_id'], ['activity', 'id', 'user_id']], $id)->fetch();
  }
}
