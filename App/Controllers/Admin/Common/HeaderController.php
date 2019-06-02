<?php

namespace App\Controllers\Admin\Common;

use System\Controller as Controller;

class HeaderController extends Controller
{
    public function index()
    {
        $data = [
            'title' => $this->html->getTitle(),
            'style' => $this->html->getCss()
        ];
        return $this->view->render('Admin\common\header', $data);
    }
}