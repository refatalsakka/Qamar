<?php

namespace App\Controllers\Admin\Common;

use System\Controller as Controller;

class HeaderController extends Controller
{
    public function index()
    {
        $title =  $this->html->getTitle() ?: '';
        $style =  $this->html->getCss() ?: '';

        $data = [
            'title' => $title,
            'style' => $style
        ];
        return $this->view->render('common\header', $data);
    }
}