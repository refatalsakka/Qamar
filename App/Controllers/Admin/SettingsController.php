<?php

namespace App\Controllers\Admin;

use System\Controller as Controller;

class SettingsController extends Controller
{
  public function index()
  {
    $context = [

    ];
    return $this->view->render('admin/pages/settings', $context);
  }
}