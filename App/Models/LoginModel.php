<?php

namespace App\Models;

use System\Model;

class LoginModel extends Model
{
  protected $table = 'users';

  private $user;

  public function isValidLogin($username, $password, $userGroup = null)
  {
    if ($userGroup === 'admin') {
      $user = $this->where('username = ? AND users_group_id != ?', [$username, 2])->fetch($this->table);
    } else {
      $user = $this->where('username = ? ', $username)->fetch($this->table);
    }

    if ($user && $this->hash->passwordCheck($password, $user->password)) {
      $this->user = $user;
      return true;
    }
    return false;
  }

  public function user()
  {
    return $this->user;
  }

  public function isLogged()
  {
    if ($this->cookie->has('login')) {
      $code = $this->cookie->get('login');
    } elseif ($this->session->has('login')) {
      $code = $this->session->get('login');
    } else {
      return false;
    }

    $user = $this->where('code = ? ', $code)->fetch($this->table);

    if (!$user) {
      return false;
    }

    $this->user = $user;

    return true;
  }
}
