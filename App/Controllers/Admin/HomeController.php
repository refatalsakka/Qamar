<?php

namespace App\Controllers\Admin;

use System\Controller as Controller;

class HomeController extends Controller
{
    public function index()
    {
        $this->html->setTitle('Dashboard');

        $view = $this->view->render('Admin/home', [

        ]);

        return $this->admin->render($view);
    }
}