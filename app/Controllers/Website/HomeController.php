<?php

namespace app\Controllers\Website;

use System\Controller as Controller;

class HomeController extends Controller
{
  public function index()
  {
    // $this->app->email->recipients(['amin' => 'refat838@gmail.com'])->content(true, 'Error', 'test','test')->send();
    $this->app->email;

    $context = [

    ];
    return $this->view->render('website/pages/home', $context);
  }
}
