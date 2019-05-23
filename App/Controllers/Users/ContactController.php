<?php

namespace App\Controllers\Users;

use System\Controller as Controller;

class ContactController extends Controller
{
    public function index()
    {
        $this->html->setTitle('Kontakt');

        $context = [

        ];
        return $this->usersLayout->render('contact', $context);
    }
}