<?php

namespace App\Controllers\Users;

use System\Controller as Controller;

class HomeController extends Controller
{
    public function index()
    {
        // $this->db->data('users_group_id', '1')
        //         ->data('first_name', 'refat')
        //         ->data('last_name', 'alsakka')
        //         ->data('email', 'refatalsakka@gmail.com')
        //         ->data('password', '1')
        //         ->data('image', '1')
        //         ->data('gender', 'male')
        //         ->data('birthday', '1')
        //         ->data('created', '1')
        //         ->data('status', '1')
        //         ->data('ip', '1')
        //         ->data('code', '1')
        //         ->insert('users');

        // $posts = $this->load->model('post');
        // $comments = $posts->comments(3);
        // pre($comments);
        // $comments = $this->load->model('Post')->comments(3);
        // pre($comments);
        // echo "<h1>hi</h1>";
        $this->app->load->model('UsersGroups')->get(1);
    }
}