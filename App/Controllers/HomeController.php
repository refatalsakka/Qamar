<?php

namespace App\Controllers;

use System\Controller as Controller;

class HomeController extends Controller
{
    public function index()
    {
        $users = $this->db->select('first_name')->from('users')->fetchAll();

        pre($users);
    }
}