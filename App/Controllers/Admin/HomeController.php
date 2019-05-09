<?php

namespace App\Controllers\Admin;

use System\Controller as Controller;

class HomeController extends Controller
{
    public function index()
    {
        $this->html->setTitle('Dashboard');

        $data = [
            
        ];

        $view = $this->view->render('Admin/home', $data);

        return $this->admin->render($view);
    }
}