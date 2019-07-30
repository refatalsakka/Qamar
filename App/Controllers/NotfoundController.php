<?php

namespace App\Controllers;

use System\Controller as Controller;

class NotfoundController extends Controller
{
    public function index()
    {
        $this->html->setTitle('404');
        
        $this->html->setCss('404');

        $this->html->setJs('404');

        $context = [

        ];
        return $this->websiteLayout->render('/../notFound', $context);
    }
}