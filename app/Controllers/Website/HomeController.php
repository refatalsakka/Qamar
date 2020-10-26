<?php

namespace app\Controllers\Website;

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
        return $this->view->render('website/pages/home', $context);
    }
}
