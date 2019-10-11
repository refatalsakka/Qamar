<?php

namespace System;

use Exception;

class File
{
  /**
   * Root Path
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
   * @return @var $root
   */
  public function root()
  {
    return $this->root;
  }

  /**
   * add the path file to the container
   *
   * @param string $file
   * @return void
   */
  private function addToContainer($key, $value)
  {
    $this->container[$key] = $value;
  }

  /**
   * check if the path file exists in the container
   *
   * @param string $key
   * @return bool
   */
  private function isInContainer($key)
  {
    return isset($this->container[$key]);
  }

  /**
   * get the path file form the container
   *
   * @param string $key
   * @return string
   */
  private function getFromContainer($key)
  {
    return $this->container[$key];
  }
  /**
   * Determine wether the given file path exists
   *
   * @param string $file
   * @return bool
   */
  public function exists($file)
  {
    return file_exists($file);
  }

  /**
   * Require The given file
   *
   * @param string $file
   * @return mixed
   */
  public function call($file)
  {
    $file = $this->to($file);

    if (!$this->isInContainer($file)) {

      if ($this->exists($file)) {

        $this->addToContainer($file, require $file);

      } else {

        throw new Exception("$file is not found");
      }
    }

    return $this->getFromContainer($file);
  }

 /**
   * Generate full path to the given path
   *
   * @param string $path
   * @return string
   */
  public function to($path)
  {
    return $this->root . DS . str_replace(['/', '\\'], DS, $path);
  }

  /**
   * Generate full path to the given path in public folder
   *
   * @param string $path
   * @return string
   */
  public function toPublic($target = null)
  {
    return $this->to('public' . DS . $target);
  }


  /**
   * Generate full path to the given path in public/img folder
   *
   * @param string $path
   * @return string
   */
  public function img()
  {
    return $this->toPublic('img');
  }

  /**
   * Generate full path to the given path in public/js folder
   *
   * @param string $path
   * @return string
   */
  public function js()
  {
    return $this->toPublic('js');
  }

  /**
   * Generate full path to the given path in public/css folder
   *
   * @param string $path
   * @return string
   */
  public function css()
  {
    return $this->toPublic('css');
  }
}
