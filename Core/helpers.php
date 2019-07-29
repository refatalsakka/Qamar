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

if (! function_exists('app')) {
    function app()
    {
        return Application::getInstance();
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
    function url($path) {
        $app = Application::getInstance();
        return $app->url->link($path);
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

            $file = rtrim($file, '/');
            
            if ($tag == 'js') {
                 
                ?>
                    <script src="<?php echo $file ?>"></script>
                <?php
            } elseif ($tag == 'css') {
                
                ?>
                    <link rel="stylesheet" href="<?php echo $file ?>" />
                <?php
            }
        }
    }
}

if (! function_exists('remove_space')) {
    function remove_space($str) {
        return str_replace(' ', '-', $str);
    }
}

if (! function_exists('remove_dash')) {
    function remove_dash($str) {
        return str_replace('-', ' ', $str);
    }
}

if (! function_exists('clean_name_url')) {
    function clean_name_url($class = null) {

        $app = Application::getInstance();
       
        if (! $class) {
            
            $class = debug_backtrace()[1]['class'];

            $clases = explode('\\', $class);
    
            $class = end($clases);
    
            $class = str_replace('Controller', '', $class);

            $class = strtolower($class);

            $class = '/' . $class .  '/';
        }
        
        $url = $app->request->url();

        $name = str_replace($class, '', $url);

        $name = remove_dash($name);

        return $name;
    }
}

if (! function_exists('text_char_limit')) {
    function text_char_limit($text, $limit) {
        if (strlen($text) > $limit) {
            return substr($text, 0, $limit) . '...';
        }
    }
}
if (! function_exists('array_equal')) {
    function array_equal($a, $b) {
        return (
            is_array($a) 
            && is_array($b) 
            && count($a) == count($b) 
            && array_diff($a, $b) === array_diff($b, $a)
        );
    }
}
if (! function_exists('redirect_after')) {
    function redirect_after($num) {
        $app = Application::getInstance();
        $app->url->redirectTo('/', $num);
    }
}