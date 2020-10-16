<?php

namespace app\Controllers\Website;

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
        return $this->view->render('website/pages/home', $context);
    }
}
