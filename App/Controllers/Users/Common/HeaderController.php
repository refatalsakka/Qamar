<?php

namespace App\Controllers\Users\Common;

use System\Controller as Controller;

class HeaderController extends Controller
{
    public function index()
    {
        $data = [
            'title' => $this->html->getTitle()
        ];
        return $this->view->render('users\common\header', $data);
    }
}