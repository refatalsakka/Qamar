<?php

namespace App\Controllers\Admin\Common;

use System\Controller as Controller;

class FooterController extends Controller
{
    public function index()
    {
        $data = [
            'script' => $this->html->getJs()
        ];
        return $this->view->render('Admin\common\footer', $data);
    }
}