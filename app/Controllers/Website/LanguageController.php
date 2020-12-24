<?php

namespace app\Controllers\Website;

use System\Controller as Controller;

class LanguageController extends Controller
{
    /**
     * Change language
     *
     * @property object $lang
     */
    public function index($language)
    {
        $this->lang->set($language[0]);
    }
}
