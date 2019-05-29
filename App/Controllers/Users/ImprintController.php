<?php

namespace App\Controllers\Users;

use System\Controller as Controller;

class ImprintController extends Controller
{
    public function index()
    {
        $this->html->setTitle('Imprint');

        $context = [

        ];
        return $this->usersLayout->render('imprint', $context);
    }
}