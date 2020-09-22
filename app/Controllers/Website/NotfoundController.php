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
        $this->response->setHeader("HTTP/1.0 404 Not Found");

        $context = [];
        return $this->view->render('website/pages/404', $context);
    }
}
