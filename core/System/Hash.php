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

  /**
   * Hash password
   *
   * @param $password
   * @return string
   */
    public function password($password)
    {
        $timeTarget = 0.05;
        $cost = 8;
        $password;

        do {
            $cost++;
            $start = microtime(true);
            $password = password_hash("test", PASSWORD_BCRYPT, ["cost" => $cost]);
            $end = microtime(true);
        } while (($end - $start) < $timeTarget);

        return $password;
    }

  /**
   * Check if the given password is verified with the given hash
   *
   * @param $password1
   * @param $password2
   * @return bool
   */
    public function passwordCheck($password1, $password2)
    {
        return password_verify($password1, $password2);
    }

  /**
   * Hash the given string
   *
   * @param $string
   * @return string
   */
    public function hash($string)
    {
        return hash($_ENV['HASH_TYPE'], $string);
    }

  /**
   * Check if the given hashes are equal
   *
   * @param $hash1
   * @param $hash2
   * @return bool
   */
    public function hashCheck($hash1, $hash2)
    {
        return hash_equals($hash1, $hash2);
    }
}
