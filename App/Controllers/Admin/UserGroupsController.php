<?php

namespace App\Controllers\Admin;

use System\Controller as Controller;

class UserGroupsController extends Controller
{
  public function index()
  {
    $context = [

    ];
    return $this->view->render('admin/pages/userGroups', $context);
  }
}
