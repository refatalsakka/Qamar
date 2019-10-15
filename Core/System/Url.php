<?php

namespace System;

class Url
{
  /**
   * Application Object
   *
   * @var \System\Application
   */
  protected $app;

  /**
   * Constructor
   *
   * @param \System\Application $app
   */
  public function __construct(Application $app)
  {
    $this->app = $app;
  }

  /**
   * Generate full link for the given path
   *
   * @param string $path
   * @return string
   */
  public function link($path)
  {
    $link = $this->app->request->host();

    $path = rtrim($path, '/');
    $path = ltrim($path, '/');

    return $link . '/' . $path;
  }

  /**
   * Redirect to the given path
   *
   * @param string $path
   * @return void
   */
  public function redirectTo($path, $num = 0)
  {
    header('Refresh: ' . $num . '; URL=' . $this->link($path));
    exit;
  }
}
