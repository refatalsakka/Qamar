<?php

namespace App\Controllers\Admin;

use System\Controller as Controller;

class LoginController extends Controller
{
    public function index()
    {
        $this->html->setTitle('Login');

        $this->html->setCss('login');

        $this->html->setJs('login');

        $context = [
            
        ];
        return $this->adminLayout->render('login', $context);
    }

    public function submit()
    {

    }
}