<?php

use System\Application;
use System\File;

if (!function_exists('pre')) {
    function pre($var)
    {
        echo '<pre>';
        print_r($var);
        echo '</pre>';
    }
}

if (!function_exists('array_get')) {
    function array_get($array, $key, $default = null)
    {
        return ($array[$key] || $array[$key] == '0') ? $array[$key] : $default;
    }
}

if (!function_exists('app')) {
    function app()
    {
        return Application::getInstance(new File(__DIR__));
    }
}

if (!function_exists('_e')) {
    function _e($value)
    {
        $value = trim($value);
        $value = preg_replace('# {2,}#', ' ', $value);
        $value = htmlspecialchars($value);
        return $value;
    }
}

if (!function_exists('getLastParameter')) {
    function getLastParameter($index)
    {
        $array = explode('/', $index);
        return end($array);
    }
}

if (!function_exists('userId')) {
    function userId()
    {
        return getLastParameter(app()->request->baseUrl());
    }
}

if (!function_exists('url')) {
    function url($path)
    {
        return app()->url->link($path);
    }
}

if (!function_exists('assets')) {
    function assets($path = null)
    {
        return app()->url->link('public' . DS . $path);
    }
}

if (!function_exists('remove_space')) {
    function remove_space($str)
    {
        return str_replace(' ', '-', $str);
    }
}

if (!function_exists('remove_dash')) {
    function remove_dash($str)
    {
        return str_replace('-', ' ', $str);
    }
}

if (!function_exists('text_char_limit')) {
    function text_char_limit($text, $limit)
    {
        if (strlen($text) > $limit) {
            return substr($text, 0, $limit) . '...';
        }
    }
}

if (!function_exists('array_equal')) {
    function array_equal($a, $b)
    {
        return (
        is_array($a)
        && is_array($b)
        && count($a) == count($b)
        && array_diff($a, $b) === array_diff($b, $a)
        );
    }
}

if (!function_exists('isExtesntionAllowed')) {
    function isExtesntionAllowed($key, $extension)
    {
        $allowExtesntions = app()->file->call('config/uploads.php')[$key];

        return in_array($extension, $allowExtesntions);
    }
}

if (!function_exists('isImage')) {
    function isImage($minetype)
    {
        return strpos($minetype, "image/") === 0;
    }
}

if (!function_exists('notFoundPage')) {

    function notFoundPage()
    {
        $notfound = 'Website\Notfound';

        if (app()->request->isRequestToAdminManagement()) {
            $notfound = 'Admin\Notfound';
        }

        return (string) app()->load->action($notfound, 'index', []);
    }
}

if (!function_exists('getAllSubDires')) {

    function getAllSubDires($direPath)
    {
        $dirs = [];
        $directions = [];

        $directions = glob($direPath, GLOB_ONLYDIR);
        $dirs = array_merge($directions, $dirs);

        do {
            $direPath .= '**/';
            $directions = glob($direPath, GLOB_ONLYDIR);
            $dirs = array_merge($directions, $dirs);
        } while (!empty($directions));

        return $dirs;
    }
}

