<?php

namespace App\Controllers\Website;

use System\Controller as Controller;

class ContactController extends Controller
{
    public function index()
    {
        $this->html->setTitle('Contact');

        $this->html->setCss('contact');

        $this->html->setJs('contact');

        $context = [

        ];
        return $this->websiteLayout->render('contact', $context);
    }
}