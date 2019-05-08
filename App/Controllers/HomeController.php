<?php

namespace App\Controllers;

use System\Controller as Controller;

class HomeController extends Controller
{
    public function index()
    {
       $user = $this->loader->model('User');

       $comments = $user->comments()->where('comments.id = ?', 2)->fetchAll();

       pre($comments);
    }
}