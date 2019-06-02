<?php

namespace App\Controllers\Admin;

use System\Controller as Controller;

class HomeController extends Controller
{
    public function index()
    {
        $this->html->setTitle('Dashboard');

        $this->html->setCss('home');

        $this->html->setJs('home');

        $context = [

        ];
        return $this->adminLayout->render('home', $context);
    }
}