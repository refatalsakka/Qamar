<?php

namespace App\Controllers\Admin\Common;

use System\Controller as Controller;

class FooterController extends Controller
{
    public function index()
    {
        $script = $this->html->getJs() ?: '';

        $data = [
            'script' => $script
        ];
        return $this->view->render('common\footer', $data);
    }
}