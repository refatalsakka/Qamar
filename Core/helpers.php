<?php

use System\Application;

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
    return Application::getInstance();
  }
}

if (!function_exists('_e')) {
  function _e($value)
  {
    return htmlspecialchars($value);
  }
}

if (!function_exists('getLastParameter')) {
  function getLastParameter($index)
  {
    $array = explode('/', $index);
    return end($array);
  }
}

if (!function_exists('url')) {
  function url($path)
  {
    $app = Application::getInstance();
    return $app->url->link($path);
  }
}

if (!function_exists('assets')) {
  function assets($path = null)
  {
    $app = Application::getInstance();
    return $app->url->link('public' . DS . $path);
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
