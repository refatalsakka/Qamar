<?php

namespace App\Controllers\Admin;

use System\Controller as Controller;

class NotfoundController extends Controller
{
    /**
     * Notfound
     *
     * @property object $view
     */
    public function index()
    {
        $context = [];
        return $this->view->render('admin/pages/notfound', $context);
    }
}
