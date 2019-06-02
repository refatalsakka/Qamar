<?php

namespace App\Controllers\Users;

use System\Controller as Controller;

class TeamController extends Controller
{
    public function index()
    {
        $this->html->setTitle('Team');

        $this->html->setCss('team');

        $this->html->setJs('team');
        
        $context = [

        ];
        return $this->usersLayout->render('team', $context);
    }
}