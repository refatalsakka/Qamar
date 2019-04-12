<?php

namespace App\Controllers;

use System\Controller as Controller;

class HomeController extends Controller
{
    public function index()
    {
        $data = [
            'my_name' => 'Refat'
        ];
        $this->view->render('home', $data);
    }

    public function profile()
    {
        $data = [
            'my_name' => 'Hassan'
        ];
        return $this->view->render('home', $data);
    }
}