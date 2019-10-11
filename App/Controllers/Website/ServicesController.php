<?php

namespace App\Controllers\Website;

use System\Controller as Controller;

class ServicesController extends Controller
{
  public function index()
  {
    $context = [

    ];
    return $this->view->render('website/pages/services', $context);
  }
}