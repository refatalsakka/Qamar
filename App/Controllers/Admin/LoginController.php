<?php

namespace App\Controllers\Admin;

use System\Controller as Controller;

class LoginController extends Controller
{
    public function index()
    {
        echo "hi";
        // $this->html->setTitle('Log in');

        // $data = [
            
        // ];

        // $view = $this->view->render('Admin/login', $data);

        // return $this->adminLayout->render($view);
    }

    public function submit()
    {
        // $this->html->setTitle('Submit');

        // // $userId = 2;
        // $this->validator->input('email')->require()->email()->unique(['users', 'email', 'id', $userId]);
        // $password = $this->validator->input('password')->require()->minLen(5)->maxLen(10);
       
        // $this->request->file('img');

        // $file = $this->request->file('img');
        // if ($file->isImage()) {
        //     $file->moveTo($this->file->images());
        // }
        // if ($this->validator->fails()) {
        //     pre($this->validator->getMsgs());
        // }
    }
}