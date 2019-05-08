<?php

namespace App\Controllers\Admin;

use System\Controller as Controller;

class CategoryController extends Controller
{
    public function index()
    {
      $view = $this->view->render('Admin/category');
      
      return $this->adminLayout->render($view);
    }
}