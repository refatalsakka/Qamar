<?php

namespace System;

class File
{
    const DS = DIRECTORY_SEPARATOR;

    private $root;

    public function __construct($root)
    {
        $this->root = $root;
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
        
            die('Ohh! <strong>' . $file .'</strong> is not found');
        }
        
    }

    public function to($path, $ext = null)
    {
        return $this->root . static::DS . str_replace(['/', '\\'], static::DS, $path . $ext);
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