<?php

namespace App\Controllers\Users;

use System\Controller as Controller;

class TeamController extends Controller
{
    public function index()
    {
        $this->html->setTitle('Team');

        $context = [

        ];
        return $this->usersLayout->render('team', $context);
    }
}