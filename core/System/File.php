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
   * @return $root
   */
  public function root()
  {
    return $this->root;
  }

  /**
   * Add the given path file to the container
   *
   * @param string $file
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
    $path = $this->to($path);

    if (!$this->isSharing($path . ':file')) {

      if ($this->exists($path)) {

        $this->share($path . ':file', require $path);

      } else {

        throw new Exception("$path is not found");
      }
    }

    return $this->container[$path . ':file'];
  }

  /**
   * Get file content
   *
   * @param string $path
   * @return mixed
   */
  public function fileContent($path)
  {
    $path = $this->to($path);

    if (!$this->isSharing($path . ':content')) {

      if ($this->exists($path)) {

        $this->share($path . ':content', file_get_contents($path));

      } else {

        throw new Exception("$path is not found");
      }
    }

    return $this->container[$path . ':content'];
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
}
