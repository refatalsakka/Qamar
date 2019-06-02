<?php

namespace App\Controllers\Users;

use System\Controller as Controller;

class ImprintController extends Controller
{
    public function index()
    {
        $this->html->setTitle('Imprint');

        $this->html->setCss('imprint');

        $this->html->setJs('imprint');

        $context = [

        ];
        return $this->usersLayout->render('imprint', $context);
    }
}