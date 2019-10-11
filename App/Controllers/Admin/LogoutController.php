<?php

namespace App\Controllers\Admin;

use System\Controller as Controller;

class LogoutController extends Controller
{
  public function index()
  {
    $this->cookie->remove('login');

    $this->session->destroy();

    $this->url->redirectTo('/admin/login');
  }
}
