<?php

namespace App\Controllers;

use System\Controller as Controller;

class HomeController extends Controller
{
    public function index()
    {
       $users = $this->loader->model('Users');

        pre($users->getUsers());
    }
}