<?php

namespace App\Controllers\Users;

use System\Controller as Controller;

class ContactController extends Controller
{
    public function index()
    {
        $this->html->setTitle('Contact');

        $this->html->setCss('contact');

        $this->html->setJs('contact');

        $context = [

        ];
        return $this->usersLayout->render('contact', $context);
    }
}