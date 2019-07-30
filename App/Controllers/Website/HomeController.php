<?php

namespace App\Controllers\Website;

use System\Controller as Controller;

class HomeController extends Controller
{
    public function index()
    {
        
        $this->html->setTitle('Home');

        $this->html->setCss('home');

        $this->html->setJs('home');
        
        $context = [

        ];
        return $this->websiteLayout->render('home', $context);
    }
}