<?php

namespace App\Controllers\Admin;

use System\Controller as Controller;

class ProfileController extends Controller
{
  public function index()
  {
    $id = $this->load->model('Login')->user()->id;

    $user = $this->load->model('User')->user($id);

    $context = [
      'user' => $user,
    ];
    return $this->view->render('admin/pages/profile', $context);
  }
}
