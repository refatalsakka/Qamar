<?php

namespace System;

class Hash
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

  public function password($password)
  {
    return password_hash($password, $_ENV['HASH_PASSWORD']);
  }

  public function passwordCheck($password, $hash)
  {
    return password_verify($password, $hash);
  }

  public function hash($input)
  {
    return hash($_ENV['HASH_TYPE'], $input);
  }

  public function hashCheck($known, $user)
  {
    return hash_equals($known, $user);
  }
}
