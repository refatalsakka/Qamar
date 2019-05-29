<?php

namespace App\Controllers;

use System\Controller as Controller;

class NotfoundController extends Controller
{
    public function index()
    {
        $this->html->setTitle('404');

        $context = [

        ];
        return $this->usersLayout->render('/../notFound', $context);
    }
}