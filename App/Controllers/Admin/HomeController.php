<?php

namespace App\Controllers\Admin;

use System\Controller as Controller;

class HomeController extends Controller
{
    public function index()
    {
        $this->html->setTitle('Dashboard');

        $context = [

        ];
        return $this->adminLayout->render('home', $context);
    }
}