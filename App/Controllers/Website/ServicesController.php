<?php

namespace App\Controllers\Website;

use System\Controller as Controller;

class ServicesController extends Controller
{
    public function index()
    {
        $this->html->setTitle('Services');
        
        $this->html->setCss('services');

        $this->html->setJs('services');
        
        $context = [

        ];
        return $this->websiteLayout->render('services', $context);
    }
}