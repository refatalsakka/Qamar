<?php

namespace app\Controllers\Admin;

use System\Controller as Controller;

/**
 * Home Controller
 *
 * @property object $view
 */
class HomeController extends Controller
{
    public function index()
    {
        $context = [];
        return $this->view->render('admin/pages/home', $context);
    }
}
