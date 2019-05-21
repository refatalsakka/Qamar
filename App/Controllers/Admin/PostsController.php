<?php

namespace App\Controllers\Admin;

use System\Controller as Controller;

class PostsController extends Controller
{
    public function index()
    {
        $this->html->setTitle('Dashboard');
        
        $data = [
            
        ];

        $view = $this->view->render('Admin/home', $data);

        return $this->admin->render($view);
    }

    public function posts()
    {
        echo 'posts';
    }

    public function post()
    {
        echo 'post';
    }
}