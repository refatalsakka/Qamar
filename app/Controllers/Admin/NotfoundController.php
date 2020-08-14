<?php

namespace App\Controllers\Admin;

use System\Controller as Controller;

class NotfoundController extends Controller
{
  public function index()
  {
    $context = [

    ];
    return $this->view->render('admin/pages/404', $context);
  }
}
