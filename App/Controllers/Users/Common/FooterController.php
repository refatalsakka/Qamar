<?php

namespace App\Controllers\Users\Common;

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