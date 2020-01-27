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
    $msg = [];
    $posts = $this->request->posts();

    if (!$this->checkParameters($posts)) {
      $msg['error'] = 'reload';
      return json_encode($msg);
    }
    extract($this->setVariables($posts));

    if ($this->areInputsEmpty()) {
      $msg['error'] = 'Please check the inputs';
      return json_encode($msg);
    }

    $login = $this->load->model('Login');
    $valid = $login->isValidLogin($username, $password, 'admin');

    if ($valid) {
      $user = $login->user();

      $this->setUserCode($user, $remember);

      $msg['success'] = true;
      return json_encode($msg);
    }
    $msg['error'] = 'Username or Passowrd is invalid';
    return json_encode($msg);
  }

  private function checkParameters($posts)
  {
    $names = array_keys($posts);
    if (!in_array('remeberme', $names)) {
      array_push($names, 'remeberme');
    }
    $allows = [
      'username',
      'password',
      'remeberme',
    ];
    if (array_equal($names, $allows)) {
      return true;
    }
    return false;
  }

  private function setVariables($posts)
  {
    $username = $posts['username'];
    $password = $posts['password'];
    $remember = false;
    if (in_array('remeberme', array_keys($posts))) {
      $remember = true;
    }
    return [
      'username' => $username,
      'password' => $password,
      'remember' => $remember,
    ];
  }

  private function areInputsEmpty()
  {
    $this->validator->input('username')->require();
    $this->validator->input('password')->require();

    if ($this->validator->fails()) {
      return true;
    }
    return false;
  }

  private function setUserCode($user, $remember)
  {
    $this->session->set('login', $user->code);

    if ($remember) {
      $this->cookie->set('login', $user->code);
    }
  }
}
