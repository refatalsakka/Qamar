<?php

use System\Application;
use System\File;

if (!function_exists('app')) {
    /**
     * Get Application instance
     *
     * @param \System\File
     * @return \System\Application
     */
    function app()
    {
        return Application::getInstance(new File(__DIR__));
    }
}

if (!function_exists('pre')) {
    /**
     * Display the give $input
     *
     * @param string $input
     * @return string
     */
    function pre($input)
    {
        echo '<pre>';
        print_r($input);
        echo '</pre>';
    }
}

if (!function_exists('sp')) {
    /**
     * Echo ======
     *
     * @return void
     */
    function sp()
    {
        echo '============================';
    }
}

if (!function_exists('_e')) {
    /**
     * Clean the fiven $value
     *
     * @param string $value
     * @return string
     */
    function _e($value)
    {
        $value = trim($value);
        $value = preg_replace('# {2,}#', ' ', $value);
        $value = htmlspecialchars($value);
        return $value;
    }
}

if (!function_exists('assets')) {
    /**
     * Get the assets of the fiven $path
     *
     * @property object $url
     * @param string $path
     * @return string
     */
    function assets($path = null)
    {
        return app()->url->link('public/' . ($path ? $path : ''));
    }
}

if (!function_exists('array_get')) {
    /**
     * Get the value of the given key of the given array
     * if the given key is not exist than return the given default
     *
     * @param array $array
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function array_get(array $array, string $key, $default = null)
    {
        return ($array[$key] || $array[$key] == '0') ? $array[$key] : $default;
    }
}

if (!function_exists('array_equal')) {
    /**
     * Check if the given arrays are equal
     *
     * @param array $a
     * @param array $b
     * @return bool
     */
    function array_equal(array $a, array $b)
    {
        return (is_array($a)
            && is_array($b)
            && count($a) == count($b)
            && array_diff($a, $b) === array_diff($b, $a));
    }
}

if (!function_exists('isExtesntionAllowed')) {
    /**
     * Check if the given extension is allow to use
     *
     * @param string $key
     * @param string $extension
     * @property object $file
     * @return bool
     */
    function isExtesntionAllowed($key, $extension)
    {
        $allowExtesntions = app()->file->call('config/uploads.php')[$key];

        return in_array($extension, $allowExtesntions);
    }
}

if (!function_exists('isImage')) {
    /**
     * Check if the given minetype is an image
     *
     * @param string $minetype
     * @return bool
     */
    function isMinetypeisAnImage($minetype)
    {
        return strpos($minetype, "image/") === 0;
    }
}

if (!function_exists('notFoundPage')) {
    /**
     * Display Notfound page of admin or user depends on the user persmissions
     *
     * @property object $load
     * @return string
     */
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
    /**
     * Getting all the sub-folders in the given path
     *
     * @param string $direPath
     * @return array
     */
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
