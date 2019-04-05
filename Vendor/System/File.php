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
            require $file;
        } else {
            die('Ohh! <strong>' . $file .'</strong> is not found');
        }
        
    }

    public function toVendor($path, $ext)
    {   
        return $this->to('Vendor' . static::DS . $path, $ext);
    }

    public function to($path, $ext)
    {
        return $this->root . static::DS . str_replace(['/', '\\'], static::DS, $path . $ext);
    }
}