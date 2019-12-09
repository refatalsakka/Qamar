<?php

namespace System;

class Hash
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

  public function password($password)
  {
    return password_hash($password, $this->app->config['hash']['algo']);
  }

  public function passwordCheck($password, $hash)
  {
    return password_verify($password, $hash);
  }
}
