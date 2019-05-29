<?php

namespace App\Models;

use System\Model;

class LoginModel extends Model
{
    protected $table = 'users';

    private $user;

    public function isValidLogin($email, $password)
    {
        $user = $this->where('email=?' , $email)->fetch($this->table);

        if (! $user) return false;

        $this->user = $user;

        return password_verify($password, $user->password);
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

        $user = $this->where('code=?' , $code)->fetch($this->table);

        if (! $user) return false;
        
        $this->user = $user;

        return true;
    }
}