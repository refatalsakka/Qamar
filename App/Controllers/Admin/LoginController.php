<?php

namespace App\Controllers\Admin;

use System\Controller as Controller;

class LoginController extends Controller
{
    public function index()
    {
        $this->html->setTitle('Login');

        $context = [
            
        ];
        return $this->adminLayout->render('login', $context);
    }

    public function submit()
    {

    }
}