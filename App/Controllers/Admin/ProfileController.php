<?php

namespace App\Controllers\Admin;

use System\Controller as Controller;

class ProfileController extends Controller
{
    public function index()
    {
        $this->html->setTitle('Profile');

        $this->html->setCss('profile');

        $this->html->setJs('profile');

        $context = [
            
        ];
        return $this->adminLayout->render('profile', $context);
    }
}