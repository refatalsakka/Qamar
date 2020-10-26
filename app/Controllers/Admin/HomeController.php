<?php

namespace app\Controllers\Admin;

use System\Controller as Controller;

class HomeController extends Controller
{
    /**
     * Home
     *
     * @property object $view
     */
    public function index()
    {
        $context = [];
        return $this->view->render('admin/pages/home', $context);
    }
}
