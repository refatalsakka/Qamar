<?php

namespace App\Controllers\Website;

use System\Controller as Controller;

class PrivacyController extends Controller
{
  public function index()
  {
    $context = [

    ];
    return $this->view->render('website/pages/privacy', $context);
  }
}
