<?php

namespace System;

use Exception;

class File
{
    private $root;

    public function __construct($root)
    {
        $this->root = $root;
    }

    public function root()
    {
        return $this->root;
    }

    public function exists($file)
    {
        return file_exists($file);
    }

    public function call($file)
    {
        if ($this->exists($file)) {

            return require $file;
        
        } else {
        
            throw new Exception("$file is not found");
        }
        
    }

    public function to($path, $ext = null)
    {
        return $this->root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $path . $ext);
    }

    public function inPublic($target = null)
    {
        return $this->to('public/' . $target);
    }

    public function images($target = null)
    {
        return $this->public('images/' . $target);
    }

    public function javasctipt($target = null)
    {
        return $this->public('javasctipt/' . $target);
    }

    public function css($target = null)
    {
        return $this->public('css/' . $target);
    }

}