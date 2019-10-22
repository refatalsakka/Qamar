<?php

namespace App\Controllers\Admin;

use System\Controller as Controller;

class LoginController extends Controller
{
  public function index()
  {
    $context = [

    ];
    return $this->view->render('admin/pages/login', $context);
  }

  public function submit()
  {
    $posts = $this->request->posts();

    $names = array_keys($posts);

    if (!in_array('remeberme', $names)) {

      array_push($names, 'remeberme');
    }

    $allows = [
      'username',
      'password',
      'remeberme',
    ];

    if (!array_equal($names, $allows)) {

      $msg['error'] = 'reload';

      return json_encode($msg);
    }

    $username =  $this->request->post('username');
    $password =  $this->request->post('password');
    $remember = false;

    if (in_array('remeberme', array_keys($posts))) {

      $remember = true;
    }

    $this->validator->input('username')->require();
    $this->validator->input('password')->require();

    if ($this->validator->fails()) {

      $msg['error'] = 'Please check the inputs';

      return json_encode($msg);
    }

    $login = $this->load->model('Login');

    $valid = $login->isValidLogin($username, $password);

    if ($valid) {

      $user = $login->user();

      $this->session->set('login', $user->code);

      if ($remember) {

        $this->cookie->set('login', $user->code);
      }

      $msg['success'] = true;

      return json_encode($msg);
    }

    $msg['error'] = 'Username or Passowrd is invalid';

    return json_encode($msg);
  }
}
