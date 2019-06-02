<?php

namespace App\Controllers\Admin;

use System\Controller as Controller;

class SettingsController extends Controller
{
    public function index()
    {
        $this->html->setTitle('Settings');

        $this->html->setCss('settings');

        $this->html->setJs('settings');

        $context = [
            
        ];
        return $this->adminLayout->render('settings', $context);
    }
}