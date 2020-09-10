<?php

namespace System;

class Url
{
  /**
   * Application Object
   *
   * @var \System\Application
   */
  private $app;

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
   * @param number $num
   * @return void
   */
  public function redirectTo($path, $num = 0)
  {
    header('Refresh: ' . $num . '; URL=' . $this->link($path));

    exit;
  }

  /**
   * Redirect to the 404 page
   *
   * @param string $path
   * @return void
   */
  public function notfound($path = null)
  {
    if (!$path) {

      $path = '/404';
      // should be (ifAdmin()) insted isLogged
      // if ($this->app->request->isRequestToAdminManagement() && $this->app->load->model('Login')->isAdmin()) {
      if ($this->app->request->isRequestToAdminManagement() && $this->app->load->model('Login')->isLogged()) {

        $path = 'admin/404';
      }
    }

    header('Refresh: 0; URL=' . $this->link($path));

    exit;
  }
}
