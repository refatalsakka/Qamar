<?php

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
        return isset($array[$key]) ? $array[$key] : $default;
    }
}

if (! function_exists('_e')) {
    function _e($value)
    {
        return htmlspecialchars($value);
    }
}

if (! function_exists('getLastIndex')) {
    function getLastIndex($index) {
        $array = explode('\\', $index);
        return end($array);
    }
}