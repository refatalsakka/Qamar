<?php

namespace App\Controllers\Users\Common;

use System\Controller as Controller;
use System\View\ViewInterface as ViewInterface;

class LayoutController extends Controller
{
    public function render($file, $context)
    {

       $data['content'] = $this->view->render('users\\' . $file, $context);
       $data['header'] = $this->load->controller('Users/Common/Header')->index();
       $data['footer'] = $this->load->controller('Users/Common/Footer')->index();


       return $this->view->render('users/common/layout', $data);

    }
}