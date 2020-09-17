<?php

namespace System;

use Exception;

class File
{
    /**
     * Root path
     *
     * @var string
     */
    private $root;

    /**
     * Container
     *
     * @var array
     */
    private $container = [];

    /**
     * Constructor
     *
     * @param string $root
     */
    public function __construct($root)
    {
        $this->root = $root;
    }

    /**
     * Root
     *
     * @return string $root
     */
    public function root()
    {
        return $this->root;
    }

    /**
     * Add the given path file to the container
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    private function share($key, $value)
    {
        $this->container[$key] = $value;
    }

    /**
     * Check if the given path file exists in the container
     *
     * @param string $key
     * @return bool
     */
    private function isSharing($key)
    {
        return isset($this->container[$key]);
    }

    /**
     * Determine if the given file path exists
     *
     * @param string $file
     * @return bool
     */
    public function exists($file)
    {
        return file_exists($file);
    }

    /**
     * Require the given file
     *
     * @param string $path
     * @return mixed
     */
    public function call($path)
    {
        return $this->processSharing($path, 'file');
    }

    /**
     * Get file content
     *
     * @param string $path
     * @return mixed
     */
    public function fileContent($path)
    {
        return $this->processSharing($path, 'content');
    }

    /**
     * After getting the $path, check if the file or the conent of the file
     * is already exists:
     * -if so: just return it from the container.
     * -if not: check if the file exits:
     *  - if so: share it to the container. the $path as the key plus :content or :file
     *      to avoid conflict bewtween the keys.
     *      :file: it's mean the file
     *      :content: it's mean the content of the file
     *  - if not: theow an Exception.
     *
     * @param string $path
     * @param string $share
     * @return mixed
     */
    private function processSharing($path, $share)
    {
        $path = $this->fullPath($path);

        if (!$this->isSharing($path . ':' . $share)) {
            if ($this->exists($path)) {
                $this->share($path . ':' . $share, ($share === 'content') ? file_get_contents($path) : require $path);
            } else {
                throw new Exception("$path is not found");
            }
        }
        return $this->container[$path . ':' . $share];
    }

    /**
     * Generate full path to the given path
     *
     * @param string $path
     * @return string
     */
    public function fullPath($path)
    {
        return $this->root . DS . str_replace(['/', '\\'], DS, $path);
    }
}
