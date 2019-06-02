<?php

use System\Application;

if (! function_exists('pre')) {
    function pre($var) {
        echo '<pre>';
        print_r($var);
        echo '</pre>';
    }
}

if (! function_exists('array_get')) {
    function array_get($array, $key, $default = null)
    {
        return $array[$key] ?: $default;
    }
}

if (! function_exists('_e')) {
    function _e($value)
    {
        return htmlspecialchars($value);
    }
}

if (! function_exists('getLastIndex')) {
    function getLastIndex($index)
    {
        $array = explode('\\', $index);
        return end($array);
    }
}

if (! function_exists('url')) {
    function url($path)
    {
        $app = Application::getInstance();
        return rtrim($app->request->baseUrl(), '/') . $path;
    }
}

if (! function_exists('assets')) {
    function assets($path)
    {
        $app = Application::getInstance();
        return $app->url->link('public/' . $path);
    }
}

if (! function_exists('htmlTag')) {

    function htmlTag($path, $tag) {
        
        $file = $_SERVER['DOCUMENT_ROOT'] .  '/public//' . $path . '.' . $tag;
        
        if (file_exists($file)) {

            $file = assets($path . '.' . $tag);

            if ($tag == 'js') {
                 
                ?>
                    <script scr="<?php echo $file ?>"></script>
                <?php
            } elseif ($tag == 'css') {
                
                ?>
                    <link rel="stylesheet" href="<?php echo $file ?>" />
                <?php
            }
        }
    }
}