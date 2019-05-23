<?php

namespace App\Controllers\Admin;

use System\Controller as Controller;

class HomeController extends Controller
{
    public function index()
    {
        $title = $this->html->setTitle('Dashboard');

        $context = [
            'title' => $title 
        ];
        return $this->adminLayout->render('home', $context);
    }
}