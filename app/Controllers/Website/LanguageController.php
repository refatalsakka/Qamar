<?php

namespace app\Controllers\Website;

use System\Controller as Controller;

/**
 * Language Controller
 *
 */
class LanguageController extends Controller
{
    public function index($language)
    {
        $this->lang->set($language[0]);
    }
}
