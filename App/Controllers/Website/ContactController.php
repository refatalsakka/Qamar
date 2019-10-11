<?php

namespace App\Controllers\Website;

use System\Controller as Controller;

class ContactController extends Controller
{
  public function index()
  {
    $context = [

    ];
    return $this->view->render('website/pages/contact', $context);
  }
}