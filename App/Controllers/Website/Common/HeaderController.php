<?php

namespace App\Controllers\Website\Common;

use System\Controller as Controller;

class HeaderController extends Controller
{
    public function index()
    {
        $title =  $this->html->getTitle() ?: '';
        $styles =  $this->html->getCss() ?: '';
        $login = $this->load->model('Login')->isLogged();
        $codeId = $this->session->has('usercode') ? $this->session->has('get'): '';

        $data = [
            'title' => $title,
            'styles' => $styles,
            'login' => $login,
            'codeId' => $codeId,
        ];
        return $this->view->render('common\header', $data);
    }
}