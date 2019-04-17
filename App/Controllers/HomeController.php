<?php

namespace App\Controllers;

use System\Controller as Controller;

class HomeController extends Controller
{
    public function index()
    {
        $this->db;
    }

    public function profile()
    {
        $data = [
            'my_name' => 'Hassan'
        ];
        return $this->view->render('home', $data);
    }
}