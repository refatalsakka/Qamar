<?php

namespace App\Controllers\Website;

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
        return $this->websiteLayout->render('imprint', $context);
    }
}