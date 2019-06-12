<?php

namespace App\Controllers\Users\Common;

use System\Controller as Controller;

class FooterController extends Controller
{
    public function index()
    {
        $scripts = $this->html->getJs() ?: '';

        $data = [
            'scripts' => $scripts
        ];
        return $this->view->render('common\footer', $data);
    }
}