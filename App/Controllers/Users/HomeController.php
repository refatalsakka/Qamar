<?php

namespace App\Controllers\Users;

use System\Controller as Controller;

class HomeController extends Controller
{
    public function index()
    {
        $this->html->setTitle('Home');

        $this->html->setCss('home');

        $this->html->setJs('home');
        
        $context = [

        ];
        return $this->usersLayout->render('home', $context);
    }
}