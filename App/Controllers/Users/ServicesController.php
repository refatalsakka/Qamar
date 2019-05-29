<?php

namespace App\Controllers\Users;

use System\Controller as Controller;

class ServicesController extends Controller
{
    public function index()
    {
        $this->html->setTitle('Services');

        $context = [

        ];
        return $this->usersLayout->render('services', $context);
    }
}