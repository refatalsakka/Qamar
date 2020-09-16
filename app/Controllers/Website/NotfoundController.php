<?php

namespace App\Controllers\Website;

use System\Controller as Controller;

/**
 * Notfound Controller
 *
 * @property object $view
 */
class NotfoundController extends Controller
{
    public function index()
    {
        $context = [];
        return $this->view->render('website/pages/404', $context);
    }
}
