<?php

namespace App\Controllers\Admin\Common;

use System\Controller as Controller;

class HeaderController extends Controller
{
    public function index()
    {
        return $this->view->render('Admin\common\header');
    }
}