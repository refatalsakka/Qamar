<?php

namespace App\Controllers\Admin\Common;

use System\Controller as Controller;

class HeaderController extends Controller
{
    public function index()
    {
        $title =  $this->html->getTitle() ?: '';
        $styles =  $this->html->getCss() ?: '';

        $data = [
            'title' => $title,
            'styles' => $styles
        ];
        return $this->view->render('common\header', $data);
    }
}