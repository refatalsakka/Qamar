<?php

namespace App\Controllers\Users\Common;

use System\Controller as Controller;

class FooterController extends Controller
{
    public function index()
    {
        return $this->view->render('users\common\footer');
    }
}